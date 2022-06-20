<?php

/*
 * (c) Alexandre Daubois <alex.daubois@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ObjectQuery\Exception;

class AlreadyRegisteredWhereFunctionException extends \Exception
{
    protected $message = 'Function "%s" has already been globally registered to be used in the "where" clause of ObjectQuery.';

    public function __construct(string $functionName)
    {
        parent::__construct(\sprintf($this->message, $functionName));
    }
}
