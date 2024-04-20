<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Discussions extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(Supervisors::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(DiscussionComments::class, 'discussion_id');
    }
}
