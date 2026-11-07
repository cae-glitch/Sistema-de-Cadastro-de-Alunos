<?php
// Inclui a conexão (require_once evita incluir o mesmo arquivo duas vezes)
require_once "conexao.php";

// Variável que vai guardar a mensagem de feedback (sucesso/erro)
$mensagem = "";

// Verifica se o formulário foi enviado via método POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Captura cada campo do formulário
    // O operador ?? evita erro caso o campo não exista
    // trim() remove espaços extras no início e fim
    $nome             = trim($_POST['nome']            ?? '');
    $email            = trim($_POST['email']           ?? '');
    $telefone         = trim($_POST['telefone']        ?? '');
    $curso            = trim($_POST['curso']           ?? '');
    $data_nascimento  = trim($_POST['data_nascimento'] ?? '');

    // Validação simples: nome, email e curso são obrigatórios
    if (!empty($nome) && !empty($email) && !empty($curso)) {

        try {
            // SQL de inserção usando parâmetros nomeados (:nome, :email...)
            // Isso evita SQL Injection (prepared statement)
            $sql = "INSERT INTO alunos (nome, email, telefone, curso, data_nascimento)
                    VALUES (:nome, :email, :telefone, :curso, :data_nascimento)";

            // Prepara a query para o MySQL
            $stmt = $pdo->prepare($sql);

            // Executa passando um array associativo com os valores
            $stmt->execute([
                'nome'            => $nome,
                'email'           => $email,
                'telefone'        => $telefone,
                'curso'           => $curso,
                'data_nascimento' => $data_nascimento
            ]);

            // Redireciona para a mesma página com ?sucesso=1
            // (padrão PRG - Post/Redirect/Get, evita reenvio ao atualizar)
            header("Location: index.php?sucesso=1");
            exit; // encerra o script após o redirecionamento

        } catch (PDOException $e) {
            // Código 1062 = erro de chave duplicada (email já cadastrado)
            if ($e->errorInfo[1] == 1062) {
                $mensagem = "Erro: Este e-mail já está cadastrado!";
            } else {
                $mensagem = "Erro ao cadastrar: " . $e->getMessage();
            }
        }
    } else {
        // Campos obrigatórios em branco
        $mensagem = "Por favor, preencha todos os campos obrigatórios.";
    }
}

// Se a URL tiver ?sucesso=1, exibe mensagem de sucesso
if (isset($_GET['sucesso']) && $_GET['sucesso'] == 1) {
    $mensagem = "Aluno cadastrado com sucesso!";
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário de Cadastro</title>
    <!-- CSS externo com tema futurista -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Seja Bem-vindo à nossa rede de cursos!!!</h1>

        <!-- Exibe a mensagem somente se ela não estiver vazia -->
        <?php if (!empty($mensagem)): ?>
            <?php
                // Detecta se a mensagem contém a palavra "sucesso"
                // para aplicar a classe CSS correta (verde/azul vs vermelho)
                $isSucesso = str_contains(strtolower($mensagem), 'sucesso');
            ?>
            <div class="<?= $isSucesso ? 'msg-sucesso' : 'msg-erro' ?>">
                <!-- Proteção XSS ao imprimir a mensagem -->
                <?= htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <!-- Formulário envia os dados via POST para a própria página -->
        <form action="index.php" method="POST">

            <!-- Campo nome (obrigatório) -->
            <input type="text" name="nome" placeholder="Digite seu Nome" required>

            <!-- Campo e-mail (validação de formato pelo type="email") -->
            <input type="email" name="email" placeholder="Digite seu E-mail" required>

            <!-- Campo telefone (opcional, sem required) -->
            <input type="text" name="telefone" placeholder="Digite seu WhatsApp">

            <!-- Select de curso (obrigatório) -->
            <select name="curso" required>
                <!-- Opção placeholder desabilitada, só aparece até o usuário escolher -->
                <option value="" disabled selected>Selecione o seu curso</option>
                <option value="Desenvolvimento de Sistemas">Desenvolvimento de Sistemas</option>
                <option value="Análise e Desenvolvimento de Sistemas">Análise e Desenvolvimento de Sistemas</option>
                <option value="Banco de Dados">Banco de Dados</option>
                <option value="Segurança da Informação">Segurança da Informação</option>
                <option value="Suporte Técnico em TI">Suporte Técnico em TI</option>
            </select>

            <!-- Campo data de nascimento (obrigatório, com seletor de data) -->
            <input type="date" name="data_nascimento" required>

            <!-- Botão que dispara o envio do formulário -->
            <button type="submit">Cadastrar</button>
        </form>

        <!-- Link para a página de listagem dos alunos -->
        <p style="text-align: center; margin-top: 15px;">
            <a href="consulta.php">Ver alunos cadastrados</a>
        </p>
    </div>
</body>
</html>