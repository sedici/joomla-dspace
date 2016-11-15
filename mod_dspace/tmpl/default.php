<?php 
// No direct access
defined('_JEXEC') or die; ?>
<?php 
require_once dirname(__FILE__) . '/View.php';

$document = JFactory::getDocument();
$document->addStyleSheet(JUri::base() . '/media/mod_dspace/css/styles.css');
$view = new View();
$view->render ($results,$attributes, $cmp, $configuration);