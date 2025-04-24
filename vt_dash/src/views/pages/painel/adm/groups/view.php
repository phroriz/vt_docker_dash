<?php

use src\helpers\CardHelper;
?>
<div class="card">
  <div class="card-body">  
    <h3 ><?= $group->name ?></h3>
    <p><?= $group->description ?></p>
</div>
</div>
<!-- CARDS -->
<div class="row">
    <?= CardHelper::renderCard($totalDashboards, 'Dashboard(s)', 'ti ti-dashboard', 'text-warning') ?>
    <?= CardHelper::renderCard($totalAccess, 'Total de Acessos', 'ti ti-calendar', 'text-info') ?>
    <?= CardHelper::renderCard($totalUsers, 'Usuarios', 'ti ti-users', 'text-info') ?>
    <?= CardHelper::renderCard($group->expires, 'Expira em', 'ti ti-clock', 'text-success') ?>
</div>

<!-- DASHBOARDS -->
<?= CardHelper::rowStart('Dashboards', ['id' => 'add-dashboard-btn', 'target' => 'dashboard-add-modal']); ?>
<?php if ($dashboards): ?>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Qtd. Acessos</th>
                    <th>Ultimo Acessos</th>
                    <th>Status</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dashboards as $dashboard): ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0"><?= $dashboard['title'] ?></h6>
                                </div>
                            </div>
                        </td>
                        <td><?= $dashboard['qtd_access'] ?></td>
                        <td><?= !empty($dashboard['last_access'])
                                ? (new DateTime($dashboard['last_access']))->format('d/m/Y m:h:s')
                                : 'Sem acesso' ?>
                        </td>
                        <td><?= $dashboard['status'] ?></td>
                        <td>
                            <ul class="list-inline me-auto mb-0">
                                <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Ver">
                                    <a href="#"
                                        class="avtar avtar-xs btn-link-secondary dashboard-view"
                                        data-id="<?= $dashboard['hash'] ?>"
                                        data-bs-toggle="modal"
                                        data-bs-target="#dashboard-view-modal">
                                        <i class="ti ti-eye f-18"></i>
                                    </a>
                                </li>
                                <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Editar">

                                    <a href="#"
                                        class="avtar avtar-xs btn-link-primary dashboard-edit"
                                        data-id="<?= $dashboard['hash'] ?>"
                                        data-bs-toggle="modal"
                                        data-bs-target="#dashboard-edit_add-modal">
                                        <i class="ti ti-edit-circle f-18"></i>
                                    </a>
                                </li>

                            </ul>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif ?>
<?= CardHelper::rowEnd(); ?>

<!-- USUÁRIOS -->
<?= CardHelper::rowStart('Usuários', ['id' => 'add-user-btn', 'target' => 'user-add-modal']); ?>
<?php if ($users): ?>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Entrou em</th>
                    <th>Último acesso</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0"><?= $user['name'] ?>
                        </td>
                        </h6>
    </div>
    </div>
    </td>
    <td><?= $user['email'] ?></td>
    <td>2023/09/12</td>
    <td>2024/03/02</td>
    <td>
        <ul class="list-inline me-auto mb-0">
            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Excluir">
                <a href="#"
                    class="avtar avtar-xs btn-link-danger user-delete"
                    data-id="<?= $user['user_id'] ?>"
                    data-bs-toggle="modal"
                    data-bs-target="#confirm-delete-user-modal">
                    <i class="ti ti-trash f-18"></i>
                </a>
            </li>


        </ul>
    </td>
    </tr>
<?php endforeach ?>
</tbody>
</table>
</div>
<?php endif ?>
<?= CardHelper::rowEnd(); ?>

<!-- MODAL: Editar Usuário -->
<div class="modal fade" id="user-edit_add-modal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Usuário</h5>
            </div>
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label for="user-name" class="form-label">Nome</label>
                    <input type="text" class="form-control" id="user-name" placeholder="Digite o nome">
                </div>
                <div class="form-group mb-3">
                    <label for="user-email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="user-email" placeholder="Digite o e-mail">
                </div>
                <div class="form-check form-switch">
                    <label class="form-check-label" for="user-status">Ativo</label>
                    <input class="form-check-input" type="checkbox" id="user-status" checked>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="save-user-btn">Salvar</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL: Editar Dashboard -->
