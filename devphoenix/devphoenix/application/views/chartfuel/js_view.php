<?php
$var_refresh            = "600000";

$arr_jam = array ("05","06","07","08","09","10","11","12","13","14","15","16","17","18","19","20","21","22","23","00","01","02","03","04");
$label_x = "'05.00','06.00','07.00','08.00','09.00','10.00','11.00','12.00','13.00','14.00','15.00','16.00','17.00','18.00','19.00','20.00','21.00','22.00','23.00','00.00','01.00','02.00','03.00','04.00'";

?>
<script type="text/javascript">
var BASE_URL = '<?php echo _URL; ?>';

jQuery(function($) {

    $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true
    })

    $( "#btnFilterTotalProduksi" ).click(function() {
        graph_fuel_per_type();
        get_liter_per_hm();
    });

    /**************************** GRAFIK TOTAL PRODUKSI PER JAM ****************************/
    graph_fuel_per_type ();
    setInterval(graph_fuel_per_type, <?php echo $var_refresh;?>); //time in milliseconds

    function graph_fuel_per_type () {
        var id = $("#txtFilterTotalProduksi").val();
        $.getJSON("<?php echo site_url('chartfuel/get_data_fuel_per_hari');?>", {id: id} ,function(chartData) {
            $('#graph_fuel_per_type').highcharts({
                chart: {
                    renderTo: 'graph_fuel_per_type'
                },
                title: {
                    text: 'Fuel Ratio Periode '+id
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
                        text: 'Liter / Tonase'
                    } ,
                    min: 0,
                   // ,max:100
                    opposite: true
                } , {
                    gridLineWidth: 0,
                    min: 0,
                    max:6,
                    title: {
                        text: 'Duration',
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

    get_liter_per_hm();
    setInterval(get_liter_per_hm, <?php echo $var_refresh;?>); //time in milliseconds
    function get_liter_per_hm () {
        var   id = $("#txtFilterTotalProduksi").val();
        var   createdHTML = '';
        var   j = 0;
        $.getJSON("<?php echo site_url('chartfuel/get_liter_per_hm');?>", {id: id} ,function(responseData) {
          for(item in responseData) {
              j = eval(item) + 1;
              formData = responseData[item];
              createdHTML += "<tr>";
              createdHTML += "<td>"+formData.egi+"</td>";
              createdHTML += "<td>"+formData.avg_today+"</td>";
              createdHTML += "<td>"+formData.avg_todate+"</td>";
              createdHTML += "</tr>";
          }
          $("#table_liter_per_hm").html(createdHTML);
        });
    }

	<?php

		$fuelTanks = $this->db->where('show_on_chart', 1)->get('master_fuel_tank');

		$ft 			= [];
		$dataCapacity 	= [];
		$dataStock 		= [];

		foreach ($fuelTanks->result() as $f)
		{
			$lastUpdate 	= new DateTime($f->last_update_volume);
			$ft[] 			= $f->name.' ('.$lastUpdate->format('d-M-Y H:i').')';
			$dataCapacity[] = $f->capacity;
			$dataStock[] 	= $f->stock;
		}

	?>


	// tambahan dari bagas
	$('#fuel-stock-chart').highcharts({
		chart: {
            type: 'column'
        },
        title: {
            text: 'FUEL STOCK'
        },
        xAxis: {
            categories: <?= json_encode($ft) ?>
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Stock Fuel dalam Liter'
            }
        },
        tooltip: {
            pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> L<br/>',
            shared: true
        },
		plotOptions: {
            column: {
                grouping: false,
                shadow: false,
                borderWidth: 0
            }
        },
        series: [{
            name: 'Kapasitas',
			color: 'rgba(165,170,217,1)',
            data: <?= json_encode($dataCapacity, JSON_NUMERIC_CHECK) ?>,
			pointPadding: 0.12,
            pointPlacement: -0.2
        }, {
            name: 'Stock',
			color: 'rgba(126,86,134,.9)',
            data: <?= json_encode($dataStock, JSON_NUMERIC_CHECK) ?>,
			pointPadding: 0.2,
            pointPlacement: -0.2
        }]
	});
})
</script>
