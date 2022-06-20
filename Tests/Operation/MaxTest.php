<?php

/*
 * (c) Alexandre Daubois <alex.daubois@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ObjectQuery\Tests\Operation;

use ObjectQuery\ObjectQuery;
use ObjectQuery\Tests\AbstractQueryTest;

class MaxTest extends AbstractQueryTest
{
    public function testMax(): void
    {
        $query = (new ObjectQuery())
            ->from($this->cities, 'city')
            ->selectMany('persons', 'person')
            ->selectMany('children', 'child');

        $this->assertSame(45, $query->max('age'));
    }

    public function testMaxWithoutResult(): void
    {
        $query = (new ObjectQuery())
            ->from($this->cities, 'city')
            ->selectMany('persons', 'person')
            ->where(fn($person) => $person->height > 190)
            ->selectMany('children', 'child');

        $this->assertNull($query->max('age'));
    }
}
