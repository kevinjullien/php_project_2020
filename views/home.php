<section id="content">
    <section class="container-fluid">
        <?php // Debug: phpinfo(); ?>

        <!-- A central area containing text on the left and a carousel of random venues on the right -->
        <section id="centralarea" class="row d-flex justify-content-center">
            <section id="textcentralareasection" class="col-xl-4">

                <h3>Démarre ta journée avec PlaceTo.be!</h3>
                <p>Trouve le lieu ou l'évènement qu'il te faut</p>


            </section>

            <!-- Carousel: random venues -->
            <?php if ($numberOfVenuesInCarousel != 0) { ?>

                <!-- Carousel small indicators below -->
                <div id="carouselIndicators" class="carousel slide col-xl-8" data-ride="carousel">
                    <ol class="carousel-indicators">
                        <li data-target="#carouselIndicators" data-slide-to="0" class="active"></li>
                        <?php for ($i = 1; $i < $numberOfVenuesInCarousel; $i++) { ?>
                            <li data-target="#carouselIndicators" data-slide-to="<?php echo $i ?>"></li>
                        <?php } ?>
                    </ol>

                    <!-- Carousel content -->
                    <div class="carousel-inner">
                        <?php foreach ($randomVenues as $i => $venue) { ?>

                            <div class="carousel-item <?php if ($i == 0) echo "active" ?>">
                                <img class="d-block w-100" src="<?php echo $venue->getPhoto() ?>"
                                     alt="<?php echo $venue->html_getTitle() ?>">

                                <!-- Title and city will be displayed on the photo for places only -->
                                <?php if ($venue->html_getType() == 'P') { ?>
                                    <div class="carousel-caption d-none d-sm-block">
                                        <h5 class="carouseltext"><?php echo $venue->html_getTitle() ?></h5>
                                        <p class="carouseltext"><?php echo $venue->getAddress()->html_getCity() ?></p>
                                    </div>
                                <?php } ?>

                            </div>
                        <?php } ?>

                    </div>

                    <!-- Carousel left(previous) and right(next) "arrows" -->
                    <a class="carousel-control-prev" href="#carouselIndicators" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Précédent</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselIndicators" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Suivant</span>
                    </a>
                </div>
            <?php } ?>

            <!-- End carousel -->
        </section>

        <br><br>
        <!-- top10-->
        <h2 class="pagetitle">Le top 10</h2>
        <?php require_once(VIEW_PATH.'venuesDisplay.php') ?>

    </section>
</section>