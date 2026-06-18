<?php

/**
 * @var string $content
 * @var string $uri
 */

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>&star; Polaris</title>
	<link rel="stylesheet" href="/css/app.css">
</head>

<body>

	<header>
		<h2 style="width: 200px;">&star; Polaris</h2>
		<nav>
			<a href="/" class="<?= ($uri ?? '/') === '/' ? 'active' : '' ?>">Home</a>
			<a href="/product/list" class="<?= ($uri ?? '') === '/product/list' ? 'active' : '' ?>">Products</a>
		</nav>
	</header>

	<main>

		<?= $content ?>
	</main>

	<footer>
		<p>&copy; <?= date('Y') ?> Made with <span style="color:red;">&hearts;</span> from tfmerk</p>
	</footer>

</body>

</html>
