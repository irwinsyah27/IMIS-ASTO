<?php   
$trip_per_hour = "";
$ton_per_hour = "";
$ton_per_hour_cpp = "";
$ton_per_hour_port = "";
$production_per_shift = "";
///////////////////////// hormon wb kpp   
$title_per_hour = "Pemakaian Solar per Egi Periode " .$tgl;

$production_per_shift = "";
$label_axis_hormon_wb_kpp = "";

if (count($rs) > 0) {
    FOREACH ($rs AS $r) {
        $arr_data[$r["master_egi_id"]] = $r["total"];
    }
} 
if (count($list_egi) > 0) {
    FOREACH ($list_egi AS $r) {
        $egi_id    = $r["master_egi_id"]; 
        if (empty($arr_data[$egi_id])) $arr_data[$egi_id] = 0;

        if (isset($label_axis_hormon_wb_kpp) && $label_axis_hormon_wb_kpp <> "") $label_axis_hormon_wb_kpp .= ",";
        $label_axis_hormon_wb_kpp .= "\"".$r["keterangan"]."\"";

        if (isset($production_per_shift) && $production_per_shift <> "") $production_per_shift .= ",";
        $production_per_shift .= $arr_data[$egi_id];  
    }
}    
?>
<script type="text/javascript">
var BASE_URL = '<?php echo _URL; ?>';

jQuery(function($) {  
    // hormon_wb_kpp
    $('#hormon_wb_kpp_port').highcharts({
        chart: { 
            //type: 'column',
            //width: 940,
            //height: 500, 
        },
        title: {
            text: '<?php echo $title_per_hour;?>'
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
                text: 'Pemakaian Solar'
            } ,
            min: 0
           // ,max:100 
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
                return '<b>'+ this.x +'</b><br/>'+
                    this.series.name +': '+ this.y  
                    // +'<br/>'+
                    // 'Total: '+ this.point.stackTotal;
            }
        },
        plotOptions: {
            column: {
                //stacking: 'normal',
                dataLabels: {
                    enabled: false,
                    color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                }
            }
        },
        credits: {
            enabled: false
        },
        series: [   {  
            type: 'column', 
            name: 'Solar/Egi',
            data: [<?php echo $production_per_shift;?>]
        }  
        ]
    });

})
</script>