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

use Tobento\Service\Tag\Attributes;
use Tobento\Service\Tag\Str;

/**
 * SvgTagAttributes
 */
class SvgTagAttributes extends Attributes
{
    /**
     * Returns the string representation of the attributes.
     * It must return an empty space at the beginning
     * if there are attributes, otherwise an empy string.
     *
     * @return string
     */
    public function render(): string
    {
        return Str::formatTagAttributes($this->setAriaHidden($this->all()), true);
    }
    
    /**
     * Returns the evaluated contents of the attributes
     * without and empty space at the beginning.
     *
     * @return string
     */
    public function renderWithoutSpace(): string
    {
        return Str::formatTagAttributes($this->setAriaHidden($this->all()), false);
    }
    
    /**
     * Set aria-hidden=true if not defined and no textual alternative provided.
     *
     * @param array $attributes
     * @return array
     */
    protected function setAriaHidden(array $attributes): array
    {
        if (array_intersect(['aria-hidden', 'aria-label', 'aria-labelledby', 'title'], array_keys($attributes)) === []) {
            $attributes['aria-hidden'] = 'true';
        }
        
        return $attributes;
    }
}