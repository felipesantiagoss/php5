<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function appConfig()
{
    static $config = null;

    if ($config === null) {
        $config = require __DIR__ . '/config.php';
    }

    return $config;
}

function redirectTo($path)
{
    header('Location: ' . $path);
    exit();
}

function renderSetupError($title, $message)
{
    http_response_code(500);
    ?>
    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo escape($title); ?></title>
        <link rel="stylesheet" href="estilos.css">
    </head>
    <body class="centered-body">
        <section class="panel login-panel">
            <span class="eyebrow error-eyebrow">Erro</span>
            <h1><?php echo escape($title); ?></h1>
            <p><?php echo escape($message); ?></p>
            <p class="small-text">Confira os arquivos <strong>config.php</strong> e <strong>sql/schema_produtos.sql</strong>.</p>
        </section>
    </body>
    </html>
    <?php
    exit();
}

function ensurePgDriver()
{
    if (!extension_loaded('pdo_pgsql')) {
        renderSetupError(
            'Extensão PostgreSQL indisponível',
            'A extensão pdo_pgsql não está habilitada no PHP do XAMPP. Ative o suporte ao PostgreSQL no php.ini antes de usar o projeto.'
        );
    }
}

function db()
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    ensurePgDriver();

    $config = appConfig();
    $settings = $config['database'];
    $dsn = sprintf(
        'pgsql:host=%s;port=%s;dbname=%s',
        $settings['host'],
        $settings['port'],
        $settings['dbname']
    );

    try {
        $pdo = new PDO(
            $dsn,
            $settings['user'],
            $settings['password'],
            array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            )
        );
    } catch (PDOException $exception) {
        renderSetupError(
            'Falha ao conectar no PostgreSQL',
            'Revise as credenciais do config.php, confirme que o banco produtos foi criado e que o servidor PostgreSQL esta em execucao.'
        );
    }

    return $pdo;
}

function escape($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function getCurrentUser()
{
    return isset($_SESSION['usuario']) ? $_SESSION['usuario'] : null;
}

function isAuthenticated()
{
    return getCurrentUser() !== null;
}

function requireLogin()
{
    if (!isAuthenticated()) {
        redirectTo('login.php?mensagem=' . urlencode('Faca login para acessar o sistema.'));
    }
}

function getLoginAttempts()
{
    return isset($_SESSION['tentativas']) ? (int) $_SESSION['tentativas'] : 0;
}

function incrementLoginAttempts()
{
    $_SESSION['tentativas'] = getLoginAttempts() + 1;

    return $_SESSION['tentativas'];
}

function authenticateUser($username, $password)
{
    $stmt = db()->prepare(
        'SELECT idusuario, username, status
         FROM public.usuario
         WHERE username = :username
           AND password = :password
           AND status = TRUE
         LIMIT 1'
    );

    $stmt->execute(array(
        ':username' => $username,
        ':password' => $password,
    ));

    $user = $stmt->fetch();

    return $user ? $user : null;
}

function findProducts()
{
    $stmt = db()->query(
        'SELECT idproduto, produtonome, produtopreco, produtofoto, produtostatus
         FROM public.produto
         ORDER BY idproduto'
    );

    return $stmt->fetchAll();
}

function formatPrice($price)
{
    return 'R$ ' . number_format((float) $price, 2, ',', '.');
}

function isDatabaseTrue($value)
{
    return $value === true
        || $value === 1
        || $value === '1'
        || $value === 't'
        || $value === 'true'
        || $value === 'TRUE';
}
