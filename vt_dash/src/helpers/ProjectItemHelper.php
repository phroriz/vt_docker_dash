<?php

namespace src\helpers;

class ProjectItemHelper
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
        //  Definindo variáveis do projeto
        $id = $project['id'] ?? '';
        $key = $project['key'] ?? '';
        $title = htmlspecialchars($project['title'] ?? 'Projeto Desconhecido');
        $description = htmlspecialchars($project['description'] ?? 'Sem descrição disponível.');
        $date = $project['date'] ?? date('Y-m-d');
        $dateFormatted = date("M d, Y", strtotime($date));
        $progress = (int) ($project['progress'] ?? 0);
        $badge = htmlspecialchars($project['badge'] ?? 'General');
        $badgeColor = $project['badgeColor'] ?? 'blue';
        $avatarProject = $project['avatarProject'] ?? [];

        //  Verifica se o projeto tem um grupo ou pasta vinculada
        $googleGroup = $project['groupName'] ?? null;
        $hasGoogleGroup = !empty($googleGroup);
        $googleDrive = $project['folderName'] ?? null;
        $hasGoogleDrive = !empty($googleDrive);

        //  Dados do Google Drive
        $folderId = $project['folderId'] ?? null;
        $fileCount = $project['fileCount'] ?? 0;
        $totalSizeGB = $project['totalSizeGB'] ?? 0;
        $lastUpdated = $project['lastUpdated'] ?? 'Não disponível';

        ob_start();
    ?>

        <div class="card profile-card">

            <!-- 🔥 Botão de Menu -->
            <a href="<?= $base . '/project/' . $key ?>" class="card-menu-btn icon-box">
                <span class="material-symbols-rounded icon" aria-hidden="true">visibility</span>
            </a>

            <!-- 🔥 Avatar + Nome do Projeto -->
            <div class="profile-card-wrapper">
                <figure class="card-avatar">
                    <img src="<?= htmlspecialchars($avatarProject['src']) ?>"
                        alt="<?= htmlspecialchars($avatarProject['alt']) ?>"
                        width="48" height="48">
                </figure>

                <div>
                    <p class="card-title"><?= $title ?></p>
                    <p class="card-subtitle"><?= $description ?></p>
                </div>
            </div>

            <!-- 🔥 Informações do Projeto -->
            <ul class="contact-list">
                <li>
                    <span class="contact-link icon-box">
                        <span class="material-symbols-rounded icon">date_range</span>
                        <p class="text"><?= $dateFormatted ?></p>
                    </span>
                </li>
                <?php if ($hasGoogleGroup): ?>
                    <li>
                        <span class="contact-link icon-box">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg"
                                alt="Google Group" width="18" height="18">
                            <p class="text"><?= htmlspecialchars($googleGroup) ?></p>
                        </span>
                    </li>
                <?php endif; ?>

   
            </ul>

            <!-- 🔥 Divider -->
            <div class="divider card-divider"></div>

            <!-- 🔥 Progresso e Informações do Drive -->
            <ul class="progress-list">


            </ul>

        </div>
        <div class="card-wrapper">

            <div class="card task-card">

                <div class="card-icon icon-box green">
                <span class="contact-link icon-box">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/1/12/Google_Drive_icon_%282020%29.svg"
                                alt="Google Drive" width="18" height="18">
                            <p class="text"><a href="https://drive.google.com/drive/folders/<?= $folderId ?>" target="_blank">
                            <?=   $googleDrive ?>
                                </a></p>
                        </span>
                </div>

                <div style="margin-top: 5px;">
                    <?php if ($hasGoogleDrive): ?>
                        <div class="">
                            <p class="progress-title">Arquivos no Google Drive</p>
                            <data class="progress-data" value="<?= $fileCount ?>"><?= $fileCount ?> arquivos</data>
                            <p class="progress-title">Tamanho Total</p>
                            <data class="progress-data"><?= number_format($totalSizeGB, 2) ?> GB</data>
                            <p class="progress-title">Última Atualização</p>
                            <data class="progress-data"><?= htmlspecialchars($lastUpdated) ?></data>
                        </div>
                        <div class="progress-label">

                        </div>
                    <?php endif; ?>

                </div>

            </div>

        </div>
        <div class="card-wrapper">

            <div class="card task-card">

                <div class="card-icon icon-box green">
                    <span class="material-symbols-rounded  icon">task_alt</span>
                </div>

                <div>
                    <data class="card-data" value="21">21</data>
                </div>
            </div>
        </div>


<?php
        return ob_get_clean();
    }
}
