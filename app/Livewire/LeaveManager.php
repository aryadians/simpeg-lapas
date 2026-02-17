<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\LeaveRequestSubmitted;
use Livewire\Attributes\On;
use App\Notifications\GeneralNotification;

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
        $req = LeaveRequest::findOrFail($id);
        $req->update(['status' => 'approved']);
        
        // Notifikasi ke User
        $req->user->notify(new GeneralNotification('Permohonan izin Anda telah DISETUJUI.'));

        $this->dispatch('flash-message', text: 'Permohonan izin telah disetujui!');
    }

    public function reject($id)
    {
        $this->dispatch('confirm-dialog', title: 'Tolak Izin?', text: 'Anda yakin ingin menolak permohonan izin ini?', confirm_event: 'rejectConfirmed', confirm_params: $id);
    }

    #[On('rejectConfirmed')]
    public function rejectConfirmed($id)
    {
        $req = LeaveRequest::findOrFail($id);
        $req->update(['status' => 'rejected']);

        // Notifikasi ke User
        $req->user->notify(new GeneralNotification('Permohonan izin Anda DITOLAK.'));

        $this->dispatch('flash-message', type: 'info', title: 'Izin Ditolak', text: 'Permohonan izin telah ditolak.');
    }

    public function submitRequest()
    {
        $this->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'reason'     => 'required|string|max:255',
        ]);

        $leaveRequest = LeaveRequest::create([
            'user_id'    => Auth::id(),
            'start_date' => $this->start_date,
            'end_date'   => $this->end_date,
            'reason'     => $this->reason,
            'status'     => 'pending'
        ]);

        $admins = User::where('role', 'admin')->get();

        // Notifikasi Database ke Admin
        foreach ($admins as $admin) {
            $admin->notify(new GeneralNotification(Auth::user()->name . ' mengajukan izin baru.'));
        }

        // Kirim email notifikasi ke semua admin
        try {
            foreach ($admins as $admin) {
                if($admin->email) {
                    Mail::to($admin->email)->send(new LeaveRequestSubmitted($leaveRequest));
                }
            }
        } catch (\Exception $e) { }

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
