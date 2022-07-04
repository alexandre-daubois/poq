<?php

/*
 * (c) Alexandre Daubois <alex.daubois@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ObjectQuery\Tests\Operation;

use ObjectQuery\Exception\IncompatibleCollectionException;
use ObjectQuery\ObjectQuery;
use ObjectQuery\Tests\AbstractQueryTest;

class SumTest extends AbstractQueryTest
{
    public function testSum(): void
    {
        $query = (ObjectQuery::from($this->cities))
            ->selectMany('persons', 'person');

        $query->selectMany('children', 'child')
            ->where(fn($child) => $child->age > 20);

        $this->assertSame(123, $query->sum('age'));
    }

    public function testSumOnNonNumericCollection(): void
    {
        $foo = new class {
            public array $collection = [1, 2, 3, 'average'];
        };

        $query = ObjectQuery::from([$foo]);

        $this->expectException(IncompatibleCollectionException::class);
        $this->expectExceptionMessage('The given collection is incompatible with "sum" because of the following reason: Operation can only be applied to a collection of numerics.');
        $query->sum('collection');
    }
}
