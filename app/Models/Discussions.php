<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Discussions extends Model
{
    use HasFactory;

    public function supervisor() : BelongsTo
    {
        return $this->belongsTo(Supervisors::class);
    }

    public function comments() : BelongsTo
    {
        return $this->belongsTo(DiscussionComments::class);
    }
}
