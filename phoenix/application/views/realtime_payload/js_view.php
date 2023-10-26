<?php
  $var_refresh            = "10000";  
?>
<script type="text/javascript"> 

jQuery(function($) { 
    $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true
    }) 
    $( "#btnFilterTotalProduksi" ).click(function() {   
        get_operator_ranking ();  
    }); 

    setInterval(get_operator_ranking, <?php echo $var_refresh;?>); //time in milliseconds  

    function get_operator_ranking () {
        var   id = $("#txtFilterTotalProduksi").val();  
        var   createdHTML = '';
        var   j = 0;
        $.getJSON("<?php echo site_url('realtime_payload/get_data');?>", {id: id} ,function(responseData) {
          for(item in responseData) {
              j = eval(item) + 1;
              formData = responseData[item]; 
              createdHTML += "<tr>";
              createdHTML += "<td>"+j+"</td>";
              createdHTML += "<td>"+formData.nama_operator+"</td>";
              createdHTML += "<td>"+formData.unit+"</td>";
              createdHTML += "<td>"+formData.egi+"</td>";
              createdHTML += "<td>"+formData.netto_1+"</td>"; 
              createdHTML += "<td>"+formData.netto_2+"</td>";
              createdHTML += "<td>"+formData.netto_3+"</td>"; 
              createdHTML += "</tr>";
          }

          $("#list_body_data").html(createdHTML); 
        });
    }
 
})
</script>