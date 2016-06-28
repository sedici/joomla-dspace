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
include_once dirname(__FILE__) .'/util/config.php';
require_once dirname(__FILE__) .'/util/Filter.php';
require_once dirname(__FILE__) .'/util/Query.php';
require_once dirname(__FILE__) .'/model/SimplepieModel.php';

$util= new Query();

$handle = ModDspacedHelper::getHandle($params);
$author = ModDspacedHelper::getAuthor($params);
$keywords = ModDspacedHelper::getKeywords($params);
if($util->validete($author,$handle,$keywords)){
    $cache = ModDspacedHelper::getCache($params);
    $description = ModDspacedHelper::getDescription($params) ? "description" : false;
    $maxlenght = ModDspacedHelper::getLimit($params);
    $share = ModDspacedHelper::getShare($params);
    $show_author = ModDspacedHelper::getShowAuthor($params);
    $date = ModDspacedHelper::getShowDate($params);
    $max_results = ModDspacedHelper::getMaxResults($params);
    $show_subtypes = ModDspacedHelper::getShowSubtype($params);
    $group_year = ModDspacedHelper::getGroupYear($params);
    $group_subtype = ModDspacedHelper::getGroupSubtype($params);
    $subtypes = ModDspacedHelper::selectedSubtypes($params);
    if (!$subtypes){ $all = true; } else { $all=false;}
    
    $attributes = $util->group_attributes ( $description, $date, $show_author, $maxlenght, $show_subtypes,$share);
    $queryStandar = $util->standarQuery($handle, $author, $keywords,$max_results);
    $results= $util->getPublications($all, $queryStandar, $cache, $subtypes ,$group_subtype,$group_year);       
}
require JModuleHelper::getLayoutPath('mod_dspace');