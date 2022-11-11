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

use Stringable;
use Exception;
use Throwable;

/**
 * CreateIconException
 */
class CreateIconException extends Exception
{
    /**
     * Create a new CreateIconException.
     *
     * @param string $icon The icon name.
     * @param null|string|Stringable $html
     * @param null|string $filename
     * @param string $message The message
     * @param int $code
     * @param null|Throwable $previous
     */
    public function __construct(
        protected string $icon,
        protected null|string|Stringable $html = null,
        protected null|string $filename = null,
        string $message = '',
        int $code = 0,
        null|Throwable $previous = null
    ) {
        if ($message === '') {            
            $message = 'Could not create icon ['.$icon.']';
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
    
    /**
     * Returns the html or null if none.
     *
     * @return null|string|Stringable
     */
    public function html(): null|string|Stringable
    {
        return $this->html;
    }
    
    /**
     * Returns the file or null if none.
     *
     * @return null|string
     */
    public function filename(): null|string
    {
        return $this->filename;
    }
}