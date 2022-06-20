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

class CountTest extends AbstractQueryTest
{
    public function testCount(): void
    {
        $query = new ObjectQuery();
        $query
            ->from($this->cities, 'city')
            ->selectMany('persons', 'person')
            ->selectMany('children', 'child')
        ;

        $query->where(fn($child) => $child->age < 9 || $child->age > 28);

        $this->assertSame(3, $query->count());
    }
}
