<?php

/*
 * (c) Alexandre Daubois <alex.daubois@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ObjectQuery\Tests\Operation;

use ObjectQuery\Exception\IncompatibleFieldException;
use ObjectQuery\ObjectQuery;
use ObjectQuery\Tests\AbstractQueryTest;

class SelectManyTest extends AbstractQueryTest
{
    public function testSelectMany(): void
    {
        $cityQuery = new ObjectQuery();
        $result = $cityQuery
            ->from($this->cities)
            ->where(fn($city) => $city->name === 'Lyon')
            ->selectMany('persons', '__')
            ->where(fn($person) => $person->height > 180)
            ->select();

        $this->assertCount(1, $result);
        $this->assertSame(181, $result[0]->height);
    }

    public function testSelectManyOnScalarField(): void
    {
        $cityQuery = new ObjectQuery();

        $this->expectException(IncompatibleFieldException::class);
        $this->expectExceptionMessage('The given field is incompatible with "selectMany" because of the following reason: You can only selectMany on fields that are collections of objects.');
        $cityQuery
            ->from($this->cities)
            ->selectMany('minimalAge');
    }
}
