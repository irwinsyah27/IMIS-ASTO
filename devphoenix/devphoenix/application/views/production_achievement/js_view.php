<?php   
 
#$tgl = $tgl;
#$tmp = explode("-", $tgl);

if ($_POST["txtFilter2"] == "") $_POST["txtFilter2"]  = date("Y");
if ($_POST["txtFilter2"] == "") $_POST["txtFilter1"]  = date("m");


$tmp["0"]           = $_POST["txtFilter2"];
$tmp["1"]           = $_POST["txtFilter1"];
$tmp["2"]           = date("d");

$tgl_awal = $tmp["0"]."-".$tmp["1"]."-01";

$bulan_tahun = date("M Y",mktime(0, 0, 0, $tmp["1"], $tmp["2"], $tmp["0"] )); 
$title = "Production Achievement Periode bulan ".$bulan_tahun;

///////////////////////// production achievement 
$akhir_tgl_bln_ini = date("t",mktime(0, 0, 0, $tmp["1"]-1, $tmp["2"], $tmp["0"] )); 


if (count($grafik_1) > 0) {
    FOREACH ($grafik_1 AS $r) {
        $jam    = $r["tgl"];
        $g_1[$jam] = $r["berat"];
    }
}
if (count($grafik_2) > 0) {
    FOREACH ($grafik_2 AS $r) {
        $jam    = $r["tgl"];
        $g_2[$jam] = $r["berat"]; 
    }
} 
$production_per_day = "";
$label_axis_production_achievement = "";
$jml_hari_bulan_lalu = $akhir_tgl_bln_ini;
$bulan_lalu = date("M",mktime(0, 0, 0,$tmp["1"]-1, '1',$tmp["0"] ));
FOR ($i=26;$i<=$jml_hari_bulan_lalu;$i++) {
    if (isset($label_axis_production_achievement) && $label_axis_production_achievement <>"") $label_axis_production_achievement .= ",";
    if (strlen($i) == 1) $tgl = "0".$i; else $tgl = $i;
    $label_axis_production_achievement .= "'".$tgl." ".$bulan_lalu."'";
 
    if (empty($g_1[$tgl])) $g_1[$tgl]  = "0";
    if (isset($production_per_day) && $production_per_day <> "") $production_per_day .= ","; 
    $production_per_day .=  $g_1[$tgl] / 1000  ; 
} 
$jml_hari_bulan_ini = 25;
$bulan_ini = date("M",mktime(0, 0, 0, $tmp["1"], '1', $tmp["0"] ));
FOR ($i=1;$i<=$jml_hari_bulan_ini;$i++) {
    if (isset($label_axis_production_achievement) && $label_axis_production_achievement <>"") $label_axis_production_achievement .= ",";
    if (strlen($i) == 1) $tgl = "0".$i; else $tgl = $i;
    $label_axis_production_achievement .= "'".$tgl." ".$bulan_ini."'";
 
    if (empty($g_2[$tgl]) ) $g_2[$tgl]  = "0";
    if (isset($production_per_day) && $production_per_day <> "") $production_per_day .= ","; 
    $production_per_day .=  $g_2[$tgl]  / 1000 ; 
} 
 
?>
<script type="text/javascript">
var BASE_URL = '<?php echo _URL; ?>';

jQuery(function($) { 

    $( "#btnFilterTotalProduksi" ).click(function() {   
        $("#frmData" ).submit();
    }); 

    //production_achievment
    $('#hormon_wb_kpp').highcharts({
        chart: { 
            //type: 'column',
            //width: 940,
            //height: 500, 
        },
        title: {
            text: '<?php echo $title;?>'
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
                    enabled: true, 
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
        series: [  
        { 
            type: 'column',
            name: 'Production/Day',
            data: [<?php echo $production_per_day; ?>]
        } 
        ]
    });
 
 

})
</script>