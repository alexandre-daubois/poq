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
use ObjectQuery\Tests\Fixtures\City;

class SelectOneTest extends AbstractQueryTest
{
    public function testObjectsSelectOne(): void
    {
        $query = ObjectQuery::from($this->cities);
        $result = $query
            ->where(fn($city) => $city->name === 'Lyon')
            ->selectOne();

        $this->assertInstanceOf(City::class, $result);
        $this->assertSame('Lyon', $result->name);
    }

    public function testObjectsSelectOneWithoutResult(): void
    {
        $query = ObjectQuery::from($this->cities);
        $result = $query
            ->where(fn($city) => $city->name === 'Invalid City')
            ->selectOne();

        $this->assertNull($result);
    }

    public function testSelectOneWithField(): void
    {
        $query = ObjectQuery::from($this->cities);
        $result = $query
            ->where(fn($city) => $city->name === 'Lyon')
            ->selectOne('name');

        $this->assertIsString($result);
        $this->assertSame('Lyon', $result);
    }

    public function testSelectOneWithFieldWithoutResult(): void
    {
        $query = ObjectQuery::from($this->cities);
        $result = $query
            ->where(fn($city) => $city->name === 'Rouen')
            ->selectOne('name');

        $this->assertNull($result);
    }
}
