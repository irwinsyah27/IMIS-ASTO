<?php
$tmp_data = '';
if (count($routes) > 0) {
    FOREACH ($routes AS $r) {
        if ($tmp_data <> "") $tmp_data  .= ",";
        $tmp_data .= "[".$r["latitude"].",".$r["longitude"]."]";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Peta KPP</title>
    <meta charset="utf-8" />
    <script>
    _URL = "<?php echo _URL;?>";
    _IMG = "<?php echo _URL;?>assets/tracker/";
    </script>
    <link 
        rel="stylesheet" 
         href="<?php echo _ASSET_LIBS;?>leaflet-0.7.3/leaflet.css"
    />
    <style>
        body {
            padding: 0;
            margin: 0;
        }
        html, body, #map {
            height: 100%;
            width: 100%;
        }
    </style>
</head>
<body>
	<div class="container-fluid">
        <div class="row"> &nbsp;
        </div>
        <div class="row">
            <div class="col-sm-12" id="selectdiv">
                &nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;
                <select id="routeSelect" tabindex="1">
                	<option value="">- Pilih Unit -</option>
                	<?php 
                	if (count($unit) > 0) {
                		FOREACH ($unit AS $u) {
                			?>
                			<option value="<?php echo $u["unit"];?>"><?php echo $u["unit"];?></option>
                			<?php
                		}
                	}
                	?>
                </select> 
                &nbsp;&nbsp;&nbsp;&nbsp;Start Date <input type="text" name="start_date" id="start_date" size="10" value="<?php echo date("Y-m-d");?>">
                &nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="start_time" id="start_time" size="10" value="00:00">
                &nbsp;&nbsp;&nbsp;&nbsp;End Date <input type="text" name="end_date" id="end_date" size="10" value="<?php echo date("Y-m-d");?>">
                &nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="end_time" id="end_time" size="10" value="23:59">
                &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" id="filter" value="RUN TRACKING" tabindex="5" class="btn btn-primary"> 
                &nbsp;&nbsp;&nbsp;&nbsp;<span class="infoproses"><img src="<?php echo _ASSET_TEMPLATE;?>assets/images/loading.gif"> Proses...</span>
            </div>
            <!-- <div class="col-sm-2 viewalldiv">
                <input type="button" id="viewall" value="View All" tabindex="5" class="btn btn-primary"> 
            </div>-->
        </div> 
        
    </div> <br>
    <div id="map"></div>

    <script
        src="<?php echo _ASSET_LIBS;?>leaflet-0.7.3/leaflet.js">
    </script>
    <script src="<?php echo _ASSET_LIBS;?>leaflet-plugins/leaflet-realtime.min.js"></script>
	<script src="<?php echo _ASSET_TRACKER;?>js/jquery-1.11.1.min.js"></script>
    <link rel="stylesheet" href="<?php echo _ASSET_TEMPLATE;?>assets/css/chosen.css" />
		<link rel="stylesheet" href="<?php echo _ASSET_TEMPLATE;?>assets/css/datepicker.css" />
		<link rel="stylesheet" href="<?php echo _ASSET_TEMPLATE;?>assets/css/bootstrap-timepicker.css" />
		<link rel="stylesheet" href="<?php echo _ASSET_TEMPLATE;?>assets/css/daterangepicker.css" />
		<link rel="stylesheet" href="<?php echo _ASSET_TEMPLATE;?>assets/css/bootstrap-datetimepicker.css" />
		<link rel="stylesheet" href="<?php echo _ASSET_TEMPLATE;?>assets/css/colorpicker.css" />
		
    <script src="<?php echo _ASSET_TEMPLATE;?>assets/js/date-time/bootstrap-datepicker.js"></script>
		<script src="<?php echo _ASSET_TEMPLATE;?>assets/js/date-time/bootstrap-timepicker.js"></script>
		<script src="<?php echo _ASSET_TEMPLATE;?>assets/js/date-time/moment.js"></script>
		<script src="<?php echo _ASSET_TEMPLATE;?>assets/js/date-time/daterangepicker.js"></script>
		<script src="<?php echo _ASSET_TEMPLATE;?>assets/js/date-time/bootstrap-datetimepicker.js"></script>
    <script>
    var markerLayer = new L.layerGroup();
	var polylineLayer = new L.layerGroup();
	
    	var osmLink = '<a href="http://openstreetmap.org">OpenStreetMap</a>' ;
        
        var osmUrl = 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            osmAttrib = '&copy; ' + osmLink + ' Contributors' ;

        var osmMap = L.tileLayer(osmUrl, {attribution: osmAttrib}); 

        var kalimantan = L.tileLayer.wms("http://10.13.130.50:8080/geoserver/KPP/wms", 
        {
            layers: 'KPP:Kalteng',
            format: 'image/png',
            transparent: true 
        });

        var jalur_hauling = L.tileLayer.wms("http://10.13.130.50:8080/geoserver/KPP/wms", 
        {
            layers: 'KPP:Jalur_Hauling',
            format: 'image/png',
            transparent: true
        }); 


        var jalur_poi = L.tileLayer.wms("http://10.13.130.50:8080/geoserver/KPP/wms", 
        {
            layers: 'KPP:POI',
            format: 'image/png',
            transparent: true
        });

        
        var map = L.map('map',{
                                center: [-1.08575, 114.46709],
                                zoom: 10,
                                layers: [osmMap, kalimantan]
                            }
                        );

        var baseLayers = {
            "OSM Mapnik": osmMap, 
            "Kalimantan":kalimantan
        };


        var houling = new L.LayerGroup();  
        jalur_hauling.addTo(houling); 

        var poi = new L.LayerGroup();  
        jalur_poi.addTo(poi); 

        map.addControl(houling);
        map.addControl(poi);

        var overlays = {
            //"Test 3 Mei 2016": test3Mei2016,
            "Jalur Houling": houling,
            "POI": poi,
            "Marker Layer" : markerLayer,
			"Path Layer" : polylineLayer
        }; 

        L.control.layers(baseLayers,overlays).addTo(map); 


        jQuery(document).ready(function($) {
        	'use strict';
        	$('.infoproses').hide();
        	$('#start_date').datepicker({
      	      autoclose: true,
      	      todayHighlight: true,
      	      format: 'yyyy-mm-dd'
      	    });
      		$('#end_date').datepicker({
      	      autoclose: true,
      	      todayHighlight: true,
      	      format: 'yyyy-mm-dd'
      	    });
      		$("#filter").click(function() { 
      	    	$('.infoproses').show();
      	    	var url = _URL+'realtime_unit_position_run/runtracking/' + $('#routeSelect').val()+'/' + $('#start_date').val()+'/' + $('#start_time').val()+'/' + $('#end_date').val()+'/' + $('#end_time').val();
      	    	$.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                    	datatrack(data);
                 	  	
                    },
                    error: function (xhr, status, errorThrown) {
                        console.log("status: " + xhr.status);
                        console.log("errorThrown: " + errorThrown);
                        alert('There is no tracking data to view');
                        $('.infoproses').hide();
                     }
                });
      	    });
      		function datatrack(json){
            	//alert(JSON.stringify(json));
            	resetMap();
            	var finalLocation = false;
                var counter = 0;
                var locationArray = [];
                var time = 500;
      			$(json.locations).each(function(key, value){
      				var latitude =  $(this).attr('latitude');
                    var longitude = $(this).attr('longitude');
                    var userName = $(this).attr('userName');
                    var speed = $(this).attr('speed');
                    var gpsTime = $(this).attr('gpsTime');
                    var unit = $(this).attr('unit');
                    var direction = $(this).attr('direction');
                    //locationArray.push(userName, speed, gpsTime, unit, direction, latitude, longitude);  
                    function delayed() {
                    	mapUpdater("" + userName, speed, gpsTime, unit, direction, latitude, longitude);
                    }
                   setTimeout( delayed , time );
                   time += 500;
                }); 
      			/*for (var i = 0, l = locationArray.length; i < l; i++) {
      			    var counter = 15;
      			    var countDown = setTimeout(function() {
      			  
      			        if (counter == 0) {
      			            //console.log(adArray[i]);
      			        	alert(JSON.stringify(locationArray));
      			        }
      			        counter--;
      			    }, 2000);
      			}*/
                $('.infoproses').hide();
            }
      
        });
        
		var idList = [];

		function checkCheckBox() {

			
				check.checked = false;
			
				//check.checked = true;
		}

		function mapUpdater(id, speed, time, unit, direction, lat, lon) {
			if (parseInt(speed) > 55) {
	            var iconType =   _IMG+'images/coolred_small2.png';
	        } else {
	            var iconType =   _IMG+'images/coolgreen2_small2.png';
	        }
			var markerIcon = new L.Icon({
                iconUrl:      iconType,
                //shadowUrl:    _IMG+'images/coolshadow_small.png',
                iconSize:     [30, 30],
                //shadowSize:   [22, 20],
                //iconAnchor:   [6, 20],
                //shadowAnchor: [6, 20],
                //popupAnchor:  [-3, -25]
        	});
			var title = id;
			var popupWindowText = "<table border=0 style=\"font-size:95%;font-family:arial,helvetica,sans-serif;color:#000;\">" +
			"<tr><td colspan='2'><b>" + title +  " </b></td></tr>" + 
			"<tr><td align=right>Unit:&nbsp;</td><td>" + unit +  "</td></tr>" + 
            "<tr><td align=right>Speed:&nbsp;</td><td>" + speed +  " mph</td></tr>" + 
            "<tr><td align=right>Time:&nbsp;</td><td colspan=2>" + time +  "</td></tr></table>";
			var proxi = true;
			var len = null;
			var poly = null;
			var mark = null;
			//If the list doesn't contain anything Adding The Markers and PolyLines
			if (idList.length == 0) {
			
				/*mark = L.marker([ lat, lon ]).bindPopup("Vehicle ID : " + id, {
					autoPan : false
				});*/
				mark = new L.marker(new L.LatLng(lat, lon), {title : title, icon: markerIcon}).bindPopup(popupWindowText).addTo(map);
				markerLayer.addLayer(mark);
				poly = L.polyline([], {
					color : 'green'
				});
				polylineLayer.addLayer(poly).addTo(map);

				idList.push([ id, speed, time, unit, direction, mark, poly, false ]);

				//idListArea.value += id + ", ";
				return;
			}

			for (var i = idList.length; i > 0; i--) {

				if (id == idList[i - 1][0]) {
					len = i - 1;
					break;
				}
				// If the ID is not in the list initiate new entry
				else if ((i - 1) == 0) {

					/*mark = L.marker([ lat, lon ]).bindPopup("Vehicle ID : " + id, {
						autoPan : false
					});*/
					if (parseInt(idList[len][1]) > 55) {
			            var iconType =   _IMG+'images/coolred_small2.png';
			        } else {
			            var iconType =   _IMG+'images/coolgreen2_small2.png';
			        }
					var markerIcon = new L.Icon({
		                iconUrl:      iconType,
		                //shadowUrl:    _IMG+'images/coolshadow_small.png',
		                iconSize:     [30, 30],
		                //shadowSize:   [22, 20],
		                //iconAnchor:   [6, 20],
		                //shadowAnchor: [6, 20],
		                //popupAnchor:  [-3, -25]
		        	});
					var title = idList[len][0];
					popupWindowText = "<table border=0 style=\"font-size:95%;font-family:arial,helvetica,sans-serif;color:#000;\">" +
					"<tr><td colspan='2'><b>" + title +  " </b></td></tr>" + 
					"<tr><td align=right>Unit:&nbsp;</td><td>" + idList[len][3] +  "</td></tr>" + 
		            "<tr><td align=right>Speed:&nbsp;</td><td>" + idList[len][1] +  " mph</td></tr>" + 
		            "<tr><td align=right>Time:&nbsp;</td><td colspan=2>" + idList[len][2] +  "</td></tr></table>";
					mark = new L.marker(new L.LatLng(lat, lon), {title : title, icon: markerIcon}).bindPopup(popupWindowText).addTo(map);		
					markerLayer.addLayer(mark);
					poly = L.polyline([], {
						color : 'green'
					});
					polylineLayer.addLayer(poly).addTo(map);
					len = idList.length - 1;

					idList.push([ id, speed, time, unit, direction, mark, poly, false ]);
					//idListArea.value += id + ", ";
					return;
				}
			}

			if (idList[len][7] == true) {
				idList[i - 1][6].addLatLng([ lat, lon ]);
				poly = L.polyline([], {
					color : 'Green'
				});
				polylineLayer.addLayer(poly).addTo(map);
				idList[len][6] = poly;
				idList[len][7] = false;
			}

			idList[len][5].setLatLng([ lat, lon ]).update(); // updating the marker

			// Drawing the Path if the check box is checked
			//if (document.getElementById('drawPath').checked) {
				//idList[len][2].addLatLng([ lat, lon ]); // updating the poly-line
			//}

			// Maintaining the focus on a selected device
			//if (document.getElementById("followId").checked
			//		&& document.getElementById("followID").value == id) {
			//	map.panTo([ lat, lon ], {
			//		duration : 0.5
			//	});
			//}

			//Updating the Output Console
			//messagesTextArea.value += "ID : " + id + " Longtitute : " + lon
			//		+ " Latitude : " + lat + "\n";
			//var textarea = document.getElementById('messagesTextArea');
			//textarea.scrollTop = textarea.scrollHeight;

		}

		// Function too Clear all the markers from the map
		function clearMarkers() {

			polylineLayer.clearLayers();

			for (var i = idList.length; i > 0; i--) { // Re adding the polylines since clear Layers remove all the objects
				poly = L.polyline([], {
					color : 'green'
				});
				polylineLayer.addLayer(poly).addTo(map);
				idList[i - 1][6] = poly;
			}
		}

		//Function to maintain focuss on a device
		function followID() {
			//var id = document.getElementById("followID").value;
			//if (id != "") {
				for (var i = idList.length; i > 0; i--) {
					if (idList[i - 1][0] == id) {
						idList[i - 1][5].openPopup();
						break;
					}
				}
			//}
		}

		//toggling the check box
		function checkBoxFollow() {
			//var fid = document.getElementById("followID").value;
			//var checkbox = document.getElementById("followId");

			//if (fid != "") {

				//if (checkbox.checked) {
				//	checkbox.checked = false;
				//} else {
				//	checkbox.checked = true;
				//}
			//}
		}

		// Function to Reset the Map
		function resetMap() {
			polylineLayer.clearLayers();
			markerLayer.clearLayers();
			idList.length = 0;
			//idListArea.value = "";
		}
    </script>
</body>
</html>
