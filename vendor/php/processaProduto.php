<?php
require_once "conexao.php";

$erro = '';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST['nome']);
    $categoria = trim($_POST['categoria']);
    $preco = floatval($_POST['preco']);
    $estoque = intval($_POST['estoque']);
    $status = $estoque > 0 ? 'disponivel' : 'indisponivel';

    $imagem = null;

    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $extensao = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
        $nomeImagem = uniqid('prod_') . '.' . $extensao;
        $caminho = '../uploads/' . $nomeImagem;

        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho)) {
            $imagem = $nomeImagem;
        } else {
            $erro = "Erro ao salvar a imagem.";
        }
    }

    if ($nome && $categoria && $preco > 0 && !$erro) {
        $stmt = $pdo->prepare("INSERT INTO produtos (nome, imagem, categoria, preco, estoque, status) 
                       VALUES (:nome, :imagem, :categoria, :preco, :estoque, :status)");
        $stmt->execute([
            ':nome' => $nome,
            ':imagem' => $imagem,
            ':categoria' => $categoria,
            ':preco' => $preco,
            ':estoque' => $estoque,
            ':status' => $status
        ]);


        header("Location: ../../adm/produtos.php?sucesso=1");
        exit;
    } else {
        if (!$erro)
            $erro = "Preencha todos os campos corretamente.";
    }
}
?>