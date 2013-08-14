<?php
/**
 * @project		XAP Project - Xive-Application-Platform
 * @subProject	XiveIRM - Interoperable Relationship Management System
 *
 * @package		XiveIRM
 * @subPackage	Library
 * @version		6.1
 *
 * @author		devXive - research and development <support@devxive.com> (http://www.devxive.com)
 * @copyright		Copyright (C) 1997 - 2013 devXive - research and development. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @assetsLicense	devXive Proprietary Use License (http://www.devxive.com/license)
 *
 * @since		6.0
 */

defined('_NFW_FRAMEWORK') or die();

/**
 * Component helper class
 */
abstract class IRMHtmlBuilder
{
	/*
	 * Method to get the client id, either as hidden field if user has only one parent usergroup or as a select list if user has more parent usergroups
	 * Note: Parent usergroups should be hash taggedt o identify them @see NFWUserGroup::getParents() description
	 *
	 * @param     int      $value      The value as set in database, given from appropriate form
	 * @param     array    $options    An array with options
	 *                                 name  => the name attribute (eg "contacts[client_id]")
	 *                                 class => the classe attributes
	 *                                 id    => the id attribute
	 *                                 style => the style attributes
	 *
	 * @return    html                 With informations from plugin config and joined extensions (folder)
	 *
	 * @see                            NFWUserGroup::getParents()
	 */
	public function getClientId($value = '', $options = array())
	{
		// GEt the usergroups
		$usergroups = NFWUserGroup::getParents();

		// Get the options
		$options['name'] = isset($options['name']) ? $options['name'] : '';
		$options['class'] = isset($options['class']) ? $options['class'] : 'input-control';
		$options['trigger'] = isset($options['trigger']) ? $options['trigger'] : 'chzn-select-clientid';
		$options['id'] = isset($options['id']) ? $options['id'] : '';
		$options['style'] = isset($options['style']) ? $options['style'] : '';
		$options['required'] = isset($options['required']) ? $options['required'] : 'required';

		$html = '';

		if ( count($usergroups) > 1 ) {
			// Build a ready to use select list
			NFWHtmlJavascript::setChosen('.' . $options['trigger'], false, array('disable_search_threshold' => '15', 'no_results_text' => 'Oops, nothing found!', 'width' => '100%'));

			$html .= '<select name="' . $options['name'] . '" class="' . $options['trigger'] . ' ' . $options['class'] . '" id="' . $options['id'] . '" data-placeholder="' . JText::_('COM_XIVEIRM_FORM_CLIENTID') . '" style="' . $options['style'] . '" ' . $options['required'] . '>';
				$html .= '<option value="">' . JText::_('COM_XIVEIRM_FORM_CLIENTID_PLEASE_SELECT') . '</option>';
				foreach ($usergroups as $usergroup) {
					if ( $value == $usergroup->id ) {
						$html .= '<option value="' . $usergroup->id . '" selected>' . str_replace('#', '', $usergroup->title) . '</option>';
					}
					else {
						$html .= '<option value="' . $usergroup->id . '">' . str_replace('#', '', $usergroup->title) . '</option>';
					}
				}
			$html .= '</select>';
		} else {
			// Build a hidden form field
			if ( $value ) {
				$val = $value;
			}
			else {
				$val = $usergroups[0]->id;
			}

			$html .= '<input type="hidden" name="' . $options['name'] . '" value="' . $val . '" />';
		}

		return $html;
	}


	/**
	 * Add javascript function to copy the value of form fields pused via tabApp into the main form
	 *
	 * @param	array		$formFields     Common id to identify the input fields
	 *
	 * @return  html
	 *
	 * @since   5.0
	 */
	public function cloneAppFormFields($formFields = null, $appKey = null)
	{
		// Check if we get formFields and the appKey
		if(empty($formFields) || empty($appKey)) {
			return false;
		}

		// Include JS framework
		NFWHtml::loadJsFramework();

		$return_html = '';
		$return_jsdec = "jQuery(document).ready(function() {";

		foreach($formFields as $formField => $placeholder) {
			// Build the html part
			$return_html .= '<input id="' . $appKey . '[' . $formField . ']clone" type="text" class="input-control span6" placeholder="' . JText::_($placeholder) . '" />';

			// Build the JavaScript part
			$return_jsdec .=
					"
					var intval_" . $appKey . "_" . $formField . " = $('[name=" . $appKey . "\\\\[" . $formField . "\\\\]]').val();
					$('#" . $appKey . "\\\\[" . $formField . "\\\\]clone').val(intval_" . $appKey . "_" . $formField . ");

					$('#" . $appKey . "\\\\[" . $formField . "\\\\]clone').keyup(function() {
						var val_" . $appKey . "_" . $formField . "_clone = $(this).val();
						$('[name=" . $appKey . "\\\\[" . $formField . "\\\\]]').val(val_" . $appKey . "_" . $formField . "_clone);
					});
					$('[name=" . $appKey . "\\\\[" . $formField . "\\\\]]').keyup(function() {
						var val_" . $appKey . "_" . $formField . " = $(this).val();
						$('#" . $appKey . "\\\\[" . $formField . "\\\\]clone').val(val_" . $appKey . "_" . $formField . ");
					});
					";
		}

		// Close the .ready function and attach to the document
		$return_jsdec .= "});\n";
		JFactory::getDocument()->addScriptDeclaration($return_jsdec);

		// Return the html build
		return $return_html;
	}
}