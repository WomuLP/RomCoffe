<?php
require_once __DIR__ . '/helpers.php';

session_start();

try {
	$body = read_json_body();
	$data = array_merge($_POST ?? [], $body);

	$identifier = isset($data['identifier']) ? trim((string)$data['identifier']) : '';
	$username = isset($data['username']) ? trim((string)$data['username']) : '';
	$email = isset($data['email']) ? trim((string)$data['email']) : '';
	$password = isset($data['password']) ? (string)$data['password'] : '';
	$devToken = isset($data['dev_token']) ? (string)$data['dev_token'] : null;

	$pdo = getPDO();

	// Dev bypass: only if enabled and token matches; no password required
	if (is_dev_mode() && $devToken) {
		$expected = env('DEV_BYPASS_TOKEN', '');
		if ($expected !== '' && hash_equals($expected, $devToken)) {
			$needle = $identifier !== '' ? $identifier : ($email !== '' ? $email : $username);
			if ($needle === '') {
				json_response(['status' => 'error', 'error' => 'Provide username or email for dev bypass'], 400);
			}
			$find = $pdo->prepare('SELECT id, username, email, role FROM usuarios WHERE username = :n OR email = :n LIMIT 1');
			$find->execute([':n' => $needle]);
			$user = $find->fetch();
			if (!$user) {
				json_response(['status' => 'error', 'error' => 'User not found for dev bypass'], 404);
			}
			$pdo->prepare('UPDATE usuarios SET last_login = NOW(), updated_at = NOW() WHERE id = :id')->execute([':id' => $user['id']]);
			$_SESSION['user_id'] = $user['id'];
			$_SESSION['username'] = $user['username'];
			$_SESSION['role'] = $user['role'];
			log_error('DEV BYPASS login used', ['username' => $user['username']]);
			json_response(['status' => 'ok', 'message' => 'Dev bypass login', 'role' => $user['role']]);
		}
	}

	// Normal auth path
	$needle = $identifier !== '' ? $identifier : ($email !== '' ? $email : $username);
	if ($needle === '' || $password === '') {
		json_response([
			'status' => 'error',
			'error' => 'Missing required fields',
			'hint' => 'Provide username/email and password'
		], 400);
	}

	$stmt = $pdo->prepare('SELECT id, username, email, role, password FROM usuarios WHERE username = :n OR email = :n LIMIT 1');
	$stmt->execute([':n' => $needle]);
	$user = $stmt->fetch();

	if (!$user || empty($user['password'])) {
		json_response(['status' => 'error', 'error' => 'Invalid credentials'], 401);
	}

	if (!password_verify($password, $user['password'])) {
		json_response(['status' => 'error', 'error' => 'Invalid credentials'], 401);
	}

	$pdo->prepare('UPDATE usuarios SET last_login = NOW(), updated_at = NOW() WHERE id = :id')->execute([':id' => $user['id']]);
	$_SESSION['user_id'] = $user['id'];
	$_SESSION['username'] = $user['username'];
	$_SESSION['role'] = $user['role'];

	json_response(['status' => 'ok', 'message' => 'Login successful', 'role' => $user['role']]);
} catch (PDOException $e) {
	log_error('PDOException in login.php', ['code' => $e->getCode(), 'msg' => $e->getMessage()]);
	json_response(['status' => 'error', 'error' => 'Database error'], 500);
} catch (Throwable $e) {
	log_error('Throwable in login.php', ['msg' => $e->getMessage()]);
	json_response(['status' => 'error', 'error' => 'Server error'], 500);
}
?>
