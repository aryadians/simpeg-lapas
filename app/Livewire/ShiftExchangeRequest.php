<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ShiftExchange;
use App\Models\Roster;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
        // Ambil jadwal saya yang akan datang
        $this->myRosters = Roster::where('user_id', Auth::id())
            ->where('date', '>=', Carbon::today())
            ->with('shift')
            ->orderBy('date')
            ->get();
            
        // Ambil user lain kecuali diri sendiri
        $this->targetUsers = User::where('id', '!=', Auth::id())->orderBy('name')->get();
        $this->targetRosters = collect();
    }

    public function updatedSelectedTargetUserId($value)
    {
        if ($value) {
            // Ambil jadwal target user yang akan datang
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

        // Validasi kepemilikan jadwal
        $myRoster = Roster::find($this->selectedRosterId);
        if ($myRoster->user_id !== Auth::id()) {
            $this->addError('selectedRosterId', 'Jadwal ini bukan milik Anda.');
            return;
        }

        if ($this->selectedTargetRosterId) {
            $targetRoster = Roster::find($this->selectedTargetRosterId);
            if ($targetRoster->user_id != $this->selectedTargetUserId) {
                $this->addError('selectedTargetRosterId', 'Jadwal target tidak valid.');
                return;
            }
        }

        ShiftExchange::create([
            'requester_id' => Auth::id(),
            'target_user_id' => $this->selectedTargetUserId,
            'roster_id_from' => $this->selectedRosterId,
            'roster_id_to' => $this->selectedTargetRosterId, // Bisa null jika cuma minta gantiin tanpa tukar
            'reason' => $this->reason,
            'status' => 'pending'
        ]);

        $this->reset(['selectedRosterId', 'selectedTargetUserId', 'selectedTargetRosterId', 'reason', 'targetRosters']);
        $this->dispatch('flash-message', text: 'Permintaan tukar dinas berhasil dikirim.');
    }

    // Aksi untuk Target User (Setujui/Tolak)
    public function approveRequest($id)
    {
        $exchange = ShiftExchange::find($id);
        
        // Pastikan yang approve adalah target user
        if ($exchange->target_user_id !== Auth::id()) return;

        $exchange->update(['status' => 'approved_by_target']);
        $this->dispatch('flash-message', text: 'Anda menyetujui pertukaran. Menunggu admin.');
    }

    public function rejectRequest($id)
    {
        $exchange = ShiftExchange::find($id);
        if ($exchange->target_user_id !== Auth::id() && $exchange->requester_id !== Auth::id()) return; // Requester juga bisa batalkan

        $exchange->update(['status' => 'rejected']);
        $this->dispatch('flash-message', type: 'info', text: 'Permintaan dibatalkan/ditolak.');
    }

    // Aksi untuk Admin (Final Approve)
    public function adminApprove($id)
    {
        if (Auth::user()->role !== 'admin') return;

        $exchange = ShiftExchange::find($id);
        
        // Lakukan Pertukaran Jadwal di Tabel Roster
        $rosterFrom = Roster::find($exchange->roster_id_from);
        $rosterTo = $exchange->roster_id_to ? Roster::find($exchange->roster_id_to) : null;

        // Tukar User ID
        // Roster From (Punya Requester) -> Jadi Punya Target
        $rosterFrom->update(['user_id' => $exchange->target_user_id]);

        if ($rosterTo) {
            // Roster To (Punya Target) -> Jadi Punya Requester
            $rosterTo->update(['user_id' => $exchange->requester_id]);
        }

        $exchange->update(['status' => 'approved_by_admin']);
        $this->dispatch('flash-message', text: 'Pertukaran jadwal resmi disetujui & diperbarui.');
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
