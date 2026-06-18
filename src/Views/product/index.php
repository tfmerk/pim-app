<?php

use tfmerk\PolarisPim\Entities\Product;

/**
 * @var int $productID
 * @var Product|null $product
 */
?>
<div class="card">
	<?php if ($product === null): ?>
		<h1>Product Not Found</h1>
		<p>The requested product could not be found in our database.</p>
	<?php else: ?>
		<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
			<h1><?= htmlspecialchars($product->productName) ?></h1>
		</div>

		<?php if ($product->marketingText): ?>
			<p style="font-style: italic; color: #94a3b8; margin-bottom: 24px; font-size: 16px;">
				"<?= htmlspecialchars($product->marketingText) ?>"
			</p>
		<?php endif; ?>

		<table>
			<thead>
				<tr>
					<th colspan="2">Product Specifications</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><strong>Product ID</strong></td>
					<td><?= htmlspecialchars((string) $product->id) ?></td>
				</tr>
				<tr>
					<td><strong>Product Image</strong></td>
					<td>
						<?php if ($product->imageUrl): ?>
							<img src="<?= htmlspecialchars($product->imageUrl) ?>" alt="<?= htmlspecialchars($product->productName) ?>" style="max-height: 150px; border-radius: 6px; border: 1px solid #334155;">
						<?php else: ?>
							<span style="color: #64748b; font-style: italic;">No image uploaded</span>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<td><strong>Price</strong></td>
					<td style="font-size: 16px; font-weight: 600; color: #10b981;">
						$<?= htmlspecialchars($product->getFormattedPrice()) ?>
					</td>
				</tr>
				<tr>
					<td><strong>Created System Timestamp</strong></td>
					<td><?= htmlspecialchars($product->createdAt->format('Y-m-d H:i:s')) ?></td>
				</tr>
				<tr>
					<td><strong>Last Modification</strong></td>
					<td><?= htmlspecialchars($product->changedAt->format('Y-m-d H:i:s')) ?></td>
				</tr>
				<tr>
					<td><strong>Extended Metadata</strong></td>
					<td>
						<?php if (!empty($product->metadata)): ?>
							<?php foreach ($product->metadata as $key => $value): ?>
								<?php
								if (is_bool($value)) {
									$displayValue = $value ? 'Yes' : 'No';
								} else {
									$displayValue = is_array($value) ? json_encode($value) : (string) $value;
								}
								?>
								<div style="margin-bottom: 4px;">
									<span style="color: #94a3b8;"><?= htmlspecialchars((string) $key) ?>:</span>
									<span><?= htmlspecialchars($displayValue) ?></span>
								</div>
							<?php endforeach; ?>
						<?php else: ?>
							<span style="color: #64748b; font-style: italic;">No extended metadata available</span>
						<?php endif; ?>
					</td>
				</tr>
			</tbody>
		</table>
	<?php endif; ?>
</div>
