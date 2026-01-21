<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Roster;
use App\Models\Post;
use App\Models\Shift;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;

#[Layout('components.layouts.app')]
class PostAssignment extends Component
{
    public $selectedDate;
    public $selectedShift;
    public $shifts;
    public $posts;
    public $rosters;
    public $draggedRosterId;

    #[Url(as: 'q', except: '')]
    public $search = '';

    public function mount()
    {
        $this->selectedDate = Carbon::today()->format('Y-m-d');
        $this->shifts = Shift::all();
        $this->posts = Post::all();
        $this->loadRosters();
    }

    public function updatedSelectedDate()
    {
        $this->loadRosters();
    }

    public function updatedSelectedShift()
    {
        $this->loadRosters();
    }

    public function loadRosters()
    {
        $this->rosters = Roster::with(['user', 'shift', 'post'])
            ->whereDate('date', $this->selectedDate)
            ->when($this->selectedShift, function ($query) {
                $query->where('shift_id', $this->selectedShift);
            })
            ->get();
    }

    public function assignPost($postId)
    {
        $roster = Roster::find($this->draggedRosterId);
        if ($roster) {
            $roster->post_id = $postId;
            $roster->save();
            $this->loadRosters();
            $this->search = ''; // Reset search after assigning
        }
    }

    public function removePost($rosterId)
    {
        $roster = Roster::find($rosterId);
        if ($roster) {
            $roster->post_id = null;
            $roster->save();
            $this->loadRosters();
        }
    }

    public function render()
    {
        $unassignedRosters = $this->rosters->filter(fn($roster) => $roster->post_id === null);

        if (strlen($this->search) > 0) {
            $unassignedRosters = $unassignedRosters->filter(function ($roster) {
                return str_contains(strtolower($roster->user->name), strtolower($this->search));
            });
        }

        $assignedRosters = $this->rosters->filter(fn($roster) => $roster->post_id !== null)
                                        ->groupBy('post_id');

        return view('livewire.post-assignment', [
            'unassignedRosters' => $unassignedRosters,
            'assignedRosters' => $assignedRosters,
        ]);
    }
}
