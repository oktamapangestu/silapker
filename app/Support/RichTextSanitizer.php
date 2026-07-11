<?php

namespace App\Support;

use DOMDocument;
use DOMNode;

class RichTextSanitizer
{
    private const ALLOWED_TAGS = ['p', 'br', 'strong', 'em', 'u', 's', 'ol', 'ul', 'li'];

    private const DISCARDED_TAGS = ['script', 'style'];

    /**
     * Rebuild the given HTML keeping only whitelisted tags with no attributes at all,
     * so pasted markup can't smuggle event-handler attributes or javascript: URLs.
     */
    public static function clean(string $html): string
    {
        $dom = new DOMDocument();

        libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="utf-8"?><div>'.$html.'</div>', LIBXML_NOERROR | LIBXML_NOWARNING);
        libxml_clear_errors();

        $wrapper = $dom->getElementsByTagName('div')->item(0);

        return $wrapper ? self::cleanNode($wrapper) : '';
    }

    private static function cleanNode(DOMNode $node): string
    {
        $html = '';

        foreach (iterator_to_array($node->childNodes) as $child) {
            if ($child->nodeType === XML_TEXT_NODE) {
                $html .= htmlspecialchars($child->textContent, ENT_QUOTES, 'UTF-8');

                continue;
            }

            if ($child->nodeType !== XML_ELEMENT_NODE) {
                continue;
            }

            $tag = strtolower($child->nodeName);

            if (in_array($tag, self::DISCARDED_TAGS, true)) {
                continue;
            }

            $inner = self::cleanNode($child);

            if (! in_array($tag, self::ALLOWED_TAGS, true)) {
                $html .= $inner;

                continue;
            }

            $html .= $tag === 'br' ? '<br>' : "<{$tag}>{$inner}</{$tag}>";
        }

        return $html;
    }
}
