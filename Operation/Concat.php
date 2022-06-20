<?php

/*
 * (c) Alexandre Daubois <alex.daubois@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ObjectQuery\Operation;

use ObjectQuery\ObjectQuery;
use ObjectQuery\ObjectQueryContext;

final class Concat extends AbstractOperation
{
    private readonly string $separator;

    public function __construct(ObjectQuery $parentQuery, string $field, string $separator = ' ')
    {
        parent::__construct($parentQuery, $field);

        $this->separator = $separator;
    }

    public function apply(array $source, ObjectQueryContext $context): string
    {
        $source = $this->applySelect($source, $context);

        $string = '';
        foreach ($source as $key => $value) {
            $string .= $value;

            if ($key !== \array_key_last($source)) {
                $string .= $this->separator;
            }
        }

        return $string;
    }
}
