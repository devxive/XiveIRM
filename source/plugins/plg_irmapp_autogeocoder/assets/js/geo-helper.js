			/*
			 * Get and format values from the address block
			 */
			function getAddress(direction, order, string) {
				var address_name     = $('#address-block-' + order + ' input[id*=\"address_name\"]').val();
				var address_name_add = $('#address-block-' + order + ' input[id*=\"address_name_add\"]').val();

				var address_street   = $('#address-block-' + order + ' input[id*=\"address_street\"]').val();
				var address_houseno  = $('#address-block-' + order + ' input[id*=\"address_houseno\"]').val();
				var address_zip      = $('#address-block-' + order + ' input[id*=\"address_zip\"]').val();
				var address_city     = $('#address-block-' + order + ' input[id*=\"address_city\"]').val();
				var address_region   = $('#address-block-' + order + ' input[id*=\"address_region\"]').val();
				var address_country  = $('#address-block-' + order + ' input[id*=\"address_country\"]').val();

				var address_lat      = $('#address-block-' + order + ' input[id*=\"address_lat\"]').val();
				var address_lng      = $('#address-block-' + order + ' input[id*=\"address_lng\"]').val();

				if( string ) {
					// Build the string
					var addressContainerHelper = '';
					addressContainerHelper += address_street ? address_street     + ' ' : '',
					addressContainerHelper += address_houseno ? address_houseno   + ' ' : '',
					addressContainerHelper += address_zip ? address_zip           + ' ' : '',
					addressContainerHelper += address_city ? address_city         + ' ' : '',
					addressContainerHelper += address_region ? address_region     + ' ' : '',
					addressContainerHelper += address_country ? address_country   + ' ' : '';
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
				}

				return addressContainerHelper;
			}






			/*
			 * Function to get the geolocation, formatted address and all address relevant objects to the given, possible to reformatting the input values
			 * Note: We can only get a perfect result for coords and address, if all address fields are filled out correctly
			 * So, this function should be only used if we have all address relevant fields.
			 *
			 * Possible workaround: The user take in the street, housenumber and city, they dont know the zip in some cases, Therefore he have to set the region and/or the country
			 *
			 * Due to the async calls, and the return is undefined if we want to get the results out here, we have to do the magic in here! Formatting field, set map, etc...... or we call other functions from in here to do some more stuff like setting map, etc...
			 *
			 * //				$('<div id=\"gmap-canvas\"></div>').insertAfter('#core-informations').width('100%').height('200px').gmap3(mapOptions);
			 */
			function geoFormatting(addr) {
				var addressContainer = new Object();

				jQuery('#map-canvas').gmap3({
					getlatlng: {
						address:  addr,
						callback: function( results ) {
							if ( !results ) {
								alertify.log('No GeoCoordinates found');
								return;
							} else {
								// Set the Geo Coordinates to position ( check for values with matches of the length and with geo coordinates bcz G is changing those vars from time to time )
								var posCounter = 0, posHelper = new Object();
								$.each(results[0].geometry.location, function(key, val) {
									if( key.match(/^.b$/) ) {
										posHelper[posCounter] = val;
										posCounter++;
									}
								});
								addressContainer.position = {lat:posHelper[0], lng:posHelper[1]};

								// Get and set the formatted address
								addressContainer.formatted_address = results[0].formatted_address;

								// Get and set the types
								addressContainer.types = results[0].types;

								// Get the first matched address
								addressContainer.address = results[0].address_components;

							//	return addressContainer;
			console.log(addressContainer);
							}
						}
					}
				});
			}

