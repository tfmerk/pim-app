<?php

declare(strict_types=1);

namespace tfmerk\PolarisPim\Controllers;

use tfmerk\PolarisPim\Framework\Controller\AbstractController;
use tfmerk\PolarisPim\Framework\Route\Route;
use tfmerk\PolarisPim\Framework\View\View;
use tfmerk\PolarisPim\Framework\ORM\Entity\EntityManager;
use tfmerk\PolarisPim\Entities\User;
use tfmerk\PolarisPim\Entities\Product;

class HomeController extends AbstractController
{
	#[Route('/', method: 'GET')]
	public function index(): string
	{
		$name = $this->request->query('name', 'Guest');
		/** @var array<User> $users */
		$users = $this->fetchUsers();
		/** @var array<Product> $products */
		$products = $this->fetchProducts();
		return View::make(
			'home/index',
			[
				'title' => 'Polaris',
				'heading' => 'Home of Polaris',
				'username' => $name,
				'users' => $users,
				'products' => $products,
			]
		);
	}

	protected function fetchUsers(): array
	{
		$entityManager = EntityManager::createFromEnv();
		return $entityManager->findBy(User::class);
	}

	protected function fetchProducts(): array
	{
		$entityManager = EntityManager::createFromEnv();
		return $entityManager->findBy(Product::class);
	}
}
