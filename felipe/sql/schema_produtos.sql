-- Execute este script depois de criar e selecionar o banco "produtos".

CREATE TABLE IF NOT EXISTS public.usuario
(
    idusuario SERIAL PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(32) NOT NULL,
    status BOOLEAN DEFAULT TRUE
);

INSERT INTO public.usuario (username, password, status)
SELECT 'admin', '123456', TRUE
WHERE NOT EXISTS (
    SELECT 1
    FROM public.usuario
    WHERE username = 'admin'
);

CREATE TABLE IF NOT EXISTS public.produto
(
    idproduto SERIAL PRIMARY KEY,
    produtonome VARCHAR(100) NOT NULL,
    produtopreco REAL NOT NULL DEFAULT 0,
    produtofoto VARCHAR(150),
    produtostatus BOOLEAN DEFAULT FALSE
);

INSERT INTO public.produto (produtonome, produtopreco, produtofoto, produtostatus)
SELECT dados.produtonome, dados.produtopreco, dados.produtofoto, dados.produtostatus
FROM (
    VALUES
        ('Teclado USB', 89.90, NULL, TRUE),
        ('Mouse Optico', 39.90, NULL, TRUE),
        ('Monitor 24"', 899.90, NULL, FALSE)
) AS dados(produtonome, produtopreco, produtofoto, produtostatus)
WHERE NOT EXISTS (
    SELECT 1
    FROM public.produto
);

-- Se precisar recriar apenas a tabela de produtos, execute antes:
-- DROP TABLE IF EXISTS public.produto;
