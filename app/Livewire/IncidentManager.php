<?php

namespace App\Livewire;

use App\Models\IncidentReport;
use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class IncidentManager extends Component
{
    use WithPagination;

    public $showModal = false;
    public $reportId;
    public $isReadOnly = false;

    // Form fields
    public $title;
    public $report_date;
    public $report_time;
    public $post_id;
    public $description;
    public $people_involved;
    public $status;

    public $allPosts;

    public function mount()
    {
        $this->allPosts = Post::all();
    }

    public function create()
    {
        $this->resetForm();
        $this->isReadOnly = false;
        $this->showModal = true;
    }

    public function edit($reportId)
    {
        $report = IncidentReport::findOrFail($reportId);
        $this->reportId = $reportId;
        $this->title = $report->title;
        $this->report_date = $report->report_date->format('Y-m-d');
        $this->report_time = $report->report_time;
        $this->post_id = $report->post_id;
        $this->description = $report->description;
        $this->people_involved = $report->people_involved;
        $this->status = $report->status;

        $this->isReadOnly = true;
        $this->showModal = true;
    }

    public function switchToEditMode()
    {
        $this->isReadOnly = false;
    }

    public function save()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'report_date' => 'required|date',
            'report_time' => 'required',
            'post_id' => 'nullable|exists:posts,id',
            'description' => 'required|string',
            'people_involved' => 'nullable|string',
        ]);

        if ($this->reportId) {
            $report = IncidentReport::findOrFail($this->reportId);
            $report->update([
                'title' => $this->title,
                'report_date' => $this->report_date,
                'report_time' => $this->report_time,
                'post_id' => $this->post_id,
                'description' => $this->description,
                'people_involved' => $this->people_involved,
                'status' => $this->status,
            ]);
            session()->flash('message', 'Laporan kejadian berhasil diperbarui.');
        } else {
            IncidentReport::create([
                'user_id' => Auth::id(),
                'title' => $this->title,
                'report_date' => $this->report_date,
                'report_time' => $this->report_time,
                'post_id' => $this->post_id,
                'description' => $this->description,
                'people_involved' => $this->people_involved,
                'status' => 'Baru',
            ]);
            session()->flash('message', 'Laporan kejadian berhasil disimpan.');
        }

        $this->showModal = false;
        $this->isReadOnly = false;
    }

    public function cancel()
    {
        $this->resetForm();
        $this->showModal = false;
        $this->isReadOnly = false;
    }

    public function render()
    {
        // For now, just render the view. Data will be added later.
        return view('livewire.incident-manager', [
            'reports' => IncidentReport::with('user', 'post')->latest()->paginate(10),
        ]);
    }

    private function resetForm()
    {
        $this->reportId = null;
        $this->title = '';
        $this->report_date = now()->format('Y-m-d');
        $this->report_time = now()->format('H:i');
        $this->post_id = null;
        $this->description = '';
        $this->people_involved = '';
        $this->status = 'Baru';
        $this->isReadOnly = false;
    }
}
