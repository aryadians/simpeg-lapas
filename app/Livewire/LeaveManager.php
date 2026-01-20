<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\LeaveRequest;
use Illuminate\Support\Facades\Auth;

class LeaveManager extends Component
{
    public $start_date, $end_date, $reason;

    // Untuk Admin Approval
    public function approve($id)
    {
        LeaveRequest::find($id)->update(['status' => 'approved']);
        $this->dispatch('roster-updated', message: 'Izin disetujui!');
    }

    public function reject($id)
    {
        LeaveRequest::find($id)->update(['status' => 'rejected']);
        $this->dispatch('roster-updated', message: 'Izin ditolak.');
    }

    // Untuk Submit Request
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
        $this->dispatch('roster-updated', message: 'Pengajuan cuti berhasil dikirim!');
    }

    public function render()
    {
        $user = Auth::user();

        // Logika Data: Kalau Admin (Jabatan Kalapas/KPLP) bisa lihat semua yg Pending
        // Kalau kroco, cuma lihat punya sendiri.
        // (Sederhananya kita anggap email admin@lapas.com adalah Admin)

        $isAdmin = $user->email === 'admin@lapas.com';

        $myRequests = LeaveRequest::where('user_id', $user->id)->latest()->get();

        $pendingRequests = [];
        if ($isAdmin) {
            $pendingRequests = LeaveRequest::where('status', 'pending')->with('user')->get();
        }

        return view('livewire.leave-manager', [
            'myRequests' => $myRequests,
            'pendingRequests' => $pendingRequests,
            'isAdmin' => $isAdmin
        ])->layout('components.layouts.app');
    }
}
