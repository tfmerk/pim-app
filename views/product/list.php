<?php

/**
 * @var array $products
 */
?>
<ul>
	<?php foreach ($products as $product): ?>
		<li>
			<a href="<?= $product['url'] ?>"><?= $product['name'] ?></a> (<?= $product['id'] ?>)
		</li>
	<?php endforeach; ?>
</ul>
