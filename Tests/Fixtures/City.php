<?php

/*
 * (c) Alexandre Daubois <alex.daubois@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ObjectQuery\Tests\Fixtures;

class City
{
    public string $name;

    public array $persons;

    public int $minimalAge;

    public function __construct(string $name, array $persons, int $minimalAge)
    {
        $this->name = $name;
        $this->persons = $persons;
        $this->minimalAge = $minimalAge;
    }
}
