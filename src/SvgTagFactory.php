<?php

/**
 * TOBENTO
 *
 * @copyright   Tobias Strub, TOBENTO
 * @license     MIT License, see LICENSE file distributed with this source code.
 * @author      Tobias Strub
 * @link        https://www.tobento.ch
 */

declare(strict_types=1);

namespace Tobento\Service\Icon;

use Tobento\Service\Tag\TagFactory;
use Tobento\Service\Tag\TagInterface;
use Tobento\Service\Tag\Attributes;
use Tobento\Service\Tag\CreateTagException;
use Stringable;
use SimpleXMLElement;

/**
 * SvgTagFactory
 */
class SvgTagFactory extends TagFactory
{
    /**
     * Create a new Tag from the specified html.
     *
     * @param string|Stringable $html
     * @return TagInterface
     * @throws CreateTagException
     */
    public function createTagFromHtml(string|Stringable $html): TagInterface
    {
        $html = (string) $html;
        
        if (str_starts_with($html, '<?xml')) {
            $html = trim(preg_replace('/^(<\?xml.+?\?>)/', '', $html));
            return $this->createSvgTagFromHtml($html);
        }
        
        if (str_starts_with($html, '<svg')) {
            return $this->createSvgTagFromHtml($html);
        }
        
        if (str_starts_with($html, '<path')) {
            return $this->createTag(
                name: 'svg',
                html: $html,
                attributes: new Attributes([
                    'xmlns' => 'http://www.w3.org/2000/svg',
                    'width' => '20',
                    'height'=> '20',
                    'viewBox' => '0 0 100 100',
                ]),
            );
        }
        
        return parent::createTagFromHtml(html: $html);
    }

    /**
     * Create a new svg tag from the specified html.
     *
     * @param string $html
     * @return TagInterface
     */
    protected function createSvgTagFromHtml(string $html): TagInterface
    {
        $xml = new SimpleXMLElement($html);
        
        $attributes = $this->namespacesToAttributes($xml);
        
        foreach($xml->attributes() as $key => $value) {
            $attributes[$key] = (string)$value;
        }
        
        foreach($xml->attributes('xml', true) as $key => $value) {
            $attributes['xml:'.(string)$key] = (string)$value;
        }
        
        $content = '';
        
        foreach (dom_import_simplexml($xml)->childNodes as $child) {
            if (!is_null($child->ownerDocument)) {
                $content .= $child->ownerDocument->saveXML($child);
            }
        }
        
        return $this->createTag(
            name: 'svg',
            html: $content,
            attributes: new Attributes($attributes),
        );
    }
    
    /**
     * Returns the attributes parsed from namespaces.
     *
     * @param SimpleXMLElement $xml
     * @return array
     */
    protected function namespacesToAttributes(SimpleXMLElement $xml): array
    {
        $namespaces = $xml->getDocNamespaces();
        
        if (!is_array($namespaces)) {
            return [];
        }
        
        $attributes = [];
        
        foreach($namespaces as $name => $value) {
            if (!empty($name)) {
                $attributes['xmlns:'.$name] = $value;
            } else {
                $attributes['xmlns'] = $value;
            }
        }
        
        return $attributes;
    }
}