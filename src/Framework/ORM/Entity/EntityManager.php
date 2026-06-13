<?php

declare(strict_types=1);

namespace tfmerk\PolarisPim\Framework\ORM\Entity;

use tfmerk\PolarisPim\Framework\ORM\Attributes\Table;
use tfmerk\PolarisPim\Framework\ORM\Attributes\Column;
use tfmerk\PolarisPim\Framework\ORM\Attributes\Id;
use tfmerk\PolarisPim\Framework\ORM\Entity\EntityInterface;
use PDO;
use InvalidArgumentException;
use RuntimeException;
use ReflectionClass;
use DateTimeImmutable;

class EntityManager
{
	public function __construct(
		private PDO $pdo,
	) {
		$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}


	public static function createFromEnv(): self
	{
		$host = $_ENV['DB_HOST'] ?? getenv('DB_HOST');
		$port = $_ENV['DB_PORT'] ?? getenv('DB_PORT');
		$dbName = $_ENV['DB_NAME'] ?? getenv('DB_NAME');
		$user = $_ENV['DB_USER'] ?? getenv('DB_USER');
		$password = $_ENV['DB_PASSWORD'] ?? getenv('DB_PASSWORD');

		$missing = [];
		if (!$host) {
			$missing[] = 'DB_HOST';
		}
		if (!$port) {
			$missing[] = 'DB_PORT';
		}
		if (!$dbName) {
			$missing[] = 'DB_NAME';
		}
		if (!$user) {
			$missing[] = 'DB_USER';
		}
		if (!$password) {
			$missing[] = 'DB_PASSWORD';
		}

		if (!empty($missing)) {
			throw new RuntimeException('Cannot initialize ' . self::class . '. Missing required environment variable(s): ' . implode(', ', $missing));
		}

		$dsn = "pgsql:host=$host;port=$port;dbname=$dbName";

		$pdo = new PDO($dsn, $user, $password);

		return new self($pdo);
	}

	public function persist(EntityInterface $entity): void
	{
		$reflect = new ReflectionClass($entity);

		$tableAttribute = $reflect->getAttributes(Table::class)[0] ?? null;
		if ($tableAttribute === null) {
			throw new InvalidArgumentException('Class is not a valid entity table');
		}
		/** @var Table $tableInstance */
		$tableInstance = $tableAttribute->newInstance();
		$tableName = $tableInstance->name;

		$columns = [];
		$values = [];
		$params = [];
		$idProperty = null;

		$properties = $reflect->getProperties();
		foreach ($properties as $property) {
			if ($property->getAttributes(Id::class)) {
				$idProperty = $property;
				continue;
			}

			$columnAttribute = $property->getAttributes(Column::class)[0] ?? null;
			if ($columnAttribute === null) {
				continue;
			}

			/** @var Column $columnInstance */
			$columnInstance = $columnAttribute->newInstance();
			$columnName = $columnInstance->name;
			$columnType = $columnInstance->type;

			if (!$property->isInitialized($entity)) {
				continue;
			}

			$value = $property->getValue($entity);

			// Convert PHP types to database representations
			$processedValue = match ($columnType) {
				'json' => json_encode($value, JSON_THROW_ON_ERROR),
				'datetime' => $value instanceof DateTimeImmutable ? $value->format('Y-m-d H:i:s') : null,
				default => $value
			};

			$columns[] = '"' . $columnName . '"';
			$paramName = ':' . $property->getName();
			$params[$paramName] = $processedValue;
			$values[] = $paramName;
		}

		if (empty($columns)) {
			throw new InvalidArgumentException('No columns found to persist.');
		}

		$sql = sprintf(
			'INSERT INTO "%s" (%s) VALUES (%s)',
			$tableName,
			implode(', ', $columns),
			implode(', ', $values)
		);
		$statement = $this->pdo->prepare($sql);
		$statement->execute($params);

		// If an ID property exists and it's an auto-incrementing int, set it back using
		if ($idProperty !== null) {
			$lastInsertedID = $this->pdo->lastInsertId();
			if ($lastInsertedID !== null) {
				$idProperty->setValue($entity, (int)$lastInsertedID);
			}
		}
	}

