<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\URL;

class Entitlement extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'order_id',
        'course_id',
        'granted_at',
        'download_expires_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'granted_at' => 'datetime',
            'download_expires_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function downloadLogs(): HasMany
    {
        return $this->hasMany(DownloadLog::class);
    }

    /**
     * A download is allowed only while download_expires_at is still in the future.
     * The PDF viewer access can remain even after this date.
     */
    public function isDownloadActive(): bool
    {
        return $this->download_expires_at !== null
            && $this->download_expires_at->isFuture();
    }

    /**
     * URL download yang valid untuk entitlement ini.
     * User login memakai route biasa; guest memakai signed URL.
     */
    public function downloadUrl(): ?string
    {
        if (! $this->isDownloadActive()) {
            return null;
        }

        if ($this->user_id) {
            return route('course.download', ['course' => $this->course_id]);
        }

        return URL::temporarySignedRoute(
            'course.download.guest',
            $this->download_expires_at,
            ['order' => $this->order_id, 'course' => $this->course_id]
        );
    }
}
