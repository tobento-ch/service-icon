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
use Stringable;

/**
 * IconInterface
 */
interface IconInterface extends Stringable
{
    /**
     * Returns the icon name.
     *
     * @return string
     */
    public function name(): string;
    
    /**
     * Returns the evaluated html of the icon. Must be escaped.
     *
     * @return string
     */
    public function render(): string;
    
    /**
     * Returns a new instance with the specified size.
     *
     * @param string $size
     * @return static
     */
    public function size(string $size): static;
    
    /**
     * Returns a new instance with the specified attribute.
     * Class must add to existing classes if a string is provided,
     * otherwise overwrite.
     *
     * @param string $name
     * @param mixed $value
     * @return static
     */
    public function attr(string $name, mixed $value = null): static;
    
    /**
     * Returns a new instance with the specified label and position.
     *
     * @param string|Stringable $text
     * @param string $position
     * @return static
     */
    public function label(string|Stringable $text, string $position = 'right'): static;
    
    /**
     * Returns a new instance with the specified label size.
     *
     * @param string $size
     * @return static
     */
    public function labelSize(string $size): static;
    
    /**
     * Returns a new instance with the specified label attribute.
     * Class must add to existing classes if a string is provided,
     * otherwise overwrite.     
     *
     * @param string $name
     * @param mixed $value
     * @return static
     */
    public function labelAttr(string $name, mixed $value = null): static;
    
    /**
     * Returns a new instance of the icon tag.
     *
     * @return TagInterface
     */
    public function tag(): TagInterface;
}