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
     *
     * @return string
     */
    private function generateSitemap(): string
    {
        $baseUrl = config('app.url');
        $currentDate = now()->toAtomString();

        $urls = [
            [
                'loc' => $baseUrl,
                'lastmod' => $currentDate,
                'changefreq' => 'weekly',
                'priority' => '1.0',
            ],
            [
                'loc' => $baseUrl . '#about',
                'lastmod' => $currentDate,
                'changefreq' => 'monthly',
                'priority' => '0.8',
            ],
            [
                'loc' => $baseUrl . '#projects',
                'lastmod' => $currentDate,
                'changefreq' => 'weekly',
                'priority' => '0.9',
            ],
            [
                'loc' => $baseUrl . '#experience',
                'lastmod' => $currentDate,
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ],
            [
                'loc' => $baseUrl . '#contact',
                'lastmod' => $currentDate,
                'changefreq' => 'monthly',
                'priority' => '0.6',
            ],
        ];

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        foreach ($urls as $url) {
            $xml .= '    <url>' . PHP_EOL;
            $xml .= '        <loc>' . htmlspecialchars($url['loc']) . '</loc>' . PHP_EOL;
            $xml .= '        <lastmod>' . $url['lastmod'] . '</lastmod>' . PHP_EOL;
            $xml .= '        <changefreq>' . $url['changefreq'] . '</changefreq>' . PHP_EOL;
            $xml .= '        <priority>' . $url['priority'] . '</priority>' . PHP_EOL;
            $xml .= '    </url>' . PHP_EOL;
        }

        $xml .= '</urlset>';

        return $xml;
    }
}
