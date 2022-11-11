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
use Tobento\Service\Filesystem\File;

/**
 * SvgFileIcons
 */
class SvgFileIcons implements IconsInterface
{
    /**
     * @var IconFactoryInterface
     */
    protected IconFactoryInterface $iconFactory;
    
    /**
     * @var array<string, IconInterface>
     */
    protected array $icons = [];
    
    /**
     * @var array<string, IconNotFoundException>
     */
    protected array $iconsException = [];
    
    /**
     * Create a new Icons.
     *
     * @param DirsInterface $dirs
     * @param null|IconFactoryInterface $iconFactory
     */
    public function __construct(
        protected DirsInterface $dirs,
        null|IconFactoryInterface $iconFactory = null,
    ) {
        $this->iconFactory = $iconFactory ?: new IconFactory();
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
        if (isset($this->icons[$icon])) {
            return $this->icons[$icon];
        }
        
        if (isset($this->iconsException[$icon])) {
            throw $this->iconsException[$icon];
        }
        
        $file = $this->getFileFromDirs($icon);
        
        if (is_null($file)) {
            $this->iconsException[$icon] = new IconNotFoundException($icon);
            throw $this->iconsException[$icon];
        }
        
        try {
            $createdIcon = $this->iconFactory->createIconFromFile($icon, $file);
            return $this->icons[$createdIcon->name()] = $createdIcon;
        } catch (CreateIconException $e) {
            $this->iconsException[$icon] = new IconNotFoundException($icon, $e->getMessage(), 0, $e);
            throw $this->iconsException[$icon];
        }
    }
    
    /**
     * Returns the file from the dirs if exists, otherwise null.
     *
     * @param string $icon The icon name.
     * @return null|File
     */
    protected function getFileFromDirs(string $icon): null|File
    {
        foreach($this->dirs->all() as $dir)
        {
            $file = new File($dir->dir().$icon.'.svg');
            
            if (!$file->isWithinDir($dir->dir())) {
                return null;
            }
            
            // checking the extension is quite slow!
            if ($file->isExtension(['svg+xml'])) {
                return $file;
            }
        }
        
        return null;
    }
}