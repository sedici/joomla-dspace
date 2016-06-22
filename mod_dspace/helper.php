<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  mod_dspace
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
class ModDspacedHelper
{
    /**
     * Retrieves the hello message
     *
     * @param   array  $params An object containing the module parameters
     *
     * @access public
     */    
    public static function getAll($params)
    {
         return $params->get('all');
    }
    public static function getAuthor($params)
    {
         return $params->get('author');
    }
     public static function getCache($params)
    {
        return $params->get('cache');
    }
    public static function getHandle($params)
    {
        return $params->get('handle');
    }
    public static function getKeywords($params)
    {
        return $params->get('keywords');
    }
}