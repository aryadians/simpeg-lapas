<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Document extends Model
{
    use LogsActivity;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
