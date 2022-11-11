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
use Tobento\Service\Icon\SvgFileIconsToJsonFiles;
use Tobento\Service\Dir\Dirs;
use Tobento\Service\Dir\Dir;
use Tobento\Service\Filesystem\Dir as FsDir;
use Tobento\Service\Filesystem\JsonFile;

/**
 * SvgFileIconsToJsonFilesTest
 */
class SvgFileIconsToJsonFilesTest extends TestCase
{
    public function testThatImplementsIconsInterface()
    {
        $icons = new SvgFileIconsToJsonFiles(
            dirs: new Dirs(new Dir(__DIR__.'/svg-icons/')),
            cacheDir: new Dir(__DIR__.'/cache/json-files/'),
        );
        
        $this->assertInstanceof(
            IconsInterface::class,
            $icons
        );
        
        (new FsDir())->delete(__DIR__.'/cache/');
    }
    
    public function testGetAndHasMethod()
    {
        $icons = new SvgFileIconsToJsonFiles(
            dirs: new Dirs(new Dir(__DIR__.'/svg-icons/')),
            cacheDir: new Dir(__DIR__.'/cache/json-files/'),
        );
        
        $this->assertInstanceof(IconInterface::class, $icons->get('edit'));
        
        $this->assertTrue($icons->has('edit'));
        $this->assertFalse($icons->has('foo/copy'));
        $this->assertFalse($icons->has('download'));
        
        (new FsDir())->delete(__DIR__.'/cache/');
    }
    
    public function testGetMethodThrowsIconNotFoundException()
    {
        $this->expectException(IconNotFoundException::class);
        
        $icons = new SvgFileIconsToJsonFiles(
            dirs: new Dirs(new Dir(__DIR__.'/svg-icons/')),
            cacheDir: new Dir(__DIR__.'/cache/json-files/'),
        );
        
        $icons->get('cart');
        
        (new FsDir())->delete(__DIR__.'/cache/');
    }
    
    public function testCreatesJsonCachedFiles()
    {
        $icons = new SvgFileIconsToJsonFiles(
            dirs: new Dirs(new Dir(__DIR__.'/svg-icons/')),
            cacheDir: new Dir(__DIR__.'/cache/json-files/'),
        );
        
        $files = (new FsDir())->getFiles(__DIR__.'/cache/json-files/');
        
        $filenames = [];
        
        foreach($files as $file) {
            $filenames[] = $file->getBasename();
        }
        
        $this->assertSame(
            ['delete.json', 'edit.json', 'xml-spacefree.json', 'xml.json'],
            $filenames
        );
        
        $file = new JsonFile(__DIR__.'/cache/json-files/edit.json');
        
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
            $file->toArray()
        );        
        
        (new FsDir())->delete(__DIR__.'/cache/');
    }
    
    public function testCacheGetsCleared()
    {
        $icons = new SvgFileIconsToJsonFiles(
            dirs: new Dirs(new Dir(__DIR__.'/svg-icons/')),
            cacheDir: new Dir(__DIR__.'/cache/json-files/'),
            clearCache: false,
        );
        
        $this->assertInstanceof(IconInterface::class, $icons->get('edit'));
        
        $icons = new SvgFileIconsToJsonFiles(
            dirs: new Dirs(new Dir(__DIR__.'/svg-icons-new/')),
            cacheDir: new Dir(__DIR__.'/cache/json-files/'),
            clearCache: true,
        );
        
        $this->assertFalse($icons->has('edit'));
        
        (new FsDir())->delete(__DIR__.'/cache/');
    }
    
    public function testCacheIconsAreUsed()
    {
        $icons = new SvgFileIconsToJsonFiles(
            dirs: new Dirs(new Dir(__DIR__.'/svg-icons/')),
            cacheDir: new Dir(__DIR__.'/cache/json-files/'),
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
        
        $icons = new SvgFileIconsToJsonFiles(
            dirs: $dirs,
            cacheDir: new Dir(__DIR__.'/cache/json-files/'),
            clearCache: true,
        );
        
        $files = (new FsDir())->getFiles(__DIR__.'/cache/json-files/');
        
        $filenames = [];
        
        foreach($files as $file) {
            $filenames[] = $file->getBasename();
        }
        
        $this->assertSame(
            ['copy.json', 'edit.json'],
            $filenames
        );
        
        $file = new JsonFile(__DIR__.'/cache/json-files/edit.json');
        
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
            $file->toArray()
        );
        
        (new FsDir())->delete(__DIR__.'/cache/');
    }
}