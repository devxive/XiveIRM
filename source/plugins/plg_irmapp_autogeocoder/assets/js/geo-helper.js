/*
Copyright 2013 devXive - research and development

Version: 6.0.0 Timestamp: Mon Aug 12 15:04:12 PDT 2013

This software is licensed under the devXive Proprietary Use License (the "devXive License") or the GNU
General Public License version 2 (the "GPL License"). You may choose either license to govern your
use of this software only upon the condition that you accept all of the terms of either the devXive
License or the GPL License.

You may obtain a copy of the devXive License and the GPL License at:

    http://www.devxive.com/license
    http://www.gnu.org/licenses/gpl-2.0.html

Unless required by applicable law or agreed to in writing, software distributed under the
devXive License or the GPL Licesnse is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR
CONDITIONS OF ANY KIND, either express or implied. See the devXive License and the GPL License for
the specific language governing permissions and limitations under the devXive License and the GPL License.
*/
	/*******************************************
			SOLVER Functions
	*******************************************/
	/* 
	 * Function to check wether or not if all sets are ok. This is the main to check for everything!
	 */
	function geoSolver( order, copyCheck, returnedCheckBack ) {
		// set validRouteAddresses to true, they will be overwritten by the addr_latlng check below (prevents the execution of the verifyRoute function until both are valid)
		var validRouteAddresses = true,
		    checkBack = {},
		    dirHelper = '';

		// Check the directions
		if( tocaQuery.directions[0] === 'b' ) {
			dirHelper = 'SINGLE';
		} else {
			dirHelper = 'ROUTE';
		}


		// Loop through directions
		for ( var i in tocaQuery.directions ) {
			// Get the address values
			var addr = getAddress( order, tocaQuery.directions[i] );

			if( !addr.address_lat || !addr.address_lng ) {
				validRouteAddresses = false;

				checkBack.address = addr;
				checkBack.order = order;
				checkBack.direction = tocaQuery.directions[i];

				setTimeout(function() {
					if( dirHelper === 'ROUTE' ) {
						verifyAddress( checkBack, copyCheck );
					} else {
						verifySingleAddress( checkBack );
					}
				}, 500);
				break;
			}
		}

		if( validRouteAddresses !== false ) {
			var usher = getUsher( order );

			if( dirHelper === 'ROUTE' ) {
				// ##### ROUTE #####
				var route = getRoute( order );

				// Set the geoIcon based on the geo coordinates we have
				setGeoIcon( order, route );

				if( !route.estimated_time && !route.estimated_distance ) {
					checkBack.route = route;
					checkBack.order = order;

					// Adjusting milliseconds to prevent closing of second window if both addresses have to verify
					setTimeout(function() {
						verifyRoute( checkBack, copyCheck );
					}, 500);
				} else {
					// Turn off the lights
					$(usher.order + ' .loader').hide();

					if( !copyCheck ) {
						// Fields are checked, set readonly
						$(usher.order + ' .input-control').attr('readonly', true);
						$(usher.order + ' select').select2('readonly', true);

						// Go back to autoCheck and remove the position/orderId from the checkArray and take next, if one exists
						autoCheck( order, true );
					} else {
						trans_copy( order, true );
					}
				}
			} else {
				// ##### SINGLE ADDRESS #####
				// Goto back to saveCheck function determine its all ok
				if( returnedCheckBack ) {
					saveCheck( returnedCheckBack );
				} else {
					saveCheck( true );
				}
			}
		}
	}


	function setGeoIcon( order, route ) {
		var usher = getUsher ( order, null );

		if( (route.origin.address_lat && route.origin.address_lng) && (route.destination.address_lat && route.destination.address_lng) ) {
			jQuery(usher.order + ' span.geo-icon').removeClass('btn-danger').removeClass('btn-warning').addClass('btn-success');
		} else if( (route.origin.address_lat && route.origin.address_lng) || (route.destination.address_lat && route.destination.address_lng) ) {
			jQuery(usher.order + ' span.geo-icon').removeClass('btn-danger').removeClass('btn-success').addClass('btn-warning');
		} else {
			jQuery(usher.order + ' span.geo-icon').removeClass('btn-warning').removeClass('btn-success').addClass('btn-danger');
		}
	}




	/*******************************************
			Map Functions
	*******************************************/
	/*
	 * Funtion to verify the address right from the gmap3 source code to catch and validate address components and also the geo latlng
	 */
	function verifySingleAddress( checkBack ) {
		alertify.log('<i class="icon-globe icon-large"></i> Checking Home address...');

		$('#map-canvas').gmap3({
			getlatlng: {
				address: checkBack.address.string_name,
				callback: function(results, status) {
					if( status == google.maps.GeocoderStatus.OK ) {
						var addressHelper = {},
						    addressArray  = {};

						var geometry = results[0].geometry,
						    position = geometry.location,
						    address  = results[0].address_components;

						 var   positions = results[0].geometry.location

						// Format latlng and reject to object
						var posCounter = 0, posHelper = {};
						for ( var i in positions ) {
							if( i.match(/^.b$/) ) {
								posHelper[posCounter] = positions[i];
								posCounter++;
							}
						}

						// Set the lat (from Format latlng and reject to object)
						addressArray.address_lat = posHelper[0];
						// Set the lng (from Format latlng and reject to object)
						addressArray.address_lng = posHelper[1];

						// Preformat the new address, we need to throw in input fields and for address comparison
						jQuery.each(address, function(key, val) {
							$.each(val.types, function(key2, val2) {
								addressHelper[val2] = val.long_name;
							});
						});
						// Set the street ( route ) if given
						addressArray.address_street = (addressHelper.route) ? addressHelper.route : '';
						// Set the street ( street_number ) if given
						addressArray.address_houseno = (addressHelper.street_number) ? addressHelper.street_number : '';
						// Set the zip ( postal_code ) if given
						addressArray.address_zip = (addressHelper.postal_code) ? addressHelper.postal_code : '';
						// Set the city ( locality, political ) if given
						addressArray.address_city = (addressHelper.locality) ? addressHelper.locality : '';
						// Set the region ( administrative_area_level_1, political ) if given
						addressArray.address_region = (addressHelper.administrative_area_level_1) ? addressHelper.administrative_area_level_1 : '';
						// Set the country ( country, political ) if given
						addressArray.address_country = (addressHelper.country) ? addressHelper.country : '';

						// Add new address
						checkBack.newAddress = addressArray;

						// Compare addresses
						var comparisonCheck = compareAddress( checkBack.address, addressArray, checkBack.direction );
						if( comparisonCheck != true ) {
							alertify.set({
								buttonFocus : 'none'
							});
							alertify.confirm(comparisonCheck, function(e) {
								if( e ) {
									// User click ok
									setNewAddress( checkBack.order, checkBack.direction, addressArray, true );

									// Show Map with new Address
									jQuery('#map-body').show();
									initialize(addressArray.address_lat, addressArray.address_lng, 15, true);

									// Set GeoCoder Icon
									jQuery('#address-geo-verified').removeClass('red').removeClass('orange').addClass('green').show();

									// Move back to the solver
									saveCheck( checkBack );
								} else {
									// User click cancel inject 0 as geopos for latLng to prevent further checks
									checkBack.address.address_lat = 0;
									checkBack.address.address_lng = 0;
									setNewAddress( checkBack.order, checkBack.direction, checkBack.address, false );

									// Hide Map
									jQuery('#map-body').fadeOut();

									// Set GeoCoder Icon
									jQuery('#address-geo-verified').removeClass('red').removeClass('green').addClass('orange').show();

									// Do nothing, because user may want to edit the address again
									return;
								}
							});
						} else {
							setNewCoordinates( checkBack.order, checkBack.direction, addressArray );

							// Set GeoCoder Icon
							jQuery('#address-geo-verified').removeClass('red').removeClass('orange').addClass('green').show();

							setTimeout(function() {
								saveCheck( checkBack );
							}, 500);
						}
					} else {
						alertify.error( '<i class=\"icon-warning-sign icon-large\"></i> Transport: ' + checkBack.order + ' An error occured on geocoding. May the service is temporary unavailable. Automatic retry in 30 seconds!' );
						setTimeout(function() {
							geoSolver( checkBack.order, copyCheck );
						}, 30000);
					}
				}
			}
		});
	}


	/*
	 * Funtion to verify the address right from the gmap3 source code to catch and validate address components and also the geo latlng
	 */
	function verifyAddress( checkBack, copyCheck ) {
		var opDir = '';
		if( checkBack.direction === 'f' ) {
			opDir = 'Origin';
		} else if( checkBack.direction === 't' ) {
			opDir = 'Destination';
		} else {
			opDir = 'Home';
		}
		alertify.log('<i class="icon-globe icon-large"></i> Checking ' + opDir + ' address...');

		$('#map-canvas').gmap3({
			getlatlng: {
				address: checkBack.address.string_name,
				callback: function(results, status) {
					if( status == google.maps.GeocoderStatus.OK ) {
						var addressHelper = {},
						    addressArray  = {};

						var geometry = results[0].geometry,
						    position = geometry.location,
						    address  = results[0].address_components;

						 var   positions = results[0].geometry.location

						// Format latlng and reject to object
						var posCounter = 0, posHelper = {};
						for ( var i in positions ) {
							if( i.match(/^.b$/) ) {
								posHelper[posCounter] = positions[i];
								posCounter++;
							}
						}

						// Set the lat (from Format latlng and reject to object)
						addressArray.address_lat = posHelper[0];
						// Set the lng (from Format latlng and reject to object)
						addressArray.address_lng = posHelper[1];

						// Preformat the new address, we need to throw in input fields and for address comparison
						jQuery.each(address, function(key, val) {
							$.each(val.types, function(key2, val2) {
								addressHelper[val2] = val.long_name;
							});
						});
						// Set the street ( route ) if given
						addressArray.address_street = (addressHelper.route) ? addressHelper.route : '';
						// Set the street ( street_number ) if given
						addressArray.address_houseno = (addressHelper.street_number) ? addressHelper.street_number : '';
						// Set the zip ( postal_code ) if given
						addressArray.address_zip = (addressHelper.postal_code) ? addressHelper.postal_code : '';
						// Set the city ( locality, political ) if given
						addressArray.address_city = (addressHelper.locality) ? addressHelper.locality : '';
						// Set the region ( administrative_area_level_1, political ) if given
						addressArray.address_region = (addressHelper.administrative_area_level_1) ? addressHelper.administrative_area_level_1 : '';
						// Set the country ( country, political ) if given
						addressArray.address_country = (addressHelper.country) ? addressHelper.country : '';

						// Add new address
						checkBack.newAddress = addressArray;

						// Compare addresses
						var comparisonCheck = compareAddress( checkBack.address, addressArray, checkBack.direction );
						if( comparisonCheck != true ) {
							alertify.set({
								buttonFocus : 'none'
							});
							alertify.confirm(comparisonCheck, function(e) {
								if( e ) {
									// User click ok
									setNewAddress( checkBack.order, checkBack.direction, addressArray );

									// Move back to the solver
									geoSolver( checkBack.order, copyCheck, checkBack );
								} else {
									// User click cancel inject 0 as geopos for latLng to prevent further checks
									checkBack.address.address_lat = 0;
									checkBack.address.address_lng = 0;
									setNewAddress( checkBack.order, checkBack.direction, checkBack.address );

									// Move back to the solver
									geoSolver( checkBack.order, copyCheck, checkBack );
								}
							});
						} else {
							setNewCoordinates( checkBack.order, checkBack.direction, addressArray );
							setTimeout(function() {
								geoSolver( checkBack.order, copyCheck, checkBack );
							}, 500);
						}
					} else {
						alertify.error( '<i class=\"icon-warning-sign icon-large\"></i> Transport: ' + checkBack.order + ' An error occured on geocoding. May the service is temporary unavailable. Automatic retry in 30 seconds!' );
						setTimeout(function() {
							geoSolver( checkBack.order, copyCheck );
						}, 30000);
					}
				}
			}
		});
	}


	/*
	 * Funtion to verify the route right from the gmap3 source code to catch the estimated duration and distance
	 */
	function verifyRoute( checkBack, copyCheck ) {
		alertify.log('<i class="icon-globe icon-large"></i> Build Route...');
		$('#map-canvas').gmap3({
			getdistance: {
				options: {
					origins: [checkBack.route.origin.string_name],
					destinations: [checkBack.route.destination.string_name],
					travelMode: google.maps.TravelMode.DRIVING
				},
				callback: function( results, status ){
					var usher = getUsher( checkBack.order, null );

					if (results){
						for (var i = 0; i < results.rows.length; i++){
							var elements = results.rows[i].elements;
							for(var j=0; j<elements.length; j++) {
								switch(elements[j].status) {
									case "OK":
										var duration = elements[j].duration;
										var distance = elements[j].distance;

										results.estimates = { 'duration': duration, 'distance': distance };

										// Route is verified, set icon to green and set text to info icons
										$(usher.order + ' span.manurouter').hide();
										$(usher.order + ' span.duration span').html(results.estimates.duration.text);
										$(usher.order + ' span.duration').fadeIn();
										$(usher.order + ' span.distance span').html(results.estimates.distance.text);
										$(usher.order + ' span.distance').fadeIn();

										// setNewRoute and move back to the solver
										setNewRoute( checkBack.order, results );
										geoSolver( checkBack.order, copyCheck );
										break;
									case "NOT_FOUND":
										alertify.error( '<i class=\"icon-warning-sign icon-large\"></i> Transport: ' + checkBack.order + ' The origin and/or destination of this pairing could not be geocoded.' );

										results.estimates.duration.value = 0;
										results.estimates.duration.text  = 'N/A';
										results.estimates.distance.value = 0;
										results.estimates.distance.text  = 'N/A';

										$(usher.order + ' span.manurouter').removeClass('btn-danger').addClass('btn-warning');

										// setNewRoute and move back to the solver
										setNewRoute( checkBack.order, results );
										geoSolver( checkBack.order, copyCheck );
										break;
									case "ZERO_RESULTS":
										alertify.error( '<i class=\"icon-warning-sign icon-large\"></i> Transport: ' + checkBack.order + ' No route could be found between the origin and destination!' );

										results.estimates.duration.value = 0;
										results.estimates.duration.text  = 'N/A';
										results.estimates.distance.value = 0;
										results.estimates.distance.text  = 'N/A';

										// setNewRoute and move back to the solver
										setNewRoute( checkBack.order, results );
										geoSolver( checkBack.order, copyCheck );
										break;
								}
							}
						}
					} else {
						alertify.error( '<i class=\"icon-warning-sign icon-large\"></i> Transport: ' + checkBack.order + ' An error occured on geocoding. May the service is temporary unavailable. Automatic retry in 30 seconds!' );
						setTimeout(function() {
							geoSolver( checkBack.order, copyCheck );
						}, 30000);
					}
				}
			}
		});
	}


	function visualVerifyAddress(addr, direction, order) {
		$('#map-canvas').gmap3({
			getlatlng: {
				address: addr,
				callback: function(results, status) {
					if( status == google.maps.GeocoderStatus.OK ) {
						// Adding direction and order to results
						var transport = {direction: direction, order: order};
						results[0].transport = transport;

						var geometry = results[0].geometry,
						position     = geometry.location,
						address      = results[0].address_components;

						// Format latlng and reject to object
						var posCounter = 0, posHelper = new Object();
						jQuery.each(results[0].geometry.location, function(key, val) {
							if( key.match(/^.b$/) ) {
								posHelper[posCounter] = val;
								posCounter++;
							}
						});
						results[0].geometry.coords = {lat: posHelper[0], lng: posHelper[1]};

						// Preformat the new address, we need to throw in input fields
						var addressHelper = new Object();
						var address = results[0].address_components;
						jQuery.each(address, function(key, val) {
							$.each(val.types, function(key2, val2) {
								addressHelper[val2] = val.long_name;
							});
						});
						var addressArray = new Object();
						// Set the street ( route ) if given
						addressArray.address_street = (addressHelper.route) ? addressHelper.route : '';
						// Set the street ( street_number ) if given
						addressArray.address_houseno = (addressHelper.street_number) ? addressHelper.street_number : '';
						// Set the zip ( postal_code ) if given
						addressArray.address_zip = (addressHelper.postal_code) ? addressHelper.postal_code : '';
						// Set the city ( locality, political ) if given
						addressArray.address_city = (addressHelper.locality) ? addressHelper.locality : '';
						// Set the region ( administrative_area_level_1, political ) if given
						addressArray.address_region = (addressHelper.administrative_area_level_1) ? addressHelper.administrative_area_level_1 : '';
						// Set the country ( country, political ) if given
						addressArray.address_country = (addressHelper.country) ? addressHelper.country : '';
						results[0]['input_address'] = addressArray;

						// Clear the last and set the new marker
						$(this).gmap3({
							clear:{
								name:['marker'],
								last: true
							},
							marker:{
								values:[
									{latLng: position}
								],
								options:{
									animation: google.maps.Animation.BOUNCE
								}
							},
							map:{
								options:{
									center: position,
									zoom: 17
								}
							}
						});

						// Throw the callback
						callbackCodeAddress(results);
					} else {
						console.log('Geocode was not successful for the following reason: ' + status);
						// Throw the callback
						callbackCodeAddress(status);
					}
				}
			}
		});
	}





	/*******************************************
			Render Map Functions
	*******************************************/
	function initialize(initLat, initLng, initZoom, initMarker) {
		// Clear the marker if initMarker == false
		jQuery('#map-canvas').gmap3({
			clear:{
				name:['marker'],
				last: true
			}
		});

		jQuery('#map-canvas').gmap3({
			marker:{
				latLng: (initMarker) ? [initLat, initLng] : false
			},
			map:{
				options: {
					zoom                   : initZoom,
					center                 : [initLat, initLng],
					mapTypeId              : google.maps.MapTypeId.ROADMAP,
					draggable              : false,
					scrollwheel            : false,
					disableDefaultUI       : true,
					disableDoubleClickZoom : true
				}
			}
		});

		// Clear the marker if initMarker == false
		if (!initMarker) {
			jQuery('#map-canvas').gmap3({
				clear:{
					name:['marker'],
					last: true
				}
			});
		}
	}


	/*
	 * Funtion to render the route and direction on the google maps tab
	 */
	function renderRouteDirection() {
		// Get the first transport directions
		var addrF = getAddress(1, 'f');
		var addrT = getAddress(1, 't');

		// Check for lat lng values, set the icon color and init the map
		if( addrF.address_lat && addrF.address_lng && addrT.address_lat && addrT.address_lng ) {
			$('#tabmap-canvas').gmap3({
				getroute: {
					options: {
						origin: addrF.string_name,
						destination: addrT.string_name,
						travelMode: google.maps.DirectionsTravelMode.DRIVING
					},
					callback: function( results ){
						if (!results) return;
						$(this).gmap3({
							clear: 'directionsrenderer',
							map:{
								options:{
									zoom: 13,
									center: [50, 9]
								}
							},
							directionsrenderer: {
								container: $('#direction-canvas'),
								options: {
									directions: results
								}
							}
						});
					}
				}
			});
		}
	}


	/*
	 * Funtion to render the route on the main map-canvas div
	 */
	function renderRoute( order ) {
		var route = getRoute( order );
		$('#map-canvas').height('200px').width('100%').gmap3({
			getroute: {
				options: {
					origin: route.origin.string_name,
					destination: route.destination.string_name,
					travelMode: google.maps.DirectionsTravelMode.DRIVING
				},
				callback: function( results, status ){
					if( status == results.status ) {
						$(this).gmap3({
							clear: 'directionsrenderer',
							map:{
								options:{
									zoom: 13,
									center: [50, 9],
									scrollwheel: false,
									streetViewControl: false
								}
							},
							directionsrenderer: {
								options: {
									directions: results
								}
							}
						});
					}
				}
			}
		});
	}