<?php

use tfmerk\PolarisPim\Entities\Product;

/**
 * @var array<Product> $products
 */
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Product Datasheet Catalog</title>
	<style>
		/* Screen Layout Styles */
		body {
			font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
			background: #f3f4f6;
			margin: 0;
			padding: 20px;
			color: #1f2937;
		}

		.no-print-bar {
			background: #1e293b;
			padding: 16px;
			margin-bottom: 20px;
			border-radius: 6px;
			display: flex;
			justify-content: space-between;
			align-items: center;
		}

		.btn-print {
			background: #38bdf8;
			color: #0f172a;
			border: none;
			padding: 8px 16px;
			font-weight: 600;
			border-radius: 4px;
			cursor: pointer;
		}

		.container {
			max-width: 900px;
			margin: 0 auto;
		}

		.catalog-header {
			background: #1e293b;
			color: white;
			padding: 24px;
			border-radius: 8px;
			margin-bottom: 24px;
		}

		.product-card {
			background: white;
			border: 1px solid #e5e7eb;
			border-radius: 8px;
			padding: 20px;
			margin-bottom: 20px;
			box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
			/* Prevents a product card from splitting awkwardly in half across pages */
			page-break-inside: avoid;
		}

		.product-header-row {
			display: flex;
			justify-content: space-between;
			align-items: baseline;
			border-bottom: 2px solid #f1f5f9;
			padding-bottom: 8px;
			margin-bottom: 16px;
		}

		.product-content-layout {
			display: flex;
			gap: 24px;
			align-items: flex-start;
		}

		.product-image-aside {
			flex-shrink: 0;
			width: 150px;
		}

		.product-image-aside img {
			width: 100%;
			height: auto;
			border-radius: 6px;
			border: 1px solid #e5e7eb;
			display: block;
			background: #f8fafc;
		}

		.product-details-middle {
			flex: 1;
		}

		.product-meta {
			width: 100%;
			border-collapse: collapse;
			margin-top: 12px;
		}

		.product-meta td {
			padding: 6px 12px;
			border-bottom: 1px solid #e5e7eb;
			font-size: 13px;
		}

		.product-meta tr:last-child td {
			border-bottom: none;
		}

		/* Native Cross-Browser Printing Configurations */
		@media print {
			@page {
				size: A4 portrait;
				margin: 20mm 15mm;
			}

			body {
				background: white;
				color: #000000;
				padding: 0;
			}

			.no-print-bar {
				display: none !important;
			}

			.product-card {
				box-shadow: none !important;
				border: 1px solid #94a3b8 !important;
			}

			.catalog-header {
				background: #1e293b !important;
				-webkit-print-color-adjust: exact;
				print-color-adjust: exact;
			}
		}
	</style>
</head>

<body>

	<div class="container">
		<div class="no-print-bar">
			<span style="color: #94a3b8;">Datasheet Print Preview Mode</span>
			<button class="btn-print" onclick="window.print()">Print / Save as PDF</button>
		</div>

		<div class="catalog-header">
			<h1 style="margin: 0; font-size: 28px;">Polaris PIM - Official Specifications</h1>
			<p style="margin: 4px 0 0 0; color: #94a3b8;">Confidential System Export</p>
		</div>

		<?php foreach ($products as $product): ?>
			<div class="product-card">
				<div class="product-header-row">
					<h2 style="margin: 0; font-size: 18px; color: #0f172a;"><?= $product->id ?> - <?= htmlspecialchars($product->productName) ?></h2>
					<span style="font-weight: 700; color: #10b981;">$<?= htmlspecialchars($product->getFormattedPrice()) ?></span>
				</div>

				<div class="product-content-layout">
					<div class="product-image-aside">
						<?php if (!empty($product->imageUrl)): ?>
							<img src="<?= htmlspecialchars($product->imageUrl) ?>" alt="<?= htmlspecialchars($product->productName) ?>">
						<?php else: ?>
							<div style="width: 100%; height: 120px; border-radius: 6px; background: #f1f5f9; border: 1px dashed #cbd5e1; display: flex; align-items: center; justify-content: center; color: #94a3b8; font-size: 13px;">No Image</div>
						<?php endif; ?>
					</div>

					<div class="product-details-middle">
						<p style="font-style: italic; color: #475569; margin: 0 0 12px 0; font-size: 14px;">"<?= htmlspecialchars($product->marketingText ?? '') ?>"</p>

						<?php if (!empty($product->metadata)): ?>
							<table class="product-meta">
								<?php foreach ($product->metadata as $key => $value): ?>
									<tr>
										<td style="font-weight: 600; width: 35%; color: #475569;"><?= htmlspecialchars((string)$key) ?></td>
										<td><?= htmlspecialchars(is_array($value) ? implode(', ', $value) : (string)$value) ?></td>
									</tr>
								<?php endforeach; ?>
							</table>
						<?php endif; ?>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>

</body>

</html>
