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
      <div class="row">
            <center>
                <div class="col-sm-12" id="selectdiv">
                    <form id="frmData" action="<?php echo _URL."realtime_unit_position/show_tracker";?>" target="_blank" method="POST">
                        <input type="text" name="kendaraan" id="kendaraan" size="8" value=""> 
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

</body>
</html>
