<nav class="navbar navbar-dark">
    <div class="container-fluid px-4 pt-2">

        <button class="btn-hamburguer me-4" type="button"
                data-bs-toggle="offcanvas"
                data-bs-target="#menuCategorias"
                aria-controls="menuCategorias"
                aria-label="Abrir menu de categorias">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <ul class="navbar-nav flex-row me-auto gap-4">
            <li class="nav-item">
                <a class="nav-link" href="<?= e(url('home/index')) ?>">Inicial</a>
            </li>
        </ul>

    </div>
</nav>

<div class="offcanvas offcanvas-start menu-lateral"
     tabindex="-1"
     id="menuCategorias"
     aria-labelledby="menuCategoriasLabel">

    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="menuCategoriasLabel">Categorias</h5>

        <button type="button"
                class="btn-close btn-close-white"
                data-bs-dismiss="offcanvas"
                aria-label="Fechar">
        </button>
    </div>

    <div class="offcanvas-body">
        <ul class="list-unstyled">
            <li><a href="#">Entenda o racismo</a></li>
            <li><a href="#">Conheça seus direitos</a></li>
            <li><a href="#">Denuncie o racismo</a></li>
            <li><a href="#">Ações afirmativas e cotas</a></li>
        </ul>
    </div>

</div>