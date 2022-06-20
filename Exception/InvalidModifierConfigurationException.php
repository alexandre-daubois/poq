<?php

/*
 * (c) Alexandre Daubois <alex.daubois@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ObjectQuery\Exception;

final class InvalidModifierConfigurationException extends \Exception
{
    protected $message = 'The modifier "%s" is wrongly configured: %s.';

    public function __construct(string $modifier, string $message)
    {
        parent::__construct(\sprintf($this->message, $modifier, $message));
    }
}
