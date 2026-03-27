<?php

namespace Tests\Architecture;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ModuleDependencyRulesTest extends TestCase
{
    private const MODULES_PATH = __DIR__ . '/../../Modules';

    /**
     * Module-level dependency guard:
     * - A module must not import another business module directly.
     * - Module code must not depend on App\Models except approved shared models.
     */
    #[Test]
    public function modules_follow_dependency_rules(): void
    {
        $violations = [];
        $moduleNames = $this->discoverModuleNames();

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(self::MODULES_PATH)
        );

        foreach ($iterator as $file) {
            if (! $file instanceof \SplFileInfo || ! $file->isFile()) {
                continue;
            }

            if ($file->getExtension() !== 'php') {
                continue;
            }

            $path = $file->getPathname();
            $module = $this->resolveOwningModule($path);
            if ($module === null) {
                continue;
            }

            $content = file_get_contents($path);
            if (! is_string($content)) {
                continue;
            }

            foreach ($moduleNames as $candidate) {
                if ($candidate === $module) {
                    continue;
                }

                // Orchestration modules may compose other modules.
                if (in_array($module, ['Admin', 'PublicSite'], true)) {
                    continue;
                }

                if (preg_match('/^\s*use\s+Modules\\\\'.$candidate.'\\\\/m', $content) === 1) {
                    $violations[] = $this->relativePath($path)." imports Modules\\{$candidate}\\*";
                }
            }

            if (preg_match_all('/^\s*use\s+App\\\\Models\\\\([A-Za-z0-9_]+)\s*;/m', $content, $matches) === false) {
                continue;
            }

            $approvedSharedModels = ['User'];
            foreach ($matches[1] as $modelName) {
                if (! in_array($modelName, $approvedSharedModels, true)) {
                    $violations[] = $this->relativePath($path)." imports App\\Models\\{$modelName}";
                }
            }
        }

        $this->assertSame(
            [],
            $violations,
            "Module dependency violations found:\n- ".implode("\n- ", $violations)
        );
    }

    /**
     * @return array<int, string>
     */
    private function discoverModuleNames(): array
    {
        $names = [];
        $entries = scandir(self::MODULES_PATH);
        if (! is_array($entries)) {
            return [];
        }

        foreach ($entries as $entry) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }

            if (is_dir(self::MODULES_PATH.'/'.$entry)) {
                $names[] = $entry;
            }
        }

        sort($names);

        return $names;
    }

    private function resolveOwningModule(string $absolutePath): ?string
    {
        if (preg_match('#/Modules/([^/]+)/#', $absolutePath, $matches) !== 1) {
            return null;
        }

        return $matches[1];
    }

    private function relativePath(string $absolutePath): string
    {
        $root = realpath(__DIR__ . '/../../');
        $realPath = realpath($absolutePath);
        if (! is_string($root) || ! is_string($realPath)) {
            return $absolutePath;
        }

        return ltrim(str_replace($root, '', $realPath), '/');
    }
}
