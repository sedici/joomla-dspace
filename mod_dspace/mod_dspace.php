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



$handle = ModDspacedHelper::getHandle($params);
$author = ModDspacedHelper::getAuthor($params);
$keywords = ModDspacedHelper::getKeywords($params);
$all= ModDspacedHelper::getAll($params);
    $cache = ModDspacedHelper::getCache($params);

    $util= new Query();
    $attributes = $util->group_attributes ( false, false, false, 100, false,false);
    $queryStandar = $util->standarQuery($handle, $author, $keywords,100);
    $results= $util->getPublications($all, $queryStandar, $cache, null ,false,false);
require JModuleHelper::getLayoutPath('mod_dspace');