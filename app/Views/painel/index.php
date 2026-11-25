<?php use App\Models\Admin; ?>
<h1 class="h3 mb-1">Olá, <?= e($currentUser['nome']) ?></h1>
<p class="text-muted">
    Seu nível de acesso:
    <strong><?= e(Admin::label((int) $currentUser['nivelAdministrador'])) ?></strong>
</p>

<div class="row g-3">
    <?php if (Admin::canManageAdmins($currentUser)): ?>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h2 class="h5">Administradores</h2>
                    <p class="text-muted">Crie contas e defina o que cada administrador pode fazer.</p>
                    <a href="<?= e(url('administradores/index')) ?>" class="btn btn-primary">Gerenciar</a>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="col-md-4">
        <div class="card h-100 shadow-sm">
            <div class="card-body">
                <h2 class="h5">Conteúdos</h2>
                <p class="text-muted">Criar, editar e excluir artigos, vídeos e gráficos.</p>
            </div>
        </div>
    </div>
</div>
