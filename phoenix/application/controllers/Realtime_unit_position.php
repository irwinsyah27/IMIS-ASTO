<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Realtime_unit_position extends CI_Controller {
 
   	public function __construct() {
      	parent::__construct();

      	if (empty($_SESSION["id"]))  header("location:login");
		$this->data['breadcrumb'] = '<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="#">Home</a>
							</li>
							<li class="active">Realtime Unit Position</li>';

		//$this->load->model('proto_model');
		$this->load->model("realtime_unit_position_model");  

		$menu["parent_menu"] 		= "dashboard";
		$menu["sub_menu"] 			= "realtime_unit_position"; 
		$this->data['check_menu']	= $menu;

		# akses level
		$akses 			= $this->realtime_unit_position_model->user_akses("realtime_unit_position");
		define('_USER_ACCESS_LEVEL_VIEW',$akses["view"]);
		define('_USER_ACCESS_LEVEL_ADD',$akses["add"]);
		define('_USER_ACCESS_LEVEL_UPDATE',$akses["edit"]);
		define('_USER_ACCESS_LEVEL_DELETE',$akses["del"]);
		define('_USER_ACCESS_LEVEL_DETAIL',''); 
   	}

	public function index()
	{ 
			$this->view();
	}

	public function view()
	{					   
		$this->load->view("realtime_unit_position/view" , $this->data);
	}  

	public function getListUnit()
	{     

		$stmt = $this->realtime_unit_position_model->get_tracker_uniq();  
		//$stmt = $this->realtime_unit_position_model->get_tracker();  
		$i = 0;
		$json = '{ "type": "FeatureCollection",
   		 		"features": [';
	
		foreach ($stmt as $row) {
			if ($i > 0) $json .= ',';
			$i += 1;
		$json .= '{ "geometry": {
				"type": "Point", 
				"coordinates": ';

					$json .= '['.$row['longitude'];
					$json .= ',';
					$json .= $row['latitude'].']'; 
					
		$json .= '}, 
				"type": "Feature",
				"properties": {
					"id": "'.$row['GPSLocationID'].'",
					"sessionId" : "'.$row['sessionId'].'"	,
					"max_gpsTime" : "'.$row['max_gpsTime'].'",
					"speed" : "'.$row['speed'].'",
					"direction" : "'.$row['direction'].'",
					"distance" : "'.$row['distance'].'",
					"locationMethod" : "'.$row['locationMethod'].'",
					"gpsTime" : "'.$row['gpsTime'].'",
					"userName" : "'.$row['userName'].'",
					"phoneNumber" : "'.$row['phoneNumber'].'",
					"accuracy" : "'.$row['accuracy'].'",
					"extraInfo" : "'.$row['extraInfo'].'"
					}
				}';
		}
		$json .= ']}';
  
		
	    header('Content-Type: application/json');
	    echo $json;
	} 
	public function getalldeviceonlinetoday()
	{     
		$routes = $this->realtime_unit_position_model->getalldeviceonlinetoday(); 

	    header('Content-Type: application/json');
	    echo $routes;
	} 
	public function getallroutesformap()
	{     
		$routes = $this->realtime_unit_position_model->getallroutesformap(); 

	    header('Content-Type: application/json');
	    echo $routes;
	} 
	public function getrouteformap()
	{     
		$tmp = explode("/",$_SERVER['REQUEST_URI']);  
		FOR ($i=0;$i<count($tmp); $i++) {
			if ($tmp[$i] == "undefined") $tmp[$i] = "";
		}
		$pk = $tmp["4"];
		
		$routes = $this->realtime_unit_position_model->getrouteformap($pk); 

	    header('Content-Type: application/json');
	    echo $routes;
	} 
	public function deleteroute()
	{     
		$tmp = explode("/",$_SERVER['REQUEST_URI']);  
		FOR ($i=0;$i<count($tmp); $i++) {
			if ($tmp[$i] == "undefined") $tmp[$i] = "";
		}
		$pk = $tmp["4"];
		
		$routes = $this->realtime_unit_position_model->deleteroute($pk); 

	    header('Content-Type: application/json');
	    echo $routes;
	} 
	public function getroutes()
	{     
		$routes = $this->realtime_unit_position_model->getroutes(); 

	    header('Content-Type: application/json');
	    echo $routes;
	} 
	public function updatelocation()
	{     
	    $latitude       = isset($_POST['latitude']) ? $_POST['latitude'] : '0';
	    $latitude       = (float)str_replace(",", ".", $latitude); // to handle European locale decimals
	    $longitude      = isset($_POST['longitude']) ? $_POST['longitude'] : '0';
	    $longitude      = (float)str_replace(",", ".", $longitude);    
	    $speed          = isset($_POST['speed']) ? $_POST['speed'] : 0;
	    $direction      = isset($_POST['direction']) ? $_POST['direction'] : 0;
	    $distance       = isset($_POST['distance']) ? $_POST['distance'] : '0';
	    $distance       = (float)str_replace(",", ".", $distance);
		
	    #$date           = isset($_POST['date']) ? $_POST['date'] : '0000-00-00 00:00:00';
	    #$date           = date("Y-m-d H:i:s");
		#$_date           = date("Y-m-d H:i:s",$_POST['gpsTime']);
	    #$date           = urldecode($date);
		
	    $_date           = $_POST['gpsTime'];
		
	    $locationmethod = isset($_POST['locationmethod']) ? $_POST['locationmethod'] : '';
	    $locationmethod = urldecode($locationmethod);
	    $username       = isset($_POST['username']) ? $_POST['username'] : 0;
	    $phonenumber    = isset($_POST['phonenumber']) ? $_POST['phonenumber'] : '';
	    $sessionid      = isset($_POST['sessionid']) ? $_POST['sessionid'] : 0;
	    $accuracy       = isset($_POST['accuracy']) ? $_POST['accuracy'] : 0;
	    $extrainfo      = isset($_POST['extrainfo']) ? $_POST['extrainfo'] : '';
	    $eventtype      = isset($_POST['eventtype']) ? $_POST['eventtype'] : '';
    
	    // doing some validation here
	    if ($latitude == 0 && $longitude == 0) {
	        exit('-1');
	    }
		
		/*** LOG FILE 
		$log  = "Insert to tbl tweet: ".PHP_EOL.  
		"Date: ".$_date.' - '.$_POST['gpsTime'].PHP_EOL. 
		"-------------------------".PHP_EOL; 
		file_put_contents('./mylxoxgf1l3.txt', $log, FILE_APPEND);
		  END LOG FILE ***/

	    $params = array('latitude'        => $latitude,
	                    'longitude'       => $longitude,
	                    'speed'           => $speed,
	                    'direction'       => $direction,
	                    'distance'        => $distance,
	                    '_date'            => $_date,
	                    'locationmethod'  => $locationmethod,
	                    'username'        => $username,
	                    'phonenumber'     => $phonenumber,
	                    'sessionid'       => $sessionid,
	                    'accuracy'        => $accuracy,
	                    'extrainfo'       => $extrainfo,
	                    'eventtype'       => $eventtype
	                );
		$timestamp = $this->realtime_unit_position_model->updatelocation($params);  
		echo $_POST['locations_id'];
		# echo $_POST['gpsTime']." :: ".$date;
	   	# echo $timestamp;    
	} 

	public function dpt()
	{     
		$choice =$_POST["button"];
		$cars = array("Honde", "BMW" , "Ferrari");
		$bikes = array("Ducaite", "Royal Enfield" , "Harley Davidson");
		if($choice == "cars") print json_encode($cars);
		else print json_encode($bikes);
	}


	public function test()
	{					   
		$this->data['routes'] = $this->realtime_unit_position_model->getLokasiTesting(); 
		$this->load->view("realtime_unit_position/test" , $this->data);
	}  
	public function track()
	{					   
		//$this->data['routes'] = $this->realtime_unit_position_model->getLokasiTesting(); 
		 
		$this->data['routes'] = $this->realtime_unit_position_model->getLokasi(); 
		$this->load->view("realtime_unit_position/track" , $this->data); 
/*
		
		$this->data['js'] 			= 'realtime_unit_position/js_track_unit';
		$this->data['sview'] 		= 'realtime_unit_position/track_unit'; 
		$this->load->view(_TEMPLATE_MAP , $this->data);
*/		

	}  
}
