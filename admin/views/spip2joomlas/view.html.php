<?php
/**
 * spip2joomlas View for Spip2Joomla Component
 * 
 *
 * @author    Carlos M. Cámara Mora
 * @link http://www.gnumla.com
 * @license    GNU/GPL
 */
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.view' );
 
/**
 * spip2joomlas View
 *
 */
class spip2joomlasViewspip2joomlas extends JView
{
    /**
     * Hellos view display method
     * @return void
     **/
    function display($tpl = null)
    {
		JHTML::_('stylesheet', 'spip2joomla.css', 'administrator/components/com_spip2joomla/assets/css/');
      JToolBarHelper::title( JText::_( 'Spip 2 Joomla Control Panel' ), 'spip2joomla.png' );
      JToolBarHelper::preferences( 'com_spip2joomla', 320 ); // adding the height of the parameters windowBox

/*
I don't like the idea of having two links for the same funcion nevertheless, the navigation bar is a great idea,
so I haven't removed it from the other views.

      //adding sublink to navigate between screens  ("true" specify the current screen)
      JSubMenuHelper::addEntry(JText::_('CONTROL PANEL'), 'index2.php?option=com_spip2joomla', true);
	  JSubMenuHelper::addEntry(JText::_('SPIP2JOOMLA_AUTEURS'), 'index2.php?option=com_spip2joomla&view=authors');
	  JSubMenuHelper::addEntry(JText::_('SPIP2JOOMLA_RUBRIQUES'), 'index2.php?option=com_spip2joomla&view=rubriques');
	  JSubMenuHelper::addEntry(JText::_('SPIP2JOOMLA_ARTICLES'), 'index2.php?option=com_spip2joomla&view=articles');
	  JSubMenuHelper::addEntry(JText::_('SPIP2JOOMLA_DOCUMENTS'), 'index2.php?option=com_spip2joomla&view=documents');
*/      

      parent::display($tpl);
    }
}

