<?php

declare(strict_types=1);

namespace tfmerk\PolarisPim\Framework\View;

use RuntimeException;

class View
{
	public static function make(string $viewName, array $data = [], string $layoutName = 'main'): string
	{
		$viewPath = BASE_PATH . '/src/Views/' . $viewName . '.php';

		if (!file_exists($viewPath)) {
			throw new RuntimeException('File "' . $viewPath . '" not found!');
		}

		extract($data);

		// Render inner page template
		ob_start();
		include $viewPath;
		$content = ob_get_clean();

		if ($layoutName === '') {
			return $content;
		}

		$layoutPath = BASE_PATH . '/src/Views/layouts/' . $layoutName . '.php';

		if (!file_exists($layoutPath)) {
			throw new RuntimeException('Layout file "' . $layoutPath . '" not found!');
		}

		// Re-extract data varaibles so they are available inside the layout file too
		extract($data);

		// Render layout file
		ob_start();
		include $layoutPath;
		return ob_get_clean();
	}
}
