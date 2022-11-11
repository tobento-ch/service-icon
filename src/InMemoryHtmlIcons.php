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
 * InMemoryHtmlIcons
 */
class InMemoryHtmlIcons implements IconsInterface
{
    /**
     * Create a new InMemoryHtmlIcons.
     *
     * @param array<string, string> $icons ['name' => 'html']
     * @param IconFactoryInterface $iconFactory
     */
    public function __construct(
        protected array $icons,
        protected IconFactoryInterface $iconFactory
    ) {}

    /**
     * Returns true if the specified icon exists, otherwise false.
     *
     * @param string $icon The icon name.
     * @return bool
     */
    public function has(string $icon): bool
    {
        return isset($this->icons[$icon]);
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
        if (!isset($this->icons[$icon])) {
            throw new IconNotFoundException($icon);
        }
        
        return $this->iconFactory->createIconFromHtml($icon, $this->icons[$icon]);
    }
}