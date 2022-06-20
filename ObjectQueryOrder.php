<?php

/*
 * (c) Alexandre Daubois <alex.daubois@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ObjectQuery;

enum ObjectQueryOrder {
    case Ascending;
    case Descending;
    case None;
    case Shuffle;
}
