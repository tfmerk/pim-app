<?php

declare(strict_types=1);

namespace tfmerk\PolarisPim\Entities;

use tfmerk\PolarisPim\Framework\ORM\Attributes\Table;
use tfmerk\PolarisPim\Framework\ORM\Attributes\Column;
use tfmerk\PolarisPim\Framework\ORM\Attributes\Id;
use tfmerk\PolarisPim\Framework\ORM\Entity\EntityInterface;
use DateTimeImmutable;
use InvalidArgumentException;

#[Table(name: 'users')]
class User implements EntityInterface
{
	#[Id]
	#[Column(name: 'id', type: 'int')]
	public private(set) ?int $id = null;

	#[Column(name: 'username')]
	public private(set) string $username {
		set(string $value) {
			if (trim($value) === '') {
				throw new InvalidArgumentException('Invalid username provided!');
			}
			$this->username = $value;
		}
	}


	#[Column(name: 'email')]
	public private(set) string $email {
		set(string $value) {
			if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
				throw new InvalidArgumentException('Invalid email provided!');
			}
			$this->email = $value;
		}
	}

	#[Column(name: 'metadata', type: 'json')]
	public private(set) array $metadata;

	#[Column(name: 'created_at', type: 'datetime')]
	public private(set) DateTimeImmutable $createdAt;

	#[Column(name: 'last_seen', type: 'datetime')]
	public private(set) DateTimeImmutable $lastSeen;


	public function __construct(
		string $username,
		string $email,
		/** @var array<string> */
		array $metadata = [],
		DateTimeImmutable $createdAt = new DateTimeImmutable(),
		DateTimeImmutable $lastSeen = new DateTimeImmutable(),
	) {
		$this->username = $username;
		$this->email = $email;
		$this->metadata = $metadata;
		$this->createdAt = $createdAt;
		$this->lastSeen = $lastSeen;
	}
}
