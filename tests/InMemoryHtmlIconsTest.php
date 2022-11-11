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
use Tobento\Service\Icon\IconsInterface;
use Tobento\Service\Icon\IconNotFoundException;
use Tobento\Service\Icon\InMemoryHtmlIcons;
use Tobento\Service\Icon\IconInterface;
use Tobento\Service\Icon\IconFactory;

/**
 * InMemoryHtmlIconsTest
 */
class InMemoryHtmlIconsTest extends TestCase
{
    public function testThatImplementsIconsInterface()
    {
        $icons = new InMemoryHtmlIcons(
            icons: [],
            iconFactory: new IconFactory(),
        );
        
        $this->assertInstanceof(
            IconsInterface::class,
            $icons
        );
    }
    
    public function testGetMethod()
    {
        $icons = new InMemoryHtmlIcons(
            icons: [
                'cart' => 'html',
            ],
            iconFactory: new IconFactory(),
        );
        
        $this->assertInstanceof(
            IconInterface::class,
            $icons->get('cart')
        );
    }
    
    public function testGetMethodThrowsIconNotFoundException()
    {
        $this->expectException(IconNotFoundException::class);
        
        $icons = new InMemoryHtmlIcons(
            icons: [
                'cart' => 'html',
            ],
            iconFactory: new IconFactory(),
        );
        
        $icons->get('download');
    }
    
    public function testHasMethod()
    {
        $icons = new InMemoryHtmlIcons(
            icons: [
                'cart' => 'html',
            ],
            iconFactory: new IconFactory(),
        );
        
        $this->assertTrue($icons->has('cart'));
        $this->assertFalse($icons->has('download'));
    }
}