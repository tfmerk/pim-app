<?php

declare(strict_types=1);

namespace tfmerk\PolarisPim\Framework\ORM\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Table
{
	public function __construct(
		public string $name
	) {}
}
