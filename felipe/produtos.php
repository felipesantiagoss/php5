<?php
require_once __DIR__ . '/conexao.php';

requireLogin();

$produtos = findProducts();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos | Programação Web em PHP</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <?php include __DIR__ . '/menu.php'; ?>

    <main class="page-wrapper">
        <section class="panel">
            <span class="eyebrow">Banco de dados</span>
            <h1>GRID de produtos</h1>
            <p>Listagem carregada diretamente da tabela <strong>produto</strong> do PostgreSQL.</p>
        </section>

        <section class="panel table-panel">
            <div class="table-scroll">
                <table class="products-table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" aria-label="Selecionar todos" disabled></th>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Preço</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!$produtos) { ?>
                            <tr>
                                <td colspan="5" class="empty-row">Nenhum produto cadastrado no banco de dados.</td>
                            </tr>
                        <?php } ?>

                        <?php foreach ($produtos as $produto) { ?>
                            <?php $produtoAtivo = isDatabaseTrue($produto['produtostatus']); ?>
                            <tr>
                                <td><input type="checkbox" aria-label="Selecionar produto <?php echo (int) $produto['idproduto']; ?>"></td>
                                <td><?php echo (int) $produto['idproduto']; ?></td>
                                <td><?php echo escape($produto['produtonome']); ?></td>
                                <td><?php echo escape(formatPrice($produto['produtopreco'])); ?></td>
                                <td>
                                    <span class="status-badge <?php echo $produtoAtivo ? 'is-active' : 'is-inactive'; ?>">
                                        <?php echo $produtoAtivo ? 'Ativo' : 'Desativado'; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
</html>
