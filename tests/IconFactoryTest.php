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
use Tobento\Service\Icon\IconFactoryInterface;
use Tobento\Service\Icon\IconFactory;
use Tobento\Service\Icon\IconInterface;
use Tobento\Service\Tag\Tag;
use Tobento\Service\Tag\TagFactory;
use Tobento\Service\Tag\NullTag;
use Tobento\Service\Tag\Attributes;
use Tobento\Service\Filesystem\File;

/**
 * IconFactoryTest
 */
class IconFactoryTest extends TestCase
{
    public function testThatImplementsIconFactoryInterface()
    {
        $this->assertInstanceof(
            IconFactoryInterface::class,
            new IconFactory()
        );
    }

    public function testCreateIconMethod()
    {
        $icon = (new IconFactory())->createIcon(
            name: 'download',
        );
        
        $this->assertInstanceof(IconInterface::class, $icon);
        
        $this->assertSame(
            '<span class="icon icon-download"></span>',
            (string)$icon
        );
        
        $this->assertSame(
            '<span class="icon icon-download"><span class="icon-label">Download</span></span>',
            (string)$icon->label('Download')
        );
    }
    
    public function testCreateIconMethodWithTag()
    {
        $icon = (new IconFactory())->createIcon(
            name: 'download',
            tag: new Tag(name: 'i'),
        );
        
        $this->assertSame(
            '<span class="icon icon-download"><i></i></span>',
            (string)$icon
        );
    }
    
    public function testCreateIconMethodWithSvgTag()
    {
        $icon = (new IconFactory())->createIcon(
            name: 'download',
            tag: new Tag(
                name: 'svg',
                html: '<path d="M0,100H100V90H0ZM100,50H66.67V0H33.33V50H0L50,83.33Z"/>',
                attributes: new Attributes([
                    'xmlns' => 'http://www.w3.org/2000/svg',
                    'width' => '20',
                    'height'=> '20',
                    'viewBox' => '0 0 100 100',
                ]),
            ),
            labelTag: null, // null|TagInterface
            parentTag: null, // null|TagInterface
        );
        
        $this->assertSame(
            '<span class="icon icon-download"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 100 100"><path d="M0,100H100V90H0ZM100,50H66.67V0H33.33V50H0L50,83.33Z"/></svg></span>',
            (string)$icon
        );
    }
    
    public function testCreateIconMethodWithLabelTag()
    {
        $icon = (new IconFactory())->createIcon(
            name: 'download',
            labelTag: new Tag(name: 'span'),
        );
        
        $this->assertSame(
            '<span class="icon icon-download"><span>download</span></span>',
            (string)$icon->label('download')
        );
    }
    
    public function testCreateIconMethodWithLabelNullTag()
    {
        $icon = (new IconFactory())->createIcon(
            name: 'download',
            labelTag: new NullTag(),
        );
        
        $this->assertSame(
            '<span class="icon icon-download">download</span>',
            (string)$icon->label('download')
        );
    }    
    
    public function testCreateIconMethodWithParentTag()
    {
        $icon = (new IconFactory())->createIcon(
            name: 'download',
            parentTag: new Tag(name: 'span'),
        );

        $this->assertSame(
            '<span></span>',
            (string)$icon
        );
        
        $this->assertSame(
            '<span><span class="icon-label">download</span></span>',
            (string)$icon->label('download')
        );
    }
    
    public function testCreateIconMethodWithParentNullTag()
    {
        $icon = (new IconFactory())->createIcon(
            name: 'download',
            parentTag: new NullTag(),
        );

        $this->assertSame(
            '',
            (string)$icon
        );
        
        $this->assertSame(
            '<span class="icon-label">download</span>',
            (string)$icon->label('download')
        );
    }

    public function testCreateIconFromHtmlMethod()
    {
        $icon = (new IconFactory())->createIconFromHtml(
            name: 'download',
            html: '<p>lorem</p>'
        );
        
        $this->assertInstanceof(IconInterface::class, $icon);
        
        $this->assertSame(
            '<span class="icon icon-download"><p>lorem</p></span>',
            (string)$icon
        );
        
        $this->assertSame(
            '<span class="icon icon-download"><p>lorem</p><span class="icon-label">Download</span></span>',
            (string)$icon->label('Download')
        );
    }
    
