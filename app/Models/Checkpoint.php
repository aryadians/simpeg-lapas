<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Checkpoint extends Model
{
    protected $guarded = [];

    public function patrolLogs()
    {
        return $this->hasMany(PatrolLog::class);
    }
}
