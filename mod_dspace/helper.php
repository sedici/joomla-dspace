<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  mod_dspace
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
require_once dirname(__FILE__) . '/util/Filter.php';
class ModDspacedHelper
{
    /**
     * Retrieves the hello message
     *
     * @param   array  $params An object containing the module parameters
     *
     * @access public
     */    
    public static function getAuthor($params)
    {
         return $params->get('author');
    }
    public static function getHandle($params)
    {
        return $params->get('handle');
    }
    public static function getKeywords($params)
    {
        return $params->get('keywords');
    }
    public static function getConfig($params)
    {
        return $params->get('config');
    }
    public static function getCache($params)
    {
        return $params->get('cache');
    }
    public static function getShowAuthor($params)
    {
        return $params->get('show_author');
    }
    public static function getShowDate($params)
    {
        return $params->get('show_date');
    }
    public static function getShowSubtype($params)
    {
        return $params->get('show_subtype');
    }
    public static function getDescription($params)
    {
        return $params->get('description');
    }
    public static function getLimit($params)
    {
        return $params->get('limit');
    }
    public static function getMaxResults($params)
    {
        return $params->get('max_results');
    }
    //Documents subtypes
    public static function getArticle($params)
    {
        return $params->get('article');
    }
    public static function getBook($params)
    {
        return $params->get('book');
    }
    public static function getWorkingPaper($params)
    {
        return $params->get('working_paper');
    }
    public static function getTechnicalReport($params)
    {
        return $params->get('technical_report');
    }
    public static function getConferenceObject($params)
    {
        return $params->get('conference_object');
    }
      public static function getConferenceDocument($params)
    {
        return $params->get('conference_document');
    }
    public static function getRevision($params)
    {
        return $params->get('revision');
    }
    public static function getWorkSpecialization($params)
    {
        return $params->get('work_specialization');
    }
    public static function getPreprint($params)
    {
        return $params->get('preprint');
    }
    public static function getMasterThesis($params)
    {
        return $params->get('master_thesis');
    }
    public static function getPhdThesis($params)
    {
        return $params->get('phD_thesis');
    }
    public static function getLicentiateThesis($params)
    {
        return $params->get('licentiate_thesis');
    }
    public static function getShare($params)
    {
        return $params->get('share');
    }
    public static function getGroupSubtype($params)
    {
        return $params->get('group_subtype');
    }
    public static function getGroupYear($params)
    {
        return $params->get('group_year');
    }
    
    public static function selectedSubtypes ($params,&$groups) {
        //this function returns all active subtypes
        $all=true;
        $groups = array ();
        $filter = new Filter ();
        $subtypes = $filter->subtypes ();
        // $subtypes: all names of subtypes
	foreach ($subtypes as $key => $subtype){
            // compares the user marked subtypes, if TRUE, save the subtype.
		if ($params->get($key)) {
                    array_push($groups, $subtype);
                    $all=false;
                }
	}
        return $all;
    }
}