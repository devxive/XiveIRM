	/*
	 * Get and format values from the address block
	 */
	function getAddress(order, direction, string) {
		var nameObserver = 'form[data-order="' + order + '"] .address-block[data-direction="' + direction + '"]';

		var address_name     = $(nameObserver + ' input[id*=\"address_name\"]').val();
		address_name_add = $(nameObserver + ' input[id*=\"address_name_add\"]').val(),
		address_street   = $(nameObserver + ' input[id*=\"address_street\"]').val(),
		address_houseno  = $(nameObserver + ' input[id*=\"address_houseno\"]').val(),
		address_zip      = $(nameObserver + ' input[id*=\"address_zip\"]').val(),
		address_city     = $(nameObserver + ' input[id*=\"address_city\"]').val(),
		address_region   = $(nameObserver + ' input[id*=\"address_region\"]').val(),
		address_country  = $(nameObserver + ' input[id*=\"address_country\"]').val(),
		address_lat      = $(nameObserver + ' input[id*=\"address_lat\"]').val(),
		address_lng      = $(nameObserver + ' input[id*=\"address_lng\"]').val();
		address_hash     = $(nameObserver + ' input[id*=\"address_hash\"]').val();

		// Build the string
		var addressContainerHelperString = '';
		addressContainerHelperString += address_street ? address_street     + ' ' : '',
		addressContainerHelperString += address_houseno ? address_houseno   + ' ' : '',
		addressContainerHelperString += address_zip ? address_zip           + ' ' : '',
		addressContainerHelperString += address_city ? address_city         + ' ' : '',
		addressContainerHelperString += address_region ? address_region     + ' ' : '',
		addressContainerHelperString += address_country ? address_country   + ' ' : '';

		if( string ) {
			var addressContainerHelper = addressContainerHelperString;
		} else {
			var addressContainerHelper = new Object();
			addressContainerHelper.address_name     = address_name;
			addressContainerHelper.address_name_add = address_name_add;
			addressContainerHelper.address_street   = address_street;
			addressContainerHelper.address_houseno  = address_houseno;
			addressContainerHelper.address_zip      = address_zip;
			addressContainerHelper.address_city     = address_city;
			addressContainerHelper.address_region   = address_region;
			addressContainerHelper.address_country  = address_country;
			addressContainerHelper.address_lat      = address_lat;
			addressContainerHelper.address_lng      = address_lng;
			addressContainerHelper.address_hash     = address_hash;
			addressContainerHelper.string_name      = addressContainerHelperString;
		}

		return addressContainerHelper;
	}


	function initialize(initLat, initLng, initZoom, initMarker) {
		jQuery('#map-canvas').gmap3({
			marker:{
				latLng: (initMarker) ? [initLat, initLng] : false
			},
			map:{
				options: {
					zoom                   : initZoom,
					center                 : [initLat, initLng],
					mapTypeId              : google.maps.MapTypeId.ROADMAP,
					draggable              : true,
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


	function codeAddress(addr, direction, order) {
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











	function codeAddress2(addr, direction, order) {
		geocoder.geocode( { 'address': addr }, function(results, status) {
			if( status == google.maps.GeocoderStatus.OK ) {
				// Check if coded address is ROOFTOP ( means precise )
				if( results[0].geometry.location_type === 'ROOFTOP' && results[0].types[0] === 'street_address') {
					var geometry = results[0].geometry,
					position     = geometry.location;

					// Used as workaround for auto zoom function
					map.fitBounds(geometry.viewport);

					var marker = new google.maps.Marker({
						map: map,
						position: position,
						animation: google.maps.Animation.BOUNCE
					});

					// Throw the callback
					callbackCodeAddress(results);
				} else {
					// console.log('Geocode was not successful for the following reason: ' + status);
					return status;
				}
			} else {
				return false;
			}
		});
	}






(function($) {
	var geocoder = new google.maps.Geocoder();

	var autoGeocoder = $.fn.autoGeocoder = function(options) {
		var options = $.extend(true, {}, autoGeocoder.defaults, options || {}),
		setup       = options.setup || autoGeocoder.base;

		for (property in setup) {
			var methods = setup[property];

			for (var i = 0, length = methods.length; i < length; i++) {
				methods[i].call(this, options);
			}
		}

		return this.trigger('auto-geocoder.initialize');
	};

	autoGeocoder.base = {
		initialize: [function(options) {
			options.initial.center = new google.maps.LatLng(
				options.initial.center[0],
				options.initial.center[1]
			);

			this.on('auto-geocoder.initialize', function() {
				$(this)
					.trigger('auto-geocoder.createMap')
					.trigger('auto-geocoder.onKeyUp');
			});
		}],
		createMap: [function(options) {
			this.on('auto-geocoder.createMap', function() {
				var element  = $(this),
				wrapper  = $('<div>', { 'class' : options.className }),
				position = options.position;

				if (position == 'before' || position == 'after') {
					element[position](wrapper);
				} else {
					$(position).append(wrapper);
				}

				element.on('keyup.auto-geocoder', function() {
					element.trigger('auto-geocoder.onKeyUp');
				});

				this.map = new google.maps.Map(wrapper[0], options.initial);
			});
		}],
		onKeyUp: [function(options) {
			this.on('auto-geocoder.onKeyUp', function() {
				var self     = this,
				element      = $(self),
				address      = $.trim(element.val()).replace(/\s+/g, ' ').toLowerCase(),
				timeout      = this.timeout,
				previous     = this.previousAddress;

				if (timeout) {
					clearTimeout(timeout);
				}

				if (previous && previous == address) {
					return;
				}

				if (address == '') {
					element.trigger('auto-geocoder.onGeocodeResult', [[], '']);
					return;
				}

				this.timeout = setTimeout(function() {
					self.previousAddress = address;

					geocoder.geocode({ address: address }, function(results, status) {
						element.trigger('auto-geocoder.onGeocodeResult', [results, status]);
					});
				}, options.delay);
			});
		}],
		onGeocodeResult: [function(options) {
			this.on('auto-geocoder.onGeocodeResult', function(e, results, status) {
				var map    = this.map,
				marker     = this.marker = this.marker || new google.maps.Marker();

				if (status == google.maps.GeocoderStatus.OK) {
					var geometry = results[0].geometry,
					position     = geometry.location;

					if (options.success.zoom == 'auto') {
						map.fitBounds(geometry.viewport);
					} else {
						map.setZoom(options.success.zoom);
						map.setCenter(position);
					}

					marker.setPosition(position);
					marker.setMap(map);

					$(this).trigger('auto-geocoder.onGeocodeSuccess', [results, status]);
				} else {
					var initial = options.initial;

					if (marker) {
						marker.setMap(null);
					}

					map.setZoom(initial.zoom);
					map.setCenter(initial.center);

					$(this).trigger('auto-geocoder.onGeocodeFailure', [results, status]);
				}
			});
		}],
		onGeocodeSuccess: [],
		onGeocodeFailure: []
	};

	autoGeocoder.defaults = {
		className : 'jquery-auto-geocoder-map',
		position  : 'after',
		delay     : 500,
		success   : {
			zoom : 'auto'
		}, initial  : {
			zoom                   : 1,
			center                 : [34, 0],
			draggable              : false,
			mapTypeId              : google.maps.MapTypeId.ROADMAP,
			scrollwheel            : false,
			disableDefaultUI       : true,
			disableDoubleClickZoom : true
		}
	};
})(jQuery);