    public function testCreateIconFromHtmlMethodWithSvg()
    {
        $icon = (new IconFactory())->createIconFromHtml(
            name: 'download',
            html: '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 100 100"><path d="M0,100H100V90H0ZM100,50H66.67V0H33.33V50H0L50,83.33Z"/></svg>'
        );

        $this->assertSame(
            [
                'xmlns' => 'http://www.w3.org/2000/svg',
                'width' => '20',
                'height' => '20',
                'viewBox' => '0 0 100 100',
            ],
            $icon->tag()->attributes()->all()
        );
        
        $this->assertSame(
            '<span class="icon icon-download"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 100 100"><path d="M0,100H100V90H0ZM100,50H66.67V0H33.33V50H0L50,83.33Z"/></svg></span>',
            (string)$icon
        );
        
        $this->assertSame(
            '<span class="icon icon-download"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 100 100"><path d="M0,100H100V90H0ZM100,50H66.67V0H33.33V50H0L50,83.33Z"/></svg><span class="icon-label">Download</span></span>',
            (string)$icon->label('Download')
        );
    }
    
    public function testCreateIconFromFileMethod()
    {
        $icon = (new IconFactory())->createIconFromFile(
            name: 'download',
            file: __DIR__.'/svg-icons/edit.svg'
        );
    
        $this->assertSame(
            [
                'xmlns' => 'http://www.w3.org/2000/svg',
                'width' => '20',
                'height' => '20',
                'viewBox' => '0 0 100 100',
            ],
            $icon->tag()->attributes()->all()
        );
        
        $this->assertSame(
            '<span class="icon icon-download"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 100 100"><title>edit</title><path d="M80,40L30,90L0,100l10-30l50-50 M90,30l10-10L80,0L70,10L90,30z"/></svg></span>',
            (string)$icon
        );
    }
    
    public function testCreateIconFromFileMethodXml()
    {
        $icon = (new IconFactory())->createIconFromFile(
            name: 'download',
            file: __DIR__.'/svg-icons/xml-spacefree.svg'
        );
    
        $this->assertSame(
            [
                'xmlns' => 'http://www.w3.org/2000/svg',
                'xmlns:xlink' => 'http://www.w3.org/1999/xlink',
                'version' => '1.1',
                'x' => '0px',
                'y' => '0px',
                'viewBox' => '0 0 100 100',
                'style' => 'enable-background:new 0 0 100 100;',
                'xml:space' => 'preserve',
            ],
            $icon->tag()->attributes()->all()
        );
        
        $this->assertSame(
            '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve"><g id="copy"><path d="M72,72V30h28v70H30V72H72z M30,70h40V30V0H0v70H30z"/></g></svg>',
            (string)$icon->tag()
        );
    }
    
    public function testCreateIconFromFileMethodUsingFile()
    {
        $icon = (new IconFactory())->createIconFromFile(
            name: 'download',
            file: new File(__DIR__.'/svg-icons/edit.svg')
        );
    
        $this->assertSame(
            [
                'xmlns' => 'http://www.w3.org/2000/svg',
                'width' => '20',
                'height' => '20',
                'viewBox' => '0 0 100 100',
            ],
            $icon->tag()->attributes()->all()
        );
        
        $this->assertSame(
            '<span class="icon icon-download"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 100 100"><title>edit</title><path d="M80,40L30,90L0,100l10-30l50-50 M90,30l10-10L80,0L70,10L90,30z"/></svg></span>',
            (string)$icon
        );
    }    
    
    public function testWithSpecifiedTagFactory()
    {
        $iconFactory = new IconFactory(
            tagFactory: new TagFactory()
        );
        
        $icon = $iconFactory->createIcon(
            name: 'download',
        );
        
        $this->assertInstanceof(IconInterface::class, $icon);        
    }
}