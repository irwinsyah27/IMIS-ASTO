<?php   
 
$tgl = $tgl;
$tmp = explode("-", $tgl);
$tgl_awal = $tmp["0"]."-".$tmp["1"]."-01";

///////////////////////// production achievement 
$akhir_tgl_bln_ini = date("t",mktime(0, 0, 0, $tmp["1"]-1, $tmp["2"], $tmp["0"] )); 


if (count($rs) > 0) {
    FOREACH ($rs AS $r) {
        if (isset($label_axis_production_achievement) && $label_axis_production_achievement <>"") $label_axis_production_achievement .= ",";
        $label_axis_production_achievement .= "'".$r["nama"]."'";

        if (isset($production_per_day) && $production_per_day <> "") $production_per_day .= ","; 
        if ($r["bpm_in"] =="") $r["bpm_in"]  = 0;
        $production_per_day .= $r["bpm_in"] ; 
    }
} 
 
 
?>
<script type="text/javascript">
var BASE_URL = '<?php echo _URL; ?>';

jQuery(function($) { 

    //production_achievment
    $('#hormon_wb_kpp').highcharts({
        chart: { 
            //type: 'column',
            //width: 940,
            //height: 500, 
        },
        title: {
            text: 'Heart Rate Statistic'
        }, 
        xAxis: {
            categories: [<?php echo $label_axis_production_achievement;?>],
            gridLineWidth: 1,
            gridLineDashStyle: 'dot',
            labels: {
                rotation  : -90, 
                align : 'right'
            }
        },
        yAxis: [{ 
            title: {
                text: 'Actual'
            } 
            //  ,max:100
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
        series: [  
        { 
            type: 'column',
            name: 'Heart Rate',
            data: [<?php echo $production_per_day; ?>]
        } 
        ]
    });
 
 

})
</script>