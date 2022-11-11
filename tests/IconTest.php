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

namespace Tobento\Service\Icon\Test;

use PHPUnit\Framework\TestCase;
use Tobento\Service\Icon\IconInterface;
use Tobento\Service\Icon\Icon;
use Tobento\Service\Tag\TagInterface;
use Tobento\Service\Tag\Tag;
use Tobento\Service\Tag\NullTag;
use Tobento\Service\Tag\Attributes;

/**
 * IconTest
 */
class IconTest extends TestCase
{
    public function testThatImplementsIconInterface()
    {
        $this->assertInstanceof(
            IconInterface::class,
            new Icon(name: 'cart', tag: new NullTag())
        );
    }
    
    public function testNameMethod()
    {
        $this->assertSame(
            'cart',
            (new Icon(name: 'cart', tag: new NullTag()))->name()
        );
    }
    
    public function testRenderMethodWithNullTagOnly()
    {
        $this->assertSame(
            '',
            (new Icon(name: 'cart', tag: new NullTag()))->render()
        );
    }
    
    public function testRenderMethodWithTagOnly()
    {
        $icon = new Icon(
            name: 'cart',
            tag: new Tag(
                name: 'i',
                attributes: new Attributes([
                    'class' => 'cart',
                ]),
            ),
        );
        
        $this->assertSame(
            '<i class="cart"></i>',
            $icon->render()
        );
    }

    public function testSizeMethod()
    {
        $icon = new Icon(
            name: 'cart',
            tag: new Tag(
                name: 'i',
                attributes: new Attributes([
                    'class' => 'cart',
                ]),
            ),
            sizeClassMap: ['m' => 'text-m'],
        );
        
        $iconNew = $icon->size('m');
        
        $this->assertFalse($icon === $iconNew);
        
        $this->assertSame(
            '<i class="cart text-m"></i>',
            $iconNew->render()
        );
    }
    
    public function testSizeMethodDoesNotRenderSizeIfNotInClassMap()
    {
        $icon = new Icon(
            name: 'cart',
            tag: new Tag(
                name: 'i',
                attributes: new Attributes([
                    'class' => 'cart',
                ]),
            ),
            sizeClassMap: ['m' => 'text-m'],
        );
        
        $this->assertSame(
            '<i class="cart"></i>',
            (string)$icon->size('s')
        );
    }    
    
    public function testSizeMethodWithParentTag()
    {
        $icon = new Icon(
            name: 'cart',
            tag: new Tag(name: 'i'),
            parentTag: new Tag(
                name: 'span',
                attributes: new Attributes([
                    'class' => 'parent',
                ]),
            ),
            sizeClassMap: ['m' => 'text-m'],
        );
        
        $this->assertSame(
            '<span class="parent text-m"><i></i></span>',
            (string)$icon->size('m')
        );
    }

    public function testAttrMethod()
    {
        $icon = new Icon(
            name: 'cart',
            tag: new Tag(
                name: 'i',
                attributes: new Attributes([
                    'class' => 'cart',
                ]),
            ),
        );
        
        $iconNew = $icon->attr('data-foo', 'foo');
        
        $this->assertFalse($icon === $iconNew);
        
        $this->assertSame(
            '<i class="cart" data-foo="foo"></i>',
            $iconNew->render()
        );
    }
    
    public function testAttrMethodWithClassAddsExisting()
    {
        $icon = new Icon(
            name: 'cart',
            tag: new Tag(
                name: 'i',
                attributes: new Attributes([
                    'class' => 'cart',
                ]),
            ),
        );
        
        $this->assertSame(
            '<i class="cart foo"></i>',
            (string)$icon->attr('class', 'foo')
        );
    }
    
    public function testAttrMethodWithClassArrayValuesOverwritesExisting()
    {
        $icon = new Icon(
            name: 'cart',
            tag: new Tag(
                name: 'i',
                attributes: new Attributes([
                    'class' => 'cart',
                ]),
            ),
        );
        
        $this->assertSame(
            '<i class="foo bar"></i>',
            (string)$icon->attr('class', ['foo', 'bar'])
        );
    }
    
