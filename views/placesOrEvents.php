<section id="content">
    <h2 class="pagetitle">Bienvenue sur la page des <?php if($venuesType == 'E') echo 'évènements';
                                                          else echo 'lieux' ?></h2>


<?php require_once(VIEW_PATH.'venuesDisplay.php') ?>

</section>