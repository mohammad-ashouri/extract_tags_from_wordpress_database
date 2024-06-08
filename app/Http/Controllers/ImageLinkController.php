<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageLinkController extends Controller
{
    public function extractImageLinks(Request $request)
    {
        $htmlContent = $request->input('html');

        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);

        $dom->loadHTML(mb_convert_encoding($htmlContent, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $xpath = new \DOMXPath($dom);
        $nodes = $xpath->query('//a[img]');

        $imageLinks = [];

        foreach ($nodes as $node) {
            $a_href = $node->getAttribute('href');
            $img = $node->getElementsByTagName('img')->item(0);
            if ($img) {
                $img_src = $img->getAttribute('src');
                if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $a_href) && preg_match('/\.(jpg|jpeg|png|gif)$/i', $img_src)) {
                    $imageLinks[] = [
                        'a_href' => str_replace('https://ofull.ir/wp-content/uploads/', '/storage/old_images/', $a_href),
                        'img_src' => str_replace('https://ofull.ir/wp-content/uploads/', '/storage/old_images/', $img_src)
                    ];
                    $node->parentNode->removeChild($node);
                }
            }
        }

        $bodyContent = '';
        $body = $dom->getElementsByTagName('body')->item(0);
        if ($body) {
            foreach ($body->childNodes as $childNode) {
                $bodyContent .= $dom->saveHTML($childNode);
            }
        } else {
            $bodyContent = $dom->saveHTML();
        }

        $cleanedHtml = html_entity_decode($bodyContent, ENT_QUOTES, 'UTF-8');

        return response()->json([
            'imageLinks' => $imageLinks,
            'cleanedHtml' => $cleanedHtml
        ]);
    }
}
