<?php

declare(strict_types=1);

namespace tfmerk\PolarisPim\Controllers;

use tfmerk\PolarisPim\Framework\Controller\AbstractController;
use tfmerk\PolarisPim\Framework\Route\Route;
use tfmerk\PolarisPim\Framework\Request\Request;
use tfmerk\PolarisPim\Framework\View\View;

class HomeController extends AbstractController
{
	#[Route('/', method: 'GET')]
	public function index(): string
	{
		$name = $this->request->query('name', 'Guest');
		return View::make(
			'home/index',
			[
				'title' => 'Polaris',
				'heading' => 'Home of Polaris',
				'username' => $name,
			]
		);
	}
}
