<!DOCTYPE html>
<html lang="fr">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh"  crossorigin="anonymous">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="views/css/custom.css">

    <title>PlaceTo.be</title>
</head>

<body>
<header class="sticky-top">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <section class="navbar-collapse collapse w-100 order-1 order-lg-0 dual-collapse2">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php?action=searchWithKeywords">Les mots-clefs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?action=places">Les lieux</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?action=events">Les évènements</a>
                </li>
                <?php if (!empty($_SESSION['authenticated'])) { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?action=addEdit">Tes lieux et évènements</a>
                    </li>
                <?php } ?>
            </ul>
        </section>

        <section class="mx-auto order-0">
            <a class="navbar-brand mx-auto" href="index.php"><h1>PlaceTo.be</h1></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".dual-collapse2">
                <span class="navbar-toggler-icon"></span>
            </button>
        </section>

        <section class="navbar-collapse collapse w-100 order-3 dual-collapse2">
            <ul class="navbar-nav ml-auto">
                <?php if (!empty($_SESSION['authenticated'])) { ?>
                    <li class="nav-item">
                        <!-- Disabled but could be enabled if a member page is created -->
                        <a class="nav-link disabled" href="index.php" aria-disabled="true">
                            Bonjour <?php echo $_SESSION['username'] ?> </a>
                    </li>
                <?php } ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown2" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Menu
                    </a>
                    <section class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown2">
                        <a class="dropdown-item" href="index.php?action=contact">Contact</a>
                        <section class="dropdown-divider"></section>
                        <?php if (empty($_SESSION['authenticated'])) { ?>
                            <a class="dropdown-item" href="index.php?action=login">Se connecter</a>
                            <a class="dropdown-item" href="index.php?action=registration">S'inscrire</a>
                        <?php } else {
                            if ($_SESSION['admin'] == true) { ?>
                                <a class="dropdown-item" href="index.php?action=membersList">Liste des membres</a>
                            <?php } ?>
                            <a class="dropdown-item" href="index.php?action=logout">Se déconnecter</a>
                        <?php } ?>
                    </section>
                </li>
            </ul>
        </section>
    </nav>
</header>