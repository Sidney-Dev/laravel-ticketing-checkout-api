<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'start_date',
        'user_id',
    ];


    /**
     * Get the user that created the event.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the tickets for the event.
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
