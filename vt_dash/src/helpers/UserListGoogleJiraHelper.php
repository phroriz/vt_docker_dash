<?php

namespace src\helpers;

class UserListGoogleJiraHelper
{
    public static function renderUserList(array $users): string
    {

        if (empty($users)) {
            return '<p>Nenhum usuário encontrado.</p>';
        }

        ob_start(); ?>
        <ul class="users-list">
            <?php foreach ($users as $index => $user): ?>
                <li class="users-item" onclick="toggleCollapse(<?= $index ?>)">
                    <div class="card user-card">
                        <!-- Cabeçalho do Usuário -->
                        <div class="card-input">
                            <div class="card-avatar-item">
                                <a href="#">
                                    <img src="<?= !empty($user['photo']) ? htmlspecialchars($user['photo']) : 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR_nLCu85ayoTKwYw6alnvrockq5QBT2ZWR2g&s' ?>"
                                        alt="<?= htmlspecialchars($user['displayName'] ?? 'Usuário') ?>"
                                        width="32" height="32">
                                </a>
                            </div>

                            <label class="user-labelb">
                                <div class="mt-1" style="margin-top: 8px;">
                                    <?= htmlspecialchars($user['displayName'] ?? 'Nome não disponível') ?>
                                </div>
                            </label>
                        </div>

                        <div class="card-badge cyan radius-pill">
                            <?= htmlspecialchars($user['emailPrimary'] ?? 'E-mail não disponível') ?>
                        </div>

                        <ul class="card-meta-list">
                            <li>
                                <div class="meta-box icon-box">
                                    <span class="material-symbols-rounded icon">list</span>
                                    <span>x/x</span>
                                </div>
                            </li>

                            <!-- Exibir ícone do Jira apenas se o usuário tiver conta no Jira Software -->
                            <?php
                            $hasJiraSoftware = false;

                            if (!empty($user['jiraProductAccess'])) {
                                // Se for array de objetos
                                if (is_array($user['jiraProductAccess'])) {
                                    foreach ($user['jiraProductAccess'] as $product) {
                                        if ((is_string($product) && strtolower($product) === 'jira software') || (is_string($product) && strtolower($product) === 'jira-software')) {
                                            $hasJiraSoftware = true;
                                            break;
                                        }
                                    }
                                }
                                // Se for string, pode ser um erro de API, mas tratamos aqui
                                elseif (is_string($user['jiraProductAccess']) && $user['jiraProductAccess'] === 'jira-software') {
                                    $hasJiraSoftware = true;
                                }
                            }
                            ?>

                            <?php if ($hasJiraSoftware): ?>
                                <li>
                                    <div class="meta-box icon-box">
                                        <img src="https://cdn.worldvectorlogo.com/logos/jira-1.svg"
                                            alt="Jira Software" width="20" height="20" />
                                    </div>
                                </li>
                            <?php endif; ?>

                            <!-- Status Google (Ativo/Inativo) -->
                            <?php if (empty($user['suspended'])): ?>
                                <li>
                                    <div class="meta-box icon-box">
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg"
                                            alt="Google" width="20" height="20" />
                                    </div>
                                </li>
                            <?php else: ?>
                                <li>
                                    <div class="meta-box icon-box">
                                        <img src="https://img.icons8.com/?size=256&id=17950&format=png"
                                            width="20" height="20"
                                            alt="Google Suspenso"
                                            style="filter: invert(63%) sepia(43%) saturate(6755%) hue-rotate(329deg) brightness(102%) contrast(96%);">
                                    </div>
                                </li>
                            <?php endif; ?>
                        </ul>

                        <!-- Conteúdo oculto que expande ao clicar -->
                        <div id="collapse-<?= $index ?>" class="collapse-content">
                            <p><strong>Telefone:</strong> <?= htmlspecialchars($user['phone'] ?? 'Não disponível') ?></p>
                            <p><strong>Status Google:</strong> <?= empty($user['suspended']) ? 'Conta Ativa' : 'Conta Suspensa' ?></p>
                            <p><strong>Último Login:</strong> <?= !empty($user['lastLogin']) ? htmlspecialchars(DateHelper::formatDateTime($user['lastLogin'])) : 'Sem registro' ?></p>
                            <p><strong>Última atividade:</strong> <?= !empty($user['lastActivity']) ? htmlspecialchars($user['lastActivity']) : 'Sem registro' ?></p>
                            <p><strong>Status Jira:</strong> <?= !empty($user['jiraAccountStatus']) ? htmlspecialchars($user['jiraAccountStatus']) : 'Sem conta Jira' ?></p>
                            <p><strong>Último Login Jira:</strong> <?= !empty($user['jiraLastActive']) ? htmlspecialchars(DateHelper::formatDateTime($user['jiraLastActive'])) : 'Sem registro' ?></p>

                            <!-- Mostrar produtos Jira acessíveis -->
                            <?php if (!empty($user['jiraProductAccess']) && is_array($user['jiraProductAccess'])): ?>
                                <p><strong>Serviços Jira:</strong></p>
                                <ul>
                                    <?php foreach ($user['jiraProductAccess'] as $product): ?>
                                        <li><?= htmlspecialchars($product) ?></li> <!-- Agora $product já é um nome, não um array -->
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>

                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>

<?php
        return ob_get_clean();
    }
}
