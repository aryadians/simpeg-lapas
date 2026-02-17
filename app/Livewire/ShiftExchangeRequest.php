<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ShiftExchange;
use App\Models\Roster;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Notifications\GeneralNotification;

class ShiftExchangeRequest extends Component
{
    public $myRosters;
    public $selectedRosterId;
    
    public $targetUsers;
    public $selectedTargetUserId;
    
    public $targetRosters;
    public $selectedTargetRosterId;
    
    public $reason;

    public function mount()
    {
        $this->myRosters = Roster::where('user_id', Auth::id())
            ->where('date', '>=', Carbon::today())
            ->with('shift')
            ->orderBy('date')
            ->get();
            
        $this->targetUsers = User::where('id', '!=', Auth::id())->orderBy('name')->get();
        $this->targetRosters = collect();
    }

    public function updatedSelectedTargetUserId($value)
    {
        if ($value) {
            $this->targetRosters = Roster::where('user_id', $value)
                ->where('date', '>=', Carbon::today())
                ->with('shift')
                ->orderBy('date')
                ->get();
        } else {
            $this->targetRosters = collect();
        }
    }

    public function submitRequest()
    {
        $this->validate([
            'selectedRosterId' => 'required|exists:rosters,id',
            'selectedTargetUserId' => 'required|exists:users,id',
            'selectedTargetRosterId' => 'nullable|exists:rosters,id',
            'reason' => 'required|string|max:255',
        ]);

        $myRoster = Roster::find($this->selectedRosterId);
        if ($myRoster->user_id !== Auth::id()) {
            $this->addError('selectedRosterId', 'Jadwal ini bukan milik Anda.');
            return;
        }

        ShiftExchange::create([
            'requester_id' => Auth::id(),
            'target_user_id' => $this->selectedTargetUserId,
            'roster_id_from' => $this->selectedRosterId,
            'roster_id_to' => $this->selectedTargetRosterId,
            'reason' => $this->reason,
            'status' => 'pending'
        ]);

        // Notifikasi ke Target
        $target = User::find($this->selectedTargetUserId);
        $target->notify(new GeneralNotification(
            Auth::user()->name . ' meminta tukar dinas dengan Anda.',
            route('shift.exchange')
        ));

        $this->reset(['selectedRosterId', 'selectedTargetUserId', 'selectedTargetRosterId', 'reason', 'targetRosters']);
        $this->dispatch('flash-message', text: 'Permintaan tukar dinas berhasil dikirim.');
    }

    public function approveRequest($id)
    {
        $exchange = ShiftExchange::find($id);
        if ($exchange->target_user_id !== Auth::id()) return;

        $exchange->update(['status' => 'approved_by_target']);

        // Notifikasi ke Requester
        $exchange->requester->notify(new GeneralNotification(
            Auth::user()->name . ' menyetujui pertukaran dinas. Menunggu verifikasi Admin.',
            route('shift.exchange')
        ));

        // Notifikasi ke Admin
        $admins = User::where('role', 'admin')->get();
        foreach($admins as $admin) {
            $admin->notify(new GeneralNotification('Request tukar dinas butuh otorisasi.'));
        }

        $this->dispatch('flash-message', text: 'Anda menyetujui pertukaran. Menunggu admin.');
    }

    public function rejectRequest($id)
    {
        $exchange = ShiftExchange::find($id);
        if ($exchange->target_user_id !== Auth::id() && $exchange->requester_id !== Auth::id()) return;

        $exchange->update(['status' => 'rejected']);
        $this->dispatch('flash-message', type: 'info', text: 'Permintaan dibatalkan/ditolak.');
    }

    public function adminApprove($id)
    {
        if (Auth::user()->role !== 'admin') return;

        $exchange = ShiftExchange::find($id);
        
        $rosterFrom = Roster::find($exchange->roster_id_from);
        $rosterTo = $exchange->roster_id_to ? Roster::find($exchange->roster_id_to) : null;

        $rosterFrom->update(['user_id' => $exchange->target_user_id]);
        if ($rosterTo) {
            $rosterTo->update(['user_id' => $exchange->requester_id]);
        }

        $exchange->update(['status' => 'approved_by_admin']);

        $exchange->requester->notify(new GeneralNotification('Swap disetujui Admin.'));
        $exchange->targetUser->notify(new GeneralNotification('Swap disetujui Admin.'));

        $this->dispatch('flash-message', text: 'Pertukaran resmi disetujui.');
    }

    public function render()
    {
        $incomingRequests = ShiftExchange::where('target_user_id', Auth::id())
            ->where('status', 'pending')
            ->with(['requester', 'rosterFrom', 'rosterTo'])
            ->get();

        $myRequests = ShiftExchange::where('requester_id', Auth::id())
            ->with(['targetUser', 'rosterFrom', 'rosterTo'])
            ->latest()
            ->get();
            
        $adminPendingRequests = [];
        if (Auth::user()->role === 'admin') {
            $adminPendingRequests = ShiftExchange::where('status', 'approved_by_target')
                ->with(['requester', 'targetUser', 'rosterFrom', 'rosterTo'])
                ->get();
        }

        return view('livewire.shift-exchange-request', [
            'incomingRequests' => $incomingRequests,
            'myRequests' => $myRequests,
            'adminPendingRequests' => $adminPendingRequests
        ])->layout('components.layouts.app');
    }
}
