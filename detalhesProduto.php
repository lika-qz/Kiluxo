<?php
session_start();
require_once 'listaProdutos.php';

// Garantir carrinho na sessão
if (!isset($_SESSION['carrinho'])) {
	$_SESSION['carrinho'] = [];
}

// Calcular total
$total = 0;
foreach ($_SESSION['carrinho'] as $item) {
	$total += ($item['preco'] ?? 0) * ($item['quantidade'] ?? 1);
}

// Pegar o ID do produto via GET
if (isset($_GET['id'])) {
	$id = $_GET['id'];

	// Buscar produto com base no ID
	$produtoSelecionado = null;
	foreach ($produtos as $produto) {
		if ($produto['id'] == $id) {
			$produtoSelecionado = $produto;
			break;
		}
	}

	if (!$produtoSelecionado) {
		die("<h2>Produto não encontrado.</h2>");
	}
} else {
	die("<h2>ID do produto não foi fornecido.</h2>");
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
	$id = (int) $_POST['id'];
	$nome = $_POST['nome'] ?? '';
	$preco = (float) $_POST['preco'] ?? 0;
	$imagem = $_POST['imagem'] ?? '';
	$descricao = $_POST['descricao'] ?? '';
	$quantidade = (int) ($_POST['quantidade'] ?? 1);
	$tamanho = $_POST['tamanho'] ?? '';
	$cor = $_POST['cor'] ?? '';

	// Validação básica
	if ($id && $nome && $preco && $imagem && $descricao) {
		if (!isset($_SESSION['carrinho'])) {
			$_SESSION['carrinho'] = [];
		}

		// Usar o ID como chave ou adicionar como item novo
		if (isset($_SESSION['carrinho'][$id])) {
			// Atualiza quantidade se já existir
			$_SESSION['carrinho'][$id]['quantidade'] += $quantidade;
		} else {
			$_SESSION['carrinho'][$id] = [
				'id' => $id,
				'nome' => $nome,
				'preco' => $preco,
				'imagem' => $imagem,
				'descricao' => $descricao,
				'quantidade' => $quantidade,
				'tamanho' => $tamanho,
				'cor' => $cor
			];
		}

		// Redireciona para o carrinho após adicionar
		header('Location: product.php');
		exit;

	} else {
		error_log("Dados inválidos recebidos: " . print_r($_POST, true));
	}
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
	<title>Kiluxo | Moda Virtual</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Ícones e fontes -->
	<link rel="icon" type="image/png" href="images/icons/favicon.png" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="fonts/iconic/css/material-design-iconic-font.min.css">
	<link rel="stylesheet" type="text/css" href="fonts/linearicons-v1.0.0/icon-font.min.css">

	<!-- Plugins -->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
	<link rel="stylesheet" type="text/css" href="vendor/slick/slick.css">
	<link rel="stylesheet" type="text/css" href="vendor/MagnificPopup/magnific-popup.css">
	<link rel="stylesheet" type="text/css" href="vendor/perfect-scrollbar/perfect-scrollbar.css">

	<!-- Estilos customizados -->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">

	<!-- Scripts -->
	<script>
		function abrirModal(id) {
			$('.wrap-modal1').hide();
			$('#modal-' + id).show();
			$('.overlay-modal1').show();
		}

		document.addEventListener('DOMContentLoaded', function () {
			document.querySelectorAll('.js-hide-modal1').forEach(btn => {
				btn.addEventListener('click', () => {
					btn.closest('.wrap-modal1').style.display = 'none';
					$('.overlay-modal1').hide();
				});
			});
		});
	</script>
</head>



<body class="animsition">
	<!--header desk-->
	<header class="header-v2">
		<div class="container-menu-desktop trans-03">
			<div class="wrap-menu-desktop">
				<nav class="limiter-menu-desktop p-l-45">

					<a href="index.php" class="logo">
						<img src="images/logo.png" alt="IMG-LOGO">
					</a>

					<div class="menu-desktop">
						<ul class="main-menu">
							<li>
								<a href="index.php">Home</a>
							</li>

							<li class="active-menu">
								<a href="product.php">Shop</a>
							</li>

							<li class="label1" <?php
						

							$totalItensCarrinho = 0;

							if (isset($_SESSION['carrinho'])) {
								foreach ($_SESSION['carrinho'] as $item) {
									$totalItensCarrinho += $item['quantidade'];
								}
							}

							echo 'data-label1="' . ($totalItensCarrinho > 0 ? $totalItensCarrinho : '') . '"';
							?>>
								<a href="shoping-cart.php">Carrinho</a>
							</li>

							<li>
								<a href="blog.php">Blog</a>
							</li>

							<li>
								<a href="about.php">Sobre nós</a>
							</li>

							<li>
								<a href="contact.php">Contatos</a>
							</li>
						</ul>
					</div>

					<!-- Icon header -->
					<div class="wrap-icon-header flex-w flex-r-m h-full">
						<div class="flex-c-m h-full p-r-24">
							<div class="icon-header-item cl2 hov-cl1 trans-04 p-lr-11 js-show-modal-search">
								<i class="zmdi zmdi-search"></i>
							</div>
						</div>

						
						<div class="flex-c-m h-full p-lr-19">
							<div class="icon-header-item cl2 hov-cl1 trans-04 p-lr-11 js-show-sidebar">
								<i class="zmdi zmdi-menu"></i>
							</div>
						</div>
					</div>
				</nav>
			</div>
		</div>

		<!-- Header Mobile -->
		<div class="wrap-header-mobile">
			<div class="logo-mobile">
				<a href="index.php"><img src="images/logo.png" alt="IMG-LOGO"></a>
			</div>

			<div class="wrap-icon-header flex-w flex-r-m h-full m-r-15">
				<div class="flex-c-m h-full p-r-10">
					<div class="icon-header-item cl2 hov-cl1 trans-04 p-lr-11 js-show-modal-search">
						<i class="zmdi zmdi-search"></i>
					</div>
				</div>

				<div class="icon-header-item cl2 hov-cl1 trans-04 p-lr-11 icon-header-noti js-show-cart"
					data-notify="<?= $totalItensCarrinho ?>">
					<i class="zmdi zmdi-shopping-cart"></i>
				</div>

				<div class="flex-c-m h-full p-l-18 p-r-25 bor5">
					<div class="icon-header-item cl2 hov-cl1 trans-04 p-lr-11 js-show">
						<a href="cadastrar.php" style="color: rgb(49, 49, 49);"><i
								class="bi bi-person-fill-add"></i></a>
					</div>
				</div>

			</div>

			<!-- Button show menu -->
			<div class="btn-show-menu-mobile hamburger hamburger--squeeze">
				<span class="hamburger-box">
					<span class="hamburger-inner"></span>
				</span>
			</div>
		</div>


		<!-- Menu Mobile -->
		<div class="menu-mobile">
			<ul class="main-menu-m">
				<li>
					<a href="index.php">Home</a>
				</li>

				<li>
					<a href="product.php">Shop</a>
				</li>

				<li>
					<a href="shoping-cart.php">
						Carrinho
						<?php

						// Inicialize a contagem
						$totalItensCarrinho = 0;

						if (isset($_SESSION['carrinho'])) {
							foreach ($_SESSION['carrinho'] as $item) {
								$totalItensCarrinho += $item['quantidade'];
							}
						}

						// Exibe o número se houver itens
						if ($totalItensCarrinho > 0) {
							echo " (<span style='color: white;'>$totalItensCarrinho</span>)";
						}
						?>
					</a>
				</li>

				<li>
					<a href="blog.php">Blog</a>
				</li>

				<li>
					<a href="about.php">Sobre nós</a>
				</li>

				<li>
					<a href="contact.php">Contatos</a>
				</li>
			</ul>
		</div>

		<!-- Modal Search -->
		<div class="modal-search-header flex-c-m trans-04 js-hide-modal-search" style="display: none;">
			<div class="container-search-header">
				<button class="flex-c-m btn-hide-modal-search trans-04 js-hide-modal-search">
					<img src="images/icons/icon-close2.png" alt="CLOSE">
				</button>

				<form class="wrap-search-header flex-w p-l-15">
					<button class="flex-c-m trans-04" type="submit">
						<i class="zmdi zmdi-search"></i>
					</button>
					<input class="plh3" type="text" name="search" placeholder="Search...">
				</form>
			</div>
		</div>
	</header>

	<!-- Sidebar -->
	<aside class="wrap-sidebar js-sidebar">
		<div class="s-full js-hide-sidebar"></div>

		<div class="sidebar flex-col-l p-t-22 p-b-25">
			<div class="flex-r w-full p-b-30 p-r-27">
				<div class="fs-35 lh-10 cl2 p-lr-5 pointer hov-cl1 trans-04 js-hide-sidebar">
					<i class="zmdi zmdi-close"></i>
				</div>
			</div>

			<div class="sidebar-content flex-w w-full p-lr-65 js-pscroll">
				<ul class="sidebar-link w-full">
					<li class="p-b-13">
						<a href="index.php" class="stext-102 cl2 hov-cl1 trans-04">
							Home
						</a>
					</li>

					<li class="p-b-13">
						<a href="#" class="stext-102 cl2 hov-cl1 trans-04">
							Favoritos
						</a>
					</li>

					<li class="p-b-13">
						<a href="#" class="stext-102 cl2 hov-cl1 trans-04">
							Minha Conta
						</a>
					</li>

					<li class="p-b-13">
						<a href="#" class="stext-102 cl2 hov-cl1 trans-04">
							Rastrear
						</a>
					</li>

					<li class="p-b-13">
						<a href="#" class="stext-102 cl2 hov-cl1 trans-04">
							Ajuda & FAQs
						</a>
					</li>
				</ul>

				<div class="sidebar-gallery w-full p-tb-30">
					<span class="mtext-101 cl5">
						@Kiluxo
					</span>

					<div class="flex-w flex-sb p-t-36 gallery-lb">
						<!-- item gallery sidebar -->
						<div class="wrap-item-gallery m-b-10">
							<a class="item-gallery bg-img1" href="images/gallery-01.jpg" data-lightbox="gallery"
								style="background-image: url('images/gallery-01.jpg');"></a>
						</div>

						<!-- item gallery sidebar -->
						<div class="wrap-item-gallery m-b-10">
							<a class="item-gallery bg-img1" href="images/gallery-02.jpg" data-lightbox="gallery"
								style="background-image: url('images/gallery-02.jpg');"></a>
						</div>

						<!-- item gallery sidebar -->
						<div class="wrap-item-gallery m-b-10">
							<a class="item-gallery bg-img1" href="images/gallery-03.jpg" data-lightbox="gallery"
								style="background-image: url('images/gallery-03.jpg');"></a>
						</div>

						<!-- item gallery sidebar -->
						<div class="wrap-item-gallery m-b-10">
							<a class="item-gallery bg-img1" href="images/gallery-04.jpg" data-lightbox="gallery"
								style="background-image: url('images/gallery-04.jpg');"></a>
						</div>

						<!-- item gallery sidebar -->
						<div class="wrap-item-gallery m-b-10">
							<a class="item-gallery bg-img1" href="images/gallery-05.jpg" data-lightbox="gallery"
								style="background-image: url('images/gallery-05.jpg');"></a>
						</div>

						<!-- item gallery sidebar -->
						<div class="wrap-item-gallery m-b-10">
							<a class="item-gallery bg-img1" href="images/gallery-06.jpg" data-lightbox="gallery"
								style="background-image: url('images/gallery-06.jpg');"></a>
						</div>

						<!-- item gallery sidebar -->
						<div class="wrap-item-gallery m-b-10">
							<a class="item-gallery bg-img1" href="images/gallery-07.jpg" data-lightbox="gallery"
								style="background-image: url('images/gallery-07.jpg');"></a>
						</div>

						<!-- item gallery sidebar -->
						<div class="wrap-item-gallery m-b-10">
							<a class="item-gallery bg-img1" href="images/gallery-08.jpg" data-lightbox="gallery"
								style="background-image: url('images/gallery-08.jpg');"></a>
						</div>

						<!-- item gallery sidebar -->
						<div class="wrap-item-gallery m-b-10">
							<a class="item-gallery bg-img1" href="images/gallery-09.jpg" data-lightbox="gallery"
								style="background-image: url('images/gallery-09.jpg');"></a>
						</div>
					</div>
				</div>

				<div class="sidebar-gallery w-full">
					<span class="mtext-101 cl5">
						Sobre nós
					</span>

					<p class="stext-108 cl6 p-t-27">
						Na <b>Kiluxo</b>, estilo e autenticidade andam lado a lado.
						Cada peça é escolhida com carinho para destacar sua personalidade única.
						Mais que uma loja, somos uma experiência de atitude e elegância.
						Queremos que você se sinta confiante em cada look.
						Vem brilhar com a gente e descubra o que é ser <b>Kiluxo</b>
					</p>
				</div>
			</div>
		</div>
	</aside>

	<!-- Cart -->
	<div class="wrap-header-cart js-panel-cart">
		<div class="s-full js-hide-cart"></div>

		<div class="header-cart flex-col-l p-l-65 p-r-25">
			<div class="header-cart-title flex-w flex-sb-m p-b-8">
				<span class="mtext-103 cl2">
					Seu Carrinho
				</span>

				<div class="fs-35 lh-10 cl2 p-lr-5 pointer hov-cl1 trans-04 js-hide-cart">
					<i class="zmdi zmdi-close"></i>
				</div>
			</div>
			<ul class="list-unstyled">
				<?php foreach ($_SESSION['carrinho'] as $item): ?>
					<li class="d-flex align-items-center mb-3 border-bottom pb-3">
						<div style="width: 60px; height: 60px; overflow: hidden; border-radius: 0.5rem;" class="me-4">
							<img src="<?= htmlspecialchars($item['imagem'] ?? 'images/placeholder.png') ?>"
								alt="<?= htmlspecialchars($item['nome'] ?? 'Produto') ?>" class="img-fluid"
								style="object-fit: cover;" />
						</div>

						<div class="flex-grow-1">
							<a href="#" class="text-decoration-none fw-semibold text-dark">
								<?= htmlspecialchars($item['nome'] ?? 'Produto') ?>
							</a>
							<div class="text-muted">
								<?= (int) ($item['quantidade'] ?? 1) ?> x R$
								<?= number_format($item['preco'] ?? 0, 2, ',', '.') ?>
							</div>
						</div>
					</li>
				<?php endforeach; ?>

				<li class="mt-4">
					<a href="/finalizar-pedido.php" class="btn btn-primary w-100">
						Finalizar Pedido
					</a>
				</li>
			</ul>
		</div>
	</div>

	<div class="container p-t-80 p-b-50">
		<div class="card shadow-sm border-0 rounded">
			<div class="row no-gutters">
				<!-- Imagem -->
				<div class="col-md-6">
					<img src="images/<?= $produtoSelecionado['imagem'] ?>" class="img-fluid rounded-left"
						alt="<?= $produtoSelecionado['nome'] ?>">
				</div>

				<!-- Detalhes -->
				<div class="col-md-6 p-4">
					<h2 class="mtext-105 mb-3"><?= $produtoSelecionado['nome'] ?></h2>
					<p class="text-muted"><?= $produtoSelecionado['descricao'] ?></p>
					<h4 class="text-primary mt-3">R$ <?= number_format($produtoSelecionado['preco'], 2, ',', '.') ?>
					</h4>

					<?php
					$mapaCores = [
						'azul' => '#007bff',
						'vermelho' => '#dc3545',
						'verde' => '#28a745',
						'preto' => '#000000',
						'branco' => '#ffffff',
						'amarelo' => '#ffc107',
						'rosa' => '#e83e8c',
					];
					?>

					<form action="" method="post" class="mt-4">
						<input type="hidden" name="id" value="<?= $produtoSelecionado['id'] ?>">
						<input type="hidden" name="nome" value="<?= $produtoSelecionado['nome'] ?>">
						<input type="hidden" name="preco" value="<?= $produtoSelecionado['preco'] ?>">
						<input type="hidden" name="imagem" value="<?= $produtoSelecionado['imagem'] ?>">
						<input type="hidden" name="descricao" value="<?= $produtoSelecionado['descricao'] ?>">

						<!-- Tamanhos -->
						<?php if (!empty($produtoSelecionado['tamanhos'])): ?>
							<div class="form-group mt-4">
								<label><strong>Tamanho:</strong></label>
								<div class="btn-group btn-group-toggle d-flex flex-wrap gap-2 mt-2" data-toggle="buttons">
									<?php foreach ($produtoSelecionado['tamanhos'] as $tamanho): ?>
										<label class="btn btn-outline-secondary rounded-circle text-uppercase px-3 py-2">
											<input type="radio" name="tamanho" value="<?= $tamanho ?>" required> <?= $tamanho ?>
										</label>
									<?php endforeach; ?>
								</div>
							</div>
						<?php endif; ?>

						<!-- Cores -->
						<?php if (!empty($produtoSelecionado['cores'])): ?>
							<div class="form-group mt-3">
								<label><strong>Cor:</strong></label>
								<div class="d-flex flex-wrap gap-3 mt-2">
									<?php foreach ($produtoSelecionado['cores'] as $cor): ?>
										<?php
										$corCss = isset($mapaCores[strtolower($cor)]) ? $mapaCores[strtolower($cor)] : '#ccc';
										?>
										<label class="d-inline-block position-relative">
											<input type="radio" name="cor" value="<?= $cor ?>" class="d-none" required>
											<span class="rounded-circle border"
												style="width: 35px; height: 35px; background-color: <?= $corCss ?>; display: inline-block; cursor: pointer;"></span>
										</label>
									<?php endforeach; ?>
								</div>
							</div>
						<?php endif; ?>

						<!-- Quantidade -->
						<div class="form-group mt-4">
							<label for="quantidade"><strong>Quantidade:</strong></label>
							<div class="input-group w-50">
								<div class="input-group-prepend">
									<button type="button" class="btn btn-outline-secondary"
										onclick="alterarQuantidade(-1)">−</button>
								</div>
								<input type="number" name="quantidade" id="quantidade" value="1" min="1"
									class="form-control text-center" required>
								<div class="input-group-append">
									<button type="button" class="btn btn-outline-secondary"
										onclick="alterarQuantidade(1)">+</button>
								</div>
							</div>
						</div>


						<button type="submit" class="btn btn-success btn-lg mt-3">
							<i class="fa fa-shopping-cart"></i> Adicionar ao Carrinho
						</button>


					</form>
				</div>
			</div>
		</div>
	</div>


	<!-- Footer -->
	<footer class="bg3 p-t-75 p-b-32">
		<div class="container">
			<div class="row">
				<div class="col-sm-6 col-lg-3 p-b-50">
					<h4 class="stext-301 cl0 p-b-30">
						Categorias
					</h4>

					<ul>
						<li class="p-b-10">
							<a href="#" class="stext-107 cl7 hov-cl1 trans-04">
								Mulheres
							</a>
						</li>

						<li class="p-b-10">
							<a href="#" class="stext-107 cl7 hov-cl1 trans-04">
								Homens
							</a>
						</li>

						<li class="p-b-10">
							<a href="#" class="stext-107 cl7 hov-cl1 trans-04">
								Sapatos
							</a>
						</li>

						<li class="p-b-10">
							<a href="#" class="stext-107 cl7 hov-cl1 trans-04">
								Relógios
							</a>
						</li>
					</ul>
				</div>

				<div class="col-sm-6 col-lg-3 p-b-50">
					<h4 class="stext-301 cl0 p-b-30">
						Ajuda
					</h4>

					<ul>
						<li class="p-b-10">
							<a href="#" class="stext-107 cl7 hov-cl1 trans-04">
								Rastrear pedido
							</a>
						</li>

						<li class="p-b-10">
							<a href="#" class="stext-107 cl7 hov-cl1 trans-04">
								Retornos
							</a>
						</li>

						<li class="p-b-10">
							<a href="#" class="stext-107 cl7 hov-cl1 trans-04">
								Envios
							</a>
						</li>

						<li class="p-b-10">
							<a href="#" class="stext-107 cl7 hov-cl1 trans-04">
								FAQs
							</a>
						</li>
					</ul>
				</div>

				<div class="col-sm-6 col-lg-3 p-b-50">
					<h4 class="stext-301 cl0 p-b-30">
						ENTRE EM CONTATO
					</h4>

					<p class="stext-107 cl7 size-201">
						Dúvidas? Entre em contato conosco na loja, localizada na Rua da Paz, 379, Centro, ou ligue para
						(97) 9876-6879.
					</p>

					<div class="p-t-27">
						<a href="#" class="fs-18 cl7 hov-cl1 trans-04 m-r-16">
							<i class="fa fa-facebook"></i>
						</a>

						<a href="#" class="fs-18 cl7 hov-cl1 trans-04 m-r-16">
							<i class="fa fa-instagram"></i>
						</a>

						<a href="#" class="fs-18 cl7 hov-cl1 trans-04 m-r-16">
							<i class="fa fa-pinterest-p"></i>
						</a>
					</div>
				</div>

				<div class="col-sm-6 col-lg-3 p-b-50">
					<h4 class="stext-301 cl0 p-b-30">
						Boletim Informativo
					</h4>

					<form>
						<div class="wrap-input1 w-full p-b-4">
							<input class="input1 bg-none plh1 stext-107 cl7" type="text" name="email"
								placeholder="email@example.com">
							<div class="focus-input1 trans-04"></div>
						</div>

						<div class="p-t-18">
							<button class="flex-c-m stext-101 cl0 size-103 bg1 bor1 hov-btn2 p-lr-15 trans-04">
								Inscrever-se
							</button>
						</div>
					</form>
				</div>
			</div>

			<div class="p-t-40">
				<div class="flex-c-m flex-w p-b-18">
					<a href="#" class="m-all-1">
						<img src="images/icons/icon-pay-01.png" alt="ICON-PAY">
					</a>

					<a href="#" class="m-all-1">
						<img src="images/icons/icon-pay-02.png" alt="ICON-PAY">
					</a>

					<a href="#" class="m-all-1">
						<img src="images/icons/icon-pay-03.png" alt="ICON-PAY">
					</a>

					<a href="#" class="m-all-1">
						<img src="images/icons/icon-pay-04.png" alt="ICON-PAY">
					</a>

					<a href="#" class="m-all-1">
						<img src="images/icons/icon-pay-05.png" alt="ICON-PAY">
					</a>
				</div>

				<p class="stext-107 cl6 txt-center">
					<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
					Kiluxo | Copyright &copy;
					<script>document.write(new Date().getFullYear());</script> Todos os direitos reservados
					<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
				</p>
			</div>
		</div>
	</footer>

</body>
<script>
	function alterarQuantidade(valor) {
		const input = document.getElementById('quantidade');
		let atual = parseInt(input.value) || 1;
		atual += valor;
		if (atual < 1) atual = 1;
		input.value = atual;
	}
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js"></script>
<script>
	$(document).ready(function () {
		var $grid = $('.isotope-grid').isotope({
			itemSelector: '.isotope-item',
			layoutMode: 'fitRows'
		});

		$('.filter-tope-group button').on('click', function () {
			$('.filter-tope-group button').removeClass('how-active1');
			$(this).addClass('how-active1');
			var filterValue = $(this).attr('data-filter');
			$grid.isotope({ filter: filterValue });
		});
	});
</script>
<!--===============================================================================================-->
<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
<script src="vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
<script src="vendor/bootstrap/js/popper.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
<script src="vendor/select2/select2.min.js"></script>
<script>
	$(".js-select2").each(function () {
		$(this).select2({
			minimumResultsForSearch: 20,
			dropdownParent: $(this).next('.dropDownSelect2')
		});
	})
</script>
<!--===============================================================================================-->
<script src="vendor/daterangepicker/moment.min.js"></script>
<script src="vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
<script src="vendor/slick/slick.min.js"></script>
<script src="js/slick-custom.js"></script>
<!--===============================================================================================-->
<script src="vendor/parallax100/parallax100.js"></script>
<script>
	$('.parallax100').parallax100();
</script>
<!--===============================================================================================-->
<script src="vendor/MagnificPopup/jquery.magnific-popup.min.js"></script>
<script>
	$('.gallery-lb').each(function () { // the containers for all your galleries
		$(this).magnificPopup({
			delegate: 'a', // the selector for gallery item
			type: 'image',
			gallery: {
				enabled: true
			},
			mainClass: 'mfp-fade'
		});
	});
</script>
<!--===============================================================================================-->
<script src="vendor/isotope/isotope.pkgd.min.js"></script>
<!--===============================================================================================-->
<script src="vendor/sweetalert/sweetalert.min.js"></script>
<script>
	$('.js-addwish-b2, .js-addwish-detail').on('click', function (e) {
		e.preventDefault();
	});

	$('.js-addwish-b2').each(function () {
		var nameProduct = $(this).parent().parent().find('.js-name-b2').php();
		$(this).on('click', function () {
			swal(nameProduct, "is added to wishlist !", "success");

			$(this).addClass('js-addedwish-b2');
			$(this).off('click');
		});
	});

	$('.js-addwish-detail').each(function () {
		var nameProduct = $(this).parent().parent().parent().find('.js-name-detail').php();

		$(this).on('click', function () {
			swal(nameProduct, "is added to wishlist !", "success");

			$(this).addClass('js-addedwish-detail');
			$(this).off('click');
		});
	});

	/*---------------------------------------------*/

	$('.js-addcart-detail').each(function () {
		var nameProduct = $(this).parent().parent().parent().parent().find('.js-name-detail').php();
		$(this).on('click', function () {
			swal(nameProduct, "is added to cart !", "success");
		});
	});

</script>
<!--===============================================================================================-->
<script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script>
	$('.js-pscroll').each(function () {
		$(this).css('position', 'relative');
		$(this).css('overflow', 'hidden');
		var ps = new PerfectScrollbar(this, {
			wheelSpeed: 1,
			scrollingThreshold: 1000,
			wheelPropagation: false,
		});

		$(window).on('resize', function () {
			ps.update();
		})
	});
</script>
<!--===============================================================================================-->
<script src="js/main.js"></script>


</html>