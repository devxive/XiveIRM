<?php
/**
 * @version     3.1.0
 * @package     com_medtranscoord
 * @copyright   Copyright (c) 1997 - 2013 by devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      devXive - research and development <support@devxive.com> - http://devxive.com
 */

defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');

/**
 * Supports an HTML select list of categories
 */
class JFormFieldTimeupdated extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'timeupdated';

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	protected function getInput()
	{
		// Initialize variables.
		$html = array();
        
        
		$old_time_updated = $this->value;
        $hidden = (boolean) $this->element['hidden'];
        if ($hidden == null || !$hidden){
            if (!strtotime($old_time_updated)) {
                $html[] = '-';
            } else {
                $jdate = new JDate($old_time_updated);
                $pretty_date = $jdate->format(JText::_('DATE_FORMAT_LC2'));
                $html[] = "<div>".$pretty_date."</div>";
            }
        }
        $time_updated = date("Y-m-d H:i:s");
        $html[] = '<input type="hidden" name="'.$this->name.'" value="'.$time_updated.'" />';
        
		return implode($html);
	}
}