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

use Tobento\Service\Filesystem\Dir;
use Tobento\Service\Filesystem\File;
use Tobento\Service\Filesystem\JsonFile;
use Tobento\Service\FileCreator\FileCreator;
use Tobento\Service\FileCreator\FileCreatorException;
use Tobento\Service\Tag\Attributes;
use Tobento\Service\Tag\CreateTagException;

/**
 * SvgFileIconsToJsonFiles
 */
class SvgFileIconsToJsonFiles extends SvgFileIconsToJsonFile
{
    /**
     * @var array<string, IconNotFoundException>
     */
    protected array $iconsException = [];
    
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
            $this->icons = [];
        }
        
        if (isset($this->icons[$icon])) {
            return $this->icons[$icon];
        }
        
        if (isset($this->iconsException[$icon])) {
            throw $this->iconsException[$icon];
        }
        
        $jsonFile = new JsonFile($this->cacheDir->dir().basename($icon).'.json');
        
        if (!$jsonFile->isFile()) {
            $this->iconsException[$icon] = new IconNotFoundException($icon);
            throw $this->iconsException[$icon];
        }
        
        try {
            $tag = $jsonFile->toArray();
            
            $tag = $this->tagFactory->createTag(
                name: $tag['name'] ?? '',
                html: $tag['html'] ?? '',
                attributes: new Attributes($tag['attributes'] ?? []),
            );

            $createdIcon = $this->iconFactory->createIcon($icon, $tag);
            
            return $this->icons[$createdIcon->name()] = $createdIcon;
            
        } catch (CreateIconException $e) {
            $this->iconsException[$icon] = new IconNotFoundException($icon, $e->getMessage(), 0, $e);
            throw $this->iconsException[$icon];
        }
    }
    
    /**
     * Create cache icons.
     *
     * @return void
     */
    protected function createCacheIcons(): void
    {
        $dir = new Dir();
        $createdIcons = [];
        
        foreach($this->dirs->all() as $directory)
        {
            $files = $dir->getFiles($directory->dir(), '', ['svg']);
            
            foreach($files as $file)
            {
                if (isset($createdIcons[$file->getFilename()])) {
                    continue 2;
                }
                
                $created = $this->createJsonFileIcon($file);
                
                if ($created) {
                    $createdIcons[$file->getFilename()] = true;
                }
            }
        }
    }
    
    /**
     * Create json file icon.
     *
     * @param File $file
     * @return bool True on success, otherwise false.
     */
    protected function createJsonFileIcon(File $file): bool
    {
        try {
            $tag = $this->tagFactory->createTagFromHtml($file->getContent());
        } catch (CreateTagException $e) {
            return false;
        }
        
        $tagData = [
            'name' => $tag->getName(),
            'html' => $tag->getHtml(),
            'attributes' => $tag->attributes()->all(),
        ];
        
        try {
            (new FileCreator())
                ->content(json_encode($tagData))
                ->create(
                    $this->cacheDir->dir().$file->getFilename().'.json',
                    FileCreator::CONTENT_NEW
                );
            return true;
        } catch (FileCreatorException $e) {
            return false;
        }
    }
}