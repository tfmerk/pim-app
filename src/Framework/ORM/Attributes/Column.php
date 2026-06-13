<?php

declare(strict_types=1);

namespace tfmerk\PolarisPim\Framework\ORM\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Column
{
	public function __construct(
		public string $name,
		public string $type = 'string',
	) {}
}
