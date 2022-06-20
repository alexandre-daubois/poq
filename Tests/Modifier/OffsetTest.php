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
use ObjectQuery\Tests\AbstractQueryTest;

class OffsetTest extends AbstractQueryTest
{
    public function testOffset(): void
    {
        $query = new ObjectQuery();
        $result = $query->from($this->cities)
            ->offset(1)
            ->select();

        $this->assertCount(1, $result);
        $this->assertSame('Paris', $result[0]->name);
    }

    public function testNullOffset(): void
    {
        $query = new ObjectQuery();
        $result = $query->from($this->cities)
            ->offset(null)
            ->select();

        $this->assertCount(\count($this->cities), $result);
    }

    public function testNegativeOffset(): void
    {
        $query = new ObjectQuery();

        $this->expectException(InvalidModifierConfigurationException::class);
        $this->expectExceptionMessage('The offset must be a positive integer or null to set no offset.');
        $query->from($this->cities)
            ->offset(-1)
            ->select();
    }
}
