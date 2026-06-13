<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use tfmerk\PolarisPim\Framework\ORM\Entity\EntityManager;
use tfmerk\PolarisPim\Entities\User;
use tfmerk\PolarisPim\Entities\Product;

try {
	echo 'Insert dummy data', PHP_EOL;

	$entityManager = EntityManager::createFromEnv();

	insertUser($entityManager, 'Bruce Wayne', 'bruce@wayne.goth', ['evil' => false]);
	insertUser($entityManager, 'Joker', 'why@so.serious', ['evil' => true]);
	insertUser($entityManager, 'Poision Ivy', 'fl.ow@ers.goth', ['evil' => true]);
	insertUser($entityManager, 'Pinguin', 'pin.gu@in.goth', ['evil' => true]);

	insertProduct(
		$entityManager,
		'Batarang Deluxe',
		14.99,
		'https://cdn.polaris.local/batarang.png',
		'Perfect for dark nights in Gotham.',
		['material' => 'Titanium Alloy', 'weight_g' => 250]
	);

	insertProduct(
		$entityManager,
		'Joker Gas Canister',
		49.99,
		null,
		'Smiles guaranteed!',
		['dangerous' => true, 'color' => 'Green']
	);
} catch (RuntimeException $t) {
	echo 'Configuration error: ' . $t->getMessage() . ' in ' . $t->getFile() . ':' . $t->getLine() . PHP_EOL;
} catch (InvalidArgumentException $t) {
	echo 'Validation error: ' . $t->getMessage() . ' in ' . $t->getFile() . ':' . $t->getLine() . PHP_EOL;
} catch (PDOException $t) {
	echo 'Database error: ' . $t->getMessage() . ' in ' . $t->getFile() . ':' . $t->getLine() . PHP_EOL;
} catch (Throwable $t) {
	echo 'Generic error: ' . $t->getMessage() . ' in ' . $t->getFile() . ':' . $t->getLine() . PHP_EOL;
}

function insertUser(EntityManager $entityManager, string $username, string $email, array $metadata): void
{
	echo 'Insert new user...', PHP_EOL;

	$user = new User(
		username: $username,
		email: $email,
		metadata: $metadata,
	);
	$entityManager->persist($user);
	echo 'User ID: ', $user->id, PHP_EOL;

	/** @var User|null $fetchedUser */
	$fetchedUser = $entityManager->find(User::class, $user->id);
	if ($fetchedUser !== null) {
		echo 'Fetched data:', PHP_EOL,
		'- Username: ', $fetchedUser->username, PHP_EOL,
		'- E-Mail: ', $fetchedUser->email, PHP_EOL,
		'- Created at: ', $fetchedUser->createdAt->format('Y-m-d H:i:s'), PHP_EOL,
		'- Metadata: ', json_encode($fetchedUser->metadata), PHP_EOL;
	}
	echo PHP_EOL;
}

function insertProduct(
	EntityManager $entityManager,
	string $productName,
	float $priceInDecimal,
	?string $imageUrl,
	?string $marketingText,
	array $metadata
): void {
	echo 'Insert new product...', PHP_EOL;

	// Convert decimal value (e.g. 14.99) safely to cents integer (1499)
	$priceInCents = (int)round($priceInDecimal * 100);

	$product = new Product(
		productName: $productName,
		price: $priceInCents,
		imageUrl: $imageUrl,
		marketingText: $marketingText,
		metadata: $metadata
	);

	$entityManager->persist($product);
	echo 'Product ID: ', $product->id, PHP_EOL;

	/** @var Product|null $fetchedProduct */
	$fetchedProduct = $entityManager->find(Product::class, $product->id);
	if ($fetchedProduct !== null) {
		echo 'Fetched data:', PHP_EOL,
		'- Product Name: ', $fetchedProduct->productName, PHP_EOL,
		'- Price (Raw Cents): ', $fetchedProduct->price, PHP_EOL,
		'- Price (Formatted): $', $fetchedProduct->getFormattedPrice(), PHP_EOL,
		'- Image URL: ', $fetchedProduct->imageUrl ?? 'None', PHP_EOL,
		'- Marketing Text: ', $fetchedProduct->marketingText ?? 'None', PHP_EOL,
		'- Created At: ', $fetchedProduct->createdAt->format('Y-m-d H:i:s'), PHP_EOL,
		'- Changed At: ', $fetchedProduct->changedAt->format('Y-m-d H:i:s'), PHP_EOL,
		'- Metadata: ', json_encode($fetchedProduct->metadata), PHP_EOL;
	}
	echo PHP_EOL;
}
