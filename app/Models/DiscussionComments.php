<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiscussionComments extends Model
{
    use HasFactory;

    protected $table = "discussion_comments";

    protected $guarded = [];

    public function supervisors() : BelongsTo
    {
        return $this->belongsTo(Supervisors::class, "supervisor_id");
    }

    public function discussions() : BelongsTo
    {
        return $this->belongsTo(Discussions::class);
    }

}
