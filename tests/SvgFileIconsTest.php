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
use Tobento\Service\Icon\SvgFileIcons;
use Tobento\Service\Dir\Dirs;
use Tobento\Service\Dir\Dir;
use Tobento\Service\Icon\IconInterface;
use Tobento\Service\Icon\IconFactory;
use Tobento\Service\Tag\TagInterface;
use Tobento\Service\Tag\Attributes;

/**
 * SvgFileIconsTest
 */
class SvgFileIconsTest extends TestCase
{
    public function testThatImplementsIconsInterface()
    {
        $this->assertInstanceof(
            IconsInterface::class,
            new SvgFileIcons(dirs: new Dirs(new Dir(__DIR__.'/svg-icons/')))
        );
    }
    
    public function testGetMethod()
    {
        $icons = new SvgFileIcons(dirs: new Dirs(new Dir(__DIR__.'/svg-icons/')));
        
        $this->assertInstanceof(
            IconInterface::class,
            $icons->get('edit')
        );
    }
    
    public function testGetMethodFromSubdir()
    {
        $icons = new SvgFileIcons(dirs: new Dirs(new Dir(__DIR__.'/svg-icons/')));
        
        $this->assertInstanceof(
            IconInterface::class,
            $icons->get('foo/copy')
        );
    }
    
    public function testGetMethodThrowsIconNotFoundException()
    {
        $this->expectException(IconNotFoundException::class);
        
        $icons = new SvgFileIcons(dirs: new Dirs(new Dir(__DIR__.'/svg-icons/')));
        
        $icons->get('download');
    }
    
    public function testGetMethodThrowsIconNotFoundExceptionIfNotWithinDir()
    {
        $this->expectException(IconNotFoundException::class);
        
        $icons = new SvgFileIcons(dirs: new Dirs(new Dir(__DIR__.'/svg-icons/foo')));
        
        $icons->get('../edit');
    }
    
    public function testGetMethodThrowsIconNotFoundExceptionOnInvalidFile()
    {
        $this->expectException(IconNotFoundException::class);
        
        $icons = new SvgFileIcons(dirs: new Dirs(new Dir(__DIR__.'/svg-icons/')));
        
        $icons->get('invalid-icon');
    }
}