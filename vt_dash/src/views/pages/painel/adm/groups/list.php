<!-- TABELA -->
<div class="col-sm-12">
    <div class="card table-card">
        <div class="card-body">
            <div class="text-end p-4 pb-sm-2">
                <a href="#" class="btn btn-primary d-inline-flex align-item-center" data-bs-toggle="modal" data-bs-target="#customer-add-modal">
                    <i class="ti ti-plus f-18"></i> Adicionar Grupo
                </a>

            </div>
            <div class="table-responsive">
                <table class="table table-hover" id="pc-dt-simple">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Grupo</th>
                            <th>Expira em</th>
                            <th>Status</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($groups as $group): ?>
                            <tr>
                                <td><?= $group['id'] ?></td>
                                <td>
                                    <div class="row">
                                        <div class="col">
                                            <h6 class="mb-0"><?= $group['name'] ?></h6>
                                        </div>
                                    </div>
                                </td>
                                <td><?= $group['expires'] ?></td>
                                <td><span class="badge bg-light-success rounded-pill f-12"><?= $group['status'] ?></span> </td>
                                <td class="text-center">
                                    <ul class="list-inline me-auto mb-0">
                                        <li class="list-inline-item align-bottom" title="Abrir">
                                            <a href="<?= $base . '/painel/adm/group/view/' . $group['hash']  ?>" class="avtar avtar-xs btn-link-secondary btn-pc-default">
                                                <i class="ti ti-eye f-18"></i>
                                            </a>
                                        </li>
                                        <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Editar">
                                            <a href="#"
                                                class="avtar avtar-xs btn-link-success btn-pc-default btn-edit-group"
                                                data-id="<?= $group['id'] ?>"
                                                data-bs-toggle="modal"
                                                data-bs-target="#customer-edit_add-modal">
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
        </div>
    </div>
</div>

<!-- MODAL -->
<div class="modal fade" id="customer-edit_add-modal" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="mb-0">Editar Grupo</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="form-label">Nome</label>
                            <input type="text" class="form-control" id="inputNome" placeholder="Nome">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Descrição</label>
                            <textarea class="form-control" id="inputDescricao" rows="3" placeholder="Descrição"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Expira em</label>
                            <input type="date" class="form-control" id="inputExpira">
                        </div>
                        <div class="form-check form-switch d-flex align-items-center justify-content-between p-0">
                            <label class="form-check-label h5 pe-3 mb-0" for="customSwitchemlnot1">Status do Grupo
                                <span class="text-muted w-75 d-block text-sm f-w-400 mt-2">Marque o checkbox para ativar o grupo</span>
                            </label>
                            <input class="form-check-input h4 m-0 position-relative flex-shrink-0" type="checkbox" id="customSwitchemlnot1">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <ul class="list-inline me-auto mb-0">
                    <li class="list-inline-item align-bottom">
                        <a href="#" class="avtar avtar-s btn-link-danger btn-pc-default w-sm-auto" data-bs-toggle="tooltip" title="Delete">
                            <i class="ti ti-trash f-18"></i>
                        </a>
                    </li>
                </ul>
                <div class="flex-grow-1 text-end">
                    <button type="button" class="btn btn-link-danger btn-pc-default" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary btn-save-group">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="customer-add-modal" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="mb-0">Adicionar Grupo</h5>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Nome</label>
                    <input type="text" class="form-control" id="addNome" placeholder="Nome">
                </div>
                <div class="form-group">
                    <label class="form-label">Descrição</label>
                    <textarea class="form-control" id="addDescricao" rows="3" placeholder="Descrição"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Expira em</label>
                    <input type="date" class="form-control" id="addExpira">
                </div>
                <div class="form-check form-switch d-flex align-items-center justify-content-between p-0">
                    <label class="form-check-label h5 pe-3 mb-0" for="addStatus">
                        Status do Grupo
                        <span class="text-muted w-75 d-block text-sm f-w-400 mt-2">Marque o checkbox para ativar o grupo</span>
                    </label>
                    <input class="form-check-input h4 m-0 position-relative flex-shrink-0" type="checkbox" id="addStatus" checked>
                </div>
            </div>
            <div class="modal-footer justify-content-end">
                <button type="button" class="btn btn-link-danger" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnCreateGroup">Criar</button>
            </div>
        </div>
    </div>
</div>




<!-- DATATABLE INIT -->
<script src="<?= $base ?>/assets/js/plugins/simple-datatables.js"></script>
<script>
    const dataTable = new simpleDatatables.DataTable('#pc-dt-simple', {
        sortable: false,
        perPage: 5
    });
</script>

<!-- SCRIPTS -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('customer-edit_add-modal');
        const inputNome = document.getElementById('inputNome');
        const inputDescricao = document.getElementById('inputDescricao');
        const inputExpira = document.getElementById('inputExpira');
        const checkboxStatus = document.getElementById('customSwitchemlnot1');
        const saveButton = modal.querySelector('.btn-save-group');

        let currentEditId = null;

        document.querySelectorAll('.btn-edit-group').forEach(button => {
            button.addEventListener('click', async function() {
                currentEditId = this.dataset.id;

                try {
                    const response = await fetch(`<?= $base ?>/private-api/1/group/id/${currentEditId}`);
                    const data = await response.json();

                    inputNome.value = data.name || '';
                    inputDescricao.value = data.description || '';
                    inputExpira.value = data.expires || '';
                    checkboxStatus.checked = !!parseInt(data.status);
                } catch (error) {
                    console.error('Erro ao buscar dados do grupo:', error);
                    alert('Erro ao carregar dados do grupo!');
                }
            });
        });

        saveButton.addEventListener('click', async function() {
            const payload = {
                id: currentEditId,
                name: inputNome.value,
                description: inputDescricao.value,
                expires: inputExpira.value,
                status: checkboxStatus.checked ? 1 : 0
            };

            try {
                const response = await fetch('<?= $base ?>/private-api/1/group/update', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                const result = await response.json();

                if (result.success) {
                    location.reload();
                } else {
                    alert('Erro ao atualizar grupo!');
                }
            } catch (error) {
                console.error('Erro ao atualizar grupo:', error);
                alert('Erro inesperado ao salvar!');
            }
        });
    });


    document.getElementById('btnCreateGroup').addEventListener('click', async () => {
        const payload = {
            name: document.getElementById('addNome').value,
            description: document.getElementById('addDescricao').value,
            expires: document.getElementById('addExpira').value,
            status: document.getElementById('addStatus').checked ? 1 : 0
        };

        if (!payload.name || !payload.expires) {
            alert('Preencha os campos obrigatórios!');
            return;
        }

        try {
            const res = await fetch('<?= $base ?>/private-api/1/group', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            const result = await res.json();

            if (result.success) {
                const modal = bootstrap.Modal.getInstance(document.getElementById('customer-add-modal'));
                modal.hide();
                location.reload();
            } else {
                alert('Erro ao criar grupo!');
            }
        } catch (err) {
            console.error('Erro ao criar grupo:', err);
            alert('Erro inesperado!');
        }
    });
</script>