<?php

namespace src\helpers;

class CardHelper
{
  public static function rowStart(string $title, array $button = null, int $rating = null): string
{
  $starsHtml = '';

  if ($rating !== null) {
    $starsHtml = '<div class="star-rating" data-score="' . $rating . '">';
    for ($i = 1; $i <= 5; $i++) {
      $selectedClass = ($i <= $rating) ? 'selected' : '';
      $starsHtml .= '<span class="star ' . $selectedClass . '" data-value="' . $i . '">&#9733;</span>';
    }
    $starsHtml .= '</div>';
  }

  $html = <<<HTML
<!-- [ Main Content ] start -->
<div class="row">
  <!-- [ sample-page ] start -->
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5>{$title} </h5>
        {$starsHtml}
HTML;

  if ($button && isset($button['id'], $button['target'])) {
    $id = htmlspecialchars($button['id'], ENT_QUOTES);
    $target = htmlspecialchars($button['target'], ENT_QUOTES);

    $html .= <<<HTML
  <a href="#"
     id="{$id}"
     class="avtar avtar-xs btn btn-link-primary"
     data-bs-toggle="modal"
     data-bs-target="#{$target}">
    <i class="ti ti-circle-plus f-18"></i>
  </a>
HTML;
  }

  $html .= <<<HTML
      </div>
      <div class="card-body">
HTML;

  return $html;
}


  public static function rowEnd(): string
  {
    return <<<HTML
            </div>
          </div>
        </div>
        <!-- [ sample-page ] end -->
      </div>
      <!-- [ Main Content ] end -->


  <!-- [ Main Content ] end -->
HTML;
  }


  /**
   * Gera o HTML de um card para dashboards ou indicadores.
   *
   * @param int $value Valor a ser exibido (ex: 145)
   * @param string $label Texto descritivo (ex: 'dashboard(s)')
   * @param string $icon Classe do ícone (ex: 'ti ti-dashboard')
   * @param string $iconColor Classe de cor (ex: 'text-danger')
   * @return string HTML do card
   */
  public static function renderCard(
    string $value,
    string $label,
    string $icon,
    string $iconColor = 'text-primary'
  ): string {
    return <<<HTML
<div class="col-lg-3 col-md-6">
  <div class="card">
      <div class="card-body">
          <div class="row align-items-center">
              <div class="col-8">
                  <h3 class="mb-1">{$value}</h3>
                  <p class="mb-0">{$label}</p>
              </div>
              <div class="col-4 text-end">
                  <i class="{$icon} {$iconColor} f-36"></i>
              </div>
          </div>
      </div>
  </div>
</div>
HTML;
  }
}
