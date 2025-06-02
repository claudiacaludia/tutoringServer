<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    protected $fillable = ['description','price','status','proposed_time','tutor_id','student_id','topic_id'];

    public function tutor():BelongsTo {
        return $this->belongsTo(User::class, 'tutor_id');
    }

    public function student():BelongsTo {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function topic(): BelongsTo {
        return $this->belongsTo(Topic::class);
    }
}
