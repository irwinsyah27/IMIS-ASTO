<?php
/*
$tmp_data = '';
if (count($routes) > 0) {
    FOREACH ($routes AS $r) {
        if ($tmp_data <> "") $tmp_data  .= ",";
        $tmp_data .= "[".$r["latitude"].",".$r["longitude"]."]";
    }
}
*/
?>

        <div class="row">
            <center>
                <div class="col-sm-12" id="selectdiv">
                    <form id="frmData" action="<?php echo _URL."realtime_unit_position/show_tracker";?>" target="_blank" method="POST">
                        <input type="text" name="kendaraan" id="kendaraan" size="8" value="">
                        <?php /*
                        <select name="kendaraan" id="kendaraan" tabindex="1">
                            <option value="">- Pilih Unit -</option>
                            <?php
                            if (count($unit) > 0) {
                                FOREACH ($unit AS $u) {
                                    ?>
                                    <option value="<?php echo $u["new_eq_num"];?>"><?php echo $u["new_eq_num"];?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select> 
                        */?>
                        Start Date <input type="text" name="start_date" id="start_date" size="8" value="<?php echo date("Y-m-d");?>">
                        <input type="text" name="start_time" id="start_time" size="4" value="<?php echo date("H:i");?>">
                        End Date <input type="text" name="end_date" id="end_date" size="8" value="<?php echo date("Y-m-d");?>">
                        <input type="text" name="end_time" id="end_time" size="4" value="<?php echo date("H:i");?>">
                        <input type="button" name="filter" id="filter" value="Filter" >
                    </form>
                </div>
            </center>
        </div>

        <div id="map-canvas"></div>

        <div class="row">
            <div class="col-sm-12" id="mapdiv">
                
            </div>
        </div> 

 
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


        var map = L.map('map-canvas',{
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
        //alert(feature.properties.speed);
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
).addTo(map); 

        var baseLayers = {
            "OSM Mapnik": osmMap,
            "OSM Landscape": landMap,
            "Kalimantan":kalimantan
        };
 

        var houling = new L.LayerGroup();  
        jalur_hauling.addTo(houling); 

        var poi = new L.LayerGroup();  
        jalur_poi.addTo(poi); 

        var overlays = {
            //"Test 3 Mei 2016": test3Mei2016,
            "Jalur Houling": houling,
            "POI": poi
        }; 

        L.control.layers(baseLayers,overlays).addTo(map); 

    </script>