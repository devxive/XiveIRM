(function($) {
  var geocoder = new google.maps.Geocoder();

	// used to prevent alertify to display message on initail check
	var checkerTrigger = 0;

  var autoGeocoder = $.fn.autoGeocoder = function(options) {
    var options = $.extend(true, {}, autoGeocoder.defaults, options || {}),
        setup   = options.setup || autoGeocoder.base;

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

        var map    = this.map,
            marker = this.marker = this.marker || new google.maps.Marker();

        if (status == google.maps.GeocoderStatus.OK) {
          var geometry = results[0].geometry,
              position = geometry.location;

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
		if (address.length == 6) {
			address_zip = address[5].long_name;
			address_country = address[4].long_name;
			address_region = address[3].long_name;
			address_city = address[1].long_name;
			address_street = address[0].long_name;
			address_houseno = '';

			if(checkerTrigger > 1) {
				alertify.log("We could find an address, but we have no valid house number. Click the Additional fields button to check if all address fields are filled correct!");
			}
		} else if (address.length == 7) {
			address_zip = address[6].long_name;
			address_country = address[5].long_name;
			address_region = address[4].long_name;
			address_city = address[2].long_name;
			address_street = address[1].long_name;
			address_houseno = address[0].long_name;

			if(checkerTrigger > 1) {
				alertify.success("We could find and verify a address. Click the Additional fields button to check if all address fields are filled correct!");
			}
		} else if (address.length == 8) {
			address_zip = address[7].long_name;
			address_country = address[6].long_name;
			address_region = address[5].long_name;
			address_city = address[3].long_name;
			address_street = address[2].long_name;
			address_houseno = address[0].long_name;

			if(checkerTrigger > 1) {
				alertify.success("We could find and verify a address. Click the Additional fields button to check if all address fields are filled correct!");
			}
		} else if (address.length == 9) {
			address_zip = address[8].long_name;
			address_country = address[7].long_name;
			address_region = address[6].long_name;
			address_city = address[4].long_name;
			address_street = address[2].long_name;
			address_houseno = address[1].long_name;

			if(checkerTrigger > 1) {
				alertify.success("We could find and verify a address. Click the Additional fields button to check if all address fields are filled correct!");
			}
		} else {
			address_zip = '';
			address_country = '';
			address_region = '';
			address_city = '';
			address_street = '';
			address_houseno = '';

			if(checkerTrigger > 1) {
				alertify.alert("<div class='modal-header'><h3>Address verification failed!</h3></div><div class='modal-body'>Sorry, we can't verify the address you typed in! Please try the following:<ul><li>Try to type in similar words</li><li>Try to type in the state or the country</li><li>Try to check the address on google maps</li></ul></div>");
			}
		}

		if(!$('#address_zip').val() || $('#address_zip').val() == '') {
			$('#address_zip').val(address_zip);
		}
		if(!$('#address_city').val() || $('#address_city').val() == '') {
			$('#address_city').val(address_city);
		}
		if(!$('#address_region').val() || $('#address_region').val() == '') {
			$('#address_region').val(address_region);
		}
		if(!$('#address_country').val() || $('#address_country').val() == '') {
			$('#address_country').val(address_country);
		}
		if(!$('#address_street').val() || $('#address_street').val() == '') {
			$('#address_street').val(address_street);
		}
		if(!$('#address_houseno').val() || $('#address_houseno').val() == '') {
			if (address_houseno == parseInt(address_houseno)) {
				$('#address_houseno').val(address_houseno);
			}
		}

		if(position.jb && position.kb) {
			$('#address_lat').val(position.jb);
			$('#address_lng').val(position.kb);
		}

		if(address_lat && address_lng && address_houseno) {
			$('#address-geo-verified').removeClass('red').addClass('green');
		} else {
			$('#address-geo-verified').removeClass('green').addClass('red');
		}

//		console.log(position);
//		console.log(address);

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
//      zoom : 'auto'
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
