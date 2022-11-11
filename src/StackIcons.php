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

/**
 * StackIcons
 */
class StackIcons implements IconsInterface
{
    /**
     * @var array<int, IconsInterface>
     */
    protected array $icons = [];
    
    /**
     * Create a new StackIcons.
     *
     * @param IconsInterface ...$icons
     */
    public function __construct(
        IconsInterface ...$icons
    ) {
        $this->icons = $icons;
    }

    /**
     * Returns true if the specified icon exists, otherwise false.
     *
     * @param string $icon The icon name.
     * @return bool
     */
    public function has(string $icon): bool
    {
        foreach($this->icons as $icons) {
            if ($icons->has($icon)) {
                return true;
            }
        }
        
        return false;
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
        foreach($this->icons as $icons) {
            if ($icons->has($icon)) {
                return $icons->get($icon);
            }
        }
        
        throw new IconNotFoundException($icon);
    }
}