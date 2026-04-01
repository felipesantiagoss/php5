<?php
require_once __DIR__ . '/conexao.php';

if (isAuthenticated()) {
    redirectTo('index.php');
}

$mensagem = isset($_GET['mensagem']) ? trim($_GET['mensagem']) : '';
$erro = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if ($username === '' || $password === '') {
        $erro = 'Informe usuario e senha para continuar.';
    } else {
        incrementLoginAttempts();
        $usuario = authenticateUser($username, $password);

        if ($usuario) {
            $_SESSION['usuario'] = array(
                'idusuario' => $usuario['idusuario'],
                'username' => $usuario['username'],
            );

            redirectTo('index.php');
        }

        $erro = 'Usuario ou senha invalidos.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Programação Web em PHP</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body class="centered-body">
    <section class="panel login-panel">
        <span class="eyebrow">Acesso</span>
        <h1>Login do sistema</h1>
        <p>Use o usuario cadastrado na tabela <strong>usuario</strong> do PostgreSQL.</p>

        <?php if ($mensagem !== '') { ?>
            <div class="alert alert-info"><?php echo escape($mensagem); ?></div>
        <?php } ?>

        <?php if ($erro !== '') { ?>
            <div class="alert alert-error"><?php echo escape($erro); ?></div>
        <?php } ?>

        <form action="login.php" method="post" class="form-grid">
            <label for="username">Usuario</label>
            <input
                type="text"
                id="username"
                name="username"
                value="<?php echo escape($username); ?>"
                placeholder="Digite o usuario"
                required
            >

            <label for="password">Senha</label>
            <input
                type="password"
                id="password"
                name="password"
                placeholder="Digite a senha"
                required
            >

            <button type="submit">Entrar</button>
        </form>

        <p class="small-text">Credencial inicial do exercicio: <strong>admin</strong> / <strong>123456</strong>.</p>
    </section>
</body>
</html>
