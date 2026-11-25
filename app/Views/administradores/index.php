<?php use App\Models\Admin; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Administradores</h1>
    <a href="<?= e(url('administradores/create')) ?>" class="btn btn-primary">+ Novo administrador</a>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nome</th>
                    <th>Permissão</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($admins as $admin):
                $level = (int) $admin['nivelAdministrador'];
                $isSelf = (int) $admin['id_administrador'] === (int) $currentUser['id_administrador'];
                $badge = [1 => 'dark', 2 => 'primary', 3 => 'secondary'][$level] ?? 'secondary';
            ?>
                <tr>
                    <td>
                        <?= e($admin['nome']) ?>
                        <?php if ($isSelf): ?><span class="text-muted small">(você)</span><?php endif; ?>
                    </td>
                    <td><span class="badge text-bg-<?= $badge ?>"><?= e(Admin::label($level)) ?></span></td>
                    <td class="text-end">
                        <?php if (Admin::canManage($currentUser, $admin)): ?>
                            <a href="<?= e(url('administradores/edit/' . $admin['id_administrador'])) ?>"
                               class="btn btn-sm btn-outline-primary">Editar</a>
                            <form action="<?= e(url('administradores/destroy/' . $admin['id_administrador'])) ?>"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Excluir o administrador &quot;<?= e($admin['nome']) ?>&quot;? Esta ação não pode ser desfeita.');">
                                <input type="hidden" name="csrf_token" value="<?= e($csrf) ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger">Excluir</button>
                            </form>
                        <?php else: ?>
                            <span class="text-muted small">
                                <?= $level === Admin::NIVEL_GERAL ? 'Protegido' : '—' ?>
                            </span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
