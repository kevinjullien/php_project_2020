<section id="content">
    <section>
        <h2>Cherche un lieu ou un évènement en cliquant sur un mot-clé.</h2>

        <section id="addoredit">
            <h4></h4>
            <br>
            <form action="index.php?action=searchWithKeywords" method="post">
                <?php foreach ($keywords as $i => $keyword) { ?>
                    <button type="submit" class="btn btn-secondary btn-lg" value="<?php echo $keyword?>" name="keyword"><?php echo $keyword?></button>
                <?php } ?>
            </form>
        </section>
    </section>

    <section>
        <?php if (isset($_POST['keyword'])) require_once(VIEW_PATH.'venuesDisplay.php') ?>
    </section>

</section>