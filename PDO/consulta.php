<?php
// Inclui o arquivo de conexão (cria a variável $pdo)
include "conexao.php";

// Monta a consulta SQL para listar todos os alunos (apenas as colunas necessárias)
$sql = "SELECT id, nome, email, curso FROM alunos";

// Prepara a consulta (mesmo sem parâmetros, manter o padrão de prepared statement é boa prática)
$stmt = $pdo->prepare($sql);

// Executa a consulta no banco
$stmt->execute();

// Recupera TODOS os resultados como array associativo (chave = nome da coluna)
$alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <!-- Configuração de caracteres e responsividade -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Alunos</title>

    <!-- Ligação com o mesmo arquivo CSS externo -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Alunos Cadastrados</h1>

        <!-- Link para retornar ao formulário de cadastro -->
        <a href="index.php" class="link-voltar">&larr; Voltar para o Cadastro</a>

        <ul>
            <!-- Percorre o array de alunos, criando um item de lista para cada um -->
            <?php foreach ($alunos as $aluno): ?>
                <li>
                    <!--
                        htmlspecialchars converte caracteres especiais (<, >, ", &)
                        em entidades HTML, prevenindo ataques de XSS (Cross-Site Scripting).
                        ENT_QUOTES = escapa aspas simples e duplas
                        UTF-8      = codificação correta
                    -->
                    <strong>Nome:</strong>   <?= htmlspecialchars($aluno['nome'],  ENT_QUOTES, 'UTF-8') ?> <br>
                    <strong>Email:</strong>  <?= htmlspecialchars($aluno['email'], ENT_QUOTES, 'UTF-8') ?> <br>
                    <strong>Curso:</strong>  <?= htmlspecialchars($aluno['curso'], ENT_QUOTES, 'UTF-8') ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>