<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Racialize Direitos</title>

  <!-- Bootstrap 5 (CSS) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Fontes: Lilita One (títulos) e Outfit (menu) -->
  <link href="https://fonts.googleapis.com/css2?family=Lilita+One&family=Outfit:wght@300;400&display=swap" rel="stylesheet">

  <!-- CSS próprio -->
  <link rel="stylesheet" href="<?= e(url('css/inicial.css')) ?>">
</head>
<body>

  <header class="hero">

    <!-- Barra de navegação: botão hambúrguer + links -->
    <nav class="navbar navbar-dark">
      <div class="container-fluid px-4 pt-2">

        <!-- Botão hambúrguer: abre o menu lateral com as categorias -->
        <button class="btn-hamburguer me-4" type="button"
                data-bs-toggle="offcanvas" data-bs-target="#menuCategorias"
                aria-controls="menuCategorias" aria-label="Abrir menu de categorias">
          <span></span>
          <span></span>
          <span></span>
        </button>

        <!-- Links principais -->
        <ul class="navbar-nav flex-row me-auto gap-4">
          <li class="nav-item"><a class="nav-link" href="<?= e(url('home/index')) ?>">Inicial</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Denuncie aqui</a></li>
        </ul>
      </div>
    </nav>

    <!-- Conteúdo do hero: título + subtítulo (à esquerda) e foto (à direita) -->
    <div class="container-fluid hero-conteudo">
      <div class="row align-items-center h-100">

        <div class="col-12 col-md-8 texto-hero">
          <h1 class="titulo">Racialize Direitos</h1>
          <p class="subtitulo">
            Entenda seus direitos, conheça as cotas e<br>
            saiba como denunciar o racismo.
          </p>
        </div>

        <!-- Troque o arquivo da imagem em img/modelo.png -->
        <div class="col-md-4 d-none d-md-flex justify-content-end align-self-end">
          <img src="<?= e(url('public/img/mulhermodelositenegra.png')) ?>" alt="Mulher negra de cabelo curto" class="foto-hero">
        </div>

      </div>
    </div>
  </header>

  <!-- ===== MENU LATERAL (categorias) ===== -->
  <div class="offcanvas offcanvas-start menu-lateral" tabindex="-1" id="menuCategorias"
       aria-labelledby="menuCategoriasLabel">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title" id="menuCategoriasLabel">Categorias</h5>
      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Fechar"></button>
    </div>
    <div class="offcanvas-body">
      <!-- Cada categoria leva para a página que lista artigos e gráficos dela.
           Quando o PHP estiver pronto, o link pode virar: categoria.php?id=1 -->
      <ul class="list-unstyled">
        <li><a href="#">Seus direitos</a></li>
        <li><a href="#">Cotas</a></li>
        <li><a href="#">Como denunciar</a></li>
        <li><a href="#">Racismo no trabalho</a></li>
        <li><a href="#">Racismo na educação</a></li>
        <li><a href="#">Dados e gráficos</a></li>
      </ul>
    </div>
  </div>


  <main class="area-branca"></main>

  <!-- Bootstrap 5 (JS) - necessário para o menu hambúrguer funcionar -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
