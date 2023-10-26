<?php
$var_refresh            = "600000"; 

///////////////////////// hormon wb kpp   

$arr_jam = array ("05","06","07","08","09","10","11","12","13","14","15","16","17","18","19","20","21","22","23","00","01","02","03","04");
$label_axis_hormon_wb_kpp = "'05.00','06.00','07.00','08.00','09.00','10.00','11.00','12.00','13.00','14.00','15.00','16.00','17.00','18.00','19.00','20.00','21.00','22.00','23.00','00.00','01.00','02.00','03.00','04.00'";
 
?>
<script type="text/javascript">
var BASE_URL = '<?php echo _URL; ?>';

jQuery(function($) { 

    $('div.demo marquee').marquee('pointer').mouseover(function () {
            $(this).trigger('stop');
        }).mouseout(function () {
            $(this).trigger('start');
        }).mousemove(function (event) {
            if ($(this).data('drag') == true) {
                this.scrollLeft = $(this).data('scrollX') + ($(this).data('x') - event.clientX);
            }
        }).mousedown(function (event) {
            $(this).data('drag', true).data('x', event.clientX).data('scrollX', this.scrollLeft);
        }).mouseup(function () {
            $(this).data('drag', false);
        });
        
 
    $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true
    })
 
    $( "#btnFilterTotalProduksi" ).click(function() {   
        grafik_port (); 
        grafik_produksi_per_shift();
        grafik_hourly_tonase_hauling ();
        grafik_produksi_per_3_jam ();
    }); 

    /**************************** GRAFIK TOTAL PRODUKSI PER JAM ****************************/
    grafik_port (); 
    setInterval(grafik_port, <?php echo $var_refresh;?>); //time in milliseconds  
    
    function grafik_port () {
        var id = $("#txtFilterTotalProduksi").val();  
        $.getJSON("<?php echo site_url('hourly_monitoring/get_data_timbangan_netto_per_hari');?>", {id: id} ,function(chartData) {
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
        $.getJSON("<?php echo site_url('hourly_monitoring/get_total_produksi_per_shift');?>", {id: id} ,function(chartDataShift) {
            $('#chartProduksiPerShift').highcharts({
                chart: { 
                    // type: 'bar',
                    //width: 940,
                    renderTo: 'chartProduksiPerShift'
                    //height: 250, 
                },
                title: {
                    text: 'Achievement Per Shift '+id,
                    style: {
                                fontSize: '14px',
                                fontFamily: 'Verdana, sans-serif'
                            }
                }, 
                xAxis: {
                    categories: ["Shift 1","Shift 2","Total"],
                    gridLineWidth: 1,
                    gridLineDashStyle: 'dot',
                    labels: {
                        rotation  : 0, 
                        align : 'center'
                    },
                    title: {
                        text: null
                    }
                },
                yAxis: { 
                    min: 0, 
                    max:20000,
                        title: {
                            text: 'Tonase'
                        } 
                        //  ,max:100
                }
                , 
                legend: {
                    enabled: false,
                    align: 'center',
                    // x: -20,
                    verticalAlign: 'bottom',
                    // y: 20,
                    floating: false,
                    backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColorSolid) || 'white',
                    borderColor: '#CCC',
                    borderWidth: 1,
                    shadow: false,
                    layout: 'horizontal',
                    width:204,
                    itemStyle: {
                         font: '6pt Verdana, sans-serif' 
                      }
                },
                tooltip: {
                    formatter: function() {
                        return   this.y   
                    }
                },
                plotOptions: {
                    column: { 
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

    /**************************** GRAFIK HOURLY TONASE HAULING ****************************/
    grafik_hourly_tonase_hauling (); 
    setInterval(grafik_hourly_tonase_hauling, <?php echo $var_refresh;?>); //time in milliseconds  
    
    function grafik_hourly_tonase_hauling () {
        var id = $("#txtFilterTotalProduksi").val();  
        $.getJSON("<?php echo site_url('hourly_monitoring/get_data_hourly_tonase_hauling');?>", {id: id} ,function(chartData) {
            $('#chartHourlyTonaseHauling').highcharts({
                chart: {
                    // height: 600, 
                    renderTo: 'chartHourlyTonaseHauling' 
                },
                title: {
                    text: 'Hourly Tonase Hauling Unit KPP Periode '+ id
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
                        text: 'Payload'
                    } ,
                    min: 20,
                    max: 40,
                    tickInterval: 20
                   // ,max:100
                   // opposite: true
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
                    } 
                },
                credits: {
                    enabled: false
                },
                series:  chartData

            });
        });
    }


    /**************************** GRAFIK TOTAL PRODUKSI PER 3 JAM ****************************/
    grafik_produksi_per_3_jam (); 
    setInterval(grafik_produksi_per_3_jam, <?php echo $var_refresh;?>); //time in milliseconds  
    
    function grafik_produksi_per_3_jam () {
        var id = $("#txtFilterTotalProduksi").val();  
        var createdHTML = '';

        $.getJSON("<?php echo site_url('hourly_monitoring/get_data_timbangan_per_3_jam');?>", {id: id} ,function(responseData) {
           
          for(item in responseData) {
              j = eval(item) + 1;
              formData = responseData[item]; 
              createdHTML += "<tr>"; 
              createdHTML += "<td>"+formData.kode+"</td>";

              if (formData.egi == undefined) {
                createdHTML += "<td></td>"; 
              } else {
                    createdHTML += "<td>"+formData.egi+"</td>"; 
              }
              

              if (formData.unit_09 == undefined) {
                  createdHTML += "<td></td>";
                  createdHTML += "<td></td>";
                  createdHTML += "<td></td>";
                  createdHTML += "<td></td>";
              } else { 
                  createdHTML += "<td>"+formData.unit_09+"</td>";
                  createdHTML += "<td>"+formData.ritase_09+"</td>";
                  createdHTML += "<td>"+formData.produksi_09+"</td>";
                  createdHTML += "<td>"+ parseFloat(formData.produksi_09 / formData.ritase_09).toFixed(2) +"</td>";
              }

              if (formData.unit_12 == undefined) {
                  createdHTML += "<td></td>";
                  createdHTML += "<td></td>";
                  createdHTML += "<td></td>";
                  createdHTML += "<td></td>";
              } else { 
                  createdHTML += "<td>"+formData.unit_12+"</td>";
                  createdHTML += "<td>"+formData.ritase_12+"</td>";
                  createdHTML += "<td>"+formData.produksi_12+"</td>";
                  createdHTML += "<td>"+ parseFloat(formData.produksi_12 / formData.ritase_12).toFixed(2) +"</td>";
              }


              if (formData.unit_15 == undefined) {
                  createdHTML += "<td></td>";
                  createdHTML += "<td></td>";
                  createdHTML += "<td></td>";
                  createdHTML += "<td></td>";
              } else { 
                  createdHTML += "<td>"+formData.unit_15+"</td>";
                  createdHTML += "<td>"+formData.ritase_15+"</td>";
                  createdHTML += "<td>"+formData.produksi_15+"</td>";
                  createdHTML += "<td>"+ parseFloat(formData.produksi_15 / formData.ritase_15).toFixed(2) +"</td>";
              }

              if (formData.unit_18 == undefined) {
                  createdHTML += "<td></td>";
                  createdHTML += "<td></td>";
                  createdHTML += "<td></td>";
                  createdHTML += "<td></td>";
              } else { 
                  createdHTML += "<td>"+formData.unit_18+"</td>";
                  createdHTML += "<td>"+formData.ritase_18+"</td>";
                  createdHTML += "<td>"+formData.produksi_18+"</td>";
                  createdHTML += "<td>"+ parseFloat(formData.produksi_18 / formData.ritase_18).toFixed(2) +"</td>";
              }

              if (formData.unit_21 == undefined) {
                  createdHTML += "<td></td>";
                  createdHTML += "<td></td>";
                  createdHTML += "<td></td>";
                  createdHTML += "<td></td>";
              } else { 
                  createdHTML += "<td>"+formData.unit_21+"</td>";
                  createdHTML += "<td>"+formData.ritase_21+"</td>";
                  createdHTML += "<td>"+formData.produksi_21+"</td>";
                  createdHTML += "<td>"+ parseFloat(formData.produksi_21 / formData.ritase_21).toFixed(2) +"</td>";
              }

              if (formData.unit_00 == undefined) {
                  createdHTML += "<td></td>";
                  createdHTML += "<td></td>";
                  createdHTML += "<td></td>";
                  createdHTML += "<td></td>";
              } else { 
                  createdHTML += "<td>"+formData.unit_00+"</td>";
                  createdHTML += "<td>"+formData.ritase_00+"</td>";
                  createdHTML += "<td>"+formData.produksi_00+"</td>";
                  createdHTML += "<td>"+ parseFloat(formData.produksi_00 / formData.ritase_00).toFixed(2) +"</td>";
              }

              if (formData.unit_03 == undefined) {
                  createdHTML += "<td></td>";
                  createdHTML += "<td></td>";
                  createdHTML += "<td></td>";
                  createdHTML += "<td></td>";
              } else { 
                  createdHTML += "<td>"+formData.unit_03+"</td>";
                  createdHTML += "<td>"+formData.ritase_03+"</td>";
                  createdHTML += "<td>"+formData.produksi_03+"</td>";
                  createdHTML += "<td>"+ parseFloat(formData.produksi_03 / formData.ritase_03).toFixed(2) +"</td>";
              }

              if (formData.unit_05 == undefined) {
                  createdHTML += "<td></td>";
                  createdHTML += "<td></td>";
                  createdHTML += "<td></td>";
                  createdHTML += "<td></td>";
              } else { 
                  createdHTML += "<td>"+formData.unit_05+"</td>";
                  createdHTML += "<td>"+formData.ritase_05+"</td>";
                  createdHTML += "<td>"+formData.produksi_05+"</td>";
                  createdHTML += "<td>"+ parseFloat(formData.produksi_05 / formData.ritase_05).toFixed(2) +"</td>"; 
              }
              createdHTML += "</tr>";
          }

          $("#data_produksi_per_3_jam").html(createdHTML); 
        });
    }
})
</script>