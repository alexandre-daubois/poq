<?php

/*
 * (c) Alexandre Daubois <alex.daubois@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ObjectQuery;

use ObjectQuery\Exception\InvalidAliasException;

class ObjectQueryContextEnvironment
{
    private readonly array $environment;

    public function __construct(array $environment)
    {
        $this->environment = $environment;
    }

    public function get(string $alias): object
    {
        if (!\array_key_exists($alias, $this->environment)) {
            throw new InvalidAliasException($alias, \array_keys($this->environment));
        }

        return $this->environment[$alias];
    }
}
