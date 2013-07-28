<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>devXive - Geocoding service</title>
    <script src="//maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script>
var geocoder;
var map;
function initialize() {
  geocoder = new google.maps.Geocoder();
  var latlng = new google.maps.LatLng(50.12333, 8.726560000000063);
  var mapOptions = {
    zoom: 8,
    center: latlng,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  }
  map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
}

function codeAddress() {
  var address = document.getElementById('address').value;
  geocoder.geocode( { 'address': address}, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
      map.setCenter(results[0].geometry.location);
      var marker = new google.maps.Marker({
          map: map,
          position: results[0].geometry.location
      });

	console.log(results[0]['geometry']['location']);
	lat = (results[0]['geometry']['location']['jb']).toFixed(4);
	long = (results[0]['geometry']['location']['kb']).toFixed(4);

	$("#res-modal span").html( 'Lat: ' + lat + ' Long: ' + long );
	$("#res-modal").show();

    } else {
      alert('Geocode was not successful for the following reason: ' + status);
    }
  });
}

google.maps.event.addDomListener(window, 'load', initialize);

    </script>

<style>
html, body {
  height: 100%;
  margin: 0;
  padding: 0;
}

#map-canvas, #map_canvas {
  height: 100%;
}

@media print {
  html, body {
    height: auto;
  }

  #map-canvas, #map_canvas {
    height: 650px;
  }
}

#panel {
  position: absolute;
  top: 10px;
  left: 50%;
  margin-left: -180px;
  z-index: 5;
  background-color: #fff;
  padding: 5px;
  border: 1px solid #999;
}

#address {
  width: 250px;
}

#res-modal {
	position: absolute;
	width: 300px;
	top: 75px;
	left: 50%;
	margin-left: -165px;
	z-index: 5;
	background-color: #fff;
	padding: 5px;
	border: 1px solid #999;
	text-align: center;
	line-height: 30px;
}
</style>


  </head>
  <body>
    <div id="panel">
      <input id="address" type="textbox" placeholder="z.B. Ratsweg 10, Frankfurt am Main, Deutschland">
      <input type="button" value="Geocode" onclick="codeAddress()">
    </div>
    <div id="res-modal" style="display: none;">
	<h1>Bazingaa!</h1>
	<hr>
	<span></span>
	<br>
	<small>&copy; 2013 by devXive - research and development.</small>
    </div>
    <div id="map-canvas"></div>
  </body>
</html>