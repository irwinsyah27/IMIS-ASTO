jQuery(document).ready(function($) {
    'use strict';
    var routeSelect = document.getElementById('routeSelect');
    var map = document.getElementById('map-canvas');
    var autoRefresh = false;
    var intervalID = 0;
    var sessionIDArray = [];
    var viewingAllRoutes = false;
 
    getAllRoutesForMap();
    loadRoutesIntoDropdownBox();
    
    /*
    $("#routeSelect").change(function() {
        if (hasMap()) {
            viewingAllRoutes = false;
            
            getRouteForMap();
        } 
    });
    */
    
    
    $("#filter").click(function() { 
    	$('.infoproses').show();
        if (hasMap()) {
            viewingAllRoutes = false;
            
            getRouteForMap();
        } 
    });
       
    $("#refresh").click(function() {
        if (viewingAllRoutes) {
            getAllRoutesForMap(); 
        } else {
            if (hasMap()) {
                getRouteForMap();
            }             
        }
    });
        
        
    $('#autorefresh').click(function() { 
        if (autoRefresh) {
            turnOffAutoRefresh();           
        } else {
            turnOnAutoRefresh();                     
        }
    }); 
    
    $("#viewall").click(function() {
        getAllRoutesForMap();
    });
        
    function getAllRoutesForMap() {
        viewingAllRoutes = true;
        routeSelect.selectedIndex = 0;
        showPermanentMessage('Please select a route below');
                   
        $.ajax({
            url: _URL+'displaymap/getallroutesformap',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                loadGPSLocations(data);
        },
        error: function (xhr, status, errorThrown) {
            console.log("error status: " + xhr.status);
            console.log("errorThrown: " + errorThrown);
        }
        });
    }           
        
    function loadRoutesIntoDropdownBox() {      
        $.ajax({
            url: _URL+'displaymap/getroutes',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
            loadRoutes(data);
        },
        error: function (xhr, status, errorThrown) {
            console.log("status: " + xhr.status);
            console.log("errorThrown: " + errorThrown);
        }
        });
    }    
    
    function loadRoutes(json) {        
        if (json.length == 0) {
            showPermanentMessage('There are no routes available to view');
        }
        else {
            // create the first option of the dropdown box
            var option = document.createElement('option');
            option.setAttribute('value', '0');
            option.innerHTML = 'Select Unit...';
            routeSelect.appendChild(option);

            // when a user taps on a marker, the position of the sessionID in this array is the position of the route
            // in the dropdown box. it's used below to set the index of the dropdown box when the map is changed
            sessionIDArray = [];
            
            // iterate through the routes and load them into the dropdwon box.
            $(json.routes).each(function(key, value){
                var option = document.createElement('option');
                option.setAttribute('value', '' + $(this).attr('sessionID'));

                sessionIDArray.push($(this).attr('sessionID'));

                option.innerHTML = $(this).attr('unit') + " " + $(this).attr('times');
                routeSelect.appendChild(option);
            });

            // need to reset this for firefox
            routeSelect.selectedIndex = 0;

            showPermanentMessage('Pilih unit');
        }
    }
    
    function getRouteForMap() { 
        if (hasMap()) {
            // console.log($("#routeSelect").prop("selectedIndex"));

           var url = _URL+'displaymap/getrouteformap/' + $('#routeSelect').val()+'/' + $('#start_date').val()+'/' + $('#start_time').val()+'/' + $('#end_date').val()+'/' + $('#end_time').val()+'/' + $('#track').val();

            $.ajax({
                   url: url,
                   type: 'GET',
                   dataType: 'json',
                   success: function(data) {
                	  loadGPSLocations(data);
                   },
                   error: function (xhr, status, errorThrown) {
                       console.log("status: " + xhr.status);
                       console.log("errorThrown: " + errorThrown);
                       alert('There is no tracking data to view');
                       $('.infoproses').hide();
                    }
               });
        
        } 
    }
    
    function loadGPSLocations(json) {
        // console.log(JSON.stringify(json));
    	if (json.locations.length == 0) {
            showPermanentMessage('There is no tracking data to view');
            map.innerHTML = '';
            $('.infoproses').hide();
        }
        else {
        	if (map.id == 'map-canvas') {
                // clear any old map objects
                document.getElementById('map-canvas').outerHTML = "<div id='map-canvas'></div>";
           
                // use leaflet (http://leafletjs.com/) to create our map and map layers
                var gpsTrackerMap = new L.map('map-canvas');
            
                var openStreetMapsURL = ('https:' == document.location.protocol ? 'https://' : 'http://') +
                 '{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';

                var openStreetMapsLayer = new L.TileLayer(openStreetMapsURL,
                {attribution:'&copy;2014 <a href="http://openstreetmap.org">OpenStreetMap</a> contributors'});
 
                // var googleMapsLayer = new L.Google('ROADMAP');
                
                var kalimantan = L.tileLayer.wms("http://10.13.130.50:8080/geoserver/KPP/wms", 
                {
                    layers: 'KPP:Kalteng',
                    format: 'image/png',
                    transparent: true 
                }); 
                /*
                var kalimantan = L.tileLayer.wms("http://localhost:8080/geoserver/indonesia_osm/wms", 
                {
                    layers: 'indonesia_osm:gis_landuse_a',
                    format: 'image/png',
                    transparent: true 
                }); 
                */
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

             
               // L.polyline([[0, 0], ]).addTo(gpsTrackerMap);

                // this sets which map layer will first be displayed
                gpsTrackerMap.addLayer(kalimantan);
                gpsTrackerMap.addControl(jalur_hauling);
                gpsTrackerMap.addControl(jalur_poi);
                // gpsTrackerMap.addLayer(openStreetMapsLayer);

                // this is the switcher control to switch between map types
                gpsTrackerMap.addControl(new L.Control.Layers({
                    // 'Bing Maps':bingMapsLayer,
                    'OpenStreetMaps':openStreetMapsLayer,
                    //'Google Maps':googleMapsLayer,
                    'Peta Kalteng':kalimantan
                }, {
                	'Jalur Houling':jalur_hauling,
                    'POI':jalur_poi
                    }));
            }

                var finalLocation = false;
                var counter = 0;
                var locationArray = [];
                
                // iterate through the locations and create map markers for each location
                $(json.locations).each(function(key, value){
                    var latitude =  $(this).attr('latitude');
                    var longitude = $(this).attr('longitude');
                    var tempLocation = new L.LatLng(latitude, longitude);
                    
                    locationArray.push(tempLocation);                    
                    counter++;

                    // want to set the map center on the last location
                    if (counter == $(json.locations).length) {
                        //gpsTrackerMap.setView(tempLocation, zoom);  if using fixed zoom
                        finalLocation = true;
                    
                        /*
                        if (!viewingAllRoutes) {
                            //displayCityName(latitude, longitude);

                            // dk start : gambar line
                            var color;
                            var r = Math.floor(Math.random() * 255);
                            var g = Math.floor(Math.random() * 255);
                            var b = Math.floor(Math.random() * 255);
                            color= "rgb("+r+" ,"+g+","+ b+")"; 

                            var firstpolyline = new L.Polyline(locationArray, {
                            color: color,
                            weight: 3,
                            opacity: 0.5,
                            smoothFactor: 1

                            });
                            firstpolyline.addTo(gpsTrackerMap);
                            // end dk
                        }
                        */
                    }

                    var marker = createMarker(
                        latitude,
                        longitude,
                        $(this).attr('speed'),
                        $(this).attr('direction'),
                        $(this).attr('distance'),
                        $(this).attr('locationMethod'),
                        $(this).attr('gpsTime'),
                        $(this).attr('userName'),
                        $(this).attr('sessionID'),
                        $(this).attr('accuracy'),
                        $(this).attr('extraInfo'),
                        gpsTrackerMap, finalLocation,$(this).attr('unit'));


                }); 
 


                // fit markers within window
                var bounds = new L.LatLngBounds(locationArray);
                gpsTrackerMap.fitBounds(bounds);


                // restarting interval here in case we are coming from viewing all routes
                if (autoRefresh) {
                    restartInterval();
                } 
            }
        $('.infoproses').hide();
    } 

    function createMarker(latitude, longitude, speed, direction, distance, locationMethod, gpsTime,
                          userName, sessionID, accuracy, extraInfo, map, finalLocation, unit) {
        var iconUrl;

        if (finalLocation) {
            iconUrl = _IMG+'images/coolblue_small.png';
        } else {
            iconUrl = _IMG+'images/coolgreen2_small.png';

            if (speed >= 57) iconUrl = _IMG+'images/coolred_small.png';
        }

        var markerIcon = new L.Icon({
                iconUrl:      iconUrl,
                shadowUrl:    _IMG+'images/coolshadow_small.png',
                iconSize:     [12, 20],
                shadowSize:   [22, 20],
                iconAnchor:   [6, 20],
                shadowAnchor: [6, 20],
                popupAnchor:  [-3, -25]
        });

        var lastMarker = "</td></tr>";

        // when a user clicks on last marker, let them know it's final one
        if (finalLocation) {
            lastMarker = "</td></tr><tr><td align=left>&nbsp;</td><td><b>Final location</b></td></tr>";
        }

        // convert from meters to feet
        accuracy = parseInt(accuracy * 3.28);

        var popupWindowText = "<table border=0 style=\"font-size:95%;font-family:arial,helvetica,sans-serif;color:#000;\">" +
            "<tr><td align=right>&nbsp;</td><td>&nbsp;</td><td rowspan=2 align=right>" +
            "<img src="+ _IMG+"/images/" + getCompassImage(direction) + ".jpg alt= />" + lastMarker +
            "<tr><td align=right>Name:&nbsp;</td><td>" + userName + "</td><td>&nbsp;</td></tr>" +
            "<tr><td align=right>Unit:&nbsp;</td><td>" + unit + "</td><td>&nbsp;</td></tr>" +
            "<tr><td align=right>Speed:&nbsp;</td><td>" + speed +  " km/h</td></tr>" + 
            "<tr><td align=right>Time:&nbsp;</td><td colspan=2>" + gpsTime +  "</td></tr>" +
            "<tr><td align=right>Accuracy:&nbsp;</td><td>" + accuracy + " ft</td><td>&nbsp;</td></tr></table>";


        var gpstrackerMarker;
        var title = userName + " - " + gpsTime;

        // make sure the final red marker always displays on top 
        if (finalLocation) {
            gpstrackerMarker = new L.marker(new L.LatLng(latitude, longitude), {title: title, icon: markerIcon, zIndexOffset: 999}).bindPopup(popupWindowText).addTo(map);
        } else {
            gpstrackerMarker = new L.marker(new L.LatLng(latitude, longitude), {title: title, icon: markerIcon}).bindPopup(popupWindowText).addTo(map);
        }
        

        // if we are viewing all routes, we want to go to a route when a user taps on a marker instead of displaying popupWindow
        if (viewingAllRoutes) {
            /* close by dwi
            gpstrackerMarker.unbindPopup();
            gpstrackerMarker.on("click", function() {        
                var url = 'getrouteformap.php?sessionid=' + sessionID;

                viewingAllRoutes = false;
 
                var indexOfRouteInRouteSelectDropdwon = sessionIDArray.indexOf(sessionID) + 1;
                routeSelect.selectedIndex = indexOfRouteInRouteSelectDropdwon;

                if (autoRefresh) {
                    restartInterval(); 
                }

                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        loadGPSLocations(data);
                    },
                    error: function (xhr, status, errorThrown) {
                        console.log("status: " + xhr.status);
                        console.log("errorThrown: " + errorThrown);
                    }
                 });
            }); // on click
            */
        } 
    }

    function getCompassImage(azimuth) {
        if ((azimuth >= 337 && azimuth <= 360) || (azimuth >= 0 && azimuth < 23))
                return "compassN";
        if (azimuth >= 23 && azimuth < 68)
                return "compassNE";
        if (azimuth >= 68 && azimuth < 113)
                return "compassE";
        if (azimuth >= 113 && azimuth < 158)
                return "compassSE";
        if (azimuth >= 158 && azimuth < 203)
                return "compassS";
        if (azimuth >= 203 && azimuth < 248)
                return "compassSW";
        if (azimuth >= 248 && azimuth < 293)
                return "compassW";
        if (azimuth >= 293 && azimuth < 337)
                return "compassNW";

        return "";
    }
    
    // check to see if we have a map loaded, don't want to autorefresh or delete without it
    function hasMap() {
        //if (routeSelect.selectedIndex == 0) { // means no map
        //    return false;
        //}
        //else {
            return true;
        //}
    }

    function displayCityName(latitude, longitude) {
        var lat = parseFloat(latitude);
        var lng = parseFloat(longitude);
        var latlng = new google.maps.LatLng(lat, lng);
        var reverseGeocoder = new google.maps.Geocoder();
        reverseGeocoder.geocode({'latLng': latlng}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                // results[0] is full address
                if (results[1]) {
                    var reverseGeocoderResult = results[1].formatted_address; 
                    showPermanentMessage(reverseGeocoderResult);
                }
            } else {
                console.log('Geocoder failed due to: ' + status);
            }
        });
    }

    function turnOffAutoRefresh() {
        showMessage('Auto Refresh Off');
        $('#autorefresh').val('Auto Refresh Off');
    
        autoRefresh = false;
        clearInterval(intervalID);         
    }

    function turnOnAutoRefresh() {
        showMessage('Auto Refresh On (1 min)'); 
        $('#autorefresh').val('Auto Refresh On');
        autoRefresh = true;

        restartInterval();         
    }
    
    function restartInterval() {
        // if someone is viewing all routes and then switches to a single route
        // while autorefresh is on then the setInterval is going to be running with getAllRoutesForMap
        // and not getRouteForMap 

        clearInterval(intervalID);
        
        if (viewingAllRoutes) {
            intervalID = setInterval(getAllRoutesForMap, 60 * 1000); // one minute 
        } else {
            intervalID = setInterval(getRouteForMap, 60 * 1000);          
        }          
    }

    function deleteRoute() {
        if (hasMap()) {
		
    		// comment out these two lines to get delete working
    		// confirm("Disabled here on test website, this works fine.");
    		// return false;
		
            var answer = confirm("This will permanently delete this route\n from the database. Do you want to delete?");
            if (answer){
                var url = 'deleteroute.php' + $('#routeSelect').val();

                $.ajax({
                       url: url,
                       type: 'GET',
                       success: function() {
                          deleteRouteResponse();
                          getAllRoutesForMap();
                       }
                   });
            }
            else {
                return false;
            }
        }
        else {
            alert("Please select a route before trying to delete.");
        }
    }

    function deleteRouteResponse() {
        routeSelect.length = 0;

        document.getElementById('map-canvas').outerHTML = "<div id='map-canvas'></div>";

        $.ajax({
               url: 'getroutes.php',
               type: 'GET',
               success: function(data) {
                  loadRoutes(data);
               }
           });
    }

    // message visible for 7 seconds
    function showMessage(message) {
        // if we show a message like start auto refresh, we want to put back our current address afterwards
        var tempMessage =  $('#messages').html();
        
        $('#messages').html(message);
        setTimeout(function() {
            $('#messages').html(tempMessage);
        }, 7 * 1000); // 7 seconds
    }

    function showPermanentMessage(message) {
        $('#messages').html(message);
    }

    // for debugging, console.log(objectToString(map));
    function objectToString (obj) {
        var str = '';
        for (var p in obj) {
            if (obj.hasOwnProperty(p)) {
                str += p + ': ' + obj[p] + '\n';
            }
        }
        return str;
    }
    
    function setTheme() {
        //var bodyBackgroundColor = $('body').css('backgroundColor');
        //$('.container').css('background-color', bodyBackgroundColor);
        //$('body').css('background-color', '#ccc');
        // $('head').append('<link rel="stylesheet" href="style2.css" type="text/css" />');        
    }
});