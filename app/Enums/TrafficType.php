<?php

namespace App\Enums;

/**
 * İstek trafik tipi: sınıflandırma çıktısı.
 */
enum TrafficType: string
{
    case Human = 'human';
    case KnownBot = 'known_bot';
    case SuspiciousBot = 'suspicious_bot';
    case Monitoring = 'monitoring';
    case Internal = 'internal';

    public function label(): string
    {
        return match ($this) {
            self::Human => 'İnsan',
            self::KnownBot => 'Bilinen Bot',
            self::SuspiciousBot => 'Şüpheli Bot',
            self::Monitoring => 'İzleme',
            self::Internal => 'Dahili',
        };
    }
}
