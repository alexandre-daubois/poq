<?php

/*
 * (c) Alexandre Daubois <alex.daubois@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ObjectQuery\Exception;

class AliasAlreadyTakenInQueryContextException extends \Exception
{
    protected $message = 'Alias "%s" is already taken in the query. You should choose another name for your alias.';

    public function __construct(string $alias)
    {
        parent::__construct(\sprintf($this->message, $alias));
    }
}
