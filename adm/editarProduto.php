<?php
require_once "../vendor/php/conexao.php";
session_start();

if (!isset($_SESSION['usuario_logado'])) {
    header('Location: ../index.php');
    exit;
}

if (!isset($_GET['id'])) {
    echo "Produto não especificado.";
    exit;
}

$id = intval($_GET['id']);

$stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = ?");
$stmt->execute([$id]);
$produto = $stmt->fetch();

if (!$produto) {
    echo "Produto não encontrado.";
    exit;
}

// Função segura para htmlspecialchars sem erro de NULL
function safe($value)
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Editar Produto | Kiluxo</title>
   <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/adm.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><img src="../images/logo.png" class="navbar-logo"></a>
            <div class="d-flex align-items-center">
                <?php
                $nome = $_SESSION['usuario_logado']['nome'] ?? 'Usuário';
                $partes = explode(' ', trim($nome));
                $nomeExibido = $partes[0] . ' ' . end($partes);
                ?>
                <div class="user-info me-3"><?= safe($nomeExibido) ?></div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar">
        <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link" href="dashboard.php"><i
                        class="bi bi-speedometer2"></i><span>Dashboard</span></a></li>
            <li class="nav-item"><a class="nav-link" href="vendas.php"><i
                        class="bi bi-cart4"></i><span>Vendas</span></a></li>
            <li class="nav-item"><a class="nav-link active" href="produtos.php"><i
                        class="bi bi-box-seam"></i><span>Produtos</span></a></li>
            <li class="nav-item"><a class="nav-link" href="usuarios.php"><i
                        class="bi bi-people"></i><span>Usuários</span></a></li>
            <div class="divider"></div>
            <li class="nav-item"><a class="nav-link" href="../vendor/php/logout.php"><i
                        class="bi bi-box-arrow-right"></i><span>Sair</span></a></li>
        </ul>
    </div>

    <!-- Main -->
    <div class="main-content mt-5">
        <div class="container">
            <h3 class="mb-4">Editar Produto</h3>

            <form action="../vendor/php/atualizarProduto.php" method="post" enctype="multipart/form-data"
                class="row g-3">
                <input type="hidden" name="id" value="<?= $produto['id'] ?>">



                <div class="col-md-6">
                    <label class="form-label">Nome</label>
                    <input type="text" name="nome" class="form-control" value="<?= safe($produto['nome']) ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Categoria</label>
                    <select name="categoria" class="form-select" required>
                        <option value="">Selecione</option>
                        <option <?= $produto['categoria'] === 'Masculino' ? 'selected' : '' ?>>Masculino</option>
                        <option <?= $produto['categoria'] === 'Feminino' ? 'selected' : '' ?>>Feminino</option>
                        <option <?= $produto['categoria'] === 'Acessórios' ? 'selected' : '' ?>>Acessórios</option>
                        <option <?= $produto['categoria'] === 'Infantil' ? 'selected' : '' ?>>Infantil</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Cor</label>
                    <select name="cor" class="form-select" required>
                        <option value="">Selecione</option>
                        <option <?= $produto['cor'] === 'Preto' ? 'selected' : '' ?>>Preto</option>
                        <option <?= $produto['cor'] === 'Branco' ? 'selected' : '' ?>>Branco</option>
                        <option <?= $produto['cor'] === 'Azul' ? 'selected' : '' ?>>Azul</option>
                        <option <?= $produto['cor'] === 'Vermelho' ? 'selected' : '' ?>>Vermelho</option>
                        <option <?= $produto['cor'] === 'Bege' ? 'selected' : '' ?>>Bege</option>
                        <option <?= $produto['cor'] === 'Outro' ? 'selected' : '' ?>>Outro</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Preço (R$)</label>
                    <input type="number" step="0.01" name="preco" class="form-control" value="<?= $produto['preco'] ?>"
                        required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Estoque</label>
                    <input type="number" name="estoque" class="form-control" value="<?= $produto['estoque'] ?>"
                        required>
                </div>

                <div class="col-md-12">
                    <label class="form-label">Descrição</label>
                    <textarea name="descricao" class="form-control" rows="3"
                        required><?= safe($produto['descricao']) ?></textarea>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-success w-100">Salvar Alterações</button>
                </div>
            </form>

        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>