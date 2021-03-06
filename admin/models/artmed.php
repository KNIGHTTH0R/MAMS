<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
/**
 * @version		$Id: artmed.php 2012-03-12 $
 * @package		MAMS.Admin
 * @subpackage	artmed
 * @copyright	Copyright (C) 2012 DtD Productions.
 * @license		GNU General Public License version 2
 */

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

/**
 * MAMS Article Media Edit Model
 *
 * @static
 * @package		MAMS.Admin
 * @subpackage	artmed
 * @since		1.0
 */
class MAMSModelArtMed extends JModelAdmin
{
	/**
	 * Method override to check if you can edit an existing record.
	 *
	 * @param	array	$data	An array of input data.
	 * @param	string	$key	The name of the key for the primary key.
	 *
	 * @return	boolean
	 * @since	1.6
	 */
	protected function allowEdit($data = array(), $key = 'am_id')
	{
		// Check specific edit permission then general edit permission.
		return JFactory::getUser()->authorise('core.edit', 'com_mams.artmed.'.((int) isset($data[$key]) ? $data[$key] : 0)) or parent::allowEdit($data, $key);
	}
	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	1.6
	 */
	public function getTable($type = 'ArtMed', $prefix = 'MAMSTable', $config = array()) 
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		Data for the form.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	mixed	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true) 
	{
		// Get the form.
		$form = $this->loadForm('com_mams.artmed', 'artmed', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) 
		{
			return false;
		}
		return $form;
	}
	/**
	 * Method to get the script that have to be included on the form
	 *
	 * @return string	Script files
	 */
	public function getScript() 
	{
		return 'administrator/components/com_mams/models/forms/artmed.js';
	}
	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
	protected function loadFormData() 
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_mams.edit.artmed.data', array());
		if (empty($data)) 
		{
			$data = $this->getItem();
			if ($this->getState('artmed.am_id') == 0) {
				$app = JFactory::getApplication();
				$data->set('am_art', JRequest::getInt('am_art', $app->getUserState('com_mams.artmeds.filter.article')));
			}
		}
		return $data;
	}
	
	/**
	* Prepare and sanitise the table prior to saving.
	*
	* @since 1.6
	*/
	protected function prepareTable(&$table)
	{
		jimport('joomla.filter.output');
		$date = JFactory::getDate();
		$user = JFactory::getUser();
		
		if (empty($table->am_id)) {
			// Set the values
			
			// Set ordering to the last item if not set
			if (empty($table->ordering)) {
				$db = JFactory::getDbo();
				$db->setQuery('SELECT MAX(ordering) FROM #__mams_artmed WHERE am_art = "'.$table->am_art.'"');
				$max = $db->loadResult();
				
				$table->ordering = $max+1;
			}
		}
		else {
			// Set the values
		}
	}
	
	/**
	* A protected method to get a set of ordering conditions.
	*
	* @param object A record object.
	* @return array An array of conditions to add to add to ordering queries.
	* @since 1.6
	*/
	protected function getReorderConditions($table)
	{
		$condition = array();
		$condition[] = 'am_art = '.(int) $table->am_art;
		return $condition;
	}
	
}
