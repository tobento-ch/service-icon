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

use Tobento\Service\Dir\DirsInterface;
use Tobento\Service\Dir\DirInterface;
use Tobento\Service\Filesystem\Dir;
use Tobento\Service\Filesystem\File;
use Tobento\Service\Filesystem\JsonFile;
use Tobento\Service\FileCreator\FileCreator;
use Tobento\Service\FileCreator\FileCreatorException;
use Tobento\Service\Tag\TagFactoryInterface;
use Tobento\Service\Tag\Attributes;
use Tobento\Service\Tag\CreateTagException;

/**
 * SvgFileIconsToJsonFile
 */
class SvgFileIconsToJsonFile implements IconsInterface
{
    /**
     * @var IconFactoryInterface
     */
    protected IconFactoryInterface $iconFactory;
    
    /**
     * @var TagFactoryInterface
     */
    protected TagFactoryInterface $tagFactory;
    
    /**
     * @var null|array<string, IconInterface>
     */
    protected null|array $icons = null;
    
    /**
     * Create a new SvgFileIconsToJsonFile.
     *
     * @param DirsInterface $dirs
     * @param DirInterface $cacheDir
     * @param bool $clearCache
     * @param null|IconFactoryInterface $iconFactory
     * @param null|TagFactoryInterface $tagFactory
     */
    public function __construct(
        protected DirsInterface $dirs,
        protected DirInterface $cacheDir,
        protected bool $clearCache = false,
        null|IconFactoryInterface $iconFactory = null,
        null|TagFactoryInterface $tagFactory = null,
    ) {
        $this->iconFactory = $iconFactory ?: new IconFactory();
        $this->tagFactory = $tagFactory ?: new SvgTagFactory();
        
        if ($clearCache) {
            (new Dir())->delete($cacheDir->dir());
        }
        
        if ((new Dir())->has($cacheDir->dir()) === false) {
            $this->createCacheIcons();
        }
    }

    /**
     * Returns true if the specified icon exists, otherwise false.
     *
     * @param string $icon The icon name.
     * @return bool
     */
    public function has(string $icon): bool
    {
        try {
            $this->get($icon);
            return true;
        } catch (IconNotFoundException|CreateIconException $e) {
            return false;
        }
    }

    /**
     * Returns the icon.
     *
     * @param string $icon The icon name.
     * @return IconInterface
     * @throws IconNotFoundException
     */
    public function get(string $icon): IconInterface
    {
        return $this->getFromCache($icon);
    }

    /**
     * Returns the icon from cache.
     *
     * @param string $icon The icon name.
     * @return IconInterface
     * @throws IconNotFoundException
     */
    protected function getFromCache(string $icon): IconInterface
    {
        if (!is_array($this->icons)) {
            $jsonFile = new JsonFile($this->cacheDir->dir().'icons.json');
            $this->icons = $jsonFile->toArray();
        }
        
        if (!isset($this->icons[$icon])) {
            throw new IconNotFoundException($icon);
        }
        
        $data = $this->icons[$icon];

        $tag = $this->tagFactory->createTag(
            name: $data['name'] ?? '',
            html: $data['html'] ?? '',
            attributes: new Attributes($data['attributes'] ?? []),
        );

        return $this->iconFactory->createIcon($icon, $tag);
    }
    
    /**
     * Create cache icons.
     *
     * @return void
     */
    protected function createCacheIcons(): void
    {
        $dir = new Dir();
        $icons = [];
        
        foreach($this->dirs->all() as $directory)
        {
            $files = $dir->getFiles($directory->dir(), '', ['svg']);
            
            foreach($files as $file)
            {
                if (isset($icons[$file->getFilename()])) {
                    continue 2;
                }
                
                try {
                    $tag = $this->tagFactory->createTagFromHtml($file->getContent());
                } catch (CreateTagException $e) {
                    continue;
                }

                $icons[$file->getFilename()] = [
                    'name' => $tag->getName(),
                    'html' => $tag->getHtml(),
                    'attributes' => $tag->attributes()->all(),
                ];
            }
        }
        
        try {
            (new FileCreator())
                ->content(json_encode($icons))
                ->create(
                    $this->cacheDir->dir().'icons.json',
                    FileCreator::CONTENT_NEW
                );
        } catch (FileCreatorException $e) {
            // ignore
        }
    }
}