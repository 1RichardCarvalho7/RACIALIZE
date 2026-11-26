<div class="container py-4">
    <h1 class="mb-4">Cadastro do administrador</h1>

    <?php if (isset($errors['geral'])): ?>
        <div class="alert alert-danger" role="alert"><?= e($errors['geral']) ?></div>
    <?php endif; ?>

    <form action="<?= e(url('admin/store')) ?>" method="POST">
        <input type="hidden" name="csrf_token" value="<?= e($csrf) ?>">

        <div class="mb-3">
            <label for="nome" class="form-label">Nome de usuário</label>
            <input
                type="text"
                class="form-control <?= isset($errors['nome']) ? 'is-invalid' : '' ?>"
                id="nome"
                name="nome"
                value="<?= e($old['nome'] ?? '') ?>"
                minlength="3"
                maxlength="30"
                autocomplete="username"
                required
            >
            <?php if (isset($errors['nome'])): ?>
                <div class="invalid-feedback"><?= e($errors['nome']) ?></div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label for="senha" class="form-label">Senha</label>
            <input
                type="password"
                class="form-control <?= isset($errors['senha']) ? 'is-invalid' : '' ?>"
                id="senha"
                name="senha"
                minlength="8"
                autocomplete="new-password"
                required
            >
            <?php if (isset($errors['senha'])): ?>
                <div class="invalid-feedback"><?= e($errors['senha']) ?></div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label for="confirmar_senha" class="form-label">Confirmar senha</label>
            <input
                type="password"
                class="form-control <?= isset($errors['confirmar_senha']) ? 'is-invalid' : '' ?>"
                id="confirmar_senha"
                name="confirmar_senha"
                minlength="8"
                autocomplete="new-password"
                required
            >
            <?php if (isset($errors['confirmar_senha'])): ?>
                <div class="invalid-feedback"><?= e($errors['confirmar_senha']) ?></div>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary">Cadastrar administrador</button>
    </form>
</div>
