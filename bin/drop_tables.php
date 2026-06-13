<?php

declare(strict_types=1);

try {
	$host = $_ENV['DB_HOST'] ?? getenv('DB_HOST');
	$port = $_ENV['DB_PORT'] ?? getenv('DB_PORT');
	$dbName = $_ENV['DB_NAME'] ?? getenv('DB_NAME');
	$user = $_ENV['DB_USER'] ?? getenv('DB_USER');
	$password = $_ENV['DB_PASSWORD'] ?? getenv('DB_PASSWORD');

	$pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbName", $user, $password);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	echo 'Dropping tables ...', PHP_EOL;
	dropTable($pdo, 'users');
} catch (Throwable $t) {
	echo 'Drop data base script failed: ', $t->getMessage(), PHP_EOL;
	exit(1);
}

function dropTable(PDO $pdo, string $databaseName): void
{
	echo "- Dropping \"$databaseName\" table", PHP_EOL;

	$sql = "DROP TABLE IF EXISTS \"$databaseName\" CASCADE;";
	$pdo->exec($sql);
}
