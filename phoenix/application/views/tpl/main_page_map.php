<!DOCTYPE html>
<html>
<head>
    <title>Peta KPP</title>
    <meta charset="utf-8" />

        <meta name="description" content="overview &amp; stats" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

        <!-- bootstrap & fontawesome -->
        <link rel="stylesheet" href="<?php echo _ASSET_TEMPLATE;?>assets/css/bootstrap.css" />
        <link rel="stylesheet" href="<?php echo _ASSET_TEMPLATE;?>components/font-awesome/css/font-awesome.css" />
        
    <link 
        rel="stylesheet" 
         href="<?php echo _ASSET_LIBS;?>leaflet-0.7.3/leaflet.css"
    />
    <style>
        body {
            padding: 0;
            margin: 0;
        }
        html, body, #map-canvas {
            height: 100%;
            width: 100%;
        }
    </style>
    <script
        src="<?php echo _ASSET_LIBS;?>leaflet-0.7.3/leaflet.js">
    </script>
    <script src="<?php echo _ASSET_LIBS;?>leaflet-plugins/leaflet-realtime.min.js"></script>
 
</head>
<body>
		<!-- PAGE CONTENT BEGINS -->
		<?php 
		if (isset($sview) && $sview<>"") $this->load->view($sview);
		?>  

        <!--[if !IE]> -->
        <script src="<?php echo _ASSET_TEMPLATE;?>components/jquery/dist/jquery.js"></script>
		<?php
		if (isset($js) && $js<>"") $this->load->view($js);
		?>

</body>
</html>
