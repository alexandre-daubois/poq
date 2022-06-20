<?php

/*
 * (c) Alexandre Daubois <alex.daubois@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ObjectQuery\Exception;

class NonUniqueResultException extends \Exception
{
    protected $message = 'The query returned %d result(s). You may use "select" instead of "selectOne"';

    public function __construct(int $count)
    {
        parent::__construct(\sprintf($this->message, $count));
    }
}
