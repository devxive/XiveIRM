<?php
/**
 * @version     6.0.0
 * @package     com_xiveirm
 * @copyright   Copyright (C) 1997 - 2013 by devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      devXive <support@devxive.com> - http://devxive.com
 */
// no direct access
defined('_JEXEC') or die;

// Import HTML and Helper Classes
nimport('NHtml.JavaScript');
// selector, url, debug
NHtmlJavascript::loadTypeahead('#typeahead-search', '#search-query', 'index.php?option=com_xiveirm&task=search.typeahead');

// Load the XiveIRMSystem Session Data (Performed by the XiveIRM System Plugin)
$xsession = JFactory::getSession()->get('XiveIRMSystem');
?>
<style>
.desktop-divider {
	margin-top: 50px;
}

/* Style the search input field. */
.field {
	float:left;
	width:500px;
	height:27px;
	line-height:27px;
	text-indent:10px;
	font-family:arial, sans-serif;
	font-size:1em;
	color:#333;
	background: #fff;
	border:solid 1px #d9d9d9;
	border-top:solid 1px #c0c0c0;
	border-right:solid 1px #c0c0c0;
	outline: none;
}

/* Style the "X" text button next to the search input field */
#delete {
	float:left;
	width:16px;
	height:29px;
	line-height:27px;
	margin-right:15px;
	padding:0 10px 0 10px;
	font-family: "Lucida Sans", "Lucida Sans Unicode",sans-serif;
	font-size:22px;
	background: #fff;
	border:solid 1px #d9d9d9;
	border-top:solid 1px #c0c0c0;
	border-left:none;
}

/* Set default state of "X" and hide it */
#delete #x {
	color:#A1B9ED;
	cursor:pointer;
	display:none;
}

/* Set the hover state of "X" */
#delete #x:hover {
	color:#36c;
}

/* Syle the search button. Settings of line-height, font-size, text-indent used to hide submit value in IE */
#submit {
	margin-left: 10px;
	cursor:pointer;
	width:70px;
	height: 31px;
	line-height:0;
	font-size:0;
	text-indent:-999px;
	color: transparent;
	background: url(/images/ico-search.png) no-repeat #4d90fe center;
	border: 1px solid #3079ED;
	-moz-border-radius: 2px;
	-webkit-border-radius: 2px;
}

/* Style the search button hover state */
#submit:hover {
	background: url(/images/ico-search.png) no-repeat center #357AE8;
	border: 1px solid #2F5BB7;
}

/* Clear floats */
.fclear {clear:both}

.searchText {
	color:#F63;
	font-family:Arial, Helvetica, sans-serif;
	font-size:22px;
	font-weight:100;
}

hr {
	color: #e5e5e5;
	width: 100%;
	border: 1px solid #e5e5e5;
}

.url {
	color: #0C3;
	font-size:12px;	
}

.title {
	font-size:14px;	
}

.desc {
	font-size:12px;	
}

#search_query {
	padding-right: 50px;
	padding-left: 50px;
	padding-top: 0px;
	padding-bottom: 0px;
}
</style>

<div id="search-wrapper" style="display: none;">
	<div class="search-header">
		<div class="search-logo span2 center">
			<img src="/images/googlelogo.png" />
		</div>
		<div class="search-fields span10">
			<input type="text" name="go" id="go" placeholder="Search..." class="field pull-left" />
			<input type="submit" name="search" id="submit" value="Search" class="pull-left" />
		</div>
	</div>
	<div id="search_query"></div>
</div>

<div class="row-fluid">
	<h1 class="header smaller lighter purple">Suche...</h1>
	<div class="desktop-divider hidden-phone"></div>
	<div class="form-search center controls controls-row">
		<!-- name uninteressant wegen get val auf id -->
		<input type="text" name="goirgendwas" id="typeahead-search" placeholder="Search..." class="offset2 span6" />
		<button type="submit" name="search" class="btn btn-mini span2 hidden-phone"><i class="icon-search"></i> Search</button>
	</div>
</div>
<div id="search-query"></div>



<div class="items">
		<?php $show = false; ?>
		<?php // foreach ($this->items as $item) : ?>
			<?php // if($item->state == 1 || ($item->state == 0 && JFactory::getUser()->authorise('core.edit.own',' com_xiveirm'))): $show = true; ?>
			<?php // endif; ?>
		<?php // endforeach; ?>
	<?php
		if (!$show):
//			echo JText::_('COM_XIVEIRM_NO_ITEMS');
		endif;
	?>
</div>
<?php if ($show): ?>
	<div class="pagination">
	<p class="counter">
		<?php // echo $this->pagination->getPagesCounter(); ?>
	</p>
		<?php // echo $this->pagination->getPagesLinks(); ?>
	</div>
<?php endif; ?>
