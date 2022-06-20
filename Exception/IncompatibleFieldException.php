<?php

/*
 * (c) Alexandre Daubois <alex.daubois@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ObjectQuery\Exception;

class IncompatibleFieldException extends \Exception
{
    protected $message = 'The given field is incompatible with "%s" because of the following reason: %s.';

    public function __construct(string $place, string $message)
    {
        parent::__construct(\sprintf($this->message, $place, $message));
    }
}
