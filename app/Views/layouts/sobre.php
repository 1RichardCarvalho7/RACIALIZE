<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sobre o projeto - Racialize Direitos</title>
 
  <!-- Bootstrap 5 (CSS) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
 
  <!-- Fontes: Lilita One (títulos) e Outfit (menu) -->
  <link href="https://fonts.googleapis.com/css2?family=Lilita+One&family=Outfit:wght@300;400&display=swap" rel="stylesheet">
 
  <!-- CSS próprio: geral + específico da página Sobre -->
  <link rel="stylesheet" href="<?= e(url('public/css/style.css')) ?>">
  <link rel="stylesheet" href="<?= e(url('public/css/sobre.css')) ?>">
</head>
<body>
 
  <!-- Topo: header + faixa de título, dentro do mesmo fundo creme/amarelo da inicial -->
  <div class="sobre-topo">
 
    <?php require __DIR__ . '/../includes/header.php'; ?>
 
    <section class="sobre-hero container-fluid">
      <div class="row align-items-center">
 
        <!-- Lado creme: título e subtítulo -->
        <div class="col-7 sobre-hero-texto">
          <h1 class="sobre-titulo">Sobre o projeto</h1>
          <p class="sobre-subtitulo">
            Informação, conscientização e valorização
            dos direitos da população negra.
          </p>
        </div>
 
        <!-- Lado amarelo: cartão de destaque -->
        <div class="col-5 sobre-hero-destaque">
          <div class="sobre-cartao-destaque">
            <span class="sobre-selo">Projeto informativo e educativo</span>
            <p>
              Um espaço para entender seus direitos, conhecer as cotas
              e saber como denunciar o racismo.
            </p>
          </div>
        </div>
 
      </div>
    </section>
  </div>
 
  <main>
 
    <!-- Missão -->
    <section class="sobre-secao container">
      <div class="row gy-4 align-items-start">
        <div class="col-md-4">
          <h2 class="sobre-secao-titulo">Nossa missão</h2>
        </div>
        <div class="col-md-8">
          <!-- Edite os textos abaixo à vontade -->
          <p>
            O Racialize Direitos nasceu para reunir, em um só lugar, informações
            claras sobre racismo, direitos e ações afirmativas.
          </p>
          <p>
            Aqui você encontra conteúdos em texto, vídeos e gráficos organizados
            por categoria, para facilitar a consulta de quem quer aprender
            e de quem precisa agir.
          </p>
        </div>
      </div>
    </section>
 
    <!-- Três pilares -->
    <section class="sobre-secao container pt-0">
      <div class="row gy-4">
 
        <div class="col-md-4">
          <article class="sobre-card">
            <h3>Informar</h3>
            <p>Conteúdo acessível sobre legislação, cotas e canais de denúncia.</p>
          </article>
        </div>
 
        <div class="col-md-4">
          <article class="sobre-card">
            <h3>Conscientizar</h3>
            <p>Artigos, vídeos e dados que ajudam a entender o racismo no dia a dia.</p>
          </article>
        </div>
 
        <div class="col-md-4">
          <article class="sobre-card">
            <h3>Valorizar</h3>
            <p>Reconhecimento da história, da cultura e dos direitos da população negra.</p>
          </article>
        </div>
 
      </div>
    </section>
 
    <!-- Faixa final com chamada para ação -->
    <section class="sobre-faixa">
      <div class="container text-center">
        <h2>Conheça seus direitos</h2>
        <p>Comece pelo conteúdo que mais combina com a sua dúvida.</p>
        <a href="<?= e(url('/categoria/conheca')) ?>" class="sobre-botao">Ver conteúdos</a>
      </div>
    </section>
 
  </main>
 
  <?php require __DIR__ . '/../includes/footer.php'; ?>
 
  <!-- Bootstrap 5 (JS) - necessário para o menu hambúrguer funcionar -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>