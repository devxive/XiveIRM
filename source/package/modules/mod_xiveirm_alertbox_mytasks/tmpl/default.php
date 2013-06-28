<?php
/**
 * @package     XAP.Site
 * @subpackage  mod_xiveirm_alertbox_mytasks
 *
 * @copyright   Copyright (C) 1997 - 2013 devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$db = JFactory::getDbo();

	/*
	 * Count all Contacts
	 */
	$query = $db->getQuery(true);
	$query
		->select('COUNT(*)')
		->from('#__xiveirm_contacts');

	$db->setQuery($query);
	$contacts = $db->loadResult();


	/*
	 * Count the Flags
	 */
	$query = $db->getQuery(true);
	$query
		->select('COUNT(*)')
		->from('#__xiveirm_flags')
		->where('item LIKE \'contacts.%\'');

	$db->setQuery($query);
	$flags = $db->loadResult();
	$flagsPercent = round(100 / $contacts * $flags);


	/*
	 * Count missing addresses
	 */
	$query = $db->getQuery(true);
	$query
		->select('COUNT(*)')
		->from('#__xiveirm_contacts')
		->where('address_street = \'\' OR address_houseno = \'\' OR address_zip = \'\' OR address_city = \'\' OR address_country = \'\'');

	$db->setQuery($query);
	$addresses =  $db->loadResult();
	$addressesPercent = round(100 / $contacts * $addresses);


	/*
	 * Count missing phone numbers
	 */
	$query = $db->getQuery(true);
	$query
		->select('COUNT(*)')
		->from('#__xiveirm_contacts')
		->where('phone = \'\' AND mobile = \'\'');

	$db->setQuery($query);
	$phonenumbers =  $db->loadResult();
	$phonenumbersPercent = round(100 / $contacts * $phonenumbers);


// + Calculate all other entries after
$totalCount = $flags + $addresses + $phonenumbers;

function getcssClass($percentage)
{
	if($percentage >= 85) {
		$cssClass = 'progress-danger';
	} else if($percentage >= 60 && $percentage < 85) {
		$cssClass = 'progress-warning';
	} else if($percentage >= 20 && $percentage < 60) {
		$cssClass = 'progress-success';
	} else if($percentage >= 10 && $percentage < 20) {
		$cssClass = 'progress-success progress-striped';
	} else {
		$cssClass = '';
	}

	return $cssClass;
}

?>
<?php if($totalCount > 0) { ?>
	<li class="grey dark no-border margin-1">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown">
			<i class="icon-eye-open icon-animated-wrench icon-only"></i>
			<span class="badge"><?php echo $totalCount; ?></span>
		</a>
		<ul class="pull-right dropdown-navbar dropdown-menu dropdown-caret dropdown-closer">
			<li class="nav-header">
				<i class="icon-tasks"></i> <?php echo $totalCount; ?> Task<?php echo $totalCount == 1 ? '' : 's'; ?> remaining
			</li>

			<?php if($flags > 0) { ?>
				<li>
					<!-- Have to be redirected to a filtered list with open flags, may we use &task=contacts.openflags -->
					<a href="<?php echo JRoute::_('index.php?option=com_xiveirm&task=contacts.openflags'); ?>">
						<div class="clearfix">
							<span class="pull-left">Open Flags</span>
							<span class="pull-right"><?php echo $flags . '/' . $contacts; ?></span>
						</div>
						<div class="progress progress-mini <?php echo getcssClass($flagsPercent); ?>"><div class="bar" style="width:<?php echo $flagsPercent; ?>%"></div></div>
					</a>
				</li>
				<?php } ?>

			<?php if($addresses > 0) { ?>
				<li>
					<a href="#">
						<div class="clearfix">
							<span class="pull-left">Incomplete adresses</span>
							<span class="pull-right"><?php echo $addressesPercent; ?>%</span>
						</div>
						<div class="progress progress-mini <?php echo getcssClass($addressesPercent); ?>"><div class="bar" style="width:<?php echo $addressesPercent; ?>%"></div></div>
					</a>
				</li>
			<?php } ?>

			<li>
				<a href="#">
					<div class="clearfix">
						<span class="pull-left">Incomplete phone numbers</span>
						<span class="pull-right"><?php echo $phonenumbersPercent; ?>%</span>
					</div>
					<div class="progress progress-mini <?php echo getcssClass($phonenumbersPercent); ?>"><div class="bar" style="width:<?php echo $phonenumbersPercent; ?>%"></div></div>
				</a>
			</li>

<!--
			<li>
				<a href="#">
					<div class="clearfix">
						<span class="pull-left">Vorplanung</span>
						<span class="pull-right">90%</span>
					</div>
					<div class="progress progress-mini <?php echo getcssClass(); ?>"><div class="bar" style="width:90%"></div></div>
				</a>
			</li>
-->
			<li>
				<a onClick="alert('Link to the global task list or the dashboard!')">
					Aufgabendetails anzeigen
					<i class="icon-arrow-right"></i>
				</a>
			</li>
		</ul>
	</li>
<?php } ?>
