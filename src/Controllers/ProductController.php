<?php

declare(strict_types=1);

namespace tfmerk\PolarisPim\Controllers;

use tfmerk\PolarisPim\Framework\Controller\AbstractController;
use tfmerk\PolarisPim\Framework\Route\Route;
use tfmerk\PolarisPim\Framework\View\View;
use tfmerk\PolarisPim\Framework\ORM\Entity\EntityManager;
use tfmerk\PolarisPim\Entities\Product;

class ProductController extends AbstractController
{
	#[Route('/product', method: 'GET')]
	public function index(): string
	{
		$productID = (int)$this->request->query('id', '0');
		/** @var ?Product $product */
		$product = $this->fetchProduct($productID);

		return View::make(
			'product/index',
			$this->request->uri,
			[
				'productID' => $productID,
				'product' => $product,
			]
		);
	}

	#[Route('/product/list', method: 'GET')]
	public function list(): string
	{
		$rawFilterProductIDs = $this->request->query('ids', '');
		$filterProductIDs = !empty($rawFilterProductIDs) ? explode(',', $this->request->query('ids', '')) : [];
		$products = $this->fetchProducts();

		return View::make(
			'product/list',
			$this->request->uri,
			[
				'products' => $products
			]
		);
	}

	#[Route('/product/datasheet', method: 'GET')]
	public function datasheet(): string
	{
		/** @var array<Product> $product */
		$products = $this->fetchProducts();
		usort($products, static fn(Product $a, Product $b) => $a->price <=> $b->price);

		return View::make(
			'product/datasheet',
			$this->request->uri,
			[
				'products' => $products,
			],
			''
		);
	}

	protected function fetchProduct(int $id): ?Product
	{
		$entityManager = EntityManager::createFromEnv();
		return $entityManager->find(Product::class, $id);
	}

	/**
	 * @return Product[]
	 */
	protected function fetchProducts(): array
	{
		$entityManager = EntityManager::createFromEnv();
		return $entityManager->findBy(Product::class);
	}
}
