<?php

/**
 * @var array $productData
 */
?>
<div class="card">
	<ul>
		<?php foreach ($productData as $label => $value): ?>
			<li><?= $label ?>: <?= $value ?></li>
		<?php endforeach; ?>
	</ul>
</div>
