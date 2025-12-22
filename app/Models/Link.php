<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Link extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'url', 'user_id'];

    // Relación: Un link pertenece a un Usuario
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}