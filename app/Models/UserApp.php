<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserApp extends Model
{
    protected $fillable = ['user_id', 'app_slug'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
