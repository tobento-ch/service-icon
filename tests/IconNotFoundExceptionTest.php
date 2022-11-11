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
use Tobento\Service\Icon\IconNotFoundException;

/**
 * IconNotFoundExceptionTest
 */
class IconNotFoundExceptionTest extends TestCase
{
    public function testMethods()
    {
        $e = new IconNotFoundException(icon: 'cart');
        
        $this->assertSame('Icon [cart] not found', $e->getMessage());
        $this->assertSame('cart', $e->icon());
    }
    
    public function testCustomMessage()
    {
        $e = new IconNotFoundException(icon: 'cart', message: 'Custom');
        
        $this->assertSame('Custom', $e->getMessage());
    }
}