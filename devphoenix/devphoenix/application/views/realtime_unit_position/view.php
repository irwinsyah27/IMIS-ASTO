<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>IMIS :: GPS Tracker</title>
        <link rel="stylesheet" type="text/css" href="<?php echo _ASSET_LIBS;?>jqueryui/themes/default/easyui.css">
        <link rel="stylesheet" type="text/css" href="<?php echo _ASSET_LIBS;?>jqueryui/themes/icon.css"> 
        <script type="text/javascript" src="<?php echo _ASSET_LIBS;?>jquery.min.js"></script>
        <script type="text/javascript" src="<?php echo _ASSET_LIBS;?>jqueryui/jquery.easyui.min.js"></script>
		
		<script type="text/javascript">
		  var baseurl = "<?php print _URL; ?>";
		  var imgurl = "<?php print _IMG_WEB; ?>";
		</script>
     
     	<!---->
	    <script src="//maps.google.com/maps/api/js?v=3&sensor=false&libraries=adsense"></script>
	
	    <?php include_once "js_tracker.php";?> 
	    <script src="<?php echo _ASSET_LIBS;?>leaflet-0.7.3/leaflet.js"></script>
	    <script src="<?php echo _ASSET_LIBS;?>leaflet-plugins/google.js"></script>   
	    <!-- 
	    -->
	    <script src="<?php echo _ASSET_LIBS;?>leaflet-plugins/bing.js"></script> 

	   <script src="<?php echo _ASSET_LIBS;?>leaflet-plugins/leaflet-realtime.min.js"></script>
			
	    <link rel="stylesheet" href="<?php echo _ASSET_LIBS;?>leaflet-0.7.3/leaflet.css">    
	    <!-- 
	        to change themes, select a theme here:  http://www.bootstrapcdn.com/#bootswatch_tab 
	        and then change the word after 3.2.0 in the following link to the new theme name
	    -->    
	    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootswatch/3.3.0/cerulean/bootstrap.min.css">
	    <link rel="stylesheet" href="<?php echo _ASSET_CSS;?>styles.css">
	    <style>
	        #map-canvas {
	            position: absolute;
	            top: 0;
	            left: 0;
	            bottom: 0;
	            right: 0;
	        }
	    </style>
    </head>
    <body>  
                <div id="map-canvas"></div> 
   	 </body>  
</html>