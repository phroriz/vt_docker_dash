<?php

namespace src\helpers;

class SectionHelper
{
    public static function start(string $class , string $title, string $viewAllUrl = null): void
    {
        ?>
        <section class="<?= htmlspecialchars($class) ?>">
        <?php if($viewAllUrl): ?>
            <div class="section-title-wrapper">
                <h2 class="section-title"><?= htmlspecialchars($title) ?></h2>
                
                <button class="btn btn-link icon-box" onclick="window.location.href='<?= htmlspecialchars($viewAllUrl) ?>'">
                    <span>View All</span>
                    <span class="material-symbols-rounded icon" aria-hidden="true">arrow_forward</span>
                </button>
              
            </div>
            <?php endif ?>
        <?php
    }

    public static function end(): void
    {
        ?>
        </section>
        <?php
    }
}
