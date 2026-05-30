<?php

declare(strict_types=1);

namespace tfmerk\PolarisPim\Controllers;

use tfmerk\PolarisPim\Framework\Controller\AbstractController;
use tfmerk\PolarisPim\Framework\Route\Route;
use tfmerk\PolarisPim\Framework\View\View;

class ProductController extends AbstractController
{
	#[Route('/product', method: 'GET')]
	public function index(): string
	{
		$productID = (int)$this->request->query('id', '0');
		$productData = [
			'width' => 123.45,
			'height' => 678.99,
			'price' => 999.99,
		];
		return View::make(
			'product/index',
			[
				'title' => 'Unknown (' . $productID . ')',
				'productData' => $productData,
			]
		);
	}

	#[Route('/product/list', method: 'GET')]
	public function list(): string
	{
		$rawFilterProductIDs = $this->request->query('ids', '');
		$filterProductIDs = !empty($rawFilterProductIDs) ? explode(',', $this->request->query('ids', '')) : [];
		$level = (int)$this->request->query('level', '0');

		// todo
		// load products of provided level
		$products = [
			0 => [
				['id' => 100, 'name' => 'bli'],
				['id' => 200, 'name' => 'bla'],
				['id' => 300, 'name' => 'blubb'],
			],
			1 => [
				['id' => 1000, 'name' => 'bli'],
				['id' => 2000, 'name' => 'bla'],
				['id' => 3000, 'name' => 'blubb'],
			],

		];

		// todo
		// get products of provided level
		$products = $products[$level] ?? [];

		// filter by product IDs or get all if "?ids=123,456,798" was not provided
		if (!empty($filterProductIDs)) {
			$products = array_filter(
				$products,
				static fn(array $product) => in_array($product['id'], $filterProductIDs)
			);
		}

		// inject url to the "product index" route
		$products = array_map(
			static fn(array $product) => array_merge($product, ['url' => '/product?id=' . $product['id']]),
			$products
		);

		return View::make(
			'product/list',
			[
				'title' => 'Products list',
				'products' => $products
			]
		);
	}
}
