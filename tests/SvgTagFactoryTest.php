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
use Tobento\Service\Icon\SvgTagFactory;
use Tobento\Service\Tag\TagFactoryInterface;

/**
 * SvgTagFactoryTest
 */
class SvgTagFactoryTest extends TestCase
{
    public function testThatImplementsTagFactoryInterface()
    {
        $this->assertInstanceof(
            TagFactoryInterface::class,
            new SvgTagFactory()
        );
    }
    
    public function testCreateTagFromHtmlMethod()
    {
        $this->assertSame(
            '<p>lorem</p>',
            (string)(new SvgTagFactory())->createTagFromHtml('<p>lorem</p>')
        );
    }
    
    public function testCreateTagFromHtmlMethodWithSvgHtml()
    {
        $tag = (new SvgTagFactory())->createTagFromHtml(
            '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 100 100"><path d="M0,100H100V90H0ZM100,50H66.67V0H33.33V50H0L50,83.33Z"/></svg>'
        );

        $this->assertSame('svg', $tag->getName());
        
        $this->assertSame(
            '<path d="M0,100H100V90H0ZM100,50H66.67V0H33.33V50H0L50,83.33Z"/>',
            $tag->getHtml()
        );
        
        $this->assertSame(
            [
                'xmlns' => 'http://www.w3.org/2000/svg',
                'width' => '20',
                'height' => '20',
                'viewBox' => '0 0 100 100',
            ],
            $tag->attributes()->all()
        );
    }
    
    public function testCreateTagFromHtmlMethodWithSvgPathOnly()
    {
        $tag = (new SvgTagFactory())->createTagFromHtml(
            '<path d="M0,100H100V90H0ZM100,50H66.67V0H33.33V50H0L50,83.33Z"/>'
        );

        $this->assertSame('svg', $tag->getName());
        
        $this->assertSame(
            '<path d="M0,100H100V90H0ZM100,50H66.67V0H33.33V50H0L50,83.33Z"/>',
            $tag->getHtml()
        );
        
        $this->assertSame(
            [
                'xmlns' => 'http://www.w3.org/2000/svg',
                'width' => '20',
                'height' => '20',
                'viewBox' => '0 0 100 100',
            ],
            $tag->attributes()->all()
        );
    }    
    
    public function testSvgNamespaces()
    {
        $tag = (new SvgTagFactory())->createTagFromHtml(
            '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"></svg>'
        );

        $this->assertSame('svg', $tag->getName());
        
        $this->assertSame(
            '',
            $tag->getHtml()
        );
        
        $this->assertSame(
            [
                'xmlns' => 'http://www.w3.org/2000/svg',
                'xmlns:xlink' => 'http://www.w3.org/1999/xlink',
            ],
            $tag->attributes()->all()
        );
    }
    
    public function testSvgWithinSvg()
    {
        $tag = (new SvgTagFactory())->createTagFromHtml(
            '<svg><circle cx="50" cy="50" r="40" /><circle cx="150" cy="50" r="4"/><svg><circle cx="5" cy="5" r="4"/></svg></svg>'
        );

        $this->assertSame('svg', $tag->getName());
        
        $this->assertSame(
            '<circle cx="50" cy="50" r="40"/><circle cx="150" cy="50" r="4"/><svg><circle cx="5" cy="5" r="4"/></svg>',
            $tag->getHtml()
        );
    }
    
    public function testSvgXml()
    {
        $tag = (new SvgTagFactory())->createTagFromHtml(
            '<?xml version="1.0" encoding="utf-8"?><!-- Generator: Adobe Illustrator 27.0.0, SVG Export Plug-In . SVG Version: 6.00 Build 0)  --><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
 viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve"><g id="copy"><path d="M72,72V30h28v70H30V72H72z M30,70h40V30V0H0v70H30z"/></g></svg>');

        $this->assertSame('svg', $tag->getName());
        
        $this->assertSame(
            '<g id="copy"><path d="M72,72V30h28v70H30V72H72z M30,70h40V30V0H0v70H30z"/></g>',
            $tag->getHtml()
        );
    }    
}