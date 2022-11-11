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
use Tobento\Service\Icon\CreateIconException;

/**
 * CreateIconExceptionTest
 */
class CreateIconExceptionTest extends TestCase
{
    public function testMethods()
    {
        $e = new CreateIconException(icon: 'cart');
        
        $this->assertSame('Could not create icon [cart]', $e->getMessage());
        $this->assertSame('cart', $e->icon());
        $this->assertSame(null, $e->html());
        $this->assertSame(null, $e->filename());
    }
    
    public function testCustomMessage()
    {
        $e = new CreateIconException(icon: 'cart', message: 'Custom');
        
        $this->assertSame('Custom', $e->getMessage());
    }
    
    public function testWithHtml()
    {
        $e = new CreateIconException(icon: 'cart', html: 'html');
        
        $this->assertSame('html', $e->html());
    }
    
    public function testWithFilename()
    {
        $e = new CreateIconException(icon: 'cart', filename: 'filename.json');
        
        $this->assertSame('filename.json', $e->filename());
    }
}