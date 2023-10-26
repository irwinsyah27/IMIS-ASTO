<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Peta Sebaran Unit</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script>
    _URL = "<?php echo _URL;?>";
    _IMG = "<?php echo _URL;?>assets/tracker/";
    </script>
		
    <!-- <script src="//code.jquery.com/jquery-1.11.1.min.js"></script> -->
    <script src="<?php echo _ASSET_TRACKER;?>js/jquery-1.11.1.min.js"></script>
    <!-- <script src="//maps.google.com/maps/api/js?v=3&sensor=false&libraries=adsense"></script> 
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCBaKwwRvyJb9ValFvbSSLG22m-EGH_w5A"></script>-->
    <!-- <script src="<?php echo _ASSET_TRACKER;?>js/api_google.js"></script> -->
    
    <script src="<?php echo _ASSET_TRACKER;?>js/maps.js"></script>
    <script src="<?php echo _ASSET_TRACKER;?>js/leaflet-0.7.5/leaflet.js"></script>
    <script src="<?php echo _ASSET_TRACKER;?>js/leaflet-plugins/google.js"></script>
    <script src="<?php echo _ASSET_TRACKER;?>js/leaflet-plugins/bing.js"></script>
    <link rel="stylesheet" href="<?php echo _ASSET_TRACKER;?>js/leaflet-0.7.5/leaflet.css">    
    <!-- 
        to change themes, select a theme here:  http://www.bootstrapcdn.com/#bootswatch_tab 
        and then change the word after 3.2.0 in the following link to the new theme name
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootswatch/3.3.5/cerulean/bootstrap.min.css">
    -->    
    <link rel="stylesheet" href="<?php echo _ASSET_TRACKER;?>css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo _ASSET_TRACKER;?>css/styles.css">
    
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
    
</head>
<body>
    <div class="container-fluid">
        <div class="row"> &nbsp;
        </div>
        <div class="row">
            <div class="col-sm-12" id="selectdiv">
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
                &nbsp;&nbsp;&nbsp;&nbsp;<select name="track" id="track">
                <option value="0"> ALL Data </option>
                <option value="1"> Over speed</opyion>
                <option value="2"> Normal speed</option>
                </select>
                &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" id="filter" value="Filter" tabindex="5" class="btn btn-primary"> 
                &nbsp;&nbsp;&nbsp;&nbsp;<span class="infoproses"><img src="<?php echo _ASSET_TEMPLATE;?>assets/images/loading.gif"> Proses...</span>
            </div>
            <!-- <div class="col-sm-2 viewalldiv">
                <input type="button" id="viewall" value="View All" tabindex="5" class="btn btn-primary"> 
            </div>-->
        </div> 
        <div class="row">
            <div class="col-sm-12" id="mapdiv">
                <div id="map-canvas"></div>
            </div>
        </div>
    </div> 
   
    <?php
		if (isset($js) && $js<>"") $this->load->view($js);
		?>  
</body>
</html>
    