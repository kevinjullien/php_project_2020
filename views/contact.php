<section id="content">

    <section id="contenu">
        <h3>Bienvenue sur la page de contact.</h3>
        <br><br>

        <div id="notification"><?php echo $message ?></div>
        <div class="formulaire">
            <form action="index.php?action=contact" method="post">

                <div class="form-group">
                    <label for="exampleFormControlInput1">Votre email :</label>
                    <?php if (!empty($_SESSION['authenticated'])) { ?>
                        <input type="email" class="form-control" name="email" id="email" aria-describedby="emailHelp"
                               value="<?php echo $_SESSION['email'] ?>" disabled>
                    <?php }
                    else {?>
                    <input type="email" class="form-control" name="email" id="email"
                           aria-describedby="emailHelp" placeholder="nom@exemple.com">
                    <?php } ?>
                    <small id="emailHelp" class="form-text text-muted">
                        <span class="blinking"><?php if (isset($mailHelp)) echo $mailHelp ?></span>
                    </small>
                </div>

                <div class="form-group">
                    <label for="message">Votre message :</label>
                    <textarea class="form-control" name="message" id="message" aria-describedby="messageHelp" rows="3"><?php echo $messageFieldContent ?></textarea>
                    <small id="imageHelp" class="form-text text-muted">
                        <span class="blinking"><?php if (isset($messageHelp)) echo $messageHelp ?></span>
                    </small>
                </div>

                <button type="submit" class="btn btn-secondary">Envoyer</button>

            </form>
        </div>
    </section>

</section>