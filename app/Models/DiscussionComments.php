<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiscussionComments extends Model
{
    use HasFactory;

    public function discussions() : BelongsTo
    {
        return $this->belongsTo(Discussions::class);
    }
}
