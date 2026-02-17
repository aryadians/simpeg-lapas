<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatrolLog extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function checkpoint()
    {
        return $this->belongsTo(Checkpoint::class);
    }
}
