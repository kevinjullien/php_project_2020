<form enctype="multipart/form-data" action="index.php?action=addEdit" method="post">
    <?php if (isset($_POST['selectedVenueId'])) { ?>
        <input type="hidden" name="selectedVenueId" value="<?php echo $_POST['selectedVenueId'] ?>">
    <?php } else { ?>
        <input type="hidden" name="choice" value="<?php echo 'add' ?>">
    <?php } ?>


    <!-- Elements of venue -->
    <div class="form-row">
        <div class="col-xl-4 mb-5">
            <label for="Title">Titre*</label>
            <input type="text" class="form-control" id="Title" name="title"
                   placeholder="<?php if (isset($_POST['selectedVenueId'])) echo $venue->html_getTitle();
                                        else echo 'ex: Atomium' ?>"
                   value="<?php if (isset($_POST['choice']) && isset($formInputs) && isset($formInputs['content']['title'])) echo $formInputs['content']['title'] ?>"
                   aria-describedby="titleHelp">
            <small id="titleHelp" class="form-text text-muted">
                <span class="blinking"><?php if (isset($formInputs['messages']['title'])) echo $formInputs['messages']['title'] ?></span>
            </small>
        </div>

        <div class="col-xl-8 mb-5">
            <div class="col-xl-4 mb-5">

                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="photoOfVenue" name="userfile"
                           aria-describedby="imageHelp">
                    <label class="custom-file-label" for="photoOfVenue">Sélectionnez votre image*</label>
                    <span class="smalltext">Le fichier sélectionné s'affichera au survol de la souris</span>
                    <small id="imageHelp" class="form-text text-muted">
                        <span class="blinking">
                    <?php if (isset($formInputs['messages']['photo'])) echo $formInputs['messages']['photo'] ?></span>
                    </small>
                </div>

            </div>

            <?php if (isset($_POST['selectedVenueId'])) { ?>

                <div id="imagepreview">
                    <img src="<?php echo $venue->html_getPhoto() ?>" alt="Photo actuelle" class="img-fluid"
                         alt="Responsive image" title="Photo actuelle">
                </div>

            <?php } ?>
        </div>
    </div>
    <br>

    <!-- Keywords -->
    <h4>Mots-clefs</h4>
    <p>Ils sont bien entendu optionnels mais vous pouvez en choisir jusqu'à 3 dans notre sélection</p>
    <br>

    <?php for ($i = 0; $i < 3; $i++) { ?>
        <select class="custom-select col-md-3 mb-5" name="<?php echo $i ?>">
            <option <?php if (!isset($_POST[$i])) echo 'selected' ?> value="<?php null ?>">Vous pouvez choisir un mot-clef
                ci-dessous
            </option>
            <?php foreach ($keywords as $j => $keyword) { ?>
                <option name="<?php echo $i ?>" value="<?php echo $j ?>"
                    <?php if (isset($_POST[$i]) && $_POST[$i] == $j) echo 'selected';
                            elseif (!isset($_POST[$i]) && isset($keywordsOfSelectedVenue) && isset($keywordsOfSelectedVenue[$i]) && $keywordsOfSelectedVenue[$i] == $j) echo 'selected' ?>><?php echo $keyword ?></option>
            <?php } ?>


        </select>
    <?php } ?>
    <br>

    <!-- Address -->
    <h4>Adresse</h4>
    <br>
    <div class="form-row">

        <div class="col-xl-4 mb-5">
            <label for="country">Pays*</label>
            <input type="text" class="form-control" id="country"
                   placeholder="<?php if (isset($_POST['selectedVenueId'])) echo $venue->getAddress()->html_getCountry();
                                        else echo 'ex: Belgique' ?>" name="country"
                   value="<?php if (isset($_POST['choice']) && isset($formInputs) && isset($formInputs['content']['country'])) echo $formInputs['content']['country'] ?>"
                   aria-describedby="countryHelp">
            <small id="countryHelp" class="form-text text-muted">
                <span class="blinking"><?php if (isset($formInputs['messages']['country'])) echo $formInputs['messages']['country'] ?></span>
            </small>
        </div>

        <div class="col-xl-4 mb-5">
            <label for="city">Ville*</label>
            <input type="text" class="form-control" id="city" name="city"
                   placeholder="<?php if (isset($_POST['selectedVenueId'])) echo $venue->getAddress()->html_getCity();
                                        else echo 'ex: Bruxelles' ?>"
                   value="<?php if (isset($_POST['choice']) && isset($formInputs) && isset($formInputs['content']['city'])) echo $formInputs['content']['city'] ?>"
                   aria-describedby="cityHelp">
            <small id="cityHelp" class="form-text text-muted">
                <span class="blinking"><?php if (isset($formInputs['messages']['city'])) echo $formInputs['messages']['city'] ?></span>
            </small>
        </div>

        <div class="col-xl-4 mb-5">
            <label for="postalcode">Code postal*</label>
            <input type="text" class="form-control" id="postalcode" name="postal_code"
                   placeholder="<?php if (isset($_POST['selectedVenueId'])) echo $venue->getAddress()->html_getPostalCode();
                                        else echo 'ex: 1000' ?>"
                   value="<?php if (isset($_POST['choice']) && isset($formInputs) && isset($formInputs['content']['postal_code'])) echo $formInputs['content']['postal_code'] ?>"
                   aria-describedby="postalcodeHelp">
            <small id="postalcodeHelp" class="form-text text-muted">
                <span class="blinking"><?php if (isset($formInputs['messages']['postal_code'])) echo $formInputs['messages']['postal_code'] ?></span>
            </small>
        </div>

    </div>

    <div class="form-row">

        <div class="col-xl-4 mb-5">
            <label for="street">Rue*</label>
            <input type="text" class="form-control" id="street" name="street"
                   placeholder="<?php if (isset($_POST['selectedVenueId'])) echo $venue->getAddress()->html_getStreet();
                                        else echo 'ex:Rue de la pinte' ?>"
                   value="<?php if (isset($_POST['choice']) && isset($formInputs) && isset($formInputs['content']['street'])) echo $formInputs['content']['street'] ?>"
                   aria-describedby="streetHelp">
            <small id="streetHelp" class="form-text text-muted">
                <span class="blinking"><?php if (isset($formInputs['messages']['street'])) echo $formInputs['messages']['street'] ?></span>
            </small>
        </div>

        <div class="col-xl-4 mb-5">
            <label for="number">Numéro*</label>
            <input type="text" class="form-control" id="number" name="number"
                   placeholder="<?php if (isset($_POST['selectedVenueId'])) echo $venue->getAddress()->html_getNumber();
                                        else echo 'ex: 42' ?>"
                   value="<?php if (isset($_POST['choice']) && isset($formInputs) && isset($formInputs['content']['number'])) echo $formInputs['content']['number'] ?>"
                   aria-describedby="numberHelp">
            <small id="numberHelp" class="form-text text-muted">
                <span class="blinking"><?php if (isset($formInputs['messages']['number'])) echo $formInputs['messages']['number'] ?></span>
            </small>
        </div>

    </div>
    <div class="form-row">

        <!-- If editing, existing values of latitude and longitude will be as value because they are optional -> easy to erase if wanted -->
        <div class="col-xl-4 mb-5">
            <label for="latitude">Latitude</label>
            <input type="text" class="form-control" id="latitude" name="latitude"
                   placeholder="ex: 50.849516"
                   value="<?php if (isset($_POST['selectedVenueId'])) echo $venue->getAddress()->html_getLatitude();
                                elseif (isset($_POST['choice']) && isset($formInputs) && isset($formInputs['content']['latitude'])) echo $formInputs['content']['latitude'] ?>"
                   aria-describedby="latitudeHelp">
            <small id="latitudeHelp" class="form-text text-muted">
                <span class="blinking"><?php if (isset($formInputs['messages']['latitude'])) echo $formInputs['messages']['latitude'] ?></span>
            </small>
        </div>

        <div class="col-xl-4 mb-5">
            <label for="longitude">Longitude</label>
            <input type="text" class="form-control" id="longitude" name="longitude"
                   placeholder="ex: 4.451087"
                   value="<?php if (isset($_POST['selectedVenueId'])) echo $venue->getAddress()->html_getLongitude();
                                elseif (isset($_POST['choice']) && isset($formInputs) && isset($formInputs['content']['longitude'])) echo $formInputs['content']['longitude'] ?>"
                   aria-describedby="longitudeHelp">
            <small id="longitudeHelp" class="form-text text-muted">
                <span class="blinking"><?php if (isset($formInputs['messages']['longitude'])) echo $formInputs['messages']['longitude'] ?></span>
            </small>
        </div>

    </div>

        <br>
        <!-- Event start/end time -->
        <h4>Évènement</h4>
        <p>Complétez ces deux champs s'il s'agit d'un évènement, sinon laissez les vides</p>
        <br>
        <div class="form-row">

            <div class="col-xl-4 mb-5">
                <label for="start">Début:</label>
                <input type="datetime-local" id="start" class="form-control" name="start_datetime"
                       value="<?php if (isset($venue)) echo $eventStart ?>"
                       aria-describedby="startHelp">
                <small id="startHelp" class="form-text text-muted">
                    <span class="blinking"><?php if (isset($formInputs['messages']['start_datetime'])) echo $formInputs['messages']['start_datetime'] ?></span>
                </small>
            </div>

            <div class="col-xl-4 mb-5">
                <label for="end">Fin:</label>
                <input type="datetime-local" id="end" class="form-control" name="end_datetime"
                       value="<?php if (isset($venue)) echo $eventEnd ?>"
                       aria-describedby="endHelp">
                <small id="endHelp" class="form-text text-muted">
                    <span class="blinking"><?php if (isset($formInputs['messages']['end_datetime'])) echo $formInputs['messages']['end_datetime'] ?></span>
                </small>
            </div>
        </div>

    <br>
    <p class="smalltext">(*) champs à compléter</p>
    <br>

    <button type="submit" class="btn btn-dark"
            name="<?php if (isset($_POST['selectedVenueId'])) echo 'updateVenue';
                        else echo 'addVenue' ?>">Envoyer
    </button>
</form>