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
 * Icons
 */
class Icons implements IconsInterface
{
    /**
     * Create a new Icons.
     *
     * @param IconFactoryInterface $iconFactory
     */
    public function __construct(
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
        return true;
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
        return $this->iconFactory->createIcon(name: $icon);
    }
}