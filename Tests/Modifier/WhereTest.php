<?php

/*
 * (c) Alexandre Daubois <alex.daubois@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ObjectQuery\Tests\Modifier;

use ObjectQuery\ObjectQuery;
use ObjectQuery\ObjectQueryContextEnvironment;
use ObjectQuery\Tests\AbstractQueryTest;

class WhereTest extends AbstractQueryTest
{
    public function testObjectsSelectWhere(): void
    {
        $query = new ObjectQuery();
        $query->from($this->cities)
            ->where(fn($city): bool => $city->minimalAge > 20);

        $this->assertSame('Lyon', \current($query->select())->name);
    }

    public function testWhereWithoutResult(): void
    {
        $query = new ObjectQuery();
        $query->from($this->cities)
            ->where(fn($city): bool => $city->minimalAge < 1);

        $this->assertEmpty($query->select());
    }

    public function testWhereWithAncestorNode(): void
    {
        $cityQuery = new ObjectQuery();
        $result = $cityQuery
            ->from($this->cities, 'city')
            ->where(fn($city) => \str_contains($city->name, 'Lyon'))
            ->selectMany('persons', 'person')
            ->where(fn($person) => $person->height > 180)
            ->selectMany('children', 'child')
            ->where(fn($child, ObjectQueryContextEnvironment $context): bool => $child->age > $context->get('city')->minimalAge)
            ->select('name');

        $this->assertCount(3, $result);
        $this->assertSame('Hubert', $result[0]);
        $this->assertSame('Alex', $result[1]);
        $this->assertSame('Will', $result[2]);
    }
}
