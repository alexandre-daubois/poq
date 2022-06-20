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

class EachTest extends AbstractQueryTest
{
    public function testEach(): void
    {
        $query = new ObjectQuery();
        $query
            ->from($this->cities)
            ->selectMany('persons', 'p');

        $result = $query
            ->each(fn($element) => $element->height * 2);

        $this->assertSame(362, $result[0]);
        $this->assertSame(352, $result[1]);
    }
}
