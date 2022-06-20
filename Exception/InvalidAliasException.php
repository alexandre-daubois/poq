<?php

/*
 * (c) Alexandre Daubois <alex.daubois@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ObjectQuery\Exception;

class InvalidAliasException extends \Exception
{
    protected $message = 'Alias "%s" is not defined in the context. Available alias are: %s.';

    public function __construct(string $alias, array $availableAliases)
    {
        parent::__construct(\sprintf($this->message, $alias, \implode(', ', $availableAliases)));
    }
}
