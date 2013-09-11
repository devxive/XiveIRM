<?php
/**
 * @version     6.0.0
 * @package     com_xiveirm
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
	// Global vars and initials
	window.tocaQuery = {};
//	tocaQuery.orderArray = [];
//	tocaQuery.checkArray = [];
//	tocaQuery.errorArray = [];
//	tocaQuery.orderNext = '';
//	tocaQuery.orderCurrent = '';
//	tocaQuery.formsTotal = [];

	// Add Transport 1
//	tocaQuery.orderArray.push(1);
//	tocaQuery.checkArray.push(1);
//	tocaQuery.formsTotal.push(1);
//	tocaQuery.orderNext = 2;

	// Get the first direction to check by testing if we are in single address or route mode
	var dirCheck = $('form[data-order=\"1\"] .address-block[data-direction=\"b\"]');
	if( dirCheck.length == 0 ) {
		tocaQuery.directions = ['f', 't'];
	} else {
		tocaQuery.directions = ['b'];
	}


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
	function setNewAddress( position, direction, data, setReadOnly ) {
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

		// Set readonly attribute
		if( setReadOnly ) {
			$(usher.deep + ' .input-control').attr('readonly', true);
		}

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


	/*
	 * Funtion to hash the transport orders on save/click by passing the right order id
	 */
	function hashAddress( position, directions ) {
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
				var hashB = $(usher.order + ' .address-block[data-direction=\"b\"] input[name*=\"address_hash\"]').val();
				if( hashB && address_hash !== address_hashEmpty ) {
					$('#address-hash-verified').removeClass('red').removeClass('orange').addClass('green');
				} else if( hashB && address_hash === address_hashEmpty ) {
					$('#address-hash-verified').removeClass('red').removeClass('green').addClass('orange');
				} else {
					$('#address-hash-verified').removeClass('orange').removeClass('green').addClass('red');
				}

				retMsg = true;
			}
		}

		return retMsg;
	}





	/*******************************************
		Check and Format Functions
	*******************************************/
	function saveCheck( returnedCheckBack, confirmBox ) {
		if( returnedCheckBack ) {
			// Hash again
			hashAddress( 1, 'b' );
			jQuery('#address-hash-verified').removeClass('red').addClass('green');

			saveForm();
			return;
		}

		var usher = getUsher( 1, null );

		var prepMsg = '';

		// Hash the address values
		var hashReturnMsg = hashAddress( 1, 'b' );
		if ( hashReturnMsg != true ) {
			prepMsg += hashReturnMsg;
		}

		// Check the form validity
		if( !$('form[data-order=\"1\"]')[0].checkValidity() ) {
			// Find all required input fields and set to prepMsg var as list
			$('form[data-order=\"1\"] input').each(function() {
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
			$('form[data-order=\"1\"] select').each(function() {
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
			var formPosition       = $('form[data-order=\"1\"]'),
			    formPositionOffset = formPosition.offset().top + -150;
			jQuery('html body').animate({scrollTop: formPositionOffset}, 500, function() {
				// Show the error message
				alertify.alert('<div class="modal-header"><h3>Logical Form check failed!</h3></div><div class="modal-body">Sorry, we can\'t verify the form! Please try the following:<br><br><ul><strong>' + prepMsg + '</strong></ul></div>');
				return; // Abort
			});
		} else {
			// If geoSolver function found, move on!
			if( isFunction(geoSolver) ) {
				geoSolver( 1, false );
				return;
			} else {
				// Go back to autoCheck and remove the position/orderId from the checkArray and take next, if one exists
				saveCheck( true );
			}
		}
	}