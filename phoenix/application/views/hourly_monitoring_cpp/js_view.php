<?php   
$trip_per_hour          = "";
$ton_per_hour           = "";
$ton_per_hour_cpp       = "";
$ton_per_hour_port      = "";
$production_per_shift   = "";
$var_refresh            = "600000"; 

///////////////////////// hormon wb kpp  
$title_shift            = "Production Achievement Per Shift Periode " .$tgl; 

$arr_jam = array ("05","06","07","08","09","10","11","12","13","14","15","16","17","18","19","20","21","22","23","00","01","02","03","04");
$label_axis_hormon_wb_kpp = "'05.00','06.00','07.00','08.00','09.00','10.00','11.00','12.00','13.00','14.00','15.00','16.00','17.00','18.00','19.00','20.00','21.00','22.00','23.00','00.00','01.00','02.00','03.00','04.00'";
 
?>
<script type="text/javascript">
var BASE_URL = '<?php echo _URL; ?>';

jQuery(function($) { 
 
    $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true
    })
 
    $( "#btnFilterTotalProduksi" ).click(function() {   
        grafik_port (); 
        grafik_produksi_per_shift();
    }); 

    /**************************** GRAFIK TOTAL PRODUKSI PER JAM ****************************/
    grafik_port (); 
    setInterval(grafik_port, <?php echo $var_refresh;?>); //time in milliseconds  
    
    function grafik_port () {
        var id = $("#txtFilterTotalProduksi").val();  
        $.getJSON("<?php echo site_url('hourly_monitoring_cpp/get_data_timbangan_netto_per_hari');?>", {id: id} ,function(chartData) {
            $('#hormon_wb_kpp_port').highcharts({
                chart: {
                    renderTo: 'hormon_wb_kpp_port' 
                },
                title: {
                    text: 'Hormon WB KPP - ASTO - PORT Periode '+id
                }, 
                xAxis: {
                    categories: [<?php echo $label_axis_hormon_wb_kpp;?>],
                    gridLineWidth: 1,
                    gridLineDashStyle: 'dot',
                    labels: {
                        rotation  : -90, 
                        align : 'right'
                    }
                },
                yAxis: [{ 
                    title: {
                        text: 'Ton'
                    } ,
                    min: 0,
                   // ,max:100
                    opposite: true
                }  , {  
                    gridLineWidth: 0,
                    min: 0,
                    max:6,
                    title: {
                        text: 'Trip',
                        style: {
                            color: Highcharts.getOptions().colors[0]
                        }
                    },
                    labels: {
                        format: '{value}',
                        style: {
                            color: Highcharts.getOptions().colors[0]
                        }
                    }

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

    /**************************** GRAFIK TOTAL PRODUKSI PER SHIFT ****************************/
    grafik_produksi_per_shift (); 
    setInterval(grafik_produksi_per_shift, <?php echo $var_refresh;?>); //time in milliseconds  
    
    function grafik_produksi_per_shift () {
        var id = $("#txtFilterTotalProduksi").val();  
        $.getJSON("<?php echo site_url('hourly_monitoring_cpp/get_total_produksi_per_shift');?>", {id: id} ,function(chartDataShift) {
            $('#chartProduksiPerShift').highcharts({
                chart: { 
                    type: 'bar',
                    //width: 940,
                    height: 250, 
                },
                title: {
                    text: 'Production Achievement Per Shift Periode '+id,
                    style: {
                                fontSize: '14px',
                                fontFamily: 'Verdana, sans-serif'
                            }
                }, 
                xAxis: {
                    categories: ["Shift"],
                    gridLineWidth: 1,
                    gridLineDashStyle: 'dot',
                    labels: {
                        rotation  : -90, 
                        align : 'right'
                    },
                    title: {
                        text: null
                    }
                },
                yAxis: { 
                    min: 0,
                    max:16000,
                        title: {
                            text: 'Actual'
                        } 
                        //  ,max:100
                }
                , 
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
                    bar: { 
                        dataLabels: {
                            enabled: true , 
                            color: "#505150",  
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
                series:  chartDataShift

            });
        });
    }
})
</script>