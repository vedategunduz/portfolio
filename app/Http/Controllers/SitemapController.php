<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Generate and return the XML sitemap.
     *
     * @return Response
     */
    public function index(): Response
    {
        $sitemap = $this->generateSitemap();

        return response($sitemap, 200)
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Generate sitemap XML content.
     * Only canonical, crawlable URLs; no hash/anchor links (#about, #contact, etc.).
     *
     * @return string
     */
    private function generateSitemap(): string
    {
        $baseUrl = rtrim(config('app.url'), '/') . '/';
        $locales = config('app.supported_locales', ['tr', 'en']);

        $urls = [];
        foreach ($locales as $locale) {
            $urls[] = ['loc' => $baseUrl . $locale, 'lastmod' => now()->toAtomString()];
            $urls[] = ['loc' => $baseUrl . 'blog', 'lastmod' => now()->toAtomString()];
        }

        $publishedPosts = Post::query()
            ->published()
            ->with('translations')
            ->get();

        foreach ($publishedPosts as $post) {
            foreach ($locales as $locale) {
                $translation = $post->translations->firstWhere('locale', $locale);
                $slug = $translation?->slug;

                if (! is_string($slug) || trim($slug) === '') {
                    continue;
                }

                $urls[] = [
                    'loc' => $baseUrl . 'blog/' . rawurlencode($slug),
                    'lastmod' => ($post->updated_at ?? $post->published_at ?? now())->toAtomString(),
                ];
            }
        }

        $urls = collect($urls)
            ->unique('loc')
            ->values()
            ->all();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        foreach ($urls as $url) {
            $xml .= '    <url>' . PHP_EOL;
            $xml .= '        <loc>' . htmlspecialchars($url['loc']) . '</loc>' . PHP_EOL;
            $xml .= '        <lastmod>' . $url['lastmod'] . '</lastmod>' . PHP_EOL;
            $xml .= '    </url>' . PHP_EOL;
        }

        $xml .= '</urlset>';

        return $xml;
    }
}
