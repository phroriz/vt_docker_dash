
<?php

use src\helpers\CardHelper;

?>



<?= CardHelper::rowStart($powerbi['title']); ?>



  <iframe id="powerbi-frame" title="Accerte" src="<?= $powerbi['src'] ?>" width="1500" height="700" allowfullscreen="true"></iframe>




<?= CardHelper::rowEnd(); ?>