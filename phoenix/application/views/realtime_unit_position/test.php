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
    <div id="map"></div>

    <script
        src="<?php echo _ASSET_LIBS;?>leaflet-0.7.3/leaflet.js">
    </script>
    <script src="<?php echo _ASSET_LIBS;?>leaflet-plugins/leaflet-realtime.min.js"></script>

    <script>


        var osmLink = '<a href="http://openstreetmap.org">OpenStreetMap</a>',
            thunLink = '<a href="http://thunderforest.com/">Thunderforest</a>';
        
        var osmUrl = 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            osmAttrib = '&copy; ' + osmLink + ' Contributors',
            landUrl = 'http://{s}.tile.thunderforest.com/landscape/{z}/{x}/{y}.png',
            thunAttrib = '&copy; '+osmLink+' Contributors & '+thunLink;

        var osmMap = L.tileLayer(osmUrl, {attribution: osmAttrib}),
            landMap = L.tileLayer(landUrl, {attribution: thunAttrib});

		var kalimantan = L.tileLayer.wms("http://localhost:8080/geoserver/KPP/wms", 
		{
		    layers: 'KPP:Kalteng',
		    format: 'image/png',
		    transparent: true 
		});

        var jalur_hauling = L.tileLayer.wms("http://localhost:8080/geoserver/KPP/wms", 
        {
            layers: 'KPP:Jalur_Hauling',
            format: 'image/png',
            transparent: true
        }); 


        var jalur_poi = L.tileLayer.wms("http://localhost:8080/geoserver/KPP/wms", 
        {
            layers: 'KPP:POI',
            format: 'image/png',
            transparent: true
        });


        var map = L.map('map',{
								center: [-1.08575, 114.46709],
								zoom: 10,
								layers: [osmMap, landMap, kalimantan]
							}
						),
    realtime = L.realtime({
        url: '<?php echo _URL;?>realtime_unit_position/getListUnit',
        crossOrigin: true,
        type: 'json'
    }, {
        interval: 3 * 1000,
    pointToLayer: function (feature, latlng) { 
        
        if (feature.properties.speed > 50) {
            var iconType =   '<?php echo _IMG_WEB;?>coolred_small.png';
        } else {
            var iconType =   '<?php echo _IMG_WEB;?>coolgreen2_small.png';
        }
        
        var azimuth = "";
         if ((feature.properties.direction >= 337 && feature.properties.direction <= 360) || (feature.properties.direction >= 0 && feature.properties.direction < 23))
                 azimuth =  "compassN";
            if (feature.properties.direction >= 23 && feature.properties.direction < 68)
                azimuth =  "compassNE";
            if (feature.properties.direction >= 68 && feature.properties.direction < 113)
                     azimuth =  "compassE";
         if (feature.properties.direction >= 113 && feature.properties.direction < 158)
                     azimuth =  "compassSE";
            if (feature.properties.direction >= 158 && feature.properties.direction < 203)
                 azimuth =  "compassS";
         if (feature.properties.direction >= 203 && feature.properties.direction < 248)
                     azimuth =  "compassSW";
         if (feature.properties.direction >= 248 && feature.properties.direction < 293)
                    azimuth =  "compassW";
            if (feature.properties.direction >= 293 && feature.properties.direction < 337)
                    azimuth =  "compassNW";
 

        // convert from meters to feet
       var accuracy = parseInt(feature.properties.accuracy * 3.28);

        var popupWindowText = "<table border=0 style=\"font-size:95%;font-family:arial,helvetica,sans-serif;color:#000;\">" +
            "<tr><td align=right>&nbsp;</td><td>&nbsp;</td><td rowspan=2 align=right>" +
          "<img src=<?php echo _URL;?>assets/images/" + azimuth + ".jpg alt= />" + " </td></tr>" +
            "<tr><td align=right>Speed:&nbsp;</td><td>" + feature.properties.speed +  " mph</td></tr>" + 
            "<tr><td align=right>Time:&nbsp;</td><td colspan=2>" + feature.properties.gpsTime +  "</td></tr>" +
            "<tr><td align=right>Name:&nbsp;</td><td>" + feature.properties.userName + "</td><td>&nbsp;</td></tr>" +
            "<tr><td align=right>Accuracy:&nbsp;</td><td>" + accuracy + " ft</td><td>&nbsp;</td></tr></table>";
         
         
            return L.marker(latlng, {
                'icon': L.icon({
                    iconUrl: iconType,
                   // shadowUrl: '//leafletjs.com/docs/images/leaf-shadow.png',
                    iconSize:     [30, 30], // size of the icon
                   //  shadowSize:   [50, 64], // size of the shadow
                  //  iconAnchor:   [22, 94], // point of the icon which will correspond to marker's location
                   // shadowAnchor: [4, 62],  // the same for the shadow
                   // popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
                })
            }
        ).bindPopup(popupWindowText);
        } 
    }
);

		var baseLayers = {
			"OSM Mapnik": osmMap,
			"Landscape": landMap,
			"Kalimantan":kalimantan
		};


        var test3Mei2016 = new L.LayerGroup(); 

        var polyline = L.polyline([
            <?php echo $tmp_data;?>
            ],
            {
                color: 'red',
                weight: 2,
                opacity: .7,
                dashArray: '20,0',
                lineJoin: 'round'
            }
            ).addTo(test3Mei2016); 

		var overlays = {
			"Test 3 Mei 2016": test3Mei2016
		};


        var houling = new L.LayerGroup();  
        jalur_hauling.addTo(houling); 

        var poi = new L.LayerGroup();  
        jalur_poi.addTo(poi); 

		var overlays = {
			"Test 3 Mei 2016": test3Mei2016,
			"Jalur Houling": houling,
			"POI": poi
		}; 

		L.control.layers(baseLayers,overlays).addTo(map);

realtime.on('update', function() {
    map.fitBounds(realtime.getBounds(), {});    //maxZoom: 15
    //alert('a');
});

    </script>
</body>
</html>
