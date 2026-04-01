<?php
$usuarioLogado = getCurrentUser();
?>
<header class="topbar">
    <div class="brand">
        <strong>Programação Web em PHP</strong>
        <?php if ($usuarioLogado) { ?>
            <span class="brand-user">Usuario: <?php echo escape($usuarioLogado['username']); ?></span>
        <?php } ?>
    </div>

    <nav class="nav-links">
        <a href="index.php">Home</a>
        <a href="produtos.php">Produtos</a>
        <a href="logout.php">Sair</a>
    </nav>
</header>
