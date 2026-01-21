<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code'];

    /**
     * Get all of the rosters for the Post
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rosters(): HasMany
    {
        return $this->hasMany(Roster::class);
    }
}
