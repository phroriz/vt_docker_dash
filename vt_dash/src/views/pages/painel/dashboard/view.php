<meta name="dashboard-hash" content="<?= isset($dash->hash) ? $dash->hash : '' ?>">
<?php

use src\helpers\CardHelper;
use src\helpers\CommentHelper;

?>

<?php if (!empty($dash)): ?>
  <?= CardHelper::rowStart($dash->title, null, $score); ?>



  <iframe id="dash-frame" title="Accerte" src="<?= $dash->src ?>" width="1500" height="700" allowfullscreen="true"></iframe>



  <?= CardHelper::rowEnd(); ?>
  <script>
    // Executa quando o DOM estiver totalmente carregado
    document.addEventListener("DOMContentLoaded", function() {
      const botao = document.querySelector("#embedWrapperID > div.logoBarWrapper > logo-bar > div > div > div > span > span > logo-bar-social-sharing > span > button");

      if (botao) {
        botao.remove(); // Remove o botão
        console.log("Botão removido ✔️");
      } else {
        console.warn("Botão não encontrado 🤔");
      }
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/dash-client@2.19.0/dist/dash.js"></script>

<?php else: ?>
  <div class="alert alert-danger" role="alert">
    <h4 class="alert-heading">Acesso Restrito</h4>
    <p>Seu acesso ao dashboard está indisponível. Isso pode ter ocorrido devido à expiração do seu vínculo ou à inativação do painel.</p>
    <p>Por favor, entre em contato com o responsável para mais informações ou para solicitar a reativação do acesso.</p>
    <hr>
    <p class="mb-0">Em caso de dúvidas, nossa equipe está à disposição para ajudar.</p>
  </div>
<?php endif ?>


<?= CardHelper::rowStart(''); ?>
<ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active text-uppercase" id="comment-tab" data-bs-toggle="tab" href="#comment" role="tab" aria-controls="comment" aria-selected="true">Comentarios</a>
  </li>
  <li class="nav-item">
    <a class="nav-link text-uppercase" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Avaliações</a>
  </li>
</ul>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="comment" role="tabpanel" aria-labelledby="comment-tab">
    <?= CommentHelper::renderComments($comments) ?>
    <div class="input-group mb-3" style="max-width: 100%;">
      <input
        type="text"
        class="form-control"
        id="comment-input"
        placeholder="Escreva um comentário..."
        aria-label="Comentário"
        aria-describedby="btn-comment">
      <button class="btn btn-primary" type="button" id="btn-comment">
        Comentar
      </button>
    </div>
  </div>
</div>




<?= CardHelper::rowEnd(); ?>


<script src="<?= $base . '/assets/js/custom/dash-comment.js' ?>" defer></script>
<script src="<?= $base . '/assets/js/custom/dash-nps.js' ?>" defer></script>
<meta name="show-nps" content="<?= $checkNps ? 'true' : 'false' ?>">


