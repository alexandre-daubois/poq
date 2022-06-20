<?php

/*
 * (c) Alexandre Daubois <alex.daubois@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ObjectQuery\Tests;

use ObjectQuery\ObjectQueryContext;
use PHPUnit\Framework\TestCase;

class QueryContextTest extends TestCase
{
    public function testWithEnvironment(): void
    {
        $context = new ObjectQueryContext();

        $environmentA = new \stdClass;
        $environmentB = new \stdClass;
        $newContext = $context
            ->withEnvironment($environmentA, ['a' => 1])
            ->withEnvironment($environmentA, ['b' => 2])
            ->withEnvironment($environmentB, ['c' => 3])
        ;

        $this->assertEmpty((array) $context->getEnvironment(new \stdClass));
        $this->assertEmpty($context->getEnvironment($environmentA));
        $this->assertEmpty($context->getEnvironment($environmentB));

        $this->assertEmpty($newContext->getEnvironment(new \stdClass));
        $this->assertEquals(['a' => 1, 'b' => 2], $newContext->getEnvironment($environmentA));
        $this->assertEquals(['c' => 3], $newContext->getEnvironment($environmentB));
    }

    public function testWithUsedAlias(): void
    {
        $context = new ObjectQueryContext();

        $newContext = $context->withUsedAlias('a');

        $this->assertFalse($context->isUsedAlias('a'));
        $this->assertFalse($context->isUsedAlias('b'));
        $this->assertTrue($newContext->isUsedAlias('a'));
        $this->assertFalse($newContext->isUsedAlias('b'));
    }
}
