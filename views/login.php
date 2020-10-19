<section id="content">

    <h2>Connectez-vous pour plus de découvertes ! </h2>
    <br>
    <h3><?php echo $message ?></h3>

    <section id="registrationarea">
        <form action="index.php?action=login" method="post">

            <!--            https://getbootstrap.com/docs/4.4/components/forms/-->

            <div class="form-group row">
                <label for="email" class="col-lg-3 col-form-label">Email*</label>
                <div class="col-lg-6">
                    <input input type="text" name="email" id="email" placeholder="prenom.nom@vinci.be // prenom.nom@student.vinci.be"
                           value="<?php echo $emailinput ?>" aria-describedby="emailhelp">
                    <small id="emailhelp" class="form-text text-muted">
                        <span class="blinking"><?php if (isset($logininfo['emailmessage'])) echo $logininfo['emailmessage'] ?></span>
                    </small>
                </div>
            </div>

            <div class="form-group row">
                <label for="password" class="col-lg-3 col-form-label">Mot de passe*</label>
                <div class="col-lg-6">
                    <input input type="password" name="password" id="password" aria-describedby="passwordhelp">
                    <small id="passwordhelp" class="form-text text-muted">
                        <span class="blinking"><?php if (isset($logininfo['passwordmessage'])) echo $logininfo['passwordmessage'] ?></span>
                    </small>
                </div>
            </div>

            <br>
            <p id="smalltext">(*) champs à compléter</p>
            <br>
            <p><label for="send" class="col-lg-3 col-form-label"></label> <input type="submit" value="Se connecter" name="loginMember" id="send">
            </p>

        </form>
    </section>
</section>