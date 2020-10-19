<section id="content">
    <section>
        <h2>Bienvenue sur la page d'ajout et d'édition.</h2>

        <section id="addoredit">
            <h3><span class="blinking"><?php echo $message ?></span></h3>
            <h4>Que veux-tu faire?</h4>
            <br>
            <form action="index.php?action=addEdit" method="post">

                <button type="submit" class="btn btn-primary btn-lg" value="add" name="choice">Ajouter</button>
                <button type="submit" class="btn btn-secondary btn-lg" value="edit" name="choice">Modifier</button>

            </form>
        </section>
    </section>

    <section>
        <?php if (isset($_POST['choice']) && $message == '') {
            if ($_POST['choice'] == "add")
                require_once('addVenue.php');
            else
                require_once('editVenue.php');
            }
        if (isset($_POST['selectedVenueId'])  && $message == ''){
            require_once('addOrEditVenueForm.php');
        } ?>
    </section>
</section>