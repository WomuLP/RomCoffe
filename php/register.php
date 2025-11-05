<?php
require_once __DIR__ . '/helpers.php';

try {
	// Accept application/json or form-encoded
	$body = read_json_body();
	$data = array_merge($_POST ?? [], $body);

	$username = isset($data['username']) ? trim((string)$data['username']) : '';
	$email = isset($data['email']) ? trim((string)$data['email']) : '';
	$password = isset($data['password']) ? (string)$data['password'] : '';

	if ($username === '' || $password === '' || $email === '') {
		json_response([
			'status' => 'error',
			'error' => 'Missing required fields',
			'hint' => 'Provide username, email, password'
		], 400);
	}
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		json_response([
			'status' => 'error',
			'error' => 'Invalid email format'
		], 400);
	}

	$pdo = getPDO();

	// Enforce uniqueness at app level; DB should also have UNIQUE (see SQL section)
	$existsStmt = $pdo->prepare('SELECT id FROM usuarios WHERE username = :u OR email = :e LIMIT 1');
	$existsStmt->execute([':u' => $username, ':e' => $email]);
	if ($existsStmt->fetch()) {
		json_response([
			'status' => 'error',
			'error' => 'Username or email already exists'
		], 409);
	}

	$hash = password_hash($password, PASSWORD_DEFAULT);
	$now = date('Y-m-d H:i:s');

	$insert = $pdo->prepare('INSERT INTO usuarios (username, email, password, role, created_at, updated_at)
		VALUES (:u, :e, :p, :r, :c, :u2)');
	$insert->execute([
		':u' => $username,
		':e' => $email,
		':p' => $hash,
		':r' => 'user',
		':c' => $now,
		':u2' => $now,
	]);

	json_response([
		'status' => 'ok',
		'message' => 'User registered',
		'user' => [
			'username' => $username,
			'email' => $email,
			'role' => 'user'
		]
	], 201);
} catch (PDOException $e) {
	log_error('PDOException in register.php', ['code' => $e->getCode(), 'msg' => $e->getMessage()]);
	json_response(['status' => 'error', 'error' => 'Database error'], 500);
} catch (Throwable $e) {
	log_error('Throwable in register.php', ['msg' => $e->getMessage()]);
	json_response(['status' => 'error', 'error' => 'Server error'], 500);
}
?>
