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
use Tobento\Service\Icon\Icons;
use Tobento\Service\Icon\IconInterface;
use Tobento\Service\Icon\IconFactory;
use Tobento\Service\Tag\TagInterface;
use Tobento\Service\Tag\Attributes;

/**
 * IconsTest
 */
class IconsTest extends TestCase
{
    public function testThatImplementsIconsInterface()
    {
        $this->assertInstanceof(
            IconsInterface::class,
            new Icons(iconFactory: new IconFactory())
        );
    }
    
    public function testGetMethod()
    {
        $icons = new Icons(iconFactory: new IconFactory());
        
        $this->assertInstanceof(
            IconInterface::class,
            $icons->get('download')
        );
    }
    
    public function testHasMethod()
    {
        $icons = new Icons(iconFactory: new IconFactory());
        
        $this->assertTrue($icons->has('download'));
    }
}