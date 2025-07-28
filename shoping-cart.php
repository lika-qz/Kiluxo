<?php
session_start();
require_once 'listaProdutos.php';

// Função para limpar o carrinho
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['limpar_carrinho'])) {
	unset($_SESSION['carrinho']);
	header('Location: shoping-cart.php'); // Redireciona para evitar reenvio de formulário
	exit;
}

// Inicializa o carrinho se não existir
if (!isset($_SESSION['carrinho'])) {
	$_SESSION['carrinho'] = [];
}

// Se for um POST com um produto sendo adicionado
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

		// Cria uma chave única considerando tamanho e cor
		$chave = $id . '-' . $tamanho . '-' . $cor;

		if (isset($_SESSION['carrinho'][$chave])) {
			$_SESSION['carrinho'][$chave]['quantidade'] += $quantidade;
		} else {
			$_SESSION['carrinho'][$chave] = [
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

		header('Location: carrinho.php');
		exit;
	} else {
		error_log("Dados inválidos recebidos: " . print_r($_POST, true));
	}
}


// Calcula o total do carrinho
$total = 0;
foreach ($_SESSION['carrinho'] as $item) {
	$total += $item['preco'] * $item['quantidade'];
}
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
	<title>Kiluxo | Moda Virtual</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
	<link rel="icon" type="image/png" href="images/icons/favicon.png" />
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="fonts/iconic/css/material-design-iconic-font.min.css">
	<link rel="stylesheet" type="text/css" href="fonts/linearicons-v1.0.0/icon-font.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
	<link rel="stylesheet" type="text/css" href="vendor/slick/slick.css">
	<link rel="stylesheet" type="text/css" href="vendor/MagnificPopup/magnific-popup.css">
	<link rel="stylesheet" type="text/css" href="vendor/perfect-scrollbar/perfect-scrollbar.css">
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
</head>

<body class="animsition">

	<!-- Header -->
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

							<li>
								<a href="product.php">Shop</a>
							</li>

							<li class="label1 active-menu" data-label1="New">
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
					<a href="shoping-cart.php" class="label1 rs1" data-label1="New">Carrinho</a>
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

		<script>
			// Garante que o modal de busca só aparece ao clicar no ícone de busca
			document.addEventListener('DOMContentLoaded', function () {
				var modal = document.querySelector('.modal-search-header');
				var showBtns = document.querySelectorAll('.js-show-modal-search');
				var hideBtns = document.querySelectorAll('.js-hide-modal-search, .btn-hide-modal-search');

				showBtns.forEach(function (btn) {
					btn.addEventListener('click', function (e) {
						e.preventDefault();
						modal.style.display = 'flex';
						var input = modal.querySelector('input');
						if (input) input.focus();
					});
				});

				hideBtns.forEach(function (btn) {
					btn.addEventListener('click', function (e) {
						e.preventDefault();
						modal.style.display = 'none';
					});
				});

				// Fecha o modal ao clicar fora do container
				modal.addEventListener('click', function (e) {
					if (e.target === modal) {
						modal.style.display = 'none';
					}
				});
			});
		</script>
	</header>

	<!-- Cart -->



	<!-- breadcrumb -->
	<div class="container">
		<div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
			<a href="index.php" class="stext-109 cl8 hov-cl1 trans-04">
				Home
				<i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
			</a>

			<span class="stext-109 cl4">
				Carrinho
			</span>
		</div>
	</div>


	<!-- Shoping Cart -->

	<form class="bg0 p-t-75 p-b-85" method="post" action="atualizar-carrinho.php">
		<div class="container">
			<div class="row">
				<div class="col-lg-10 col-xl-7 m-lr-auto m-b-50">
					<div class="m-l-25 m-r--38 m-lr-0-xl">
						<div class="wrap-table-shopping-cart">
							<table class="table-shopping-cart">
								<!-- Cabeçalho da tabela -->
								<tr class="table_head">
									<th class="column-1">Produto</th>
									<th class="column-2">Nome</th>
									<th class="column-3">Preço</th>
									<th class="column-4">Quantidade</th>
									<th class="column-5">Total</th>
								</tr>

								<?php
								$total = 0;

								if (!empty($_SESSION['carrinho'])):
									foreach ($_SESSION['carrinho'] as $id => $produto):
										$nome = htmlspecialchars($produto['nome']);
										$preco = (float) $produto['preco'];
										$quant = (int) $produto['quantidade'];
										$imagem = htmlspecialchars($produto['imagem']);
										$subtotal = $preco * $quant;
										$total += $subtotal;
										?>
										<!-- Linha do produto -->
										<tr class="table_row">
											<td class="column-1">
												<div class="how-itemcart1">
													<img src="images/<?= $imagem ?>" alt="<?= $nome ?>"
														style="width: 80px; height: auto;">
												</div>
											</td>
											<td class="column-2"><?= $nome ?></td>
											<td class="column-3">R$ <?= number_format($preco, 2, ',', '.') ?></td>
											<td class="column-4">
												<input type="number" name="quantidade[<?= $id ?>]" value="<?= $quant ?>" min="1"
													class="form-control text-center" style="max-width: 70px;">
											</td>
											<td class="column-5">R$ <?= number_format($subtotal, 2, ',', '.') ?></td>
										</tr>

										<?php
									endforeach;
								else:
									?>
									<!-- Carrinho vazio -->
									<tr>
										<td colspan="5" class="text-center">Seu carrinho está vazio.</td>
									</tr>

								<?php endif; ?>
							</table>

						</div>

						<div class="flex-c-m stext-101 cl2 size-119 bg8 bor13 hov-btn3 p-lr-15 trans-04 pointer m-tb-10">
							<a href="shoping-cart.php">Atualizar carrinho</a>
						</div>
						
						
					</div>
				</div>

				<div class="col-sm-10 col-lg-7 col-xl-5 m-lr-auto m-b-50">
					<div class="bor10 p-lr-40 p-t-30 p-b-40">
						<h4 class="mtext-109 cl2 p-b-30">Total do Carrinho</h4>

						<div class="flex-w flex-t p-t-27 p-b-33">
							<div class="size-208">
								<span class="mtext-101 cl2">Total:</span>
							</div>

							<div class="size-209 p-t-1">
								<span class="mtext-110 cl2">R$ <?= number_format($total, 2, ',', '.') ?></span>
							</div>
						</div>

						<a href="checkout.php"
							class="flex-c-m stext-101 cl0 size-116 bg3 bor14 hov-btn3 p-lr-15 trans-04 pointer">
							Finalizar compra
						</a>
					</div>
				</div>
			</div>
		</div>
	</form>




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

	<!-- Back to top -->
	<div class="btn-back-to-top" id="myBtn">
		<span class="symbol-btn-back-to-top">
			<i class="zmdi zmdi-chevron-up"></i>
		</span>
	</div>

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
	<script src="vendor/MagnificPopup/jquery.magnific-popup.min.js"></script>
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

</body>

</html>