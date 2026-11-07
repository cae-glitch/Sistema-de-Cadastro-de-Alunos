<?php

// Bloco try/catch para tentar conectar ao banco e capturar possíveis erros de conexão
try {

    // Cria a instância PDO (PHP Data Objects) responsável pela conexão com o MySQL
    // - host=localhost  -> servidor onde o banco está rodando
    // - dbname=escola_f -> nome do banco de dados
    // - charset=utf8mb4 -> garante suporte a acentos e emojis
    // - "root" e ""     -> usuário e senha do banco (padrão XAMPP)
    $pdo = new PDO(
        "mysql:host=localhost;dbname=escola_f;charset=utf8mb4",
        "root",
        ""
    );

    // Define que erros do PDO serão lançados como EXCEÇÕES (facilita o tratamento com try/catch)
    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

    // Desativa a emulação de prepared statements
    // Isso garante que o MySQL faça o prepare real, aumentando a SEGURANÇA contra SQL Injection
    $pdo->setAttribute(
        PDO::ATTR_EMULATE_PREPARES,
        false
    );

} catch (PDOException $e) {
    // Se a conexão falhar, encerra a execução e exibe a mensagem de erro
    die("Erro na conexão: " . $e->getMessage());
}