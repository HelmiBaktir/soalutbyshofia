<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $urls = [
            ['loc' => route('catalog.index'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'daily', 'priority' => '1.0'],
            ['loc' => route('page.about'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'monthly', 'priority' => '0.5'],
            ['loc' => route('page.contact'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'monthly', 'priority' => '0.5'],
            ['loc' => route('page.terms'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'yearly', 'priority' => '0.3'],
            ['loc' => route('page.refund'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'yearly', 'priority' => '0.3'],
            ['loc' => route('login'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'yearly', 'priority' => '0.4'],
            ['loc' => route('register'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'yearly', 'priority' => '0.4'],
        ];

        foreach (Course::query()->orderBy('code')->get(['id', 'code', 'updated_at']) as $course) {
            $urls[] = [
                'loc' => route('catalog.show', $course),
                'lastmod' => $course->updated_at->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ];
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

        foreach ($urls as $url) {
            $xml .= '  <url>'."\n";
            $xml .= '    <loc>'.e($url['loc']).'</loc>'."\n";
            $xml .= '    <lastmod>'.e($url['lastmod']).'</lastmod>'."\n";
            $xml .= '    <changefreq>'.e($url['changefreq']).'</changefreq>'."\n";
            $xml .= '    <priority>'.e($url['priority']).'</priority>'."\n";
            $xml .= '  </url>'."\n";
        }

        $xml .= '</urlset>';

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }
}
