<?php
/**
 * @package     XAP.Plugin
 * @subpackage  User.xiveirmclientprofile
 *
 * @copyright   Copyright (C) 1997 - 2013 devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

/**
 * An example custom profile plugin.
 *
 * @package     XAP.Plugin
 * @subpackage  User.profile
 * @since       3.0
 */
class PlgUserXiveIrmClientProfile extends JPlugin
{
	/**
	 * @param   string     $context  The context for the data
	 * @param   integer    $data     The user id
	 *
	 * @return  boolean
	 *
	 * @since   3.0
	 */
	public function onContentPrepareData($context, $data)
	{
		// Check we are manipulating a valid form.
		if (!in_array($context, array('com_users.profile', 'com_users.registration', 'com_users.user', 'com_admin.profile')))
		{
			return true;
		}

		$userId = isset($data->id) ? $data->id : 0;

		// Load the profile data from the database.
		$db = JFactory::getDbo();
		$db->setQuery(
			'SELECT profile_key, profile_value FROM #__user_profiles' .
			' WHERE user_id = ' . (int) $userId .
			' AND profile_key LIKE \'xiveirmclientprofile.%\'' .
			' ORDER BY ordering'
		);
		$results = $db->loadRowList();

		// Check for a database error.
		if ($db->getErrorNum()) {
			$this->_subject->setError($db->getErrorMsg());
			return false;
		}

		// Merge the profile data.
		$data->xiveirmclientprofile = array();
		foreach ($results as $v) {
			$k = str_replace('xiveirmclientprofile.', '', $v[0]);
			$data->xiveirmclientprofile[$k] = json_decode($v[1], true);
		}

		return true;
	}

	/**
	 * @param   JForm    $form    The form to be altered.
	 * @param   array    $data    The associated data for the form.
	 *
	 * @return  boolean
	 * @since   3.0
	 */
	public function onContentPrepareForm($form, $data)
	{
		//Load user_xiveirmclientprofile plugin language
		$lang = JFactory::getLanguage();
		$lang->load('plg_user_xiveirmclientprofile', JPATH_ADMINISTRATOR);

		if (!($form instanceof JForm))
		{
			$this->_subject->setError('JERROR_NOT_A_FORM');
			return false;
		}

		// Check we are manipulating a valid form.
		if (!in_array($form->getName(), array('com_admin.profile', 'com_users.registration', 'com_users.user', 'com_users.profile')))
		{
			return true;
		}

		if ($form->getName()=='com_users.profile')
		{
			// Add the profile fields to the form.
			JForm::addFormPath(dirname(__FILE__).'/profiles');
			$form->loadFile('profile', false);

			// Toggle whether the usergroup (client id) field is required.
			if ($this->params->get('profile-client_id', 1) > 0)
			{
				$form->setFieldAttribute('client_id', 'required', $this->params->get('profile-client_id') == 2, 'xiveirmclientprofile');
			} else {
				$form->removeField('client_id', 'xiveirmclientprofile');
			}

			// Toggle whether to show global list options is required.
			if ($this->params->get('profile-show_globals', 1) > 0)
			{
				$form->setFieldAttribute('show_globals', 'required', $this->params->get('profile-show_globals') == 2, 'xiveirmclientprofile');
			} else {
				$form->removeField('show_globals', 'xiveirmclientprofile');
			}

			// Toggle whether the jobtitle field is required.
			if ($this->params->get('profile-jobtitle', 1) > 0)
			{
				$form->setFieldAttribute('jobtitle', 'required', $this->params->get('profile-jobtitle') == 2, 'xiveirmclientprofile');
			} else {
				$form->removeField('jobtitle', 'xiveirmclientprofile');
			}
		}

		//In this example, we treat the frontend registration and the back end user create or edit as the same.
		elseif ($form->getName()=='com_users.registration' || $form->getName()=='com_users.user' )
		{
			// Add the registration fields to the form.
			JForm::addFormPath(dirname(__FILE__).'/profiles');
			$form->loadFile('profile', false);

			// Toggle whether the usergroup (client id) field is required.
			if ($this->params->get('register-client_id', 1) > 0)
			{
				$form->setFieldAttribute('client_id', 'required', $this->params->get('register-client_id') == 2, 'xiveirmclientprofile');
			} else {
				$form->removeField('client_id', 'xiveirmclientprofile');
			}

			// Toggle whether to show global list options is required.
			if ($this->params->get('register-show_globals', 1) > 0)
			{
				$form->setFieldAttribute('show_globals', 'required', $this->params->get('register-show_globals') == 2, 'xiveirmclientprofile');
			} else {
				$form->removeField('show_globals', 'xiveirmclientprofile');
			}

			// Toggle whether the jobtitle field is required.
			if ($this->params->get('register-jobtitle', 1) > 0)
			{
				$form->setFieldAttribute('jobtitle', 'required', $this->params->get('register-jobtitle') == 2, 'xiveirmclientprofile');
			} else {
				$form->removeField('jobtitle', 'xiveirmclientprofile');
			}
		}
	}

	function onUserAfterSave($data, $isNew, $result, $error)
	{
		$userId = JArrayHelper::getValue($data, 'id', 0, 'int');

		if ($userId && $result && isset($data['xiveirmclientprofile']) && (count($data['xiveirmclientprofile'])))
		{
			try
			{
				$db = JFactory::getDbo();

				// Try to update existing profile fields. If nothing is there to update, we create a new entry
				$order  = 1;

				foreach ($data['xiveirmclientprofile'] as $k => $v)
				{
					$profile_key = $db->quote('xiveirmclientprofile.'.$k);

					// Check if row (field) exist for that user
					$db->setQuery('SELECT * FROM #__user_profiles WHERE user_id = '.$userId.' AND profile_key = '.$profile_key.'');
					if (!$db->loadObjectList())
					{
						$query = $db->getQuery(true);
						$columns = array('user_id', 'profile_key', 'profile_value', 'ordering');
						$values = array($userId, $profile_key, $db->quote(json_encode($v)), $order++);
						$query
							->insert($db->quoteName('#__user_profiles'))
							->columns($db->quoteName($columns))
							->values(implode(', ', $values));
						$db->setQuery($query);
						$db->query();
					} else {
						$db->setQuery('UPDATE #__user_profiles SET profile_value = '.$db->quote(json_encode($v)).' WHERE user_id = '.$userId.' AND profile_key = '.$profile_key.'');
						if (!$db->query())
						{
							throw new Exception($db->getErrorMsg());
						}
					}
				}
			}
			catch (JException $e)
			{
				$this->_subject->setError($e->getMessage());
				return false;
			}
		}

		return true;
	}

	/**
	  * Remove all user profile information for the given user ID
	  *
	  * Method is called after user data is deleted from the database
	  *
	  * @param       array           $user           Holds the user data
	  * @param       boolean         $success        True if user was succesfully stored in the database
	  * @param       string          $msg            Message
	  */
	function onUserAfterDelete($user, $success, $msg)
	{
		if (!$success)
		{
			return false;
		}

		$userId = JArrayHelper::getValue($user, 'id', 0, 'int');

		if ($userId)
		{
			try
			{
				$db = JFactory::getDbo();
				$db->setQuery(
					'DELETE FROM #__user_profiles WHERE user_id = '.$userId .
					' AND profile_key LIKE \'profile5.%\''
				);

				if (!$db->query())
				{
					throw new Exception($db->getErrorMsg());
				}
			}
			catch (JException $e)
			{
				$this->_subject->setError($e->getMessage());
				return false;
			}
		}

		return true;
	}

}
?>