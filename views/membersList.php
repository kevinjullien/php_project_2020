<section id="content">
    <form action="?action=membersList" method="post">
        <table class="membersTable">
            <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php for ($i = 0; $i < count($members); $i++) { ?>
                <tr>
                    <td><span class="html"><?php echo $members[$i]->html_getFirstname() ?></span></td>
                    <td><?php echo $members[$i]->html_getLastname() ?></td>
                    <td><?php echo $members[$i]->html_getEmail() ?></td>
                    <?php if ($members[$i]->html_getActivate() == 0) {?>
                    <td><input type="submit" name="form_activate[<?php echo $members[$i]->html_getId() ?>]" value="Activer"></td>
                    <?php } else { ?>
                    <td><input type="submit" name="form_deactivate[<?php echo $members[$i]->html_getId() ?>]" value="Désactiver"></td>
                    <?php } ?>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </form>

</section>