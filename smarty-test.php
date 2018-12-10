<?php
session_start();

require_once 'config/init.conf.php';
require_once 'config/bdd.conf.php';
require_once 'includes/connexion.inc.php';

include_once 'includes/fonction.inc.php';

require_once 'libs/Smarty.class.php';

$prenom = 'fabien';

$smarty = new Smarty();

$smarty->setTemplateDir('templates/');
$smarty->setCompileDir('templates_c/');

$smarty->debugging = 'true';
$smarty->assign('prenom', $prenom);

include_once 'includes/header.inc.php';
include_once 'includes/menu.inc.php';

$smarty->display('smarty-test.tpl');

include_once 'includes/footer.inc.php';

?>
