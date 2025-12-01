<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'bug_id',
        'user_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relasi: Comment belongs to Bug
    public function bug()
    {
        return $this->belongsTo(Bug::class);
    }

    // Relasi: Comment belongs to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
