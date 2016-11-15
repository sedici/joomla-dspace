<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  mod_dspace
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
// No direct access
defined('_JEXEC') or die;
// Include the syndicate functions only once
require_once dirname(__FILE__) . '/helper.php';
require_once dirname(__FILE__) .'/configuration/config.php';
require_once dirname(__FILE__) .'/util/WidgetFilter.php';
require_once dirname(__FILE__) .'/util/WidgetValidation.php';
require_once dirname(__FILE__) .'/util/Query.php';
require_once dirname(__FILE__) .'/util/XmlOrder.php';
require_once dirname(__FILE__) .'/model/SimplepieModel.php';
require_once dirname(__FILE__) .'/configuration/Configuration.php';
foreach ( glob ( dirname(__FILE__) ."/configuration/*_config.php" ) as $app ) { 
    require_once $app;
}


$util= new Query();
$validation = new WidgetValidation();
$handle = ModDspacedHelper::getHandle($params);
$author = ModDspacedHelper::getAuthor($params);
$keywords = ModDspacedHelper::getKeywords($params);
if($validation->labelValidation($author,$handle,$keywords)){
    $config = ModDspacedHelper::getConfig($params);
    $configuration = $validation->create_configuration($config);
    $cache = ModDspacedHelper::getCache($params);
    $description = ModDspacedHelper::getDescription($params);
    $description = $configuration->is_description($description);  
    $description = $validation->description($description, false);
    $maxlenght = ModDspacedHelper::getLimit($params);
    $share = ModDspacedHelper::getShare($params);
    $show_author = ModDspacedHelper::getShowAuthor($params);
    $date = ModDspacedHelper::getShowDate($params);
    $max_results = ModDspacedHelper::getMaxResults($params);
    $show_subtypes = ModDspacedHelper::getShowSubtype($params);
    $show_subtypes= $configuration->is_label_true($show_subtypes);
    $group_year = ModDspacedHelper::getGroupYear($params);
    $group_subtype = ModDspacedHelper::getGroupSubtype($params);
    $group_subtype = $configuration->is_label_true( $group_subtype);
    $all = ModDspacedHelper::selectedSubtypes($params,$subtypes_selected);
    $all = $configuration->instance_all($all);
    $attributes = $util->group_attributes ( $description, $date, $show_author, $maxlenght, $show_subtypes,$share);
    $queryStandar = $util->standarQuery($handle, $author, $keywords,$max_results, $configuration);  
    $cmp=$validation->getOrder($group_subtype,$group_year);
    $util->setCmp($cmp);
    $results= $util->getPublications($all, $queryStandar, $cache, $subtypes_selected );   
}
require JModuleHelper::getLayoutPath('mod_dspace');