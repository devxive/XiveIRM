<?php
/**
 * @version     6.0.0
 * @package     com_xivetranscorder
 * @copyright   Copyright (c) 1997 - 2013 by devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      devXive - research and development <support@devxive.com> - http://devxive.com
 */

// no direct access
defined('_JEXEC') or die;
?>
	/*******************************************
		General Helper Functions
	*******************************************/
	/*
	 * Function to hold the global structure to identify every field in every direction
	 * Single Address: order = 1, direction = b --- address block with only one single address
	 * Multi Address : order = 1 - ~, direction = [f, t] --- address block with directions (origin/from address and destination/to address)
	 */
	function getUsher(order, direction) {
		var usher = {};

		usher.order = 'form[data-order=\"' + order + '\"]';
		usher.direction = '.address-block[data-direction=\"' + direction + '\"]';
		usher.deep = usher.order + ' ' + usher.direction;

		return usher;
	}

	/*
	 * Funtion to check if the given var is a function
	 */
	function isFunction(possibleFunction) {
		return ( typeof(possibleFunction) == typeof(Function) );
	}


	/*
	 * Method to format the timestamp from date and time fields
	 */
	function checkFormatTime( position ) {
		var usher = getUsher( position, null );

		// Get the current values
		var dateField = jQuery(usher.order + ' input[name*=\"date\"]').val(),
		    timeField = jQuery(usher.order + ' input[name*=\"time\"]').val();

		if( !dateField && !timeField ) {
			return '<li>Please check date and time field</li>';
		} else if ( !dateField ) {
			return '<li>Please check the date field</li>';
		} else if ( !timeField ) {
			return '<li>Please check the time field</li>';
		} else {}

		// Build the date/time string
		var stringDateTime = dateField + ' ' + timeField + ':00';

		// Check if it is valid and push in appropriate fields
		if( moment(stringDateTime).isValid() === true ) {
			jQuery(usher.order + ' .datetimeconstruct').addClass('span12').html(moment(stringDateTime).format('dddd, DD.MM.YYYY HH:mm') + ' <i class="icon-ok-circle"></a>');

			// Format the unix timestamp
			var unixTimestamp = moment( stringDateTime ).format('X');
			jQuery(usher.order + ' input[name*=\"transport_timestamp\"]').val(unixTimestamp);
		} else {
			alertify.error("Invalid Date/Timeobject");

			return '<li>Invalid Date/Timeobject</li>';
		}

		return true;
	}


	/*
	 * Funtion to hash the transport orders on save/click by passing the right order id
	 */
	function hashOrder( position, directions ) {
		var retMsg = false;

		for ( var i in directions ) {
			var usher = getUsher( position, directions[i] );

			// Get the live var on every change
			var address_name     = ( $(usher.deep + ' input[name*=\"address_name\"]').val() )     ? $(usher.deep + ' input[name*=\"address_name\"]').val()     : '';
			var address_name_add = ( $(usher.deep + ' input[name*=\"address_name_add\"]').val() ) ? $(usher.deep + ' input[name*=\"address_name_add\"]').val() : '';
			var address_street   = ( $(usher.deep + ' input[name*=\"address_street\"]').val() )   ? $(usher.deep + ' input[name*=\"address_street\"]').val()   : '';
			var address_houseno  = ( $(usher.deep + ' input[name*=\"address_houseno\"]').val() )  ? $(usher.deep + ' input[name*=\"address_houseno\"]').val()  : '';
			var address_zip      = ( $(usher.deep + ' input[name*=\"address_zip\"]').val() )      ? $(usher.deep + ' input[name*=\"address_zip\"]').val()      : '';
			var address_city     = ( $(usher.deep + ' input[name*=\"address_city\"]').val() )     ? $(usher.deep + ' input[name*=\"address_city\"]').val()     : '';
			var address_region   = ( $(usher.deep + ' input[name*=\"address_region\"]').val() )   ? $(usher.deep + ' input[name*=\"address_region\"]').val()   : '';
			var address_country  = ( $(usher.deep + ' input[name*=\"address_country\"]').val() )  ? $(usher.deep + ' input[name*=\"address_country\"]').val()  : '';

			var address_full = address_name + address_name_add + address_street + address_houseno + address_zip + address_city + address_region + address_country;
			if( address_full === '' ) {
				if( directions[i] === 'f' ) {
					retMsg = '<li>The Origin Address (A) is empty!<br><em><small>Fill out at least the Address Name field.</small></em></li>';
					break;
				} else if ( directions[i] === 't' ) {
					retMsg = '<li>The Destination Address (B) is empty!<br><em><small>Fill out at least the Address Name field.</small></em></li>';
					break;
				} else {
					retMsg = '<li>The Address is empty!<br><small>Fill out at least the Address Name field.</small></li>';
					break;
				}
			} else {
				// Hashing the values
				var address_hashEmpty = sha256_digest(''),
				    address_hash = sha256_digest(address_full);

				$(usher.deep + ' input[name*=\"address_hash\"]').val(address_hash);

				// Check and set the hash ancor icon
				var hashF = $(usher.order + ' .address-block[data-direction=\"f\"] input[name*=\"address_hash\"]').val();
				var hashT = $(usher.order + ' .address-block[data-direction=\"t\"] input[name*=\"address_hash\"]').val();
				if( hashF && hashT && address_hash !== address_hashEmpty ) {
					$(usher.order + ' .hash-icon').removeClass('btn-danger').removeClass('btn-warning').addClass('btn-success');
				} else if( hashF || hashT ) {
					$(usher.order + ' .hash-icon').removeClass('btn-danger').removeClass('btn-success').addClass('btn-warning');
				} else {
					$(usher.order + ' .hash-icon').removeClass('btn-warning').removeClass('btn-success').addClass('btn-danger');
				}

				retMsg = true;
			}
		}

		return retMsg;
	}





	/*******************************************
			Address Functions
	*******************************************/
	/*
	 * Function to get the address and return an object with values, also a new formatted address.string_name for usage with renderFunctions
	 */
	function getAddress( position, direction ) {
		var addressContainer = {},
                  addressStringName = '',
		    usher = getUsher( position, direction );
        
		var address_name = $(usher.deep + ' input[name*=\"address_name\"]').val(),
		address_name_add = $(usher.deep + ' input[name*=\"address_name_add\"]').val(),
		address_street   = $(usher.deep + ' input[name*=\"address_street\"]').val(),
		address_houseno  = $(usher.deep + ' input[name*=\"address_houseno\"]').val(),
		address_zip      = $(usher.deep + ' input[name*=\"address_zip\"]').val(),
		address_city     = $(usher.deep + ' input[name*=\"address_city\"]').val(),
		address_region   = $(usher.deep + ' input[name*=\"address_region\"]').val(),
		address_country  = $(usher.deep + ' input[name*=\"address_country\"]').val(),
		address_lat      = $(usher.deep + ' input[name*=\"address_lat\"]').val(),
		address_lng      = $(usher.deep + ' input[name*=\"address_lng\"]').val();

		// Build the address.string_name
		addressStringName += address_street  ? address_street  + ' ' : '',
		addressStringName += address_houseno ? address_houseno + ' ' : '',
		addressStringName += address_zip     ? address_zip     + ' ' : '',
		addressStringName += address_city    ? address_city    + ' ' : '',
		addressStringName += address_region  ? address_region  + ' ' : '',
		addressStringName += address_country ? address_country       : '';

		addressContainer.address_name     = address_name;
		addressContainer.address_name_add = address_name_add;
		addressContainer.address_street   = address_street;
		addressContainer.address_houseno  = address_houseno;
		addressContainer.address_zip      = address_zip;
		addressContainer.address_city     = address_city;
		addressContainer.address_region   = address_region;
		addressContainer.address_country  = address_country;
		addressContainer.address_lat      = address_lat;
		addressContainer.address_lng      = address_lng;
		addressContainer.string_name      = addressStringName;

		return addressContainer;
	}


	/*
	 * Function to set the new (may map formatted) address (incl. latLng) to appropriate input fields
	 */
	function setNewAddress( position, direction, data ) {
		var usher = getUsher( position, direction );

		$(usher.deep + ' input[name*=\"address_street\"]').val(data.address_street);
		$(usher.deep + ' input[name*=\"address_houseno\"]').val(data.address_houseno);
		$(usher.deep + ' input[name*=\"address_zip\"]').val(data.address_zip);
		$(usher.deep + ' input[name*=\"address_city\"]').val(data.address_city);
		$(usher.deep + ' input[name*=\"address_region\"]').val(data.address_region);
		$(usher.deep + ' input[name*=\"address_country\"]').val(data.address_country);

		$(usher.deep + ' input[name*=\"address_lat\"]').val(data.address_lat);
		$(usher.deep + ' input[name*=\"address_lng\"]').val(data.address_lng);

		if( data.address_hash ) {
			$(usher.deep + ' input[name*=\"address_name\"]').val(data.address_name);
			$(usher.deep + ' input[name*=\"address_name_add\"]').val(data.address_name_add);
			$(usher.deep + ' input[name*=\"address_hash\"]').val(data.address_hash);
		}

		$(usher.deep + ' .input-control').attr('readonly', true);

		return;
	}


	/*
	 * Function to set the new (may map formatted) address (incl. latLng) to appropriate input fields
	 */
	function setNewCoordinates( position, direction, data ) {
		var usher = getUsher( position, direction );

		$(usher.deep + ' input[name*=\"address_lat\"]').val(data.address_lat);
		$(usher.deep + ' input[name*=\"address_lng\"]').val(data.address_lng);

		$(usher.deep + ' .input-control').attr('readonly', true);

		return;
	}


	/*
	 * Function to get the address and return an object with values, also a new formatted address.string_name for usage with renderFunctions
	 */
	function getRoute( position ) {
		var routeContainer = {},
		    usher = getUsher( position, null );

		var estimated_time = $(usher.order + ' input[name*=\"estimated_time\"]').val(),
                  estimated_distance = $(usher.order + ' input[name*=\"estimated_distance\"]').val();

		var origin  = getAddress( position, 'f' ),
                  destination = getAddress( position, 't' );

		routeContainer.estimated_time     = estimated_time;
		routeContainer.estimated_distance = estimated_distance;
		routeContainer.origin             = origin;
		routeContainer.destination        = destination;

		return routeContainer;
	}


	/*
	 * Function to set estimated values returned by geo maps to appropriate input fields
	 */
	function setNewRoute( position, mapObject ) {
		var usher = getUsher( position, null );

		$(usher.order + ' input[name*=\"estimated_time\"]').val(mapObject.estimates.duration.value);
		$(usher.order + ' input[name*=\"estimated_distance\"]').val(mapObject.estimates.distance.value);

		return;
	}


	/*
	 * Function to compare 2 addresses (used to compare the address as set in form and the address, returned by geo maps)
	 * Return true for no difference, else return plain html message for further processings
	 *
	 * Use smthng like:
	 *	alertify.confirm(alertHtml, function(e) {
	 *		if( e ) {
	 *			// User click ok
	 *			return false;
	 *		} else {
	 *			// User click cancel
	 *			return true;
	 *		}
	 *	});
	 */
	function compareAddress( userAddress, mapAddress, direction ) {
		var addrDiff = 'NO';
		// compare only postal relevant fields
		if( userAddress.address_street !== mapAddress.address_street ) {
			addrDiff = 'street';
		} else if( userAddress.address_houseno !== mapAddress.address_houseno ) {
			addrDiff = 'houseno';
		} else if( userAddress.address_zip !== mapAddress.address_zip ) {
			addrDiff = 'zip';
		} else if( userAddress.address_city !== mapAddress.address_city ) {
			addrDiff = 'city';
		} else if( userAddress.address_region !== mapAddress.address_region ) {
			addrDiff = 'region';
		} else if( userAddress.address_country !== mapAddress.address_country ) {
			addrDiff = 'country';
		} else {
			return true;
		}

		// Set the direction in header for more usabillity
		var htmlDir;
		if( direction === 'f' ) {
			htmlDir = '(A) ';
		} else if( direction === 't' ) {
			htmlDir = '(B) ';
		} else {
			htmlDir = '';
		}

		if( addrDiff !== 'NO' ) {
			var alertHtml = '';
			alertHtml += '<div class=\"modal-header\"><h3>' + htmlDir + 'Address conflict!</h3></div>';
			alertHtml += '<div class=\"modal-body\">';
				alertHtml += 'The address you\'ve entered could not be clearly identified. Click <strong>OK</strong> to accept the reflecting suggestion, otherwise click cancel.<br><br>';
				alertHtml += '<div class=\"row-fluid\">';
					alertHtml += '<div class=\"span2\">';
					alertHtml += '</div>';
					alertHtml += '<div class=\"span5\">';
						alertHtml += '<strong>Old Address</strong>';
					alertHtml += '</div>';
					alertHtml += '<div class=\"span5\">';
						alertHtml += '<strong>New Address</strong>';
					alertHtml += '</div>';
				alertHtml += '</div>';

				alertHtml += '<div class=\"row-fluid\">';
					alertHtml += '<div class=\"span2\">';
						alertHtml += '<strong>Street</strong>';
					alertHtml += '</div>';
					alertHtml += '<div class=\"span5\">';
						alertHtml += userAddress.address_street;
					alertHtml += '</div>';
					alertHtml += '<div class=\"span5\">';
						alertHtml += mapAddress.address_street;
					alertHtml += '</div>';
				alertHtml += '</div>';
				alertHtml += '<div class=\"row-fluid\">';
					alertHtml += '<div class=\"span2\">';
						alertHtml += '<strong>House No.</strong>';
					alertHtml += '</div>';
					alertHtml += '<div class=\"span5\">';
						alertHtml += userAddress.address_houseno;
					alertHtml += '</div>';
					alertHtml += '<div class=\"span5\">';
						alertHtml += mapAddress.address_houseno;
					alertHtml += '</div>';
				alertHtml += '</div>';
				alertHtml += '<div class=\"row-fluid\">';
					alertHtml += '<div class=\"span2\">';
						alertHtml += '<strong>ZIP</strong>';
					alertHtml += '</div>';
					alertHtml += '<div class=\"span5\">';
						alertHtml += userAddress.address_zip;
					alertHtml += '</div>';
					alertHtml += '<div class=\"span5\">';
						alertHtml += mapAddress.address_zip;
					alertHtml += '</div>';
				alertHtml += '</div>';
				alertHtml += '<div class=\"row-fluid\">';
					alertHtml += '<div class=\"span2\">';
						alertHtml += '<strong>City</strong>';
					alertHtml += '</div>';
					alertHtml += '<div class=\"span5\">';
						alertHtml += userAddress.address_city;
					alertHtml += '</div>';
					alertHtml += '<div class=\"span5\">';
						alertHtml += mapAddress.address_city;
					alertHtml += '</div>';
				alertHtml += '</div>';
				alertHtml += '<div class=\"row-fluid\">';
					alertHtml += '<div class=\"span2\">';
						alertHtml += '<strong>State/Region</strong>';
					alertHtml += '</div>';
					alertHtml += '<div class=\"span5\">';
						alertHtml += userAddress.address_region;
					alertHtml += '</div>';
					alertHtml += '<div class=\"span5\">';
						alertHtml += mapAddress.address_region;
					alertHtml += '</div>';
				alertHtml += '</div>';
				alertHtml += '<div class=\"row-fluid\">';
					alertHtml += '<div class=\"span2\">';
						alertHtml += '<strong>Country</strong>';
					alertHtml += '</div>';
					alertHtml += '<div class=\"span5\">';
						alertHtml += userAddress.address_country;
					alertHtml += '</div>';
					alertHtml += '<div class=\"span5\">';
						alertHtml += mapAddress.address_country;
					alertHtml += '</div>';
				alertHtml += '</div>';

				alertHtml += '<div class=\"no-margin-bottom center alert alert-info\"><small><small>Note: This is an address pre-check to provide geo location informations for the routing and navigation engine. It is possible that there will no geo based informations be stored if you click cancel. It is also possible that the render engine will found (may only approximate) geo informations later. Geo informations are available if the globe icon is green</small></small></div>';

			alertHtml += '</div>';

			return alertHtml;
		} else {
			return true;
		}		
	}





	/*******************************************
		COPY / ADD / CLONE Functions
	*******************************************/
	// Global vars and initials
	window.tocaQuery = {};
	tocaQuery.orderArray = [];
	tocaQuery.checkArray = [];
	tocaQuery.errorArray = [];
	tocaQuery.orderNext = '';
	tocaQuery.orderCurrent = '';
	tocaQuery.formsTotal = [];

	// Add Transport 1
	tocaQuery.orderArray.push(1);
	tocaQuery.checkArray.push(1);
	tocaQuery.formsTotal.push(1);
	tocaQuery.orderNext = 2;

	// Get the first direction to check by testing if we are in single address or route mode
	var dirCheck = $('form[data-order=\"1\"] .address-block[data-direction=\"b\"]');
	if( dirCheck.length == 0 ) {
		tocaQuery.directions = ['f', 't'];
	} else {
		tocaQuery.directions = ['b'];
	}

