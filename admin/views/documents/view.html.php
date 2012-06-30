<?php
/**
 * spip2joomlas View for Hello World Component
 * 
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://dev.joomla.org/component/option,com_jd-wiki/Itemid,31/id,tutorials:components/
 * @license        GNU/GPL
 */
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.view' );
 
/**
 * spip2joomlas View
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class spip2joomlasViewdocuments extends JView
{
    /**
     * Hellos view display method
     * @return void
     **/
    function display($tpl = null)
    {
		JHTML::_('stylesheet', 'spip2joomla.css', 'administrator/components/com_spip2joomla/assets/css/');
      JToolBarHelper::title( JText::_( 'Spip 2 Joomla Article Manager' ), 'spip2joomla.png' );
 
       //adding sublink to navigate between screens  ("true" specify the current screen)
      JSubMenuHelper::addEntry(JText::_('CONTROL PANEL'), 'index2.php?option=com_spip2joomla');
	  JSubMenuHelper::addEntry(JText::_('SPIP2JOOMLA_AUTEURS'), 'index2.php?option=com_spip2joomla&view=authors');
	  JSubMenuHelper::addEntry(JText::_('SPIP2JOOMLA_RUBRIQUES'), 'index2.php?option=com_spip2joomla&view=rubriques');
	  JSubMenuHelper::addEntry(JText::_('SPIP2JOOMLA_SUBRUBRIQUES'), 'index2.php?option=com_spip2joomla&view=subrubriques');
	  JSubMenuHelper::addEntry(JText::_('SPIP2JOOMLA_ARTICLES'), 'index2.php?option=com_spip2joomla&view=articles');
	  JSubMenuHelper::addEntry(JText::_('SPIP2JOOMLA_DOCUMENTS'), 'index2.php?option=com_spip2joomla&view=documents', true);

	  // Get data from the model
        $items =& $this->get( 'Data');
 
        $this->assignRef( 'items', $items );
 
        parent::display($tpl);
    }
}
