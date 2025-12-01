<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'filename',
        'file_path',
        'mime_type',
        'file_size',
        'bug_id',
        'user_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relasi: Attachment belongs to Bug
    public function bug()
    {
        return $this->belongsTo(Bug::class);
    }

    // Relasi: Attachment belongs to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
