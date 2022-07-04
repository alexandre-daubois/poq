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

class SelectTest extends AbstractQueryTest
{
    public function testObjectsSelect(): void
    {
        $query = ObjectQuery::from($this->cities);
        $result = $query->select('name');

        $this->assertSame('Lyon', $result[0]);
        $this->assertSame('Paris', $result[1]);
    }

    public function testObjectsMultipleSelect(): void
    {
        $query = ObjectQuery::from($this->cities);
        $result = $query->select(['name', 'minimalAge']);

        $this->assertSame('Lyon', $result[0]['name']);
        $this->assertSame(21, $result[0]['minimalAge']);
        $this->assertSame('Paris', $result[1]['name']);
        $this->assertSame(10, $result[1]['minimalAge']);
    }
}
