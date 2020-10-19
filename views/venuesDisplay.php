<section id="venuesDisplay" class="row d-flex justify-content-center">


    <?php foreach ($venues as $i => $venue) { ?>

        <!-- Card containing a venue -->
        <section class="card" id="card<?php echo $venue->getId() ?>" style="width: 25rem;">

            <!-- Card header as an image AND a button that triggers a pop-up with the image in full size -->
            <button type="button" class="btn" data-toggle="modal"
                    data-target="#imageModal<?php echo $i ?>">
                <img class="card-img-top" src="<?php echo $venue->getPhoto() ?>"
                     alt="top <?php echo $i + 1 ?>/10">
            </button>

            <!-- Pop-up image -->
            <!-- Images will have their original size MAX to avoid pixels, or will be reduce to a screen size if bigger -->
            <div class="modal fade" id="imageModal<?php echo $i ?>" tabindex="<?php echo $i ?>" role="dialog"
                 aria-labelledby="<?php echo $i ?>ImageLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"
                                id="<?php echo $i ?>ImageLabel"><?php echo $venue->html_getTitle() ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body realsizeimage">
                            <p>
                                <img class="img-fluid realsizeimage" alt="Uncrop image pop-up"
                                     src="<?php echo $venue->getPhoto() ?>">
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card content  -->
            <section class="card-body">
                <h5 class="card-title"><?php echo $venue->html_getTitle() ?></h5>
                <h6 class="card-subtitle mb-2 text-muted"><?php echo $venue->getAddress()->html_getCity() ?></h6>
                <p class="card-text">
                    <br>
                    <?php if ($venue->getType() == 'E') echo $venue->html_getStartDatetime();
                    else
                        echo "<br>" ?>
                    <br>
                    <?php if ($venue->getType() == 'E') echo $venue->html_getEndDatetime() ?>
                </p>

                <!-- Vote for authenticated members only but not for admins -->
                <?php if (isset($_SESSION['authenticated']) && $_SESSION['admin'] == false) {
                    if ($venue->getSubmitter()->getId() == $_SESSION['id_member']) { ?>

                        <!-- Image if venue's submitter is the member -->
                        <div class="voteicon">
                            <img src="<?php echo IMAGE_PATH ?>heart_full_black.png"
                                 title="Vote impossible pour un lieu/évènement vous appartenant" alt="Possédé">
                        </div>
                    <?php } elseif (empty($memberVotes[$venue->getId()])) { ?>

                        <!-- Vote button if not voted by the member -->
                        <div class="voteicon">
                            <form action="index.php<?php if (isset($_GET['action'])) echo '?action='.$_GET['action'] ?>" method="post">
                                <input type="hidden" name="id_venue" value="<?php echo $venue->html_getId() ?>">
                                <button type="submit" value="submitVote" name="submitVote" class="btn"
                                        formmethod="post">
                                    <img src="<?php echo IMAGE_PATH ?>heart_empty.png"
                                         title="Votez si vous aimez!" alt="Non voté">
                                </button>
                            </form>
                        </div>
                    <?php } else { ?>

                        <!-- Image only if already voted -->
                        <div class="voteicon">
                            <img src="<?php echo IMAGE_PATH ?>heart_full.png" title="Vous avez déjà voté!"
                                 alt="Voté">
                        </div>
                    <?php }
                } ?>

                <!-- Adress button that triggers a pop-up -->
                <button type="button" class="btn btn-primary " data-toggle="modal"
                        data-target="#modal<?php echo $i ?>">
                    Détails
                </button>

                <!-- Button that only shows up for admins in case they wanna delete a place or event -->
                <?php if (isset($_SESSION['authenticated']) && $_SESSION['admin'] == true) {?>
                <form action="index.php<?php if (isset($_GET['action'])) echo '?action='.$_GET['action'] ?>" method="post">
                    <input id="submitButton" type="submit" class="btn btn-secondary" name="form_delete[<?php echo $venue->getId() ?>]" value="Effacer">
                </form>
                <?php } ?>

                <!-- Address pop-up -->
                <div class="modal fade" id="modal<?php echo $i ?>" tabindex="<?php echo $i ?>" role="dialog"
                     aria-labelledby="<?php echo $i ?>Label" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"
                                    id="<?php echo $i ?>Label"><?php echo $venue->html_getTitle() ?></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>


                            <!-- Address pop-up content -->
                            <div class="modal-body">
                                <p>
                                    <?php echo $venue->getAddress()->html_getNumber() . " " . $venue->getAddress()->html_getStreet() ?>
                                    <br>
                                    <?php echo $venue->getAddress()->html_getPostalCode() . " " . $venue->getAddress()->html_getCity() ?>
                                    <br>
                                    <?php echo $venue->getAddress()->html_getCountry();

                                    if ($venue->getAddress()->getLatitude() != null && $venue->getAddress()->html_getLongitude() != null) { ?>
                                        <br>
                                        Latitude:  <?php echo $venue->getAddress()->html_getLatitude() ?>
                                        <br>
                                        Longitude: <?php echo $venue->getAddress()->html_getLongitude() ?>
                                    <?php } ?>
                                <p>Créateur: <?php echo $venue->getSubmitter()->html_getFirstname() ?></p>
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </section>
    <?php } ?>
</section>
<!-- End all venues section-->
</section>