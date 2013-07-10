<?php
/**
 * @version     4.2.3
 * @package     com_xiveirm
 * @copyright   Copyright (C) 1997 - 2013 by devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      devXive <support@devxive.com> - http://devxive.com
 */

// No direct access.
defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/controller.php';

/**
 * Contacts list controller class.
 */
class XiveirmControllerSearch extends XiveirmController
{
	var $app;
	var $model;
	var $user;

	/**
	 * INITIATE THE CONSTRUCTOR
	 */
	public function __construct()
	{
		// Initialise variables.
		$this->app = JFactory::getApplication();
		$this->model = $this->getModel('Api', 'XiveirmModel');
		$this->user = JFactory::getUser();

		parent::__construct();
	}

	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
//	public function &getModel($name = 'Search', $prefix = 'XiveirmModel')
//	{
//		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
//		return $model;
//	}

	/**
	 * Method to get live search results (like typeahead / autocomplete)
	 *
	 * @return void
	 *
	 * @since 3.3
	 */
	public function typeahead()
	{
//		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

//		$data = htmlentities($_POST['typeahead_search']);
		$data = $this->app->input->get('typeahead_search', null, 'string');

		// Get the model.
		$model = $this->getModel('Search', 'XiveirmModel');

		$return = $model->searchbywords($data);

//		echo json_encode($data);

		foreach($return->results as $item) {
			// Set the name for the header
			if($item->last_name && $item->first_name) {
				$contactName = $item->last_name . ', ' . $item->first_name;
				if($item->company) {
					$contactName .= ' (' . $item->company . ')';
				}
			} else {
				if($item->company) {
					$contactName = $item->company;
				} else {
					$contactName = 'Kontakt mit System-ID' . $item->id;
				}
			}

			echo '<a href="' . JRoute::_('index.php?option=com_xiveirm&task=contactform.edit&id=' . $item->id) . '"><h3 class="header lighter green">' . $contactName . '</h3></a>';

			if($item->catid_title) {
				echo '<span>Eingetragen im System als ' . $item->catid_title . '</span>';
			}

			if($item->catid_title && $item->dob && $item->dob != '0001-01-01') { echo '. '; }

			if($item->dob && $item->dob != '0001-01-01') {
				$dob = true;
				echo '<span>Wurde am ' . $item->dob . ' geboren</span>';
			} else { $dob = false; }

			if($item->catid_title && !$dob) { echo ', '; }

			echo '<span>';
				if( ($item->address_street && $item->address_houseno) || ($item->address_zip || $item->address_city) || $item->address_region || $item->address_country ) {
					if($item->first_name && $item->last_name) {
						echo ' und wohnt';
					} else if($item->company) {
						echo ' mit Sitz';
					}

					if($item->address_street) {
						echo ' in der ' . $item->address_street;
						if($item->address_houseno) {
							echo ' ' . $item->address_houseno;
						}
					}
					if($item->address_street && $item->address_zip && $item->address_city) { echo ', '; }
					if(!$item->address_street && $item->address_zip && $item->address_city) { echo 'in '; }
					if($item->address_zip && $item->address_city) {
						echo $item->address_zip . ' ' . $item->address_city;
					}
					if( (!$item->address_street && !$item->address_zip && !$item->address_city && $item->address_region) || ($item->address_zip && $item->address_city && $item->address_region) ) { echo ' in '; }
					if($item->address_region) {
						echo $item->address_region;
					}
					if(!$item->address_street && !$item->address_zip && !$item->address_city && !$item->address_region && $item->address_country) { echo ' in '; }
					if($item->address_region && $item->address_country) { echo ', '; }
					if(!$item->address_region && (($item->address_zip && $item->address_city) || $item->address_street) && $item->address_country) { echo ' '; }
					if($item->address_country) {
						echo $item->address_country;
					}
				} else {
					echo ' und die Adresse ist leider unbekannt!';
				}
				
			echo '. </span><br>';
			if($item->remarks) {
				echo '<span>Folgende interne Bemerkungen wurden &uuml;ber ' . $contactName . ' gespeichert:<br><ul><li>' . $item->remarks . '</li></ul></span>';
			}
		}

		echo '<br><br><hr><br><br><pre><h3 class="header">print_r($query):</h3>';
		echo '>> protected <<';
//		print_r($return->query);
		echo '</pre>';

		$this->app->close();

		// Redirect to the edit screen.
//		$this->setRedirect(JRoute::_('index.php?option=com_xiveirm&view=contactform&layout=edit', false));
	}
}