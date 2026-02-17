<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\LogsActivity;

class LeaveRequest extends Model
{
    use HasFactory, LogsActivity;

    protected $guarded = []; // Izinkan semua kolom diisi

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
