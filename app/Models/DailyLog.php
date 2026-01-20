<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyLog extends Model
{
    use HasFactory;

    // FIX: Buka gembok Mass Assignment
    // Artinya: Tidak ada kolom yang dijaga, semua boleh diisi.
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
