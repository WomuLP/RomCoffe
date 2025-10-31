<?php

// Utility helpers shared by register.php and login.php

function env(string $key, $default = null) {
	static $cache = null;
	if ($cache === null) {
		$cache = [];
		$root = dirname(__DIR__);
		$envPath = $root . DIRECTORY_SEPARATOR . '.env';
		if (is_readable($envPath)) {
			$lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
			foreach ($lines as $line) {
				$line = trim($line);
				if ($line === '' || strpos($line, '#') === 0) { continue; }
				$pos = strpos($line, '=');
				if ($pos === false) { continue; }
				$k = trim(substr($line, 0, $pos));
				$v = trim(substr($line, $pos + 1));
				// Remove surrounding quotes if present
				if ((substr($v, 0, 1) === '"' && substr($v, -1) === '"') || (substr($v, 0, 1) === "'" && substr($v, -1) === "'")) {
					$v = substr($v, 1, -1);
				}
				$cache[$k] = $v;
			}
		}
		// Merge real environment variables (do not override .env explicitly set)
		foreach ($_ENV as $k => $v) { if (!array_key_exists($k, $cache)) { $cache[$k] = $v; } }
		foreach ($_SERVER as $k => $v) { if (!array_key_exists($k, $cache)) { $cache[$k] = $v; } }
	}
	return array_key_exists($key, $cache) ? $cache[$key] : $default;
}

function is_dev_mode(): bool {
	$raw = env('DEV_MODE', 'false');
	return in_array(strtolower((string)$raw), ['1','true','on','yes'], true);
}

function ensure_log_dir(): string {
	$root = dirname(__DIR__);
	$logDir = $root . DIRECTORY_SEPARATOR . 'logs';
	if (!is_dir($logDir)) {
		@mkdir($logDir, 0775, true);
	}
	return $logDir;
}

function log_error(string $message, array $context = []): void {
	$dir = ensure_log_dir();
	$file = $dir . DIRECTORY_SEPARATOR . 'error.log';
	$timestamp = date('Y-m-d H:i:s');
	$contextJson = '';
	if (!empty($context)) {
		// Avoid leaking secrets
		if (isset($context['password'])) { $context['password'] = '[REDACTED]'; }
		if (isset($context['dev_token'])) { $context['dev_token'] = '[REDACTED]'; }
		$contextJson = ' ' . json_encode($context, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
	}
	@file_put_contents($file, "[$timestamp] $message$contextJson\n", FILE_APPEND);
}

function getPDO(): PDO {
	$host = env('DB_HOST', '127.0.0.1');
	$port = env('DB_PORT', '3306');
	$db   = env('DB_NAME', 'u157683007_romcoffe');
	$user = env('DB_USER', 'root');
	$pass = env('DB_PASS', '');
	$charset = 'utf8mb4';
	$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
	$options = [
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		PDO::ATTR_EMULATE_PREPARES => false,
	];
	return new PDO($dsn, $user, $pass, $options);
}

function json_response(array $payload, int $status = 200): void {
	header('Content-Type: application/json; charset=utf-8');
	http_response_code($status);
	echo json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
	exit;
}

function read_json_body(): array {
	$raw = file_get_contents('php://input');
	if (!$raw) { return []; }
	$decoded = json_decode($raw, true);
	return is_array($decoded) ? $decoded : [];
}

?>
