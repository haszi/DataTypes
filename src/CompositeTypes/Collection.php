<?php

declare(strict_types=1);

namespace Haszi\DataTypes\CompositeTypes;

use \Countable;

interface Collection extends Countable
{
    public function count(): int;

    public function isEmpty(): bool;

    public function clear(): void;
}
