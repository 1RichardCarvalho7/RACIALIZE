<?php
use App\Models\Admin;

$isEdit = $target !== null;
$action = $isEdit
    ? url('administradores/update/' . $target['id_administrador'])
    : url('administradores/store');

$descriptions = [
    Admin::NIVEL_GERENTE => 'Pode gerenciar outros administradores (exceto o administrador geral) e também criar, editar e excluir conteúdos.',
    Admin::NIVEL_EDITOR => 'Pode somente criar, editar e excluir conteúdos. Não vê nem altera administradores.',
];
?>
<h1 class="h3 mb-4"><?= e($title) ?></h1>

<form action="<?= e($action) ?>" method="POST" class="card shadow-sm p-4" style="max-width: 640px;">
    <input type="hidden" name="csrf_token" value="<?= e($csrf) ?>">

    <div class="mb-3">
        <label for="nome" class="form-label">Nome de usuário</label>
        <input type="text" id="nome" name="nome" required minlength="3" maxlength="30"
               class="form-control <?= isset($errors['nome']) ? 'is-invalid' : '' ?>"
               value="<?= e($old['nome']) ?>" autocomplete="off">
        <?php if (isset($errors['nome'])): ?>
            <div class="invalid-feedback"><?= e($errors['nome']) ?></div>
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <label for="senha" class="form-label">
            <?= $isEdit ? 'Nova senha' : 'Senha' ?>
            <?php if ($isEdit): ?><span class="text-muted small">(deixe em branco para manter a atual)</span><?php endif; ?>
        </label>
        <input type="password" id="senha" name="senha" minlength="8" autocomplete="new-password"
               class="form-control <?= isset($errors['senha']) ? 'is-invalid' : '' ?>"
               <?= $isEdit ? '' : 'required' ?>>
        <?php if (isset($errors['senha'])): ?>
            <div class="invalid-feedback"><?= e($errors['senha']) ?></div>
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <label for="confirmar_senha" class="form-label">Confirmar senha</label>
        <input type="password" id="confirmar_senha" name="confirmar_senha" minlength="8" autocomplete="new-password"
               class="form-control <?= isset($errors['confirmar_senha']) ? 'is-invalid' : '' ?>"
               <?= $isEdit ? '' : 'required' ?>>
        <?php if (isset($errors['confirmar_senha'])): ?>
            <div class="invalid-feedback"><?= e($errors['confirmar_senha']) ?></div>
        <?php endif; ?>
    </div>

    <fieldset class="mb-4">
        <legend class="form-label fs-6">Permissão</legend>
        <?php foreach (Admin::ASSIGNABLE_LEVELS as $level): ?>
            <div class="form-check mb-2">
                <input class="form-check-input <?= isset($errors['nivel']) ? 'is-invalid' : '' ?>"
                       type="radio" name="nivel" id="nivel<?= $level ?>" value="<?= $level ?>"
                       <?= $old['nivel'] === $level ? 'checked' : '' ?> required>
                <label class="form-check-label" for="nivel<?= $level ?>">
                    <strong><?= e(Admin::label($level)) ?></strong><br>
                    <span class="text-muted small"><?= e($descriptions[$level]) ?></span>
                </label>
            </div>
        <?php endforeach; ?>
        <?php if (isset($errors['nivel'])): ?>
            <div class="text-danger small"><?= e($errors['nivel']) ?></div>
        <?php endif; ?>
    </fieldset>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Salvar alterações' : 'Criar administrador' ?></button>
        <a href="<?= e(url('administradores/index')) ?>" class="btn btn-outline-secondary">Cancelar</a>
    </div>
</form>
