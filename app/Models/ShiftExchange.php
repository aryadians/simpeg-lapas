<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class ShiftExchange extends Model
{
    use LogsActivity;

    protected $guarded = [];

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function targetUser()
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }

    public function rosterFrom()
    {
        return $this->belongsTo(Roster::class, 'roster_id_from');
    }

    public function rosterTo()
    {
        return $this->belongsTo(Roster::class, 'roster_id_to');
    }
}
