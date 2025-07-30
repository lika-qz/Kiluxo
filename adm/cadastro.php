<?php
session_start();
require '../vendor/php/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if ($nome && $email && $senha) {
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senhaHash);

        try {
            $stmt->execute();
            $_SESSION['usuario_logado'] = [
                'id' => $pdo->lastInsertId(),
                'nome' => $nome,
                'email' => $email
            ];
            header('Location: index.php');
            exit;
        } catch (PDOException $e) {
            $_SESSION['erro_login'] = 'Erro ao cadastrar: ' . $e->getMessage();
        }
    } else {
        $_SESSION['erro_login'] = 'Todos os campos são obrigatórios.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <title>Kiluxo | Cadastro</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="icon" type="image/png" href="../images/icons/favicon.png" />
    <link rel="stylesheet" type="text/css" href="../vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../fonts/iconic/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" type="text/css" href="../css/util.css">
    <link rel="stylesheet" type="text/css" href="../css/main.css">
</head>

<body class="animsition">

    <section class="bg0 p-t-75 p-b-85">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5 bor10 p-lr-40 p-t-30 p-b-40">
                    <h4 class="mtext-109 cl2 p-b-30 text-center">Criar Conta</h4>

                    <?php if (!empty($_SESSION['erro_login'])): ?>
                        <div class="alert alert-danger">
                            <?= $_SESSION['erro_login'];
                            unset($_SESSION['erro_login']); ?>
                        </div>
                    <?php endif; ?>

                    <form action="cadastro.php" method="post">
                        <div class="wrap-input1 w-full p-b-20 mt-3">
                            <input class="input1 bg-none plh1 stext-107 cl7" type="text" name="nome"
                                placeholder="Nome completo" required>
                            <div class="focus-input1 trans-04"></div>
                        </div>

                        <div class="wrap-input1 w-full p-b-20 mt-3">
                            <input class="input1 bg-none plh1 stext-107 cl7" type="email" name="email"
                                placeholder="Email" required>
                            <div class="focus-input1 trans-04"></div>
                        </div>

                        <div class="wrap-input1 w-full p-b-20 mt-3">
                            <input class="input1 bg-none plh1 stext-107 cl7" type="password" name="senha"
                                placeholder="Senha" required>
                            <div class="focus-input1 trans-04"></div>
                        </div>

                        <button type="submit"
                            class="flex-c-m stext-101 cl0 size-116 bg3 bor14 hov-btn3 p-lr-15 trans-04 pointer w-100  mt-5">
                            Cadastrar
                        </button>
                    </form>
                    <div class="text-center p-t-30">
                        <span class="stext-107 cl6">Já possui uma conta?</span>
                        <a href="index.php" class="stext-107 cl8 hov-cl1">Login</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Scripts -->
    <script src="..//vendor/jquery/jquery-3.2.1.min.js"></script>
    <script src="..//vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="..//js/main.js"></script>
</body>

</html>