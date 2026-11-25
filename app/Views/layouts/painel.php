<?php use App\Models\Admin; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title ?? 'Painel') ?> — Racialize Direitos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar-racialize { background-color: #654f3c; }
        .navbar-racialize .nav-link { color: #fff !important; font-size: 1rem; }
        .navbar-racialize .nav-link:hover { opacity: .75; }
    </style>
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-md navbar-dark navbar-racialize mb-4">
    <div class="container">
        <a class="navbar-brand" href="<?= e(url('painel/index')) ?>">Racialize Direitos</a>
        <?php if ($currentUser): ?>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navPainel" aria-label="Abrir menu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navPainel">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="<?= e(url('painel/index')) ?>">Painel</a></li>
                    <?php if (Admin::canManageAdmins($currentUser)): ?>
                        <li class="nav-item"><a class="nav-link" href="<?= e(url('administradores/index')) ?>">Administradores</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link" href="<?= e(url('home/index')) ?>">Ver site</a></li>
                </ul>
                <form action="<?= e(url('auth/logout')) ?>" method="POST" class="d-flex align-items-center gap-3">
                    <input type="hidden" name="csrf_token" value="<?= e($csrf) ?>">
                    <span class="text-white small"><?= e($currentUser['nome']) ?></span>
                    <button class="btn btn-outline-light btn-sm" type="submit">Sair</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</nav>

<main class="container pb-5">
    <?php if (!empty($flash)): ?>
        <div class="alert alert-<?= e($flash['type']) ?> alert-dismissible fade show" role="alert">
            <?= e($flash['message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
        </div>
    <?php endif; ?>

    <?= $content ?>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
