<?php
require_once __DIR__ . '/conexao.php';

requireLogin();

$usuario = getCurrentUser();
$tentativas = getLoginAttempts();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Início | Programação Web em PHP</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <?php include __DIR__ . '/menu.php'; ?>

    <main class="page-wrapper">
        <section class="panel">
            <span class="eyebrow">Sessão ativa</span>
            <h1>Bem-vindo, <?php echo escape($usuario['username']); ?>.</h1>
            <p>
                O acesso foi realizado por formulário POST, os dados do usuário estão na sessão
                e o menu foi reaproveitado com include, exatamente no escopo pedido pelo README.
            </p>
        </section>

        <section class="cards-grid">
            <article class="info-card">
                <h2>Resumo do acesso</h2>
                <p><strong>Usuário autenticado:</strong> <?php echo escape($usuario['username']); ?></p>
                <p><strong>Tentativas até o login atual:</strong> <?php echo (int) $tentativas; ?></p>
            </article>

            <article class="info-card">
                <h2>Recursos implementados</h2>
                <p>POST no login, GET para mensagens, SESSION para autenticação, PostgreSQL e include compartilhado.</p>
            </article>
        </section>

        <section class="panel compact-panel">
            <h2>Próximo passo</h2>
            <p>Abra a página de produtos no menu para verificar a GRID carregada do banco.</p>
        </section>
    </main>
</body>
</html>
