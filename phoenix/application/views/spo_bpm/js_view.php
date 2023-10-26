<?php    
$var_refresh            = "600000"; 
$label_x                = "";

//$arr_jam = array ("05","06","07","08","09","10","11","12","13","14","15","16","17","18","19","20","21","22","23","00","01","02","03","04");
FOR ($i=1;$i<=31;$i++) {
    if (isset($label_x) && $label_x <> "") $label_x .= ",";
    $label_x .= (strlen($i)<2)?'"0'.$i.'"':'"'.$i.'"';
}   

# $label_x = "'1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','20','20','20','20','20','20','20',";

?>
<script type="text/javascript">
var BASE_URL = '<?php echo _URL; ?>';

jQuery(function($) { 
    $(".chosen-select").chosen();  
 
    $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true
    })
 
    $( "#btnFilter" ).click(function() {   
        get_graph_spo_bpm();   
    }); 
 
    // get_graph_spo_bpm (); 
    // setInterval(graph_fuel_per_type, <?php echo $var_refresh;?>); //time in milliseconds  
    
    function get_graph_spo_bpm () {
        var nrp     = $("#nrp").val();  
        var start   = $("#txtFilter1").val();  
        var end     = $("#txtFilter2").val();  

        $.getJSON("<?php echo site_url('spo_bpm/get_data_spo_bpm_pegawai');?>", {nrp: nrp, start: start, end: end} ,function(chartData) {
            $('#graph_spo_bpm').highcharts({
                chart: {
                    renderTo: 'graph_spo_bpm' 
                },
                title: {
                    text: 'Grafik History SPO - BPM'
                }, 
                xAxis: {
                    categories: [<?php echo $label_x;?>],
                    gridLineWidth: 1,
                    gridLineDashStyle: 'dot',
                    labels: {
                        rotation  : -90, 
                        align : 'right'
                    }
                },
                yAxis: [{ 
                    title: {
                        text: 'SPO'
                    } ,
                    min: 0,
                   // ,max:100
                    opposite: true,
                    plotBands: [{ // Light air
                        from: 95,
                        to: 200,
                        color: '#e7fde8',
                        label: {
                            text: 'SPO : Disetujui bekerja >= 95%',
                            style: {
                                fontSize: '6px',
                                color: '#606060'
                            }
                        }
                        }, { // Light breeze
                            from: 90.0,
                            to: 94.9,
                            color: '#fbfde7',
                            label: {
                                text: 'SPO : Dalam Pengawasan 90%-94%',
                                style: {
                                    fontSize: '6px',
                                    color: '#606060'
                                }
                            }
                        }
                    ]
                } , {  
                    gridLineWidth: 0,
                    min: 0,
                    //max:6,
                    title: {
                        text: 'BPM',
                        style: {
                            color: Highcharts.getOptions().colors[0]
                        }
                    },
                    labels: {
                        format: '{value}',
                        style: {
                            color: Highcharts.getOptions().colors[0]
                        }
                    } ,
                    plotBands: [{ // Light air
                        from: 50,
                        to: 100.9,
                        color: '#e7fde8',
                        label: {
                            text: 'BPM : Disetujui bekerja. 50% - 100%',
                            style: {
                                fontSize: '6px',
                                color: '#606060'
                            }
                        }
                        } 
                    ]
                }   
                
                ], 
                legend: {
                    align: 'center',
                    // x: -20,
                    verticalAlign: 'bottom',
                    // y: 20,
                    floating: false,
                    backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColorSolid) || 'white',
                    borderColor: '#CCC',
                    borderWidth: 1,
                    shadow: false,
                    layout: 'horizontal'
                },
                tooltip: {
                    formatter: function() {
                        return  this.series.name +': '+ this.y   
                    }
                },
                plotOptions: {
                    column: { 
                        dataLabels: {
                            enabled: true , 
                            color: "#505150",
                            y: 5, 
                            crop: false,
                            overflow: 'none',
                            style: {
                                fontSize: '8px',
                                fontFamily: 'Verdana, sans-serif'
                            }
                        } 
                    },
                    line: { 
                        dataLabels: {
                            enabled: true  ,
                            color: "#505150",
                            y: -5, 
                            crop: false,
                            overflow: 'none',
                            style: {
                                fontSize: '8px',
                                fontFamily: 'Verdana, sans-serif'
                            }
                        } 
                    }
                },
                credits: {
                    enabled: false
                },
                series:  chartData

            });
        });
    }
})
</script>