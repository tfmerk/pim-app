<?php

use tfmerk\PolarisPim\Entities\Product;

/**
 * @var array<Product> $products
 */
?>
<div class="card">
	<h1>Products</h1>
	<table>
		<thead>
			<tr>
				<th>ID</th>
				<th>Image</th>
				<th>Product Name</th>
				<th>Price</th>
				<th>Marketing Text</th>
				<th>Created At</th>
				<th>Changed At</th>
				<th>Metadata</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($products as $product): ?>
				<tr>
					<td><?= htmlspecialchars((string) $product->id) ?></td>
					<td>
						<?php if ($product->imageUrl): ?>
							<img src="<?= htmlspecialchars($product->imageUrl) ?>" alt="<?= htmlspecialchars($product->productName) ?>" style="max-height: 40px; border-radius: 4px;">
						<?php else: ?>
							<span style="color: #64748b; font-style: italic;">No Image</span>
						<?php endif; ?>
					</td>
					<td>
						<a href="/product?id=<?= urlencode((string) $product->id) ?>" style="color: #38bdf8; text-decoration: none; font-weight: 600;">
							<?= htmlspecialchars($product->productName) ?>
						</a>
					</td>
					<td>$<?= htmlspecialchars($product->getFormattedPrice()) ?></td>
					<td><?= htmlspecialchars($product->marketingText ?? '') ?></td>
					<td><?= htmlspecialchars($product->createdAt->format('Y-m-d H:i:s')) ?></td>
					<td><?= htmlspecialchars($product->changedAt->format('Y-m-d H:i:s')) ?></td>
					<td>
						<?php foreach ($product->metadata as $key => $value): ?>
							<?php
							if (is_bool($value)) {
								$displayValue = $value ? 'true' : 'false';
							} else {
								$displayValue = (string) $value;
							}
							?>
							<div>
								<strong><?= htmlspecialchars($key) ?>:</strong>
								<?= htmlspecialchars($displayValue) ?>
							</div>
						<?php endforeach; ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
