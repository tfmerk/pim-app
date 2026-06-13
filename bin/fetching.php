<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use tfmerk\PolarisPim\Framework\ORM\Entity\EntityManager;
use tfmerk\PolarisPim\Entities\User;

try {
	echo 'Fetching data', PHP_EOL;

	$entityManager = EntityManager::createFromEnv();

	$entities = $entityManager->findBy(className: User::class, criteria: [
		'email' => [
			'LIKE' => '%goth'
		]
	]);
	echo '- Fetched ' . count($entities) . ' goth mail users from database', PHP_EOL;

	$entities = $entityManager->findBy(className: User::class, criteria: [
		'email' => [
			'NOT LIKE' => '%goth'
		]
	]);
	echo '- Fetched ' . count($entities) . ' users that dont use goth mail from database', PHP_EOL;

	$entities = $entityManager->findBy(className: User::class, criteria: [
		'metadata.evil' => true
	]);
	echo '- Fetched ' . count($entities) . ' evil users from database', PHP_EOL;

	$entities = $entityManager->findBy(className: User::class, criteria: [
		'metadata.evil' => true,
		'email' => ['LIKE' => '%why%'],
	]);
	echo '- Fetched ' . count($entities) . ' evil users that use a mail like "%why%" from database', PHP_EOL;
} catch (RuntimeException $t) {
	echo 'Configuration error: ' . $t->getMessage() . ' in ' . $t->getFile() . ':' . $t->getLine() . PHP_EOL;
} catch (InvalidArgumentException $t) {
	echo 'Validation error: ' . $t->getMessage() . ' in ' . $t->getFile() . ':' . $t->getLine() . PHP_EOL;
} catch (PDOException $t) {
	echo 'Database error: ' . $t->getMessage() . ' in ' . $t->getFile() . ':' . $t->getLine() . PHP_EOL;
} catch (Throwable $t) {
	echo 'Generic error: ' . $t->getMessage() . ' in ' . $t->getFile() . ':' . $t->getLine() . PHP_EOL;
}
