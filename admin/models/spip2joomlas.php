<?php
/**
 * Model for Spip 2 Joomla Component
 * 
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://dev.joomla.org/component/option,com_jd-wiki/Itemid,31/id,tutorials:components/
 * @license        GNU/GPL
 */
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.model' );
 
/**
 * Spip 2 Joomla
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class spip2joomlasModelspip2joomlas extends JModel
{
    /**
     * Hellos data array
     *
     * @var array
     */
    var $_data;
 
    /**
     * Returns the query
     * @return string The query to be used to retrieve the rows from the database
     */
    function _buildQuery()
    {
        $query = ' SELECT * '
            . ' FROM #__spip_auteurs ';
        return $query;
    }
 
    /**
     * Retrieves the hello data
     * @return array Array of objects containing the data from the database
     */
    function getData()
    {
        // Lets load the data if it doesn't already exist
        if (empty( $this->_data ))
        {
	 $query = $this->_buildQuery();
	 $this->_data = $this->_getList( $query );
        }
 
        return $this->_data;
    }
}

