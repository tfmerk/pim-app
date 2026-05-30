<?php

declare(strict_types=1);

namespace tfmerk\PolarisPim\Framework\Controller;

use tfmerk\PolarisPim\Framework\Request\Request;

abstract class AbstractController
{
	public function __construct(
		public readonly Request $request,
	) {}
}
