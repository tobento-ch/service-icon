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
use Tobento\Service\Icon\IconInterface;
use Tobento\Service\Icon\IconNotFoundException;
use Tobento\Service\Icon\SvgFileIconsToJsonFile;
use Tobento\Service\Dir\Dirs;
use Tobento\Service\Dir\Dir;
use Tobento\Service\Filesystem\Dir as FsDir;
use Tobento\Service\Filesystem\JsonFile;

/**
 * SvgFileIconsToJsonFileTest
 */
class SvgFileIconsToJsonFileTest extends TestCase
{
    public function testThatImplementsIconsInterface()
    {
        $icons = new SvgFileIconsToJsonFile(
            dirs: new Dirs(new Dir(__DIR__.'/svg-icons/')),
            cacheDir: new Dir(__DIR__.'/cache/json-file/'),
        );
        
        $this->assertInstanceof(
            IconsInterface::class,
            $icons
        );
        
        (new FsDir())->delete(__DIR__.'/cache/');
    }
    
    public function testGetAndHasMethod()
    {
        $icons = new SvgFileIconsToJsonFile(
            dirs: new Dirs(new Dir(__DIR__.'/svg-icons/')),
            cacheDir: new Dir(__DIR__.'/cache/json-file/'),
        );
        
        $this->assertInstanceof(IconInterface::class, $icons->get('edit'));
        $this->assertInstanceof(IconInterface::class, $icons->get('delete'));
        
        $this->assertTrue($icons->has('edit'));
        $this->assertFalse($icons->has('foo/edit'));
        $this->assertFalse($icons->has('download'));
        
        (new FsDir())->delete(__DIR__.'/cache/');
    }
    
    public function testGetMethodThrowsIconNotFoundException()
    {
        $this->expectException(IconNotFoundException::class);
        
        $icons = new SvgFileIconsToJsonFile(
            dirs: new Dirs(new Dir(__DIR__.'/svg-icons/')),
            cacheDir: new Dir(__DIR__.'/cache/json-file/'),
        );
        
        $icons->get('cart');
        
        (new FsDir())->delete(__DIR__.'/cache/');
    }
    
    public function testCreatesJsonCachedFile()
    {
        $icons = new SvgFileIconsToJsonFile(
            dirs: new Dirs(new Dir(__DIR__.'/svg-icons/')),
            cacheDir: new Dir(__DIR__.'/cache/json-file/'),
        );
        
        $file = new JsonFile(__DIR__.'/cache/json-file/icons.json');
        $tags = $file->toArray();
        $names = [];
        
        foreach($tags as $iconName => $tag) {
            $names[] = $iconName;
        }
        
        $this->assertSame(
            ['delete', 'edit', 'xml-spacefree', 'xml'],
            $names
        );
        
        $this->assertSame(
            [
                'name' => 'svg',
                'html' => '<title>edit</title><path d="M80,40L30,90L0,100l10-30l50-50 M90,30l10-10L80,0L70,10L90,30z"/>',
                'attributes' => [
                    'xmlns' => 'http://www.w3.org/2000/svg',
                    'width' => '20',
                    'height'=> '20',
                    'viewBox' => '0 0 100 100',
                ],
            ],
            $tags['edit']
        );
        
        (new FsDir())->delete(__DIR__.'/cache/');
    }
    
    public function testCacheGetsCleared()
    {
        $icons = new SvgFileIconsToJsonFile(
            dirs: new Dirs(new Dir(__DIR__.'/svg-icons/')),
            cacheDir: new Dir(__DIR__.'/cache/json-file/'),
            clearCache: false,
        );
        
        $this->assertInstanceof(IconInterface::class, $icons->get('edit'));
        
        $icons = new SvgFileIconsToJsonFile(
            dirs: new Dirs(new Dir(__DIR__.'/svg-icons-new/')),
            cacheDir: new Dir(__DIR__.'/cache/json-file/'),
            clearCache: true,
        );
        
        $this->assertFalse($icons->has('edit'));
        
        (new FsDir())->delete(__DIR__.'/cache/');
    }
    
    public function testCacheIconsAreUsed()
    {
        $icons = new SvgFileIconsToJsonFile(
            dirs: new Dirs(new Dir(__DIR__.'/svg-icons/')),
            cacheDir: new Dir(__DIR__.'/cache/json-file/'),
            clearCache: false,
        );
        
        (new FsDir())->rename(__DIR__.'/svg-icons/', 'svg-icons-tmp');
        
        $this->assertInstanceof(IconInterface::class, $icons->get('edit'));
        
        (new FsDir())->rename(__DIR__.'/svg-icons-tmp/', 'svg-icons');
        (new FsDir())->delete(__DIR__.'/cache/');
    }
    
    public function testMultipleDirs()
    {
        $dirs = new Dirs(
            new Dir(__DIR__.'/svg-icons/bar/'),
            new Dir(__DIR__.'/svg-icons/foo/'),
        );
        
        $icons = new SvgFileIconsToJsonFile(
            dirs: $dirs,
            cacheDir: new Dir(__DIR__.'/cache/json-file/'),
            clearCache: true,
        );
        
        $file = new JsonFile(__DIR__.'/cache/json-file/icons.json');
        $tags = $file->toArray();
        $names = [];
        
        foreach($tags as $iconName => $tag) {
            $names[] = $iconName;
        }
        
        $this->assertSame(['edit', 'copy'], $names);
        
        $this->assertSame(
            [
                'name' => 'svg',
                'html' => '<title>edit</title><path d="M80,40L30,90L0,100l10-30l50-50 M90,30l10-10L80,0L70,10L90,30z"/>',
                'attributes' => [
                    'xmlns' => 'http://www.w3.org/2000/svg',
                    'width' => '24',
                    'height'=> '24',
                    'viewBox' => '0 0 100 100',
                ],
            ],
            $tags['edit']
        );
        
        (new FsDir())->delete(__DIR__.'/cache/');
    }
}