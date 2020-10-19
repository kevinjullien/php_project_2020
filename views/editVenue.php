<section id="addedittitle">
<h3>Modifier un lieu ou un évènement</h3>
</section>
<?php if (!isset($_POST['selectedVenueId'])) { ?>
<section>

        <form action="index.php?action=addEdit" method="post">
            <table class="table">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">Titre</th>
                    <th scope="col">Ville</th>
                    <th scope="col">Pays</th>
                    <th scope="col">Début</th>
                    <th scope="col">Fin</th>
                    <th scope="col">Choisir</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($venues as $i => $venue) { ?>

                    <tr>
                        <th scope="row"><?php echo $venue->html_getTitle() ?></th>
                        <td><?php echo $venue->getAddress()->html_getCity() ?></td>
                        <td><?php echo $venue->getAddress()->html_getCountry() ?></td>
                        <td><?php if ($venue->html_getStartDatetime() != null) echo $venue->html_getStartDatetime() ?></td>
                        <td><?php if ($venue->html_getEndDatetime() != null) echo $venue->html_getEndDatetime() ?></td>
                        <td>
                            <button type="submit" class="btn btn-light" name="selectedVenueId"
                                    value="<?php echo $venue->html_getiD() ?>">Sélectionner
                            </button>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </form>
</section>

    <?php } ?>
    <section>
</section>
