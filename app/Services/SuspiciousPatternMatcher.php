<?php

namespace App\Services;

/**
 * Şüpheli path/query pattern eşleştirme. Config'deki kuralları kullanır.
 * Sadece eşleşme kontrolü; sınıflandırma VisitorClassificationService'de.
 */
class SuspiciousPatternMatcher
{
    public function __construct(
        protected array $pathPatterns,
        protected array $queryPatterns
    ) {
    }

    /**
     * Path şüpheli mi? İlk eşleşen kural (pattern veya key) döner.
     */
    public function matchPath(string $path, string $queryString = ''): ?string
    {
        $combined = $path . ($queryString ? '?' . $queryString : '');
        foreach ($this->pathPatterns as $name => $pattern) {
            $regex = is_string($pattern) && str_starts_with($pattern, '#') ? $pattern : '#'.preg_quote($pattern, '#').'#i';
            if (preg_match($regex, $combined)) {
                return is_int($name) ? $pattern : $name;
            }
        }
        return null;
    }

    /**
     * Query/path içinde exploit çağrışımlı kelime var mı?
     */
    public function matchQueryOrBody(string $path, string $queryString = ''): ?string
    {
        $combined = $path . ' ' . $queryString;
        $lower = mb_strtolower($combined);
        foreach ($this->queryPatterns as $pattern) {
            $needle = is_array($pattern) ? ($pattern['pattern'] ?? $pattern) : $pattern;
            if (mb_strpos($lower, mb_strtolower($needle)) !== false) {
                return is_array($pattern) ? ($pattern['name'] ?? $needle) : $needle;
            }
        }
        return null;
    }

    /**
     * Hem path hem query kontrolü; ilk şüpheli eşleşmeyi döner. [rule => matched_rule]
     */
    public function firstMatch(string $path, string $queryString = ''): ?array
    {
        $pathRule = $this->matchPath($path, $queryString);
        if ($pathRule !== null) {
            return ['rule' => $pathRule, 'type' => 'path'];
        }
        $queryRule = $this->matchQueryOrBody($path, $queryString);
        if ($queryRule !== null) {
            return ['rule' => $queryRule, 'type' => 'query'];
        }
        return null;
    }
}
