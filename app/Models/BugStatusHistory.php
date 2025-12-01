<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BugStatusHistory extends Model
{
    use HasFactory;

    protected $table = 'bug_status_histories';
    protected $fillable = [
        'bug_id',
        'user_id',
        'old_status',
        'new_status',
        'notes',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relasi: History belongs to Bug
    public function bug()
    {
        return $this->belongsTo(Bug::class);
    }

    // Relasi: History belongs to User (who changed it)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
