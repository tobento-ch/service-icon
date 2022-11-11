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

use Tobento\Service\Tag\TagFactoryInterface;
use Tobento\Service\Tag\TagInterface;
use Tobento\Service\Filesystem\File;
use Stringable;

/**
 * IconFactory
 */
class IconFactory implements IconFactoryInterface
{
    /**
     * @var TagFactoryInterface
     */
    protected TagFactoryInterface $tagFactory;
    
    /**
     * @var array
     */
    protected array $sizeClassMap = [
        'xxs' => 'text-xxs',
        'xs' => 'text-xs',
        's' => 'text-s',
        'm' => 'text-m',
        'l' => 'text-l',
        'xl' => 'text-xl',
        'xxl' => 'text-xxl',
        'body' => 'text-body',
    ];
    
    /**
     * Create a new IconFactory.
     *
     * @param null|TagFactoryInterface $tagFactory
     */
    public function __construct(
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
     * Create a new icon from html.
     *
     * @param string $name
     * @param string|Stringable $html
     * @return IconInterface
     * @throws CreateIconException
     */
    public function createIconFromHtml(string $name, string|Stringable $html): IconInterface
    {
        $tag = $this->tagFactory->createTagFromHtml($html);
        
        return $this->createIcon($name, $tag);
    }
    
    /**
     * Create a new icon from file.
     *
     * @param string $name
     * @param string|File $file
     * @return IconInterface
     * @throws CreateIconException
     */
    public function createIconFromFile(string $name, string|File $file): IconInterface
    {
        if (is_string($file)) {
            $file = new File($file);   
        }
        
        // checking the extension is quite slow!
        if (!$file->isExtension(['svg+xml'])) {
            throw new CreateIconException(
                icon: $name,
                filename: $file->getFile(),
                message: 'Invalid svg file!'
            );
        }
        
        return $this->createIconFromHtml($name, $file->getContent());
    }
}