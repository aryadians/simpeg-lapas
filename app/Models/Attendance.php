<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $guarded = []; // Izinkan mass assignment

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
