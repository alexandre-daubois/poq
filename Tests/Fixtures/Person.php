<?php

/*
 * (c) Alexandre Daubois <alex.daubois@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ObjectQuery\Tests\Fixtures;

class Person
{
    public array $children;

    public int $height;

    public function __construct(array $children, int $height)
    {
        $this->children = $children;
        $this->height = $height;
    }
}
