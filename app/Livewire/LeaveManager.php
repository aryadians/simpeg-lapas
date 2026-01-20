<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\LeaveRequest;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class LeaveManager extends Component
{
    public $start_date, $end_date, $reason;

    public function approve($id)
    {
        $this->dispatch('confirm-dialog', title: 'Setujui Izin?', text: 'Anda yakin ingin menyetujui permohonan izin ini?', confirm_event: 'approveConfirmed', confirm_params: $id);
    }

    #[On('approveConfirmed')]
    public function approveConfirmed($id)
    {
        LeaveRequest::find($id)->update(['status' => 'approved']);
        $this->dispatch('flash-message', text: 'Permohonan izin telah disetujui!');
    }

    public function reject($id)
    {
        $this->dispatch('confirm-dialog', title: 'Tolak Izin?', text: 'Anda yakin ingin menolak permohonan izin ini?', confirm_event: 'rejectConfirmed', confirm_params: $id);
    }

    #[On('rejectConfirmed')]
    public function rejectConfirmed($id)
    {
        LeaveRequest::find($id)->update(['status' => 'rejected']);
        $this->dispatch('flash-message', type: 'info', title: 'Izin Ditolak', text: 'Permohonan izin telah ditolak.');
    }

    public function submitRequest()
    {
        $this->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'reason'     => 'required|string|max:255',
        ]);

        LeaveRequest::create([
            'user_id'    => Auth::id(),
            'start_date' => $this->start_date,
            'end_date'   => $this->end_date,
            'reason'     => $this->reason,
            'status'     => 'pending'
        ]);

        $this->reset(['start_date', 'end_date', 'reason']);
        $this->dispatch('flash-message', text: 'Pengajuan cuti Anda telah berhasil dikirim!');
    }

    public function render()
    {
        $user = Auth::user();
        $isAdmin = $user->role === 'admin';
        $myRequests = LeaveRequest::where('user_id', $user->id)->latest()->get();

        $pendingRequests = [];
        if ($isAdmin) {
            $pendingRequests = LeaveRequest::where('status', 'pending')->with('user')->latest()->get();
        }

        return view('livewire.leave-manager', [
            'myRequests' => $myRequests,
            'pendingRequests' => $pendingRequests,
            'isAdmin' => $isAdmin
        ])->layout('components.layouts.app');
    }
}
