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

class AverageTest extends AbstractQueryTest
{
    public function testAverage(): void
    {
        $query = (new ObjectQuery())
            ->from($this->cities, 'city')
            ->selectMany('persons', 'person');

        $query->selectMany('children', 'child')
            ->where(fn($child) => $child->age > 20);

        $this->assertSame(30.75, $query->average('age'));
    }

    public function testAverageOnNonNumericCollection(): void
    {
        $query = new ObjectQuery();
        $foo = new class {
            public array $collection = [1, 2, 3, 'average'];
        };

        $query->from([$foo]);

        $this->expectException(IncompatibleCollectionException::class);
        $this->expectExceptionMessage('The given collection is incompatible with "average" because of the following reason: Operation can only be applied to a collection of numerics.');
        $query->average('collection');
    }
}
