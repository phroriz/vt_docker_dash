<?php

namespace src\helpers;

class ProjectsItemHelper
{
    public static function start()
    {
        ob_start(); ?>
        <ul class="project-list">
        <?php
        return ob_get_clean();
    }

    public static function end()
    {
        ob_start(); ?>
        </ul>
    <?php
        return ob_get_clean();
    }

    public static function renderProjectCard(array $project, string $base): string
    {
        // Definindo variáveis a partir do array recebido
        $id = $project['id'] ?? '';
        $key = $project['key'] ?? '';
        $title = htmlspecialchars($project['title'] ?? 'Untitled Project');
        $description = htmlspecialchars($project['description'] ?? 'No description available.');
        $date = $project['date'] ?? date('Y-m-d');
        $dateFormatted = date("M d, Y", strtotime($date));
        $progress = (int) ($project['progress'] ?? 0);
        $badge = htmlspecialchars($project['badge'] ?? 'General');
        $badgeColor = $project['badgeColor'] ?? 'blue';
        $avatarProject = $project['avatarProject'] ?? [];
        $avatars = $project['avatars'] ?? [];

        // Verifica se o projeto tem um grupo do Google vinculado
        $googleGroup = $project['groupName'] ?? null;
        $hasGoogleGroup = !empty($googleGroup);
        // Verifica se o projeto tem um grupo do Google vinculado
        $googleDrive = $project['folderName'] ?? null;
        $hasGooglerive = !empty($googleDrive);

        ob_start();
    ?>
        <li class="project-item">
            <div class="card project-card">

                <!-- Botão de Menu -->
                <a href="<?= $base . '/project/' . $key ?>" class="card-menu-btn icon-box">
                    <span class="material-symbols-rounded icon" aria-hidden="true">visibility</span>
                    <span class="ctx-menu-text"></span>
                </a>

                <!-- Data -->
                <time class="card-date" datetime="<?= $date ?>"><?= $dateFormatted ?></time>

                <!-- Título com Ícone do Google se houver grupo vinculado -->
                <div class="d-flex align-items-center" style=" display:flex; margin-bottom:10px">
                    <a href="#" class="flex-shrink-0 me-2">
                        <img src="<?= htmlspecialchars($avatarProject['src']) ?>"
                            alt="<?= htmlspecialchars($avatarProject['alt']) ?>"
                            width="40" height="40"
                            style="border-radius: 5px; border: 1px solid rgb(233, 233, 233);">
                    </a>
                    <h3 class="mb-0 flex-grow-1" style="margin-left: 5px;margin-top:7px">
                        <?= $title ?>

                    </h3>
                </div>

                <!-- Badge -->
                <div class="card-badge <?= $badgeColor ?>"><?= $badge ?></div>

                <!-- Descrição -->
                <p class="card-text">
                    <?= $description ?>
                </p>

                <hr />
                <!-- Progresso -->
                <div class="card-progress-box mt-2">
                    <div class="progress-label d-flex align-items-center">
                        <img 
                            src="https://cdn.worldvectorlogo.com/logos/jira-1.svg"
                            alt="Jira"
                            width="18" height="18">

                        <?php if ($hasGoogleGroup): ?>
                            <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg"
                                alt="Google Group"
                                width="18" height="18">
                        <?php endif; ?>

                        <?php if ($hasGooglerive): ?>
                            <img src="https://upload.wikimedia.org/wikipedia/commons/1/12/Google_Drive_icon_%282020%29.svg"
                                alt="Google Drive"
                                width="18" height="18">
                        <?php endif; ?>
                    </div>
                </div>


            </div>
        </li>
<?php
        return ob_get_clean();
    }
}
