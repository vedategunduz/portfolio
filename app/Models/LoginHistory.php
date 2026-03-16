<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoginHistory extends Model
{
    public const TYPE_SUCCESS = 'success';
    public const TYPE_FAILED = 'failed';

    public const FAILURE_REASON_WRONG_PASSWORD = 'wrong_password';
    public const FAILURE_REASON_USER_NOT_FOUND = 'user_not_found';

    protected $table = 'login_histories';

    protected $fillable = [
        'user_id',
        'email',
        'ip_address',
        'user_agent',
        'type',
        'failure_reason',
        'attempted_at',
    ];

    protected $casts = [
        'attempted_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isSuccess(): bool
    {
        return $this->type === self::TYPE_SUCCESS;
    }

    public function isFailed(): bool
    {
        return $this->type === self::TYPE_FAILED;
    }

    public function getFailureReasonLabel(): ?string
    {
        return match ($this->failure_reason) {
            self::FAILURE_REASON_WRONG_PASSWORD => 'Yanlış şifre',
            self::FAILURE_REASON_USER_NOT_FOUND => 'Kullanıcı bulunamadı',
            default => $this->failure_reason,
        };
    }

    public static function logSuccess(User $user, string $ipAddress = null, string $userAgent = null): self
    {
        return self::create([
            'user_id' => $user->id,
            'email' => $user->email,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'type' => self::TYPE_SUCCESS,
            'failure_reason' => null,
            'attempted_at' => now(),
        ]);
    }

    public static function logFailed(string $email, string $failureReason, string $ipAddress = null, string $userAgent = null): self
    {
        return self::create([
            'user_id' => null,
            'email' => $email,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'type' => self::TYPE_FAILED,
            'failure_reason' => $failureReason,
            'attempted_at' => now(),
        ]);
    }
}
