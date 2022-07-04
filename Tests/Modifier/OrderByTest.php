<?php

/*
 * (c) Alexandre Daubois <alex.daubois@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ObjectQuery\Tests\Modifier;

use ObjectQuery\Exception\InvalidModifierConfigurationException;
use ObjectQuery\ObjectQuery;
use ObjectQuery\ObjectQueryOrder;
use ObjectQuery\Tests\AbstractQueryTest;

class OrderByTest extends AbstractQueryTest
{
    public function testObjectsAscendingOrderBy(): void
    {
        $query = ObjectQuery::from($this->cities);

        $query->orderBy(ObjectQueryOrder::Ascending, 'minimalAge');

        $result = $query->select();
        $this->assertSame('Paris', $result[0]->name);
        $this->assertSame('Lyon', $result[1]->name);
    }

    public function testObjectsDescendingOrderBy(): void
    {
        $query = ObjectQuery::from($this->cities);
        $query->orderBy(ObjectQueryOrder::Descending, 'minimalAge');

        $result = $query->select();
        $this->assertSame('Lyon', $result[0]->name);
        $this->assertSame('Paris', $result[1]->name);
    }

    public function testObjectsShuffleWithOrderFieldFailure(): void
    {
        $query = ObjectQuery::from($this->cities);
        $query->orderBy(ObjectQueryOrder::Shuffle, 'minimalAge');

        $this->expectException(InvalidModifierConfigurationException::class);
        $this->expectExceptionMessage('The modifier "orderBy" is wrongly configured: An order field must not be provided when shuffling a collection.');
        $query->select();
    }

    public function testObjectsShuffle(): void
    {
        $query = ObjectQuery::from($this->cities);

        $query->selectMany('persons', 'person')
            ->selectMany('children', 'child')
            ->orderBy(ObjectQueryOrder::Shuffle);

        $firstShuffle = $query->concat(', ', 'name');
        $secondShuffle = $query->concat(', ', 'name');

        $this->assertNotSame($firstShuffle, $secondShuffle);
    }
}
