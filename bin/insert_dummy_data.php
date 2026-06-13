<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use tfmerk\PolarisPim\Framework\ORM\Entity\EntityManager;
use tfmerk\PolarisPim\Entities\User;

try {
	echo 'Insert dummy data', PHP_EOL;

	$entityManager = EntityManager::createFromEnv();

	insertUser($entityManager, 'Bruce Wayne', 'bruce@wayne.goth', ['evil' => false]);
	insertUser($entityManager, 'Joker', 'why@so.serious', ['evil' => true]);
	insertUser($entityManager, 'Poision Ivy', 'fl.ow@ers.goth', ['evil' => true]);
	insertUser($entityManager, 'Pinguin', 'pin.gu@in.goth', ['evil' => true]);
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
