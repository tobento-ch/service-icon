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

use Tobento\Service\Translation\TranslatorInterface;
use Tobento\Service\Tag\TagFactoryInterface;
use Tobento\Service\Tag\TagInterface;
use Tobento\Service\Tag\Str;

/**
 * IconFactoryTranslator
 */
class IconFactoryTranslator extends IconFactory
{
    /**
     * Create a new IconFactory.
     *
     * @param TranslatorInterface $translator
     * @param null|TagFactoryInterface $tagFactory
     */
    public function __construct(
        protected TranslatorInterface $translator,
        null|TagFactoryInterface $tagFactory = null
    ) {
        $this->tagFactory = $tagFactory ?: new SvgTagFactory();
    }
    
    /**
     * Create a new icon.
     *
     * @param string $name
     * @param null|TagInterface $tag
     * @param null|TagInterface $labelTag
     * @param null|TagInterface $parentTag
     * @return IconInterface
     * @throws CreateIconException
     */
    public function createIcon(
        string $name,
        null|TagInterface $tag = null,
        null|TagInterface $labelTag = null,
        null|TagInterface $parentTag = null,
    ): IconInterface {
        
        if (is_null($tag)) {
            $tag = $this->tagFactory->createTag(name: '');
        }

        $tag = $this->translateSvgTagTitle($tag);
        
        if (is_null($labelTag)) {
            $labelTag = $this->tagFactory->createTag(name: 'span')
                ->class('icon-label');
        }
        
        if (is_null($parentTag)) {
            $parentTag = $this->tagFactory->createTag(name: 'span')
                ->class('icon')
                ->class('icon-'.strtolower($name));
        }
        
        return new Icon(
            name: $name,
            tag: $tag,
            labelTag: $labelTag,
            parentTag: $parentTag,
            sizeClassMap: $this->sizeClassMap,
        );
    }
    
    /**
     * Translates svg title tag.
     *
     * @param TagInterface $tag
     * @return TagInterface
     */
    protected function translateSvgTagTitle(TagInterface $tag): TagInterface
    {
        if ($tag->getName() === 'svg')
        {
            $html = preg_replace_callback(
                '/(<title>.+?)+(<\/title>)/i',
                function($matches) {
                    $title = strip_tags($matches[0]);
                    $title = $this->translator->trans($title);
                    return '<title>'.Str::esc($title).'</title>';
                },
                $tag->getHtml()
            );
            
            if (is_string($html)) {
                return $tag->withHtml($html);
            }
        }
        
        return $tag;
    }
}