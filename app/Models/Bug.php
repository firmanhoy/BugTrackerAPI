<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bug extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'reproduction_steps',
        'severity',
        'status',
        'reporter_id',
        'assignee_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relasi: Bug belongs to reporter (User)
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    // Relasi: Bug belongs to assignee (User)
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    // Relasi: Bug has many comments
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Relasi: Bug has many attachments
    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    // Relasi: Bug has many status histories
    public function statusHistories()
    {
        return $this->hasMany(BugStatusHistory::class);
    }

    // Scope: Filter by status
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Scope: Filter by severity
    public function scopeBySeverity($query, $severity)
    {
        return $query->where('severity', $severity);
    }

    // Scope: Filter by assignee
    public function scopeByAssignee($query, $assigneeId)
    {
        return $query->where('assignee_id', $assigneeId);
    }

    // Scope: Filter by date range
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }
}
