<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SessionManager extends Component
{
    public function getSessionsProperty()
    {
        return DB::table('sessions')
            ->where('user_id', Auth::id())
            ->orderBy('last_activity', 'desc')
            ->get();
    }

    public function logoutOtherDevices()
    {
        $currentSessionId = Session::getId();

        DB::table('sessions')
            ->where('user_id', Auth::id())
            ->where('id', '!=', $currentSessionId)
            ->delete();

        $this->dispatch('flash-message', text: 'Semua sesi di perangkat lain telah diakhiri.');
    }

    public function logoutSession($id)
    {
        DB::table('sessions')->where('id', $id)->delete();
        $this->dispatch('flash-message', text: 'Sesi perangkat berhasil diakhiri.');
    }

    public function render()
    {
        return view('livewire.session-manager');
    }
}
