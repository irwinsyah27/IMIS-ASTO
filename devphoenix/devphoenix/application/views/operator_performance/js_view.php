<?php
  $var_refresh            = "3600000";  
?>
<script type="text/javascript"> 

jQuery(function($) {  
    $('.date-picker').datepicker({
      autoclose: true,
      todayHighlight: true
    })

    $( "#btnDownload" ).click(function() {   
        window.open("<?php echo site_url('operator_performance/download/')?>/" + $("#txtFilter").val(), '_blank'); 
    }); 

    $( "#btnFilter" ).click(function() {   
        get_operator_performance ();  
    }); 

    setInterval(get_operator_performance, <?php echo $var_refresh;?>); //time in milliseconds  

    function get_operator_performance () {
        var   id = $("#txtFilter").val();  
        var   createdHTML = '';
        var   j = 0;
        var   titik_jam_pengawan1 = "";
        var   titik_jam_stop1 = "";
        var   time_in = "";
        var   time_out = "";
        var   time_in_mancal = "";
        var   time_out_mancal = "";

        $.getJSON("<?php echo site_url('operator_performance/get_data');?>", {id: id} ,function(responseData) {
          for(item in responseData) {
              j = eval(item) + 1;
              formData = responseData[item]; 
              bgcolor = "";
              if (formData.status_persetujuan == 1) {
                bgcolor = "bgcolor=\"#f85842\"";
              } else if (formData.status_persetujuan == 2 ) {
                bgcolor = "bgcolor=\"#f8e542\"";
              } else if (formData.status_persetujuan == 3 )  {
                bgcolor = "bgcolor=\"#6cf24d\"";
              }

              //if (formData.time_in != '0000-00-00 00:00') {
                  time_in = formData.time_in ;
              //}
              //if (formData.time_out != '0000-00-00 00:00') {
                  time_out = formData.time_out ;
              //}
              //if (formData.jam_pengawasan > 0  )  {
                titik_jam_pengawan1 = formData.titik_jam_pengawasan;
              //}
              //if (formData.jam_stop > 0  )  {
                titik_jam_stop1 = formData.titik_jam_stop;
              //}

              //if (formData.time_in_mancal != null) {
                  time_in_mancal = formData.time_in_mancal ;
              //}
              //if (formData.time_out_mancal != null) {
                  time_out_mancal = formData.time_out_mancal ;
              //}

              if (formData.unit == null ) formData.unit = '';
              if (time_in == null ) time_in = '';
              if (time_out == null ) time_out = '';
              if (time_in_mancal == null ) time_in_mancal = '';
              if (time_out_mancal == null ) time_out_mancal = '';
              if (formData.avg_ct == null ) formData.avg_ct = '';
              if (formData.total_over_speed == null ) formData.total_over_speed = '';
              if (formData.total_cycle_time == null ) formData.total_cycle_time = '';
              if (formData.spo_in == null ) formData.spo_in = '';
              if (formData.bpm_in == null ) formData.bpm_in = '';
              if (titik_jam_pengawan1 == null ) titik_jam_pengawan1 = '';
              if (titik_jam_stop1 == null ) titik_jam_stop1 = '';

              createdHTML += "<tr>";
              createdHTML += "<td "+bgcolor+">"+j+"</td>";
              createdHTML += "<td "+bgcolor+">"+formData.nama_operator+"</td>";
              createdHTML += "<td "+bgcolor+">"+formData.unit+"</td>";
              createdHTML += "<td "+bgcolor+">"+formData.label_status_persetujuan+"</td>";
              createdHTML += "<td "+bgcolor+">"+time_in+"</td>"; 
              createdHTML += "<td "+bgcolor+">"+time_out+"</td>";
              createdHTML += "<td "+bgcolor+">"+formData.durasi_in+"</td>";
              createdHTML += "<td "+bgcolor+">"+time_in_mancal+"</td>";
              createdHTML += "<td "+bgcolor+">"+time_out_mancal+"</td>"; 
              createdHTML += "<td "+bgcolor+">"+formData.avg_ct+"</td>"; 
              createdHTML += "<td "+bgcolor+">"+formData.total_over_speed+"</td>";  
              createdHTML += "<td "+bgcolor+">"+formData.total_cycle_time+"</td>";  
              createdHTML += "<td "+bgcolor+">"+formData.spo_in+"</td>";
              createdHTML += "<td "+bgcolor+">"+formData.bpm_in+"</td>";
              createdHTML += "<td "+bgcolor+">"+titik_jam_pengawan1+"</td>";
              createdHTML += "<td "+bgcolor+">"+titik_jam_stop1+"</td>";
              createdHTML += "</tr>";
          }

          $("#list_body_data").html(createdHTML); 
        });
    }
})
</script>
