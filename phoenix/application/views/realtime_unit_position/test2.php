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
    <title>Simple Leaflet Map</title>
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

    <script>

        var osmLink = '<a href="http://openstreetmap.org">OpenStreetMap</a>',
            thunLink = '<a href="http://thunderforest.com/">Thunderforest</a>';
        
        var osmUrl = 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            osmAttrib = '&copy; ' + osmLink + ' Contributors',
            landUrl = 'http://{s}.tile.thunderforest.com/landscape/{z}/{x}/{y}.png',
            thunAttrib = '&copy; '+osmLink+' Contributors & '+thunLink;

        var osmMap = L.tileLayer(osmUrl, {attribution: osmAttrib}),
            landMap = L.tileLayer(landUrl, {attribution: thunAttrib});

		var kpp = L.tileLayer.wms("http://localhost:8080/geoserver/KPP/wms", 
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

        var track = L.tileLayer.wms("http://localhost:8080/geoserver/KPP/wms", 
        {
            layers: 'KPP:Track',
            format: 'image/png',
            transparent: true
        });


        var poi = L.tileLayer.wms("http://localhost:8080/geoserver/KPP/wms", 
        {
            layers: 'KPP:POI',
            format: 'image/png',
            transparent: true
        });


        var map = L.map('map', {
			center: [-1.08575, 114.46709],
			zoom: 12,
			layers: [osmMap, landMap, kpp, jalur_hauling, track, poi]
		});

		var baseLayers = {
			"OSM Mapnik": osmMap,
			"Landscape": landMap,
			"KPP":kpp
		};

		L.control.layers(baseLayers).addTo(map);



        var polyline = L.polyline([
            <?php echo $tmp_data;?>
            ],
            {
                color: 'green',
                weight: 4,
                opacity: .7,
                dashArray: '20,0',
                lineJoin: 'round'
            }
            ).addTo(map);

    </script>
</body>
</html>
