<?php
$var_refresh            = "3000"; 
?>
<script type="text/javascript"> 
var BASE_URL = '<?php echo _URL; ?>';

jQuery(function($) { 
    $("#box_advance_filter").hide();  

    $( "#hideFilter" ).click(function() {   
        $("#box_advance_filter").hide();  
    }); 
    $( "#advanceFilter" ).click(function() {   
        $("#box_advance_filter").show();  
    }); 

    $( "#Filter" ).click(function() {   
        show_antrian ();  
    }); 


    setInterval(show_antrian, <?php echo $var_refresh;?>); //time in milliseconds  

    function show_antrian () {
        var   createdHTML = '';
        var   j = 0; 
        var   type = [];  
        var   breakdown = [];   
        
        $('.type_checkbox:checked').each(function () {
             type.push($(this).val());
        });  
        
        $('.breakdown_checkbox:checked').each(function () {
             breakdown.push($(this).val());
        });  


        var data = "type=" + type +"&breakdown=" + breakdown;

        $.getJSON("<?php echo site_url('breakdown_pcr/get_data_antrian');?>", {type: type.join(", ") , breakdown : breakdown.join(", ")} ,function(responseData) {
          for(item in responseData) {
                     j = eval(item) + 1;
                      formData = responseData[item]; 
                      createdHTML += "<tr>";
                      createdHTML += "<td>"+j+"</td>";
                      createdHTML += "<td>"+formData.no_wo+"</td>";
                      createdHTML += "<td>"+formData.new_eq_num+"</td>";
                      createdHTML += "<td>"+formData.alokasi+"</td>";
                      createdHTML += "<td>"+formData.kode+"</td>"; 
                      createdHTML += "<td>"+formData.lokasi+"</td>";
                      createdHTML += "<td>"+formData.hm+"</td>"; 
                      createdHTML += "<td>"+formData.km+"</td>"; 
                      createdHTML += "<td>"+formData.date_time_in+"</td>"; 
                      createdHTML += "<td>"+formData.durasi+"</td>"; 
                      createdHTML += "<td>"+formData.diagnosa+"</td>"; 
                      createdHTML += "<td><a href=\"<?php echo  _URL;?>breakdown_pcr/update/"+formData.breakdown_id + "\">update</a><br><br><a href=\"<?php echo  _URL;?>breakdown_pcr/edit/"+formData.breakdown_id + "\">close</a></td>"; 
                      createdHTML += "</tr>";
          }
          $("#list_body_data").html(createdHTML); 
        }); 

    } 
})
</script>