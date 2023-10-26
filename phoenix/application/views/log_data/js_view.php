<?php    
$var_refresh            = "600000";  
?>
<script type="text/javascript">
var BASE_URL = '<?php echo _URL; ?>';

jQuery(function($) { 
    $(".chosen-select").chosen();  
 
    $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true
    }) 
    
    $( "#btnFilterOverSpeed" ).click(function() {   
        if ($("#nrp_over_speed").val() =="") { alert('NRP harus diisi'); return false; } 
        if ($("#txtFilterOverSpeed1").val() =="") { alert('Tgl Awal harus diisi'); return false; } 
        if ($("#txtFilterOverSpeed2").val() =="") { alert('Tgl Akhir harus diisi'); return false; } 

        if( $("#txtFilterOverSpeed1").val() > $("#txtFilterOverSpeed2").val() ) {
            alert("Tgl awal harus lebih besar dari tgl akhir");
            return false;
        }
        window.open("<?php echo site_url('log_data/get_log_overspeed/')?>/" + $("#nrp_over_speed").val()+"/" + $("#txtFilterOverSpeed1").val() +"/" + $("#txtFilterOverSpeed2").val() , '_blank'); 
    }); 

    
    $( "#btnFilterCycleTime" ).click(function() {   
        if ($("#nrp_cycle_time").val() =="") { alert('NRP harus diisi'); return false; } 
        if ($("#txtFilterCycleTime1").val() =="") { alert('Tgl Awal harus diisi'); return false; } 
        if ($("#txtFilterCycleTime2").val() =="") { alert('Tgl Akhir harus diisi'); return false; } 

        if( $("#txtFilterCycleTime1").val() > $("#txtFilterCycleTime2").val() ) {
            alert("Tgl awal harus lebih besar dari tgl akhir");
            return false;
        }
        window.open("<?php echo site_url('log_data/get_log_cycle_time/')?>/" + $("#nrp_cycle_time").val()+"/" + $("#txtFilterCycleTime1").val() +"/" + $("#txtFilterCycleTime2").val() , '_blank'); 
    }); 
  
})
</script>