<?php

namespace src\helpers;

class CardHelper
{
    public static function rowStart(string $title): string
    {
        return <<<HTML
<!-- [ Main Content ] start -->
<div class="row">
  <!-- [ sample-page ] start -->
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header">
        <h5>{$title}</h5>
      </div>
      <div class="card-body">
HTML;
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
    </div>
  </div>
  <!-- [ Main Content ] end -->
HTML;
    }
}
