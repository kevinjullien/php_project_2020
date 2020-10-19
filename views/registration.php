<section id="content" class="centeredsection">
    <h2>Bienvenue, futur compagnon de voyage!</h2>
    <section id="registrationarea">
        <form action="index.php?action=registration" method="post">
            <h3><?php echo $message ?></h3>

            <div class="form-group row">
                <label for="firstname" class="col-lg-3 col-form-label">Prénom*</label>
                <div class="col-lg-6">
                    <input type="text" name="firstname" id="firstname" placeholder="Jean"
                           value="<?php if (isset($formInputs['content']['firstname'])) echo $formInputs['content']['firstname'] ?>"
                           aria-describedby="firstnamehelp">
                    <small id="firstnamehelp" class="form-text text-muted">
                        <span class="blinking"><?php if (isset($formInputs['messages']['firstname'])) echo $formInputs['messages']['firstname'] ?></span>
                    </small>
                </div>
            </div>

            <div class="form-group row">
                <label for="lastname" class="col-lg-3 col-form-label">Nom*</label>
                <div class="col-lg-6">
                    <input type="text" name="lastname" id="lastname" placeholder="Cérien"
                           value="<?php if (isset($formInputs['content']['lastname'])) echo $formInputs['content']['lastname'] ?>"
                           aria-describedby="lastnamehelp">
                    <small id="lastnamehelp" class="form-text text-muted">
                        <span class="blinking"><?php if (isset($formInputs['messages']['lastname'])) echo $formInputs['messages']['lastname'] ?></span>
                    </small>
                </div>
            </div>

            <div class="form-group row">
                <label for="email" class="col-lg-3 col-form-label">E-mail*</label>
                <div class="col-lg-6">
                    <input type="email" name="email" id="email"
                           placeholder="jean.cerien@(student.)vinci.be"
                           value="<?php if (isset($formInputs['content']['email'])) echo $formInputs['content']['email'] ?>"
                           aria-describedby="emailhelp">
                    <small id="emailhelp" class="form-text text-muted">
                        <span class="blinking"><?php if (isset($formInputs['messages']['email'])) echo $formInputs['messages']['email'] ?></span>
                    </small>
                </div>
            </div>

            <div class="form-group row">
                <label for="password" class="col-lg-3 col-form-label">Mot de passe*</label>
                <div class="col-lg-6">
                    <input type="password" name="password" id="password" aria-describedby="passwordhelp">
                    <small id="passwordhelp" class="form-text text-muted">
                        <span class="blinking"><?php if (isset($formInputs['messages']['password'])) echo $formInputs['messages']['password'] ?></span>
                    </small>
                </div>
            </div>

            <div class="form-group row">
                <label for="passwordbis" class="col-lg-3 col-form-label">Confirmation mot de passe*</label>
                <div class="col-lg-6">
                    <input type="password" name="passwordbis" id="passwordbis">
                </div>
            </div>

            <br>
            <p class="smalltext">(*) champs à compléter</p>
            <br>
            <p><label for="send" class="col-lg-3 col-form-label"></label>
                <button type="submit" id="send" class="btn btn-primary" name="memberRegistration">Devenir membre</button>
            </p>

        </form>
    </section>
</section>