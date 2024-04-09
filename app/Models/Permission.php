<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    protected $fillable = ['key'];

    public function user(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
