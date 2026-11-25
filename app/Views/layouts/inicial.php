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
  <link rel="stylesheet" href="<?= e(url('public/css/style.css')) ?>">
  <link rel="stylesheet" href="<?= e(url('public/css/inicial.css')) ?>">
</head>
<body>

  <?php require __DIR__ . '/../includes/header.php'; ?>

  <header class="hero">

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

  <main class="area-branca"></main>

  <?php require __DIR__ . '/../includes/footer.php'; ?>

  <!-- Bootstrap 5 (JS) - necessário para o menu hambúrguer funcionar -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
