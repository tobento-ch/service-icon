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
use Tobento\Service\Icon\IconFactoryTranslator;
use Tobento\Service\Translation\Translator;
use Tobento\Service\Translation\Resources;
use Tobento\Service\Translation\Resource;
use Tobento\Service\Translation\Modifiers;
use Tobento\Service\Translation\MissingTranslationHandler;
use Tobento\Service\Tag\Tag;

/**
 * IconFactoryTranslatorTest
 */
class IconFactoryTranslatorTest extends TestCase
{
    protected function translator()
    {
        return new Translator(
            new Resources(
                new Resource('*', 'de', [
                    'edit' => 'bearbeiten',
                ]),
            ),
            new Modifiers(),
            new MissingTranslationHandler(),
            'de',
        );
    }
    
    public function testThatImplementsIconFactoryInterface()
    {
        $this->assertInstanceof(
            IconFactoryInterface::class,
            new IconFactoryTranslator($this->translator())
        );
    }

    public function testCreateIconMethod()
    {
        $factory = new IconFactoryTranslator($this->translator());
        
        $icon = $factory->createIcon(
            name: 'edit',
            tag: new Tag(
                name: 'svg',
                html: '<title>edit</title>',
            ),
        );
        
        $this->assertSame(
            '<svg><title>bearbeiten</title></svg>',
            (string)$icon->tag()
        );
    }
    
    public function testCreateIconFromHtmlMethod()
    {
        $factory = new IconFactoryTranslator($this->translator());
        
        $icon = $factory->createIconFromHtml(
            name: 'edit',
            html: '<svg><title>edit</title></svg>'
        );
        
        $this->assertSame(
            '<svg><title>bearbeiten</title></svg>',
            (string)$icon->tag()
        );
    }
    
    public function testCreateIconFromFileMethod()
    {
        $factory = new IconFactoryTranslator($this->translator());
        
        $icon = $factory->createIconFromFile(
            name: 'download',
            file: __DIR__.'/svg-icons/edit.svg'
        );
        
        $this->assertSame(
            '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 100 100"><title>bearbeiten</title><path d="M80,40L30,90L0,100l10-30l50-50 M90,30l10-10L80,0L70,10L90,30z"/></svg>',
            (string)$icon->tag()
        );
    }    
}