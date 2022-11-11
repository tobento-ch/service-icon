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

use Tobento\Service\Tag\Str;
use Tobento\Service\Tag\Tag;
use Tobento\Service\Tag\NullTag;
use Tobento\Service\Tag\TagInterface;
use Tobento\Service\Tag\Attributes;
use Stringable;

/**
 * Icon
 */
class Icon implements IconInterface
{
    /**
     * @var null|string
     */
    protected null|string $size = null;
    
    /**
     * @var null|string
     */
    protected null|string $labelSize = null;    
    
    /**
     * @var string
     */
    protected string $labelPosition = 'right';    
    
    /**
     * Create a new Icon.
     *
     * @param string $name
     * @param TagInterface $tag
     * @param array<string, string> $sizeClassMap
     */
    public function __construct(
        protected string $name,
        protected TagInterface $tag,
        protected null|TagInterface $labelTag = null,
        protected null|TagInterface $parentTag = null,
        protected array $sizeClassMap = []
    ) {}

    /**
     * Returns the icon name.
     *
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }
    
    /**
     * Returns the evaluated html of the icon. Must be escaped.
     *
     * @return string
     */
    public function render(): string
    {
        $parentTag = $this->parentTag();
        $tag = $this->tag();
        
        // handle size:
        if ($this->size && array_key_exists($this->size, $this->sizeClassMap)) {
            
            if ($parentTag instanceof NullTag) {
                $tag->class(value: $this->sizeClassMap[$this->size]);   
            } else {
                $parentTag->class(value: $this->sizeClassMap[$this->size]);
            }
        }
        
        $parentTag = $parentTag->withHtml($tag);
        
        // handle label
        $labelTag = $this->labelTag();
        
        if ($this->labelSize && array_key_exists($this->labelSize, $this->sizeClassMap)) {
            $labelTag->class(value: $this->sizeClassMap[$this->labelSize]);
        }
        
        if ($labelTag->getHtml()) {
            
            if ($this->labelPosition === 'right') {
                $parentTag->append($labelTag);
            } else {
                $parentTag->prepend($labelTag);
            }
        }
        
        return (string)$parentTag;
    }
    
    /**
     * Returns a new instance with the specified size.
     *
     * @param string $size
     * @return static
     */
    public function size(string $size): static
    {
        $new = clone $this;
        $new->size = $size;
        return $new;
    }
    
    /**
     * Returns a new instance with the specified attribute.
     * Class must add to existing classes if a string is provided,
     * otherwise overwrite.     
     *
     * @param string $name
     * @param mixed $value
     * @return static
     */
    public function attr(string $name, mixed $value = null): static
    {
        $new = clone $this;
        
        if ($name === 'class' && is_string($value)) {
            $new->tag = $new->tag()->class($value);
        } else {
            $new->tag = $new->tag()->attr($name, $value);
        }
        
        return $new;
    }
    
    /**
     * Returns a new instance with the specified label and position.
     *
     * @param string|Stringable $text
     * @param string $position
     * @return static
     */
    public function label(string|Stringable $text, string $position = 'right'): static
    {
        $new = clone $this;
        $new->labelTag = $new->labelTag()->withHtml(Str::esc($text));
        $new->labelPosition = $position;
        return $new;
    }
    
    /**
     * Returns a new instance with the specified label size.
     *
     * @param string $size
     * @return static
     */
    public function labelSize(string $size): static
    {
        $new = clone $this;
        $new->labelSize = $size;
        return $new;
    }
    
    /**
     * Returns a new instance with the specified label attribute.
     * Class must add to existing classes if a string is provided,
     * otherwise overwrite.     
     *
     * @param string $name
     * @param mixed $value
     * @return static
     */
    public function labelAttr(string $name, mixed $value = null): static
    {
        $new = clone $this;
        
        if ($name === 'class' && is_string($value)) {
            $new->labelTag = $new->labelTag()->class($value);
        } else {
            $new->labelTag = $new->labelTag()->attr($name, $value);
        }
        
        return $new;
    }
    
    /**
     * Returns a new instance with the specified parent attribute.
     *
     * @param string $name
     * @param mixed $value
     * @return static
     */
    public function parentAttr(string $name, mixed $value = null): static
    {
        $new = clone $this;
        $new->parentTag = $new->parentTag()->attr($name, $value);
        return $new;
    }
    
    /**
     * Returns a new instance of the icon tag.
     *
     * @return TagInterface
     */
    public function tag(): TagInterface
    {
        return clone $this->tag;
    }
    
    /**
     * Returns the string representation of the icon. Must be escaped.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->render();
    }
    
    /**
     * Returns a new instance of the parent tag.
     *
     * @return TagInterface
     */
    protected function parentTag(): TagInterface
    {
        if (is_null($this->parentTag)) {
            $this->parentTag = new NullTag();
        }
        
        return clone $this->parentTag;
    }
    
    /**
     * Returns a new instance the label tag.
     *
     * @return TagInterface
     */
    protected function labelTag(): TagInterface
    {
        if (is_null($this->labelTag)) {
            $this->labelTag = new NullTag();
        }
        
        return clone $this->labelTag;
    }
}