<div class="modal fade" id="dashboard-edit_add-modal" tabindex="-1" aria-labelledby="dashboardModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Dashboard</h5>
            </div>
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label for="dashboard-title" class="form-label">Título</label>
                    <input type="text" class="form-control" id="dashboard-title" placeholder="Digite o título">
                </div>
                <div class="form-group mb-3">
                    <label for="dashboard-description" class="form-label">Descrição</label>
                    <textarea class="form-control" id="dashboard-description" rows="3" placeholder="Descreva o dashboard"></textarea>
                </div>

                <div class="form-group mb-3">
                    <label for="dashboard-link" class="form-label">Link</label>
                    <input type="url" class="form-control" id="dashboard-link" placeholder="URL do Dashboard">
                </div>
                <div class="form-check form-switch">
                    <label class="form-check-label" for="dashboard-status">Ativo</label>
                    <input class="form-check-input" type="checkbox" id="dashboard-status" checked>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="save-dashboard-btn">Salvar</button>
            </div>
        </div>
    </div>
</div>
<!-- MODAL: Visualizar Dashboard -->
<div class="modal fade" id="dashboard-view-modal" tabindex="-1" aria-labelledby="dashboardViewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Visualizar Dashboard</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <div class="ratio ratio-16x9">
                    <iframe id="dashboard-iframe" src="" allowfullscreen style="border: none;"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="user-add-modal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cadastrar Usuário</h5>
            </div>
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label for="new-user-name" class="form-label">Nome</label>
                    <input type="text" class="form-control" id="new-user-name" placeholder="Digite o nome">
                </div>
                <div class="form-group mb-3">
                    <label for="new-user-email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="new-user-email" placeholder="Digite o e-mail">
                </div>

                <div class="form-check form-switch">
                    <label class="form-check-label" for="new-user-status">Ativo</label>
                    <input class="form-check-input" type="checkbox" id="new-user-status" checked>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="create-user-btn">Cadastrar</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="dashboard-add-modal" tabindex="-1" aria-labelledby="addDashboardModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cadastrar Dashboard</h5>
            </div>
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label for="new-dashboard-title" class="form-label">Título</label>
                    <input type="text" class="form-control" id="new-dashboard-title" placeholder="Digite o título">
                </div>
                <div class="form-group mb-3">
                    <label for="new-dashboard-description" class="form-label">Descrição</label>
                    <textarea class="form-control" id="new-dashboard-description" rows="3" placeholder="Descreva o dashboard"></textarea>
                </div>
                <div class="form-group mb-3">
                    <label for="new-dashboard-link" class="form-label">Link</label>
                    <input type="url" class="form-control" id="new-dashboard-link" placeholder="URL do Dashboard">
                </div>
                <div class="form-check form-switch">
                    <label class="form-check-label" for="new-dashboard-status">Ativo</label>
                    <input class="form-check-input" type="checkbox" id="new-dashboard-status" checked>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="create-dashboard-btn">Cadastrar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirm-delete-user-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Exclusão</h5>
            </div>
            <div class="modal-body">
                Tem certeza que deseja excluir este usuário desse grupo?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirm-delete-user-btn" data-id="">Excluir</button>
            </div>
        </div>
    </div>
</div>


