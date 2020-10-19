<?php

session_start();
ob_start();
$time_start = microtime(true);


define('VIEW_PATH', 'views/');
define('IMAGE_PATH', 'views/images/');
define('EMAIL', 'admin@placeToBe.be');

$date = date("j/m/Y");


function loadClass($classe)
{
    require 'models/' . $classe . '.class.php';
}

spl_autoload_register('loadClass');

$db = Db::getInstance();

$keywords = $db->select_keywords();

#Updates privileges and activation status if a member is authenticated
if (!empty($_SESSION['authenticated'])){
    $user = $db->select_member_by_id($_SESSION['id_member']);
    if ($user != null && $user->getActivate() == 0) {
        $_SESSION = array();
        session_destroy();
    }
    else {
        if ($user->isAdmin() == 1)
            $_SESSION['admin'] = true;
        else
            $_SESSION['admin'] = false;
    }
}


require_once(VIEW_PATH . 'header.php');



$action = (isset($_GET['action'])) ? htmlentities($_GET['action']) : 'default';

switch ($action) {
    case 'places':
    case 'events':
        require_once('controllers/PlacesOrEventsController.php');
        $controller = new PlacesOrEventsController($db);
        break;
    case 'contact':
        require_once('controllers/ContactController.php');
        $controller = new ContactController();
        break;
    case 'registration':
        require_once('controllers/RegistrationController.php');
        $controller = new RegistrationController($db);
        break;
    case 'login':
        require_once('controllers/LoginController.php');
        $controller = new LoginController($db);
        break;
    case 'logout':
        require_once('controllers/LogoutController.php');
        $controller = new LogoutController();
        break;
    case 'membersList':
        require_once('controllers/MembersListController.php');
        $controller = new MembersListController($db);
        break;
    case 'addEdit':
        require_once('controllers/AddEditVenueController.php');
        $controller = new AddEditVenueController($db);
        break;
    case 'searchWithKeywords':
        require_once('controllers/SearchWithKeywordsController.php');
        $controller = new SearchWithKeywordsController($db);
        break;
    default:
        require_once('controllers/HomeController.php');
        $controller = new HomeController($db);
        break;
}

$controller->run();


require_once(VIEW_PATH . 'footer.php');

ob_end_flush();

?>