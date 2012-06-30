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
class spip2joomlasViewrubriques extends JView
{
    /**
     * Hellos view display method
     * @return void
     **/
    function display($tpl = null)
    {
    	  JHTML::_('stylesheet', 'spip2joomla.css', 'administrator/components/com_spip2joomla/assets/css/');
        JToolBarHelper::title( JText::_( 'Spip 2 Joomla Section Manager' ), 'import-sections.png' );
        JToolBarHelper::divider();
        JToolBarHelper::custom('importSections','import-sections.png','import-sections.png',JText::_( 'Import Sections'),false,false);
        
        JSubMenuHelper::addEntry(JText::_('CONTROL PANEL'), 'index2.php?option=com_spip2joomla');
		JSubMenuHelper::addEntry(JText::_('SPIP2JOOMLA_AUTEURS'), 'index2.php?option=com_spip2joomla&view=authors');
		JSubMenuHelper::addEntry(JText::_('SPIP2JOOMLA_RUBRIQUES'), 'index2.php?option=com_spip2joomla&view=rubriques', true);
		JSubMenuHelper::addEntry(JText::_('SPIP2JOOMLA_SUBRUBRIQUES'), 'index2.php?option=com_spip2joomla&view=subrubriques');
		JSubMenuHelper::addEntry(JText::_('SPIP2JOOMLA_ARTICLES'), 'index2.php?option=com_spip2joomla&view=articles');
		JSubMenuHelper::addEntry(JText::_('SPIP2JOOMLA_DOCUMENTS'), 'index2.php?option=com_spip2joomla&view=documents');
        
        // Get data from the model
        $items =& $this->get('Data');
        $pagination =& $this->get('Pagination');
 
        $this->assignRef( 'items', $items );
        $this->assignRef( 'pagination', $pagination);
 
        parent::display($tpl);
    }
}
