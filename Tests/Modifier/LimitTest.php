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

class LimitTest extends AbstractQueryTest
{
    public function testLimit(): void
    {
        $query = new ObjectQuery();
        $result = $query->from($this->cities)
            ->limit(1)
            ->select();

        $this->assertCount(1, $result);
    }

    public function testNullLimit(): void
    {
        $query = new ObjectQuery();
        $result = $query->from($this->cities)
            ->limit(null)
            ->select();

        $this->assertCount(\count($this->cities), $result);
    }

    public function testNegativeLimit(): void
    {
        $query = new ObjectQuery();

        $this->expectException(InvalidModifierConfigurationException::class);
        $this->expectExceptionMessage('The limit must be a positive integer or null to set no limit.');
        $query->from($this->cities)
            ->limit(-1)
            ->select();
    }

    public function testLimitWithObjects(): void
    {
        $query = new ObjectQuery();
        $query->from($this->cities)
            ->orderBy(ObjectQueryOrder::Descending, 'minimalAge')
            ->limit(1);

        $result = $query->select();
        $this->assertCount(1, $result);
        $this->assertSame('Lyon', $result[0]->name);
    }
}