    public function testLabelMethod()
    {
        $icon = new Icon(
            name: 'cart',
            tag: new Tag(name: 'i'),
            labelTag: new Tag(
                name: 'span',
                attributes: new Attributes([
                    'class' => 'label',
                ]),
            ),
        );
        
        $iconNew = $icon->label('Cart');
        
        $this->assertFalse($icon === $iconNew);
        
        $this->assertSame(
            '<i></i><span class="label">Cart</span>',
            $iconNew->render()
        );
    }
    
    public function testLabelMethodWithoutLabelTagRendersOnlyLabel()
    {
        $icon = new Icon(
            name: 'cart',
            tag: new Tag(name: 'i'),
        );
        
        $this->assertSame(
            '<i></i>Cart',
            (string)$icon->label('Cart')
        );
    }
    
    public function testLabelMethodWithPositionLeft()
    {
        $icon = new Icon(
            name: 'cart',
            tag: new Tag(name: 'i'),
            labelTag: new Tag(name: 'span'),
        );
        
        $this->assertSame(
            '<span>Cart</span><i></i>',
            (string)$icon->label('Cart', position: 'left')
        );
    }    
    
    public function testLabelSizeMethod()
    {
        $icon = new Icon(
            name: 'cart',
            tag: new Tag(name: 'i'),
            labelTag: new Tag(
                name: 'span',
                attributes: new Attributes([
                    'class' => 'label',
                ]),
            ),
            sizeClassMap: ['m' => 'text-m'],
        );
        
        $iconNew = $icon->labelSize('m');
        
        $this->assertFalse($icon === $iconNew);
        
        $this->assertSame(
            '<i></i><span class="label text-m">Cart</span>',
            $iconNew->label('Cart')->render()
        );
    }
    
    public function testLabelSizeMethodDoesNotRenderSizeIfNotInClassMap()
    {
        $icon = new Icon(
            name: 'cart',
            tag: new Tag(name: 'i'),
            labelTag: new Tag(
                name: 'span',
                attributes: new Attributes([
                    'class' => 'label',
                ]),
            ),
            sizeClassMap: ['m' => 'text-m'],
        );
        
        $this->assertSame(
            '<i></i><span class="label">Cart</span>',
            (string)$icon->label('Cart')->labelSize('s')
        );
    }
    
    public function testLabelAttrMethod()
    {
        $icon = new Icon(
            name: 'cart',
            tag: new Tag(name: 'i'),
            labelTag: new Tag(
                name: 'span',
                attributes: new Attributes([
                    'class' => 'label',
                ]),
            ),
        );
        
        $iconNew = $icon->labelAttr('data-foo', 'foo');
        
        $this->assertFalse($icon === $iconNew);
        
        $this->assertSame(
            '<i></i><span class="label" data-foo="foo">Cart</span>',
            (string)$iconNew->label('Cart')
        );
    }
    
    public function testLabelAttrMethodWithClassAddsExisting()
    {
        $icon = new Icon(
            name: 'cart',
            tag: new Tag(name: 'i'),
            labelTag: new Tag(
                name: 'span',
                attributes: new Attributes([
                    'class' => 'label',
                ]),
            ),
        );
        
        $this->assertSame(
            '<i></i><span class="label foo">Cart</span>',
            (string)$icon->label('Cart')->labelAttr('class', 'foo')
        );
    }
    
    public function testLabelAttrMethodWithClassArrayValuesOverwritesExisting()
    {
        $icon = new Icon(
            name: 'cart',
            tag: new Tag(name: 'i'),
            labelTag: new Tag(
                name: 'span',
                attributes: new Attributes([
                    'class' => 'label',
                ]),
            ),
        );
        
        $this->assertSame(
            '<i></i><span class="foo bar">Cart</span>',
            (string)$icon->label('Cart')->labelAttr('class', ['foo', 'bar'])
        );
    }
    
    public function testTagMethod()
    {
        $tag = new Tag(name: 'i');
        
        $icon = new Icon(
            name: 'cart',
            tag: $tag,
        );
        
        $this->assertFalse($tag === $icon->tag());
        
        $this->assertInstanceof(
            TagInterface::class,
            $icon->tag()
        );        
    }
}