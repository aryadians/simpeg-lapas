<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'serial_number',
        'status',
        'current_holder_id',
        'checked_out_at',
        'due_at',
    ];

    protected $casts = [
        'checked_out_at' => 'datetime',
        'due_at' => 'datetime',
    ];

    public function holder()
    {
        return $this->belongsTo(User::class, 'current_holder_id');
    }

    public function logs()
    {
        return $this->hasMany(InventoryLog::class)->latest('action_at');
    }
}
