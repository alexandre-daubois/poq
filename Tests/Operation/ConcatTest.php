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

class ConcatTest extends AbstractQueryTest
{
    public function testConcat(): void
    {
        $query = new ObjectQuery();
        $query->from($this->cities)
            ->selectMany('persons', 'p')
            ->selectMany('children', 'c');

        $this->assertSame('Hubert, Alex, Will, Fabien, Nicolas, Salah, Bob', $query->concat(', ', 'name'));
    }
}
