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

namespace Tobento\Service\Icon;

use Exception;
use Throwable;

/**
 * IconNotFoundException
 */
class IconNotFoundException extends Exception
{
    /**
     * Create a new IconNotFoundException.
     *
     * @param string $icon The icon name.
     * @param string $message The message
     * @param int $code
     * @param null|Throwable $previous
     */
    public function __construct(
        protected string $icon,
        string $message = '',
        int $code = 0,
        null|Throwable $previous = null
    ) {
        if ($message === '') {            
            $message = 'Icon ['.$icon.'] not found';
        }
        
        parent::__construct($message, $code, $previous);
    }

    /**
     * Returns the icon name.
     *
     * @return string
     */
    public function icon(): string
    {
        return $this->icon;
    }
}