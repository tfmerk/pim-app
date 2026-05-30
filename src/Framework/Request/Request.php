<?php

declare(strict_types=1);

namespace tfmerk\PolarisPim\Framework\Request;

class Request
{
	public readonly string $uri;
	public readonly string $method;
	private readonly array $queryParams;

	public function __construct()
	{
		$this->uri = $this->parseUri();
		$this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
		$this->queryParams = $this->parseQueryParams();
	}

	protected function parseUri(): string
	{
		$uri = $_SERVER['REQUEST_URI'] ?? '/';
		$uri = strtok($uri, '?'); // remove query part, ?bla=blubb
		$uri = rawurldecode($uri);

		if ($uri !== '/' && str_ends_with($uri, '/')) {
			$uri = rtrim($uri, '/');
		}

		return $uri;
	}

	protected function parseQueryParams(): array
	{
		$params = filter_input_array(INPUT_GET, FILTER_UNSAFE_RAW) ?? [];
		$params = array_map(
			static fn(string $param) => trim($param),
			$params
		);

		$params = array_filter(
			$params,
			static fn(string $param) => trim($param) !== ''
		);

		return $params;
	}

	public function query(string $key, string $default = ''): string
	{
		return $this->queryParams[$key] ?? $default;
	}
}
