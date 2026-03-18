<?php

namespace App\Http\Controllers;

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
        $currentDate = now()->toAtomString();
        $locales = config('app.supported_locales', ['tr', 'en']);

        $urls = [];
        foreach ($locales as $locale) {
            $urls[] = ['loc' => $baseUrl . $locale, 'lastmod' => $currentDate];
        }

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
