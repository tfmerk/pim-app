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

	echo 'Creating "users" table if it does not exist...', PHP_EOL;
	$sql = 'CREATE TABLE IF NOT EXISTS "users" ('
		. '"id" SERIAL PRIMARY KEY,'
		. '"username" VARCHAR(255) NOT NULL,'
		. '"created_at" TIMESTAMP NOT NULL'
		. ');';
	$pdo->exec($sql);

	echo 'Checking for schema updates...', PHP_EOL;
	addNewColumn($pdo, 'users', 'email', 'VARCHAR(255)', ['NOT NULL']);
	addNewColumn($pdo, 'users', 'metadata', 'JSONB', ['NOT NULL'], '\'{}\'');
	addNewColumn($pdo, 'users', 'last_seen', 'TIMESTAMP', ['NOT NULL'], 'NOW()');


	echo 'Migration completed successfully!', PHP_EOL;
} catch (Throwable $t) {
	echo 'Migration failed: ' . $t->getMessage() . PHP_EOL;
	exit(1);
}

function addNewColumn(PDO $pdo, string $tableName, string $columnName, string $columnType, array $constraints = [], ?string $fallbackValue = null): void
{
	$checkColumn = $pdo->prepare("SELECT 1 FROM information_schema.columns WHERE table_name=:table AND column_name=:column");
	$checkColumn->execute(['table' => $tableName, 'column' => $columnName]);

	if (!$checkColumn->fetch()) {
		echo "- Add to \"$tableName\" table missing column \"$columnName\" ", PHP_EOL;

		// If a NOT NULL constraint exists and we have a fallback value -> add it cleanly
		$isNotNull = in_array('NOT NULL', $constraints, true);
		if ($isNotNull && $fallbackValue !== null) {
			$tempConstraints = array_filter($constraints, static fn(string $defition) => $defition !== 'NOT NULL');
			$constraintsString = implode(' ', $tempConstraints);

			// Add column as nullable but with any other constraint
			$pdo->exec("ALTER TABLE \"$tableName\" ADD COLUMN \"$columnName\" $columnType $constraintsString;");

			// Backfill pre-existing rows with fallback value
			$pdo->exec("UPDATE \"$tableName\" SET \"$columnName\" = $fallbackValue WHERE \"$columnName\" IS NULL");

			// Add NOT NULL constraint to column
			$pdo->exec("ALTER TABLE \"$tableName\" ALTER COLUMN \"$columnName\" SET NOT NULL;");
		} else {
			$constraintsString = implode(' ', $constraints);
			$pdo->exec("ALTER TABLE \"$tableName\" ADD COLUMN \"$columnName\" $columnType $constraintsString;");
		}
	}
}
