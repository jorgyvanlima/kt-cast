<?php
declare(strict_types=1);

session_start();

define('BASE_PATH', dirname(__DIR__));
define('VIEW_PATH', BASE_PATH . DIRECTORY_SEPARATOR . 'views');

function env_value(string $key, ?string $default = null): ?string
{
    $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);
    return ($value === false || $value === null || $value === '') ? $default : (string) $value;
}

function db(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $host = env_value('DB_HOST', 'db');
    $port = env_value('DB_PORT', '3306');
    $database = env_value('DB_DATABASE', 'kt_cast');
    $username = env_value('DB_USERNAME', 'ktcast');
    $password = env_value('DB_PASSWORD', 'ktcast123');

    $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $host, $port, $database);

    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    return $pdo;
}

function ensure_default_admin(): void
{
    $pdo = db();
    $count = (int) $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();

    if ($count === 0) {
        $stmt = $pdo->prepare('INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, ?)');
        $stmt->execute([
            'admin',
            'admin@ktcast.local',
            password_hash('admin123', PASSWORD_DEFAULT),
            'admin',
        ]);
    }
}

function h(mixed $value): string
{
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
}

function flash(string $message, string $type = 'info'): void
{
    $_SESSION['flashes'][] = ['message' => $message, 'type' => $type];
}

function get_flashes(): array
{
    $flashes = $_SESSION['flashes'] ?? [];
    unset($_SESSION['flashes']);
    return $flashes;
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function verify_csrf(): void
{
    $token = $_POST['csrf_token'] ?? '';
    if (!hash_equals(csrf_token(), $token)) {
        http_response_code(419);
        exit('CSRF token inválido.');
    }
}

function redirect_to(string $path): never
{
    header('Location: ' . $path);
    exit;
}

function request_path(): string
{
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    return $path ?: '/';
}

function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

function is_logged_in(): bool
{
    return current_user() !== null;
}

function require_login(): void
{
    if (!is_logged_in()) {
        flash('Faça login para continuar.', 'warning');
        redirect_to('/login');
    }
}

function login_user(array $user): void
{
    $_SESSION['user'] = [
        'id' => $user['id'],
        'username' => $user['username'],
        'role' => $user['role'],
    ];
}

function logout_user(): void
{
    unset($_SESSION['user']);
}

function render(string $view, array $params = [], string $layout = 'layout'): void
{
    $title = $params['title'] ?? 'KT-CAST';
    $contentView = VIEW_PATH . DIRECTORY_SEPARATOR . $view . '.php';
    $layoutFile = VIEW_PATH . DIRECTORY_SEPARATOR . $layout . '.php';
    $flashes = get_flashes();
    extract($params, EXTR_SKIP);
    require $layoutFile;
}

function active_menu(string $path): bool
{
    $current = request_path();
    return $current === $path || str_starts_with($current, $path . '/');
}

function selected_applications_for_contact(int $contactId): array
{
    $stmt = db()->prepare('SELECT application_id FROM contact_applications WHERE contact_id = ?');
    $stmt->execute([$contactId]);
    return array_map('intval', array_column($stmt->fetchAll(), 'application_id'));
}

function all_applications(): array
{
    return db()->query('SELECT * FROM applications ORDER BY name')->fetchAll();
}