	public function find(string $className, int $id): ?EntityInterface
	{
		$reflect = new ReflectionClass($className);
		if (!$reflect->implementsInterface(EntityInterface::class)) {
			throw new InvalidArgumentException('Target class must implement ' . EntityInterface::class . '.');
		}

		$tableAttribute = $reflect->getAttributes(Table::class)[0] ?? null;
		if ($tableAttribute === null) {
			throw new InvalidArgumentException('Class is not a valid entity table');
		}

		/** @var Table $tableInstance */
		$tableInstance = $tableAttribute->newInstance();
		$tableName = $tableInstance->name;


		$properties = $reflect->getProperties();


		// Find the designated database column name for the primary ID
		$idColumnName = 'id';
		foreach ($properties as $property) {
			if ($property->getAttributes(Id::class)) {
				$columnAttribute = $property->getAttributes(Column::class)[0] ?? null;
				if ($columnAttribute !== null) {
					$idColumnName = $columnAttribute->newInstance()->name;
				}
				break;
			}
		}

		$sql = sprintf(
			'SELECT * FROM "%s" WHERE "%s" = :id LIMIT 1',
			$tableName,
			$idColumnName
		);
		$statement = $this->pdo->prepare($sql);
		$statement->execute(['id' => $id]);

		$rawData = $statement->fetch(PDO::FETCH_ASSOC);
		if (!$rawData) {
			return null;
		}

		return $this->hydrateEntity($className, $rawData);
	}

	public function findBy(string $className, array $criteria = []): array
	{
		$reflect = new ReflectionClass($className);
		if (!$reflect->implementsInterface(EntityInterface::class)) {
			throw new InvalidArgumentException('Target class must implement ' . EntityInterface::class . '.');
		}

		$tableAttribute = $reflect->getAttributes(Table::class)[0] ?? null;
		if ($tableAttribute === null) {
			throw new InvalidArgumentException('Class is not a valid entity table');
		}
		$tableName = $tableAttribute->newInstance()->name;

		$whereClauses = [];
		$params = [];
		$like = '';

		foreach ($criteria as $key => $value) {
			$paramName = 'c_' . str_replace('.', '_', $key);
			$operator = '=';

			// Check for explicit comparison operator
			if (
				is_array($value) && count($value) === 1
				&& in_array(strtoupper((string)array_key_first($value)), ['LIKE', 'NOT LIKE', '!=', '<', '>', '<=', '>='], true)
			) {
				$operator = strtoupper((string)array_key_first($value));
				$value = current($value);
			}

			// Handle JSONB path queries
			if (str_contains($key, '.')) {
				$parts = explode('.', $key);
				$columnName = array_shift($parts);
				$jsonPath = '"' . $columnName . '"';
				while (count($parts) > 1) {
					$jsonPath .= "->'" . array_shift($parts) . "'";
				}
				$jsonPath .= "->>'" . array_shift($parts) . "'";
				$whereClauses[] = sprintf(
					'%s %s :%s',
					$jsonPath,
					$operator,
					$paramName
				);
			} else {
				$whereClauses[] = sprintf(
					'"%s" %s :%s',
					$key,
					$operator,
					$paramName
				);
			}

			if (is_bool($value)) {
				$value = $value ? 'true' : 'false';
			}

			$params[$paramName] = $value;
		}

		$sql = sprintf('SELECT * FROM "%s"', $tableName);
		if (!empty($whereClauses)) {
			$sql .= ' WHERE ' . implode(' AND ', $whereClauses);
		}

		$statement = $this->pdo->prepare($sql);
		$statement->execute($params);

		$entities = [];

		while ($rawData = $statement->fetch(PDO::FETCH_ASSOC)) {
			if (!$rawData) {
				continue;
			}
			$entities[] = $this->hydrateEntity($className, $rawData);
		}

		return $entities;
	}

	protected function hydrateEntity(string $className, array $rawData): EntityInterface
	{
		$reflect = new ReflectionClass($className);

		$entity = $reflect->newInstanceWithoutConstructor();
		$properties = $reflect->getProperties();

		foreach ($properties as $property) {
			$columnAttribute = $property->getAttributes(Column::class)[0] ?? null;
			if ($columnAttribute === null) {
				continue;
			}

			/** @var Column $columnInstance */
			$columnInstance = $columnAttribute->newInstance();
			$columnName = $columnInstance->name;
			$columnType = $columnInstance->type;

			if (!array_key_exists($columnName, $rawData)) {
				continue;
			}

			$rawDbValue = $rawData[$columnName];
			$value = match ($columnType) {
				'int' => $rawDbValue !== null ? (int)$rawDbValue : null,
				'json' => $rawDbValue !== null ? json_decode((string)$rawDbValue, true, 512, JSON_THROW_ON_ERROR) : [],
				'datetime' => $rawDbValue !== null ? new DateTimeImmutable((string)$rawDbValue) : null,
				default => (string)$rawDbValue
			};

			$property->setValue($entity, $value);
		}

		return $entity;
	}
}
