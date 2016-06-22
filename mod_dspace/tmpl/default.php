<?php 
// No direct access
defined('_JEXEC') or die; ?>
<?php 
require_once dirname(__FILE__) . '/View.php';

$document = JFactory::getDocument();
$document->addStyleSheet(dirname(__FILE__) . '/css/styles.css');

$view = new View();
$view->allPublications($results, $attributes);

?>