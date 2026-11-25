<div class="container py-5" style="max-width: 440px;">
    <h1 class="mb-4">Entrar</h1>

    <?php if (!empty($firstAccess)): ?>
        <div class="alert alert-info">
            Nenhum administrador cadastrado ainda.
            <a href="<?= e(url('admin/create')) ?>">Criar o administrador geral</a>.
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= e($error) ?></div>
    <?php endif; ?>

    <form action="<?= e(url('auth/authenticate')) ?>" method="POST">
        <input type="hidden" name="csrf_token" value="<?= e($csrf) ?>">

        <div class="mb-3">
            <label for="nome" class="form-label">Nome de usuário</label>
            <input type="text" class="form-control" id="nome" name="nome"
                   value="<?= e($old) ?>" autocomplete="username" required autofocus>
        </div>

        <div class="mb-3">
            <label for="senha" class="form-label">Senha</label>
            <input type="password" class="form-control" id="senha" name="senha"
                   autocomplete="current-password" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Entrar</button>
    </form>
</div>
