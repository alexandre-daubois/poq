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

class MinTest extends AbstractQueryTest
{
    public function testMin(): void
    {
        $query = (ObjectQuery::from($this->cities))
            ->selectMany('persons', 'person')
            ->selectMany('children', 'child');

        $this->assertSame(8, $query->min('age'));
    }

    public function testMinWithoutResult(): void
    {
        $query = (ObjectQuery::from($this->cities))
            ->from($this->cities, alias: 'city')
            ->selectMany('persons', 'person')
            ->selectMany('children', 'child')
                ->where(fn($child) => $child->age < 0);

        $this->assertNull($query->min('age'));
    }
}
