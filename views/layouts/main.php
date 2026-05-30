<?php

/**
 * @var string $title
 * @var string $content
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
		<h2><?= htmlspecialchars($title ?? '') ?></h2>
	</header>

	<main>
		<?= $content ?>
	</main>

	<footer>
		<p>&copy; <?= date('Y') ?> Made with <span style="color:red;">&hearts;</span> from tfmerk</p>
	</footer>

</body>

</html>
