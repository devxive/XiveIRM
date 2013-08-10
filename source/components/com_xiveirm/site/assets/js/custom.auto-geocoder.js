(function($) {
	var geocoder = new google.maps.Geocoder();

	// used to prevent alertify to display message on initail check
	var checkerTrigger = 0;

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
				var element = $(this),
				wrapper     = $('<div>', { 'class' : options.className }),
				position    = options.position;

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
				var self = this,
				element  = $(self),
				address  = $.trim(element.val()).replace(/\s+/g, ' ').toLowerCase(),
				timeout  = this.timeout,
				previous = this.previousAddress;

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
				var map = this.map,
				marker  = this.marker = this.marker || new google.maps.Marker();

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

					checkerTrigger++;

					var address = results[0].address_components;

					addressHelper = new Object();

					$.each(address, function(key, val) {
						$.each(val.types, function(key2, val2) {
							addressHelper[val2] = val.long_name;
						});
					});

					// Set the street ( route ) if given
					address_street = (addressHelper.route) ? addressHelper.route : '';

					// Set the street ( street_number ) if given
					address_houseno = (addressHelper.street_number) ? addressHelper.street_number : '';

					// Set the zip ( postal_code ) if given
					address_zip = (addressHelper.postal_code) ? addressHelper.postal_code : '';

					// Set the city ( locality, political ) if given
					address_city = (addressHelper.locality) ? addressHelper.locality : '';

					// Set the region ( administrative_area_level_1, political ) if given
					address_region = (addressHelper.administrative_area_level_1) ? addressHelper.administrative_area_level_1 : '';

					// Set the country ( country, political ) if given
					address_country = (addressHelper.country) ? addressHelper.country : '';

					// Pull out the message
					if(address_street && address_houseno && address_zip && address_city && address_region && address_country) {
						// If all is set
						alertify.success("We could find and verify an address. Click the Additional fields button to check if all address fields are filled correct!");
					}
					if(checkerTrigger < 1 && !address_houseno) {
						// We have no house number
						alertify.error("There is no street number given in your request. Geo-Location could be different!");
					}
					if(!address_street && !address_houseno && !address_zip && !address_city && !address_region && !address_country && checkerTrigger < 2) {
						alertify.alert("<div class='modal-header'><h3>Address verification failed!</h3></div><div class='modal-body'>Sorry, we can't verify the address you typed in! Please try the following:<ul><li>Try to type in similar words</li><li>Try to type in the state or the country</li><li>Try to check the address on google maps</li></ul></div>");
					}

					// Set the vars to form field values
					$('#address_street').val(address_street);
					$('#address_houseno').val(address_houseno);
					$('#address_zip').val(address_zip);
					$('#address_city').val(address_city);
					$('#address_region').val(address_region);
					$('#address_country').val(address_country);

					// Geo Coordinates
					if(position.lb && position.mb) {
						var set_address_lat = position.lb.toFixed(10);
						var set_address_lng = position.mb.toFixed(10);
						$('#address_lat').val(set_address_lat);
						$('#address_lng').val(set_address_lng);
					} else {
						$('#inner-address-block input').val('');
						alertify.alert("<div class='modal-header'><h3>Geo verification failed!</h3></div><div class='modal-body'>Sorry, but we can't get Geo-Coordinates from the Google API!<br>You have to type in your address manually!<br><br><br>Note: Google has changed their API access variables.<br>Please notify the support immedeatly to get this issue fixed. Thanks!</div>");
					}

					// Fade out chart processor
					$('#geocode-progress.ep-chart').fadeOut('fast');

//					console.log(position);
//					console.log(address);

//					console.log(addressHelper);

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
		onGeocodeSuccess: [function() {
			console.log('gMaps Plugin by devXive - research and development (c) 1997 - 2013; http://devxive.com');
		}],
		onGeocodeFailure: []
	};

	autoGeocoder.defaults = {
		className : 'jquery-auto-geocoder-map',
		position  : 'after',
		delay     : 1500,
		success   : {
//			zoom : 'auto'
			zoom : 16
		},
		initial  : {
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