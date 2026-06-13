<?php

declare(strict_types=1);

namespace tfmerk\PolarisPim\Entities;

use tfmerk\PolarisPim\Framework\ORM\Attributes\Table;
use tfmerk\PolarisPim\Framework\ORM\Attributes\Column;
use tfmerk\PolarisPim\Framework\ORM\Attributes\Id;
use tfmerk\PolarisPim\Framework\ORM\Entity\EntityInterface;
use DateTimeImmutable;
use InvalidArgumentException;

#[Table(name: 'products')]
class Product implements EntityInterface
{
	#[Id]
	#[Column(name: 'id', type: 'int')]
	public private(set) ?int $id = null;

	#[Column(name: 'product_name')]
	public private(set) string $productName {
		set(string $value) {
			if (trim($value) === '') {
				throw new InvalidArgumentException('Invalid productName provided!');
			}
			$this->productName = $value;
		}
	}

	// Stored as cents value
	#[Column(name: 'price')]
	public private(set) int $price {
		set(int $value) {
			if ($value <= 0) {
				throw new InvalidArgumentException('Invalid price provided!');
			}
			$this->price = $value;
		}
	}

	#[Column(name: 'image_url')]
	public private(set) ?string $imageUrl = null;

	#[Column(name: 'marketing_text')]
	public private(set) ?string $marketingText = null;

	#[Column(name: 'metadata', type: 'json')]
	public private(set) array $metadata;

	#[Column(name: 'created_at', type: 'datetime')]
	public private(set) DateTimeImmutable $createdAt;

	#[Column(name: 'changed_at', type: 'datetime')]
	public private(set) DateTimeImmutable $changedAt;

	public function __construct(
		string $productName,
		int $price,
		?string $imageUrl = null,
		?string $marketingText = null,
		/** @var array<string> */
		array $metadata = [],
		DateTimeImmutable $createdAt = new DateTimeImmutable(),
		DateTimeImmutable $changedAt = new DateTimeImmutable(),
	) {
		$this->productName = $productName;
		$this->price = $price;
		$this->imageUrl = $imageUrl;
		$this->marketingText = $marketingText;
		$this->metadata = $metadata;
		$this->createdAt = $createdAt;
		$this->changedAt = $changedAt;
	}

	public function getFormattedPrice(): string
	{
		return number_format($this->price / 100, 2, '.', '');
	}
}
