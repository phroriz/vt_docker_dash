<?php

namespace src\helpers;

class UserListHelper
{
    public static function renderUserList(array $users): string
    {
        // Se não houver usuários, retorna uma mensagem
        if (empty($users)) {
            return '<p>Nenhum usuário encontrado.</p>';
        }

        ob_start(); ?>
        <ul class="users-list">
            <?php foreach ($users as $user): ?>
                <li class="users-item">
                    <div class="card user-card">

                        <div class="card-input">
                            <div class="card-avatar-item">
                                <a href="#">
                                    <img src="<?= htmlspecialchars($user['avatar']) ?>" 
                                         alt="<?= htmlspecialchars($user['displayName']) ?>" 
                                         width="32" height="32">
                                </a>
                            </div>

                            <label class="user-labelb">
                                <div class="mt-1" style="margin-top: 8px;">
                                    <?= htmlspecialchars($user['displayName']) ?>
                                </div>
                            </label>
                        </div>

                        <div class="card-badge cyan radius-pill">
                            <?= htmlspecialchars($user['emailAddress']) ?>
                        </div>

                        <ul class="card-meta-list">
                            <li>
                                <div class="meta-box icon-box">
                                    <span class="material-symbols-rounded icon">list</span>
                                    <span>x/x</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php
        return ob_get_clean();
    }
}
