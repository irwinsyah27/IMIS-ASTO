<script type="text/javascript"> 

jQuery(function($) { 
    setInterval(function()
    { 
        $.ajax({
          type:"post",
          url:"<?php echo site_url('isi_bensin/get_data_antrian');?>",
          datatype:"html",
          success:function(data)
          {
              $("#list_body_data").html(data);
          }
        });
    }, 10000); //time in milliseconds  
})
</script>