//	var regex = /^(.*)(\d)+$/i;

	/*
	 * Method to add a new container with empty fields
	 */
	function trans_add() {
		var usher = getUsher( tocaQuery.orderNext );

		// Build container, append and hide related sections
		divContainer = buildContainer( tocaQuery.orderNext );
		jQuery(divContainer).appendTo("#tcopycontainer");
		jQuery(usher.order).fadeIn('slow');
		jQuery(usher.order+ ' .toca-shortinfo').hide();

		// Print out he Message what we've done
		alertify.success('<i class="icon-plus"></i> Created new <strong>Transport ' + tocaQuery.orderNext + '</strong>');

		// Remove the edit because we add an empty order
		jQuery(usher.order + ' .edit-transport').hide();

		// Reinit select2 on the new created html element
		jQuery(usher.order + ' select').select2({width: '100%', minimumResultsForSearch: 10, placeholder: 'Please select'});

		// Add the Id to the tocaOrderArray
		tocaQuery.orderArray.push( tocaQuery.orderNext );
		tocaQuery.checkArray.push( tocaQuery.orderNext );
		tocaQuery.formsTotal.push( tocaQuery.orderNext );

		// Scroll to new position
		var formPosition       = $(usher.order),
		    formPositionOffset = formPosition.offset().top + -50;
		jQuery('html body').animate({scrollTop: formPositionOffset}, 500);

		// Count orderNext
		tocaQuery.orderNext++;
	}


	/*
	 * Method to copy the selected container with all the prefilled values
	 */
	function trans_copy( torderId, copyCheckCB ) {
		var usher = getUsher( tocaQuery.orderNext );
		var usherCurrent = getUsher( torderId );

		// Perform init check to prevent copy from invalid transports
		if( !copyCheckCB ) {
			jQuery(usherCurrent.order + ' .loader div div').addClass('alert-info');

			// Perform init check to prevent copy from invalid transports
			formatCheck( torderId, true );
			return false;
		} else {
			// Build container, append and hide related sections
			divContainer = buildContainer( tocaQuery.orderNext );
			jQuery(divContainer).appendTo("#tcopycontainer");
			jQuery(usher.order).fadeIn('slow');
			jQuery(usher.order + ' .toca-formfields').hide();

			// Get the values from the Container which is copied
			var inputValues = getValues( torderId, true );
			jQuery.each(inputValues, function(i, val) {
				// console.log(i + ' - ' + val);
				jQuery(usher.order + ' [name*=\"' + i + '\"]').val(val);
			});

			// Process and set the shortInfoBar
			var htmlValues = setShortInfoBar( inputValues );
			jQuery(usher.order + ' .torder-sum-left').html(htmlValues.from);
			jQuery(usher.order + ' .torder-sum-right').html(htmlValues.to);

			// Reinit select2 on the new created html element
			jQuery(usher.order + ' select').select2({width: '100%', minimumResultsForSearch: 10, placeholder: 'Please select'});

			// Print out he Message what we've done
			alertify.log('<i class="icon-copy"></i> <strong>Transport ' + torderId + '</strong> copied to <strong>Transport ' + tocaQuery.orderNext + '</strong>');

			// Add the Id to the tocaOrderArray
			tocaQuery.orderArray.push( tocaQuery.orderNext );
			tocaQuery.checkArray.push( tocaQuery.orderNext );
			tocaQuery.formsTotal.push( tocaQuery.orderNext );

			// Remove the edit button, readonly the select and input fields
			jQuery(usherCurrent.order + ' .edit-transport').hide();
			jQuery(usherCurrent.order + ' select').select2('readonly', true);
			jQuery(usherCurrent.order + ' .input-control').attr('readonly', true);
			jQuery(usherCurrent.order + ' .poi-selects').hide();

			// Remove switching abillity for the copied transport
			jQuery(usherCurrent.order + ' .loader div div').removeClass('alert-info');
			jQuery(usherCurrent.order + ' div[id*=\"torder-sum-change\"]').removeAttr('onclick');

			// Scroll to new position
			var formPosition       = $(usher.order),
			    formPositionOffset = formPosition.offset().top + -50;
			jQuery('html body').animate({scrollTop: formPositionOffset}, 500);

			// Count orderNext
			tocaQuery.orderNext++;
		}
	}


	/*
	 * Method to edit the selected container
	 */
	function trans_edit( torderId ) {
		var usher = getUsher( torderId );

		jQuery(usher.order + ' .toca-shortinfo').hide();
		jQuery(usher.order + ' .toca-formfields').slideToggle('fast', 'linear');
		jQuery(usher.order + ' .edit-transport').hide();

		// Remove the hash, geo values from both form fields because user want to edit the values and this may not valid related to the origins
		jQuery(usher.order + ' input[name*=\"f_address_hash\"]').val('');
		jQuery(usher.order + ' input[name*=\"f_address_lat\"]').val('');
		jQuery(usher.order + ' input[name*=\"f_address_lng\"]').val('');
		jQuery(usher.order + ' input[name*=\"f_poi_id\"]').val('');

		jQuery(usher.order + ' input[name*=\"t_address_hash\"]').val('');
		jQuery(usher.order + ' input[name*=\"t_address_lat\"]').val('');
		jQuery(usher.order + ' input[name*=\"t_address_lng\"]').val('');
		jQuery(usher.order + ' input[name*=\"t_poi_id\"]').val('');
	}


	/*
	 * Method to remove the selected container
	 */
	function trans_remove( torderId ) {
		var usher = getUsher( torderId );

		// extend confirm function with modal (bootstrap or alertify)
		alertify.set({
			buttonFocus : 'none'
		});
		alertify.confirm('<div class="modal-header"><h3>Confirm the deletion of Transport ' + torderId + '</h3></div><div class="modal-body">Do you really want to remove <strong>Transport ' + torderId + '</strong>?</div>', function (e) {
			if (e) {
				// user klicked ok
				jQuery(usher.order).fadeOut('slow', function() {
					$(usher.order).remove();
				});

				// Remove the Id from the tocaOrderArray
				tocaQuery.orderArray.splice( tocaQuery.orderArray.indexOf(torderId), 1 );
				tocaQuery.checkArray.splice( tocaQuery.checkArray.indexOf(torderId), 1 );

				console.log( tocaQuery.orderArray );
				console.log( tocaQuery.checkArray );

				// Print out he Message what we've done
				alertify.success('<i class="icon-remove"></i> <strong>Transport ' + torderId + '</strong> successfully removed');
			} else {
				// user clicked cancel
				alertify.warning = alertify.extend('warning');
				alertify.warning('<i class="icon-lightbulb"></i> <strong>Abort action for Transport ' + torderId + '</strong>');
			}
		});
	}


	/*
	 * Method to get the values from given torderId
	 * Using in seperate to determine what fields we want to have for the copy process
	 */
	function getValues( torderId, allValues ) {
		var usher = getUsher( torderId );
		inputValues = {};
		// catch base values
		if( allValues ) {
			inputValues.transport_device = jQuery(usher.order + ' select[name*=\"transport_device\"]').val();
			inputValues.transport_type = jQuery(usher.order + ' select[name*=\"transport_type\"]').val();
			inputValues.order_type = jQuery(usher.order + ' select[name*=\"order_type\"]').val();
		}
		// catch from values
			inputValues.f_poi_id = jQuery(usher.order + ' input[name*=\"f_poi_id\"]').val();
			inputValues.f_address_name = jQuery(usher.order + ' input[name*=\"f_address_name\"]').val();
			inputValues.f_address_name_add = jQuery(usher.order + ' input[name*=\"f_address_name_add\"]').val();
			inputValues.f_address_street = jQuery(usher.order + ' input[name*=\"f_address_street\"]').val();
			inputValues.f_address_houseno = jQuery(usher.order + ' input[name*=\"f_address_houseno\"]').val();
			inputValues.f_address_zip = jQuery(usher.order + ' input[name*=\"f_address_zip\"]').val();
			inputValues.f_address_city = jQuery(usher.order + ' input[name*=\"f_address_city\"]').val();
			inputValues.f_address_region = jQuery(usher.order + ' input[name*=\"f_address_region\"]').val();
			inputValues.f_address_country = jQuery(usher.order + ' input[name*=\"f_address_country\"]').val();
			inputValues.f_address_lat = jQuery(usher.order + ' input[name*=\"f_address_lat\"]').val();
			inputValues.f_address_lng = jQuery(usher.order + ' input[name*=\"f_address_lng\"]').val();
			inputValues.f_address_hash = jQuery(usher.order + ' input[name*=\"f_address_hash\"]').val();
		// catch to values
			inputValues.t_poi_id = jQuery(usher.order + ' input[name*=\"t_poi_id\"]').val();
			inputValues.t_address_name = jQuery(usher.order + ' input[name*=\"t_address_name\"]').val();
			inputValues.t_address_name_add = jQuery(usher.order + ' input[name*=\"t_address_name_add\"]').val();
			inputValues.t_address_street = jQuery(usher.order + ' input[name*=\"t_address_street\"]').val();
			inputValues.t_address_houseno = jQuery(usher.order + ' input[name*=\"t_address_houseno\"]').val();
			inputValues.t_address_zip = jQuery(usher.order + ' input[name*=\"t_address_zip\"]').val();
			inputValues.t_address_city = jQuery(usher.order + ' input[name*=\"t_address_city\"]').val();
			inputValues.t_address_region = jQuery(usher.order + ' input[name*=\"t_address_region\"]').val();
			inputValues.t_address_country = jQuery(usher.order + ' input[name*=\"t_address_country\"]').val();
			inputValues.t_address_lat = jQuery(usher.order + ' input[name*=\"t_address_lat\"]').val();
			inputValues.t_address_lng = jQuery(usher.order + ' input[name*=\"t_address_lng\"]').val();
			inputValues.t_address_hash = jQuery(usher.order + ' input[name*=\"t_address_hash\"]').val();

		return inputValues;
	}


	/*
	 * Method to set the shortInfoBar
	 */
	function setShortInfoBar( inputValues ) {
		var htmlValues = {};

		var orderFrom = '',
		orderTo = '';

		orderFrom += '<div class=\"media\">';
			orderFrom += '<span class=\"pull-left\">';
				orderFrom += '<i class=\"icon-map-marker green\" style=\"font-size: 45px;\"></i>';
			orderFrom += '</span>';
			orderFrom += '<div class="media-body">';
			if( inputValues.f_address_name && inputValues.f_address_name_add ) {
				orderFrom += '<small>' + inputValues.f_address_name;
				orderFrom += ' (' + inputValues.f_address_name_add + ')</small><br>';
			} else {
				if( inputValues.f_address_name ) {
					orderFrom += '<small>' + inputValues.f_address_name + '</small><br>';
				} else if( inputValues.f_address_name_add ) {
					orderFrom += '<small>' + inputValues.f_address_name_add + '</small><br>';
				} else {
					orderFrom += '';
				}
			}
			orderFrom += inputValues.f_address_street;
			orderFrom += ' ' + inputValues.f_address_houseno + ',';
			orderFrom += ' ' + inputValues.f_address_zip;
			orderFrom += ' ' + inputValues.f_address_city + ',';
			orderFrom += ' ' + inputValues.f_address_region;
			orderFrom += ' ' + inputValues.f_address_country;
			orderFrom += '</div>';
		orderFrom += '</div>';


		orderTo += '<div class=\"media\">';
			orderTo += '<span class=\"pull-left\">';
				orderTo += '<i class=\"icon-map-marker red\" style=\"font-size: 45px;\"></i>';
			orderTo += '</span>';
			orderTo += '<div class="media-body">';
			if( inputValues.t_address_name && inputValues.t_address_name_add ) {
				orderTo += '<small>' + inputValues.t_address_name;
				orderTo += ' (' + inputValues.t_address_name_add + ')</small><br>';
			} else {
				if( inputValues.t_address_name ) {
					orderTo += '<small>' + inputValues.t_address_name + '</small><br>';
				} else if( inputValues.t_address_name_add ) {
					orderTo += '<small>' + inputValues.t_address_name_add + '</small><br>';
				} else {}
			}
			orderTo += inputValues.t_address_street;
			orderTo += ' ' + inputValues.t_address_houseno + ',';
			orderTo += ' ' + inputValues.t_address_zip;
			orderTo += ' ' + inputValues.t_address_city + ',';
			orderTo += ' ' + inputValues.t_address_region;
			orderTo += ' ' + inputValues.t_address_country;
			orderTo += '</div>';
		orderTo += '</div>';

		htmlValues.from = orderFrom;
		htmlValues.to = orderTo;

		// console.log( htmlValues );

		return htmlValues;
	}


	/*
	 * Method to switch the hidden values and the shortInfoBar
	 */
	function switchValues( torderId ) {
		var usher = getUsher( torderId );

		// Get the values from the Container which is copied
		var inputValues = getValues( torderId );
		var switchedValues = {},
		    newKey = '';

		// Switch the values and store in new object
		for ( var i in inputValues) {
			if( i[0] === 'f' ) {
				newKey = i.replace('f_', 't_');
				jQuery(usher.order + ' .address-block[data-direction=\"f\"] input[name*=\"' + i + '\"]').val( inputValues[newKey] );
			} else if( i[0] === 't' ) {
				newKey = i.replace('t_', 'f_');
				jQuery(usher.order + ' .address-block[data-direction=\"t\"] input[name*=\"' + i + '\"]').val( inputValues[newKey] );
			} else {
				newKey = i;
			}

			switchedValues[i] = inputValues[newKey];
		}

		// Process and set the shortInfoBar
		var htmlValues = setShortInfoBar( switchedValues );
		jQuery(usher.order + ' .torder-sum-left').html(htmlValues.from);
		jQuery(usher.order + ' .torder-sum-right').html(htmlValues.to);


		// Print out the Message what we've done
		alertify.log('<i class="icon-exchange"></i> Switched directions for <strong>Transport ' + torderId + '</strong>');
	}




	/*******************************************
		Save and Format Functions
	*******************************************/
	function formatCheck( position, copyCheck ) {
		if( !position ) {
			autoCheck();
			return;
		}

		var usher = getUsher( position, null );

		// Turn on the lights
		$(usher.order + ' .loader').fadeIn();
		if( !copyCheck ) {
			$(usher.order + ' .actions').hide();
		}

		var prepMsg = '';

		// Hash the address values with the hash function from tocacore plugin/app
		var hashReturnMsg = hashOrder( position, tocaQuery.directions );
		if ( hashReturnMsg != true ) {
			prepMsg += hashReturnMsg;
		}

		// Check date/time -> timestamp construct first (Check function is in time function)
		var timeReturnMsg = checkFormatTime( position );
		if ( timeReturnMsg != true ) {
			prepMsg += timeReturnMsg;
		}

		// Check the form validity
		if( !$('form[data-order=\"' + position + '\"]')[0].checkValidity() ) {
			// Find all required input fields and set to prepMsg var as list
			$('form[data-order=\"' + position + '\"] input').each(function() {
				if ( $(this).attr("required") && $(this).val() === '' ) {
					var idAttr = $(this).attr('data-placeholder');
					if ( !idAttr ) {
						var idAttr = $(this).attr('placeholder');
					}
					if ( idAttr ) {
						// Add message
						prepMsg += '<li>' + idAttr + '</li>';
					}
				}
			});

			// Find all required select fields and set to prepMsg var as list
			$('form[data-order=\"' + position + '\"] select').each(function() {
				if ( $(this).attr("required") && $(this).val() === '' ) {
					var idAttr = $(this).attr('data-placeholder');
					if ( !idAttr ) {
						var idAttr = $(this).attr('placeholder');
					}
					// Add message
					prepMsg += '<li>' + idAttr + '</li>';
				}
			});
		}

		// Finalcheck of this function
		if( prepMsg !== '' ) {
			// Scroll to current position
			var formPosition       = $('form[data-order=\"' + position + '\"]'),
			    formPositionOffset = formPosition.offset().top + -50;
			jQuery('html body').animate({scrollTop: formPositionOffset}, 500, function() {
				// Show the error message
				alertify.alert('<div class="modal-header"><h3>Logical Check for Transport ' + position + ' failed!</h3></div><div class="modal-body">Sorry, we can\'t verify the form! Please try the following:<br><br><ul><strong>' + prepMsg + '</strong></ul></div>');
				return; // Abort
			});
		} else {
			// If geoSolver function found, move on!
			if( isFunction(geoSolver) ) {
				geoSolver( position, copyCheck );
				return;
			} else {
				// Turn off the lights
				$(usher.order + ' .loader').hide();

				if( !copyCheck ) {
					// Fields are checked, set readonly
					$(usher.order + ' .input-control').attr('readonly', true);
					$(usher.order + ' select').select2('readonly', true);

					// Go back to autoCheck and remove the position/orderId from the checkArray and take next, if one exists
					autoCheck( position, true );
				} else {
					trans_copy( position, true );
				}
			}
		}
	}


	function autoCheck( returnedPosition, validity ) {
		if( returnedPosition && validity ) {
			// Hash the address values with the hash function again
			hashOrder( returnedPosition, tocaQuery.directions );
			tocaQuery.checkArray.shift();
		}

		// Take the first transport from the checkArray and unshift it back to have the pos in the array again, we'll remove this in next round, if the position comes back with true on validity checks
		var checkPosition = tocaQuery.checkArray.shift();
		tocaQuery.checkArray.unshift(checkPosition);

		if( checkPosition ) {
			formatCheck( checkPosition );
			return;
		} else {
			console.log('fertsch zum saven!');
			saveItems();
		}
	}





	/*******************************************
			Ajax functions
	*******************************************/
	/*
	 * Function to save the item(s)
	 */
	function saveItems() {
		var orderPos = tocaQuery.orderArray.pop();
		if( orderPos ) {
			jQuery.ajax({
				type: 'POST',
				dataType: 'json',
				url: 'index.php?option=com_xiveirm&task=api.ajaxsave',
				data: $('#form-transcorder-core, form[data-order=\"' + orderPos + '\"]').serialize(),
			//	async: false,
				beforeSend: function ( dataCallBackBeforeSend ) {
						// NOTE: Checks are done before, because we could have more than one form!
						// console.log( dataCallBackBeforeSend );

						// Runs only once ( we have to add the popped id for this initial check )
						if ( (tocaQuery.orderArray.length + 1) === tocaQuery.formsTotal.length ) {
							// Show the siteready-overlay and override the standard spinner
							var displayInitCountHelper = tocaQuery.orderArray.length + 1;
							jQuery('#siteready-overlay').html('<div style="width: 375px; margin: 50px auto; color: whitesmoke;" class="center"><img src="/media/nawala/images/loader.gif"><br><br><br><br><span id="spaceCounter" style="font-size: 75px; font-weight: bold;">' + displayInitCountHelper + ' / ' + tocaQuery.formsTotal.length + '</span></div>');
							jQuery('#siteready-overlay').css('position', 'fixed');
							jQuery('#siteready-overlay').show();
						}
					},
				success: function ( dataCallBackSuccess ) {
						// console.log( dataCallBackSuccess );

						var usher = getUsher( orderPos, null );

						// Set the counter in the overlay
						jQuery('#spaceCounter').html(tocaQuery.orderArray.length + ' / ' + tocaQuery.formsTotal.length);

						// Check the request and pull back the database id in the form
						if ( dataCallBackSuccess.status === true ) {
							jQuery(usher.order + ' input[name=\"transcorders\[id\]\"]').val( dataCallBackSuccess.id );

							// Set visuals
							jQuery(usher.order + ' .loader div div').addClass('alert-success').html('Successfully saved!');
							jQuery(usher.order + ' .loader').fadeIn();
						} else {
							// An error occured, push the popped key to the tocaErrorKey array for later manual processes
							tocaQuery.errorArray.push(orderPos);
							console.log( tocaQuery.errorArray );

							// Override the loader with the error message
							jQuery(usher.order + ' .loader div div').html('<div class="alert alert-error"><strong><em>Code: ' + dataCallBackSuccess.code + '</em></strong><br>' + dataCallBackSuccess.message + '</div>');

							// Do some visual magic
							jQuery(usher.order + ' .loader').fadeIn();
						}
					},
				error: function( dataCallBackError ) {
						var usher = getUsher( orderPos, null );

						console.log( dataCallBackError );

						// An error occured, push the popped key back to the array
						tocaQuery.errorArray.push(orderPos);
						console.log( tocaQuery.errorArray );

						// Do some visual magic
						jQuery(usher.order + ' .loader').fadeIn();

						// Override the loader with the error message
						jQuery(usher.order + ' .loader div div').html('<div class="alert alert-error"><strong><em>Code: ' + dataCallBackError.code + '</em></strong><br>' + dataCallBackError.message + '</div>');
						alertify.error('<div class="alert alert-error"><strong><em>Code: ' + dataCallBackError.code + '</em></strong><br>' + dataCallBackError.message + '</div>');
					},
				complete: function( dataCallBackComplete ) {
						if ( tocaQuery.orderArray.length !== 0 ) {
							saveItems();
						} else {
							// Render the first route in map-canvas
							renderRoute( 1 );

							jQuery('#siteready-overlay').hide();

							// Hide the buttons
							jQuery('#form-buttons').fadeOut();
							jQuery('#loading-btn-edit').button('complete').button('reset').fadeIn();

							var formPosition       = $('#form-transcorder-core'),
//							var formPosition       = $('form[data-order=\"' + orderPos + '\"]'),
							    formPositionOffset = formPosition.offset().top + -50;
							jQuery('html body').animate({scrollTop: formPositionOffset}, 500);

							if( tocaQuery.errorArray.length !== 0 ) {
								alertify.alert('We have unsaved transport items! Please check the transports!');
							}
						}
					}
			});
		} else {
			// If someone click on save again
			alertify.alert('You have already saved all items!');
		}
	}


	/*
	 * Function to edit the item
	 */
	$("#loading-btn-edit").click(function() {
		var editButton = this;

		// TODO: Better comments for this :)

		// If we added more than one transport we shoud delete all the copied transports from the DOM to prevent verwirrung, but we have to ask the user before, if he want really to edit the transport and remove the list of other transports. Show him that he have to join all transports manually!

		// Check and make sure if the transport 1 is blown away from save function, while user still clicks edit -> save, edit -> save, edit -> save that the first transport is still there
		// Should only be used if the user want to edit the first transport for existing items/orders!
		if( !tocaQuery.orderArray[0] ) { tocaQuery.orderArray.push(1); }
		if( !tocaQuery.checkArray[0] ) { tocaQuery.checkArray.push(1); }

		$(editButton).addClass("btn-warning").button("loading");

		jQuery.post('index.php?option=com_xiveirm&task=api.ajaxcheckout', {'irmapi[id]': $("#order_cid-1").val(), 'irmapi[coreapp]': "transcorders", '<?php echo NFWSession::getToken(); ?>': 1},
			function(data){
				// console.log(data);
				if(data.status === true){
					alertify.warning = alertify.extend('warning');
					alertify.warning('<i class="icon-signout"></i> <?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_CHECKED_OUT_INFO_TITLE'); ?> <br> <?php echo JText::sprintf('COM_XIVEIRM_CONTACT_FORM_CHECKED_OUT_INFO_BODY', $full_name); ?>');

					// Hide the edit button
					$(editButton).fadeOut();

					// Set a checkeditform value to get the prevent leave message even if nothing has changed, but user is now in edit screen
					$("#checkEditForm").val("1");

					// Show the form buttons, disable .link-control
					$("#form-buttons, #clear-address-icon-helper").fadeIn();
					$(".link-control").attr("disabled", true);

					// Remove all disabled from fields with .input-control
					$(".input-control").attr("disabled", false);
					$(".input-control").attr("readonly", false);
				} else {
					alertify.error('<i class="icon-warning-sign"></i> An error occured: <br> Error code: ' + data.code + '<br><br>error message: ' + data.message + '<br><br>If this error persists, please contact the support immediately with the given error!');

					$("#loading-btn-edit").removeClass("btn-warning").button("error").addClass("btn-danger");
				}
			},
		"json");
	});


	/*
	 * Method to get an OrderId/EmergencyId based and related to the timestamp and may unique in database
	 *
	 */
	function getOrderIdFromDB( torderId ) {
		
	}






	/*******************************************
		HTML TRANSPORT ORDER CONTAINER
	*******************************************/
	/*
	 * Method to build the container if copy or add
	 */
	function buildContainer( torderId ) {
		var htmlOut = '';
		htmlOut += '<form data-order=\"' + torderId + '\" style=\"display: none;\">';
		htmlOut += '<div class=\"cloned-transport\">';
			htmlOut += '<div class=\"widget-box transparent\">';

				htmlOut += '<div class=\"widget-header\">';
					htmlOut += '<h4 class=\"lighter green\"><div class=\"input-medium\">Transport: ' + torderId + '</div></h4>';
					htmlOut += '<div class=\"widget-toolbar no-border\">';
						htmlOut += '<div class=\"btn-group\">';
							htmlOut += '<span class=\"geo-icon btn btn-mini">';
								htmlOut += '<i class=\"icon-globe icon-only\"></i>';
							htmlOut += '</span>';
							htmlOut += '<span class=\"hash-icon btn btn-mini\">';
								htmlOut += '<i class=\"icon-cny icon-only\"></i>';
							htmlOut += '</span>';
							htmlOut += '<span class=\"btn btn-mini btn-inverse ordernumber\">';
								htmlOut += '<i class=\"icon-ambulance icon-only\"></i> <span>N/A</span>';
							htmlOut += '</span>';
							htmlOut += '<span class=\"btn btn-mini btn-light manurouter\">';
								htmlOut += '<i class=\"icon-compass icon-large icon-only\"></i>';
							htmlOut += '</span>';
							htmlOut += '<span class=\"btn btn-mini btn-light duration\" style=\"display: none;\">';
								htmlOut += '<i class=\"icon-time\"></i> <span>N/A</span>';
							htmlOut += '</span>';
							htmlOut += '<span class=\"btn btn-mini btn-light distance\" style=\"display: none;\">';
								htmlOut += '<i class=\"icon-road\"></i> <span>N/A</span>';
							htmlOut += '</span>';
						htmlOut += '</div>';
					htmlOut += '</div>';
				htmlOut += '</div>';

				htmlOut += '<div class=\"widget-body\">';
					htmlOut += '<div class=\"widget-main padding-6 no-padding-left no-padding-right\">';
						htmlOut += '<div class=\"row-fluid\">';

							htmlOut += '<div class=\"span8\">';
								htmlOut += '<div class=\"well toca-shortinfo\">';
									htmlOut += '<div class=\"span5 torder-sum-left\">N/A</div>';
									htmlOut += '<div class=\"span2\" id=\"torder-sum-change-' + torderId + '\" onClick=\"switchValues(' + torderId + ')\" style=\"vertical-align: middle; font-size: 30px; line-height: 40px; cursor: pointer;\">';
										htmlOut += '<span class=\"hidden-phone\"><i class=\"icon-exchange\"></i></span>';
										htmlOut += '<span class=\"visible-phone\"><i class=\"icon-exchange icon-rotate-90\"></i></span>';
									htmlOut += '</div>';
									htmlOut += '<div class=\"span5 torder-sum-right\">N/A</div>';
									htmlOut += '<div class="clearfix"></div>';
								htmlOut += '</div>';
								htmlOut += '<div class=\"controls controls-row toca-formfields\">';
									htmlOut += '<div class=\"span6\">';
										htmlOut += '<div class=\"well\">';
											htmlOut += '<div class=\"control-group address-block\" data-direction=\"f\">';
												htmlOut += '<label class=\"control-label contact-home\"><i class=\"icon-map-marker icon-large green\"></i> <?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_ADDRESS_FROM'); ?></label>';
												htmlOut += '<div class=\"inner-address-block\">';
													htmlOut += '<div class=\"controls\">';
														htmlOut += '<input type=\"text\" id=\"f_address_name-' + torderId + '\" name=\"transcorders[f_address_name]\" class=\"input-control span12\" placeholder=\"<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_NAME'); ?>\" maxlength=\"150\" value />';
													htmlOut += '</div>';
													htmlOut += '<div class=\"controls\">';
														htmlOut += '<input type=\"text\" id=\"f_address_name_add-' + torderId + '\" name=\"transcorders[f_address_name_add]\" class=\"input-control span12\" placeholder=\"<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_NAME_ADD'); ?>\" maxlength=\"100\" />';
													htmlOut += '</div>';
													htmlOut += '<div class=\"controls controls-row\">';
														htmlOut += '<input type=\"text\" id=\"f_address_street-' + torderId + '\" name=\"transcorders[f_address_street]\" class=\"input-control span9\" placeholder=\"<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_STREET'); ?>\" maxlength=\"100\" />';
														htmlOut += '<input type=\"text\" id=\"f_address_houseno-' + torderId + '\" name=\"transcorders[f_address_houseno]\" class=\"input-control span3\" placeholder=\"<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_HOUSENO'); ?>\" maxlength=\"10\" />';
													htmlOut += '</div>';
													htmlOut += '<div class=\"controls controls-row\">';
														htmlOut += '<input type=\"text\" id=\"f_address_zip-' + torderId + '\" name=\"transcorders[f_address_zip]\" class=\"input-control span4\" placeholder=\"<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_ZIP'); ?>\" maxlength=\"10\" />';
														htmlOut += '<input type=\"text\" id=\"f_address_city-' + torderId + '\" name=\"transcorders[f_address_city]\" class=\"input-control span8\" placeholder=\"<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_CITY'); ?>\" maxlength=\"100\" />';
													htmlOut += '</div>';
													htmlOut += '<div class=\"controls controls-row\">';
														htmlOut += '<input type=\"text\" id=\"f_address_region-' + torderId + '\" name=\"transcorders[f_address_region]\" class=\"input-control span6\" placeholder=\"<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_REGION'); ?>\" />';
														htmlOut += '<input type=\"text\" id=\"f_address_country-' + torderId + '\" name=\"transcorders[f_address_country]\" class=\"input-control span6\" placeholder=\"<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_COUNTRY'); ?>\" />';
													htmlOut += '</div>';
													htmlOut += '<div class=\"geo-coords\">';
														htmlOut += '<input type=\"hidden\" name=\"transcorders[f_address_lat]\" id=\"f_address_lat-' + torderId + '\" />';
														htmlOut += '<input type=\"hidden\" name=\"transcorders[f_address_lng]\" id=\"f_address_lng-' + torderId + '\" />';
													htmlOut += '</div>';
													htmlOut += '<input type=\"hidden\" name=\"transcorders[f_address_hash]\" id=\"f_address_hash-' + torderId + '\" />';
													htmlOut += '<input type=\"hidden\" name=\"transcorders[f_poi_id]\" id=\"f_poi_id-' + torderId + '\" />';
												htmlOut += '</div>';
											htmlOut += '</div>';
										htmlOut += '</div>';
									htmlOut += '</div>';
									htmlOut += '<div class=\"span6\">';
										htmlOut += '<div class=\"well\">';
											htmlOut += '<div class=\"control-group address-block\" data-direction=\"t\">';
												htmlOut += '<label class=\"control-label contact-home\"><i class=\"icon-map-marker icon-large red\"></i> <?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_ADDRESS_TO'); ?></label>';
												htmlOut += '<div class=\"inner-address-block\">';
													htmlOut += '<div class=\"controls\">';
														htmlOut += '<input type=\"text\" id=\"t_address_name-' + torderId + '\" name=\"transcorders[t_address_name]\" class=\"input-control span12\" placeholder=\"<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_NAME'); ?>\" maxlength=\"150\" value />';
													htmlOut += '</div>';
													htmlOut += '<div class=\"controls\">';
														htmlOut += '<input type=\"text\" id=\"t_address_name_add-' + torderId + '\" name=\"transcorders[t_address_name_add]\" class=\"input-control span12\" placeholder=\"<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_NAME_ADD'); ?>\" maxlength=\"100\" />';
													htmlOut += '</div>';
													htmlOut += '<div class=\"controls controls-row\">';
														htmlOut += '<input type=\"text\" id=\"t_address_street-' + torderId + '\" name=\"transcorders[t_address_street]\" class=\"input-control span9\" placeholder=\"<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_STREET'); ?>\" maxlength=\"100\" />';
														htmlOut += '<input type=\"text\" id=\"t_address_houseno-' + torderId + '\" name=\"transcorders[t_address_houseno]\" class=\"input-control span3\" placeholder=\"<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_HOUSENO'); ?>\" maxlength=\"10\" />';
													htmlOut += '</div>';
													htmlOut += '<div class=\"controls controls-row\">';
														htmlOut += '<input type=\"text\" id=\"t_address_zip-' + torderId + '\" name=\"transcorders[t_address_zip]\" class=\"input-control span4\" placeholder=\"<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_ZIP'); ?>\" maxlength=\"10\" />';
														htmlOut += '<input type=\"text\" id=\"t_address_city-' + torderId + '\" name=\"transcorders[t_address_city]\" class=\"input-control span8\" placeholder=\"<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_CITY'); ?>\" maxlength=\"100\" />';
													htmlOut += '</div>';
													htmlOut += '<div class=\"controls controls-row\">';
														htmlOut += '<input type=\"text\" id=\"t_address_region-' + torderId + '\" name=\"transcorders[t_address_region]\" class=\"input-control span6\" placeholder=\"<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_REGION'); ?>\" />';
														htmlOut += '<input type=\"text\" id=\"t_address_country-' + torderId + '\" name=\"transcorders[t_address_country]\" class=\"input-control span6\" placeholder=\"<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_COUNTRY'); ?>\" />';
													htmlOut += '</div>';
													htmlOut += '<div class=\"geo-coords\">';
														htmlOut += '<input type=\"hidden\" name=\"transcorders[t_address_lat]\" id=\"t_address_lat-' + torderId + '\" />';
														htmlOut += '<input type=\"hidden\" name=\"transcorders[t_address_lng]\" id=\"t_address_lng-' + torderId + '\" />';
													htmlOut += '</div>';
													htmlOut += '<input type=\"hidden\" name=\"transcorders[t_address_hash]\" id=\"t_address_hash-' + torderId + '\" />';
													htmlOut += '<input type=\"hidden\" name=\"transcorders[t_poi_id]\" id=\"t_poi_id-' + torderId + '\" />';
												htmlOut += '</div>';
											htmlOut += '</div>';
										htmlOut += '</div>';
									htmlOut += '</div>';
								htmlOut += '</div>'; // <!-- /.controls .controls-row .extended- -->
							htmlOut += '</div><!-- /.span8 -->';

							htmlOut += '<div class=\"span4\">';
								htmlOut += '<div class=\"well\">';
									htmlOut += '<div class=\"controls controls-row\">';
										htmlOut += '<span class=\"span6\">';
											htmlOut += '<input name=\"transcorders[date]\" type=\"date\" class=\"span12 input-control center\" style=\"font-size: 23px; height: 40px;\" required />';
										htmlOut += '</span>';
										htmlOut += '<span class=\"span6\">';
											htmlOut += '<input name=\"transcorders[time]\" type=\"time\" class=\"span12 input-control center\" style=\"font-size: 23px; height: 40px;\" required />';
										htmlOut += '</span>';
										htmlOut += '<span class=\"datetimeconstruct center\"></span>';
									htmlOut += '</div>';

									htmlOut += '<div class=\"toca-formfields\">';
										htmlOut += '<div class=\"control-group\">';
											htmlOut += '<label class=\"control-label\"><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_TRANSPORT_DEVICE_AND_TYPE'); ?></label>';
											htmlOut += '<div class=\"controls controls-row\">';
												htmlOut += '<div class=\"span6\">';
													htmlOut += '<select id=\"transport_device-' + torderId + '\" name=\"transcorders[transport_device]\" class=\"select2 input-control\" data-placeholder=\"<?php echo JText::_('COM_XIVETRANSCORDER_FORM_OPTIONLIST_TRANSPORT_DEVICE_PLEASE_SELECT'); ?>\" required>';
														htmlOut += '<option></option>';
														<?php
															foreach ( $transportDeviceOptions as $key => $value ) {
																echo 'htmlOut += \'<option value=\"' . $key . '\">' . $value . '</option>\';';
															}
														?>
													htmlOut += '</select>';
												htmlOut += '</div>';
												htmlOut += '<div class=\"span6\">';
													htmlOut += '<select id=\"transport_type-' + torderId + '\" name=\"transcorders[transport_type]\" class=\"select2 input-control\" data-placeholder=\"<?php echo JText::_('COM_XIVETRANSCORDER_FORM_OPTIONLIST_TRANSPORT_TYPE_PLEASE_SELECT'); ?>\" required>';
														htmlOut += '<option></option>';
														<?php
															foreach ( $transportTypeOptions as $key => $value ) {
																echo 'htmlOut += \'<option value=\"' . $key . '\">' . $value . '</option>\';';
															}
														?>
													htmlOut += '</select>';
												htmlOut += '</div>';
											htmlOut += '</div>';
										htmlOut += '</div><!-- #end control group -->';

										htmlOut += '<div class=\"control-group\">';
											htmlOut += '<label class=\"control-label\"><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_ORDER_TYPE'); ?></label>';
											htmlOut += '<div class=\"controls controls-row\">';
												htmlOut += '<div class=\"span12\">';
													htmlOut += '<select id=\"order_type-' + torderId + '\" name=\"transcorders[order_type]\" class=\"select2 input-control\" data-placeholder=\"<?php echo JText::_('COM_XIVETRANSCORDER_FORM_OPTIONLIST_ORDER_TYPE_PLEASE_SELECT'); ?>\" required>';
														htmlOut += '<option></option>';
														<?php
															foreach ( $orderTypeOptions as $key => $value ) {
																echo 'htmlOut += \'<option value=\"' . $key . '\">' . $value . '</option>\';';
															}
														?>
													htmlOut += '</select>';
												htmlOut += '</div>';
											htmlOut += '</div>';
										htmlOut += '</div><!-- #end control group -->';
									htmlOut += '</div><!-- /.extended- -->';

									htmlOut += '<div class=\"control-group actions\">';
										htmlOut += '<div class=\"controls controls-row center\">';
											htmlOut += '<div class=\"btn-group\">';
												htmlOut += '<a class=\"btn btn-warning edit-transport\" onClick=\"trans_edit(' + torderId + ')\">';
													htmlOut += '<i class=\"icon-edit\"></i> <?php echo JText::_('COM_XIVETRANSCORDER_EDIT_ITEM'); ?>';
												htmlOut += '</a>';
												htmlOut += '<a class=\"btn btn-success xtooltip trans-add\" onClick=\"trans_add(' + torderId + ')\">';
													htmlOut += '<i class=\"icon-plus\"></i> <?php echo JText::_('COM_XIVETRANSCORDER_ADD_EMPTY_ITEM'); ?>';
												htmlOut += '</a>';
												htmlOut += '<a class=\"btn btn-primary trans-copy\" onClick=\"trans_copy(' + torderId + ')\">';
													htmlOut += '<i class=\"icon-copy\"></i> <?php echo JText::_('COM_XIVETRANSCORDER_COPY_THIS_ITEM'); ?>';
												htmlOut += '</a>';
												htmlOut += '<a class=\"btn btn-danger trans-remove\" onClick=\"trans_remove(' + torderId + ')\">';
													htmlOut += '<i class=\"icon-remove\"></i> <?php echo JText::_('COM_XIVETRANSCORDER_DELETE_ITEM'); ?>';
												htmlOut += '</a>';
											htmlOut += '</div>';
										htmlOut += '</div>';
									htmlOut += '</div><!-- #end control group -->';
									htmlOut += '<div class=\"control-group loader\" style=\"display: none;\">';
										htmlOut += '<div class=\"controls controls-row margin-top\">';
											htmlOut += '<div class=\"alert center\">';
												htmlOut += '<img src=\"/media/nawala/images/loader.gif\">';
											htmlOut += '</div>';
										htmlOut += '</div>';
									htmlOut += '</div>';
								htmlOut += '</div><!-- /.well -->';
							htmlOut += '</div><!-- /.span4 -->';
						htmlOut += '</div><!-- /.row-fluid -->';
					htmlOut += '</div><!-- /.widget-main padding-6 no-padding-left no-padding-right -->';
				htmlOut += '</div><!-- /.widget-body -->';
			htmlOut += '</div><!-- /.widget-box .transparent -->';

			htmlOut += '<input type=\"hidden\" name=\"transcorders[id]\" id=\"order_cid-' + torderId + '\" value=\"0\" />';
			htmlOut += '<input type=\"hidden\" name=\"transcorders[order_id]\" id=\"order_id-' + torderId + '\" value />';
			htmlOut += '<input type=\"hidden\" name=\"transcorders[transport_timestamp]\" id=\"transport_timestamp-' + torderId + '\" value required />';
			htmlOut += '<input type=\"hidden\" name=\"transcorders[estimated_time]\" id=\"estimated_time-' + torderId + '\" value />';
			htmlOut += '<input type=\"hidden\" name=\"transcorders[estimated_distance]\" id=\"estimated_distance-' + torderId + '\" value />';
			htmlOut += '<input type=\"hidden\" name=\"transcorders[state]\" value=\"1\" />';

		htmlOut += '</div>';
		htmlOut += '</form><!-- /.torder-' + torderId + ' -->';

		return htmlOut;
	}