<!-- JS: Integração -->
<script>
    document.addEventListener('DOMContentLoaded', () => {

        // ========== EDITAR USUÁRIO ==========
        document.querySelectorAll('.user-edit, .user-view').forEach(el => {
            el.addEventListener('click', async () => {
                const id = el.dataset.id;
                if (!id) return;

                const res = await fetch(`/api/usuarios/${id}`);
                const data = await res.json();

                document.getElementById('user-name').value = data.name;
                document.getElementById('user-email').value = data.email;
                document.getElementById('user-password').value = '';
                document.getElementById('user-status').checked = data.status === 1;
                document.getElementById('save-user-btn').dataset.id = id;
            });
        });

        document.getElementById('save-user-btn').addEventListener('click', async () => {
            const id = document.getElementById('save-user-btn').dataset.id;
            if (!id) return;

            const payload = {
                name: document.getElementById('user-name').value,
                email: document.getElementById('user-email').value,
                password: document.getElementById('user-password').value,
                status: document.getElementById('user-status').checked ? 1 : 0
            };

            await fetch(`/api/usuarios/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            location.reload();
        });

        // ========== EDITAR DASHBOARD ==========
        document.querySelectorAll('.dashboard-edit, .dashboard-view').forEach(el => {
            el.addEventListener('click', async () => {
                const id = el.dataset.id;
                if (!id) return;

                const res = await fetch(`<?= $base . '/private-api/1/dashboard/hash/' ?>${id}`);
                const data = await res.json();

                document.getElementById('dashboard-title').value = data.title;
                document.getElementById('dashboard-link').value = data.src;
                document.getElementById('dashboard-status').checked = data.status === 1;
                document.getElementById('dashboard-description').value = data.description || '';
                document.getElementById('save-dashboard-btn').dataset.id = id;
            });
        });

        document.getElementById('save-dashboard-btn').addEventListener('click', async () => {
            const id = document.getElementById('save-dashboard-btn').dataset.id;
            if (!id) return;

            const payload = {
                title: document.getElementById('dashboard-title').value,
                src: document.getElementById('dashboard-link').value,
                description: document.getElementById('dashboard-description').value,
                status: document.getElementById('dashboard-status').checked ? 1 : 0
            };

            await fetch(`<?= $base . '/private-api/1/dashboard/hash/' ?>${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            location.reload();
        });

        // ========== NOVO USUÁRIO ==========
        const userBtn = document.getElementById('create-user-btn');
        if (userBtn) {
            userBtn.addEventListener('click', async () => {
                console.log('📨 Clicou em Cadastrar Usuário');

                const payload = {
                    name: document.getElementById('new-user-name').value,
                    email: document.getElementById('new-user-email').value,
                    group: "<?= $groupHash ?>",
                };

                if (!payload.name || !payload.email) {
                    alert('Preencha todos os campos obrigatórios.');
                    return;
                }

                try {
                    const res = await fetch(`<?= $base . '/private-api/1/user' ?>`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(payload)
                    });

                    if (!res.ok) throw new Error('Erro ao cadastrar usuário');

                    const modalEl = document.getElementById('user-add-modal');
                    const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                    modal.hide();

                    location.reload();
                } catch (err) {
                    console.error(err);
                    alert('Erro ao cadastrar usuário!');
                }
            });
        }

        // ========== NOVO DASHBOARD ==========
        const dashBtn = document.getElementById('create-dashboard-btn');
        if (dashBtn) {
            dashBtn.addEventListener('click', async () => {
                console.log('📨 Clicou em Cadastrar Dashboard');

                const payload = {
                    title: document.getElementById('new-dashboard-title').value,
                    description: document.getElementById('new-dashboard-description').value,
                    src: document.getElementById('new-dashboard-link').value,
                    group: "<?= $groupHash ?>",
                    status: document.getElementById('new-dashboard-status').checked ? 1 : 0
                };

                if (!payload.title || !payload.src) {
                    alert('Título e link são obrigatórios.');
                    return;
                }

                try {
                    const res = await fetch(`<?= $base . '/private-api/1/dashboard' ?>`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(payload)
                    });

                    if (!res.ok) throw new Error('Erro ao cadastrar dashboard');

                    const modalEl = document.getElementById('dashboard-add-modal');
                    const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                    modal.hide();

                    location.reload();
                } catch (err) {
                    console.error(err);
                    alert('Erro ao cadastrar dashboard!');
                }
            });
        }
        // ========== EXCLUIR USUÁRIO ==========
        document.querySelectorAll('.user-delete').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;
                document.getElementById('confirm-delete-user-btn').dataset.id = id;
            });
        });
        document.getElementById('confirm-delete-user-btn').addEventListener('click', async () => {
            const id = document.getElementById('confirm-delete-user-btn').dataset.id;
            const hashGroup = "<?= $groupHash ?>";

            if (!id) return;

            try {
                const res = await fetch(`<?= $base . '/private-api/1/group/user' ?>`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        user_id: id,
                        group_hash: hashGroup
                    })
                });

                if (!res.ok) throw new Error('Erro ao excluir');

                const modalEl = document.getElementById('confirm-delete-user-modal');
                const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                modal.hide();

                location.reload();
            } catch (err) {
                console.error(err);
                alert('Erro ao excluir usuário!');
            }
        });




        // ========== VISUALIZAR DASHBOARD ==========
        document.querySelectorAll('.dashboard-view').forEach(button => {
            button.addEventListener('click', async () => {
                const id = button.dataset.id;
                if (!id) return;

                try {
                    const res = await fetch(`<?= $base . '/private-api/1/dashboard/hash/' ?>${id}`);
                    const data = await res.json();

                    document.getElementById('dashboard-iframe').src = data.src || '';
                } catch (err) {
                    console.error('Erro ao carregar dashboard:', err);
                    alert('Erro ao carregar o dashboard.');
                }
            });
        });

        document.getElementById('dashboard-view-modal').addEventListener('hidden.bs.modal', () => {
            document.getElementById('dashboard-iframe').src = '';
        });

        // ========== COPIAR LINK ==========
        const copyBtn = document.getElementById('copy-dashboard-link');
        if (copyBtn) {
            copyBtn.addEventListener('click', () => {
                const input = document.getElementById('dashboard-link');
                input.select();
                input.setSelectionRange(0, 99999);
                navigator.clipboard.writeText(input.value)
                    .then(() => {
                        copyBtn.innerHTML = '<i class="ti ti-check"></i>';
                        setTimeout(() => {
                            copyBtn.innerHTML = '<i class="ti ti-copy"></i>';
                        }, 2000);
                    })
                    .catch(err => {
                        console.error('Erro ao copiar:', err);
                        alert('Não foi possível copiar o link!');
                    });
            });
        }
    });
</script>