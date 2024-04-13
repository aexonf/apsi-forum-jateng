<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Supervisors extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function discussions() : BelongsTo
    {
        return $this->belongsTo(Discussions::class);
    }
}
