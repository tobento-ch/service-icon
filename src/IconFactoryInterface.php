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

use Tobento\Service\Tag\TagInterface;
use Tobento\Service\Filesystem\File;
use Stringable;

/**
 * IconFactoryInterface
 */
interface IconFactoryInterface
{
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
    ): IconInterface;
    
    /**
     * Create a new icon from html.
     *
     * @param string $name
     * @param string|Stringable $html
     * @return IconInterface
     * @throws CreateIconException
     */
    public function createIconFromHtml(string $name, string|Stringable $html): IconInterface;
    
    /**
     * Create a new icon from file.
     *
     * @param string $name
     * @param string|File $file
     * @return IconInterface
     * @throws CreateIconException
     */
    public function createIconFromFile(string $name, string|File $file): IconInterface;
}