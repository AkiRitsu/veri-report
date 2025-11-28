<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'client_name',
        'model_name',
        'device_serial_id',
        'device_type',
        'problem_description',
        'fix_description',
        'phone_number',
        'client_email',
        'status',
        'additional_notes',
        'pdf_hash',
        'completed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'completed_at' => 'datetime',
    ];

    /**
     * Get the user that owns the report.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mark the report as complete.
     */
    public function markAsComplete()
    {
        $this->update([
            'status' => 'complete',
            'completed_at' => now(),
        ]);
    }
}

