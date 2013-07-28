<?php
/**
 * @version     5.0.0
 * @package     com_xivetranscorder
 * @copyright   Copyright (c) 1997 - 2013 by devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      devXive - research and development <support@devxive.com> - http://devxive.com
 */

defined('_JEXEC') or die;

abstract class XivetranscorderHelper
{
	public static function myFunction()
	{
		$result = 'Something';
		return $result;
	}

	public function getTransDevice()
	{
		NHtmlJavaScript::setChosen('.chzn-select-trans', false, array('width' => '100%', 'disable_search' => true));

		$options = IRMSystem::getListOptions('options', 'transdevice');

		$html = '';
		$html .= '<select name="transcorder[transdevice]" class="chzn-select-trans input-control" data-placeholder="' . JText::_('COM_XIVEIRM_SELECT_CATEGORY') . '" required>';
			$html .= '<option value="">' . JText::_('COM_XIVETRANSCORDER_FORM_SELECT_TRANSPORT_DEVICE') . '</option>';
			if($options->client) {
				$html .= '<optgroup label="' . JText::sprintf('COM_XIVETRANSCORDER_FORM_SELECT_SPECIFIC', NItemHelper::getTitleById('usergroup', $xsession->client_id)) . '">';
					foreach ($options->client as $key => $val) {
						if($this->item->gender == $key) {
							$html .= '<option value="' . $key . '" selected>' . JText::_($val) . '</option>';
						} else {
							$html .= '<option value="' . $key . '">' . JText::_($val) . '</option>';
						}
					}
				$html .= '</optgroup>';
			}
			if($options->global) {
				$html .= '<optgroup label="' . JText::_('COM_XIVEIRM_SELECT_GLOBAL') . '">';
					foreach ($options->global as $key => $val) {
						if($this->item->gender == $key) {
							$html .= '<option value="' . $key . '" selected>' . JText::_($val) . '</option>';
						} else {
							$html .= '<option value="' . $key . '">' . JText::_($val) . '</option>';
						}
					}
				$html .= '</optgroup>';
			}
		$html .= '</select>';

		return $html;
	}

	public function getTransType()
	{
		NHtmlJavaScript::setChosen('.chzn-select-trans', false, array('width' => '100%', 'disable_search' => true));

		$options = IRMSystem::getListOptions('options', 'transtype');

		$html = '';
		$html .= '<select name="transcorder[transtype]" class="chzn-select-trans input-control" data-placeholder="' . JText::_('COM_XIVEIRM_SELECT_CATEGORY') . '" required>';
			$html .= '<option value="">' . JText::_('COM_XIVETRANSCORDER_FORM_SELECT_TRANSPORT_TYPE') . '</option>';
			if($options->client) {
				$html .= '<optgroup label="' . JText::sprintf('COM_XIVETRANSCORDER_FORM_SELECT_SPECIFIC', NItemHelper::getTitleById('usergroup', $xsession->client_id)) . '">';
					foreach ($options->client as $key => $val) {
						if($this->item->gender == $key) {
							$html .= '<option value="' . $key . '" selected>' . JText::_($val) . '</option>';
						} else {
							$html .= '<option value="' . $key . '">' . JText::_($val) . '</option>';
						}
					}
				$html .= '</optgroup>';
			}
			if($options->global) {
				$html .= '<optgroup label="' . JText::_('COM_XIVEIRM_SELECT_GLOBAL') . '">';
					foreach ($options->global as $key => $val) {
						if($this->item->gender == $key) {
							$html .= '<option value="' . $key . '" selected>' . JText::_($val) . '</option>';
						} else {
							$html .= '<option value="' . $key . '">' . JText::_($val) . '</option>';
						}
					}
				$html .= '</optgroup>';
			}
		$html .= '</select>';
	
		return $html;
	}

	public function getOrderType()
	{
		NHtmlJavaScript::setChosen('.chzn-select-ordertype', false, array('width' => '100%', 'disable_search' => true));

		$options = IRMSystem::getListOptions('options', 'ordertype');

		$html = '';
		$html .= '<select name="transcorder[transtype]" class="chzn-select-trans input-control" data-placeholder="' . JText::_('COM_XIVEIRM_SELECT_CATEGORY') . '" required>';
			$html .= '<option value="">' . JText::_('COM_XIVETRANSCORDER_FORM_SELECT_ORDER_TYPE') . '</option>';
			if($options->client) {
				$html .= '<optgroup label="' . JText::sprintf('COM_XIVETRANSCORDER_FORM_SELECT_SPECIFIC', NItemHelper::getTitleById('usergroup', $xsession->client_id)) . '">';
					foreach ($options->client as $key => $val) {
						if($this->item->gender == $key) {
							$html .= '<option value="' . $key . '" selected>' . JText::_($val) . '</option>';
						} else {
							$html .= '<option value="' . $key . '">' . JText::_($val) . '</option>';
						}
					}
				$html .= '</optgroup>';
			}
			if($options->global) {
				$html .= '<optgroup label="' . JText::_('COM_XIVEIRM_SELECT_GLOBAL') . '">';
					foreach ($options->global as $key => $val) {
						if($this->item->gender == $key) {
							$html .= '<option value="' . $key . '" selected>' . JText::_($val) . '</option>';
						} else {
							$html .= '<option value="' . $key . '">' . JText::_($val) . '</option>';
						}
					}
				$html .= '</optgroup>';
			}
		$html .= '</select>';

		return $html;
	}

}

