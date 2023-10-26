<script type="text/javascript"> 

jQuery(function($) { 
    
    get_summary_daily_check();
    setInterval(get_summary_daily_check, 3000); //time in milliseconds 
    
    function get_summary_daily_check() {
        var   id = 0;
        var   createdHTML = '';
        var   j = 0;
        var   bgcolor = "";
        $.getJSON("<?php echo site_url('leadtime_daily_check/get_summary_daily_check');?>", {id: id} ,function(responseData) {
          for(item in responseData) {
              j = eval(item) + 1;
              formData = responseData[item];  
              createdHTML += "<tr>"; 
              createdHTML += "<td>"+formData.plan+"</td>";
              createdHTML += "<td>"+formData.actual+"</td>"; 
              createdHTML += "</tr>";
          }

          $("#list_summary_daily_check").html(createdHTML); 
        });
    }


    today_plan_daily_check();
    setInterval(today_plan_daily_check, 3000); //time in milliseconds 
    
    function today_plan_daily_check() {
        $.ajax({
            type:"post",
            url:"<?php echo site_url('leadtime_daily_check/today_plan_daily_check');?>",
            datatype:"html",
            success:function(data)
            {
                $("#list_plan_daily_check").html(data);
            }
        });
    }

    get_antrian();
    setInterval(get_antrian, 1000); //time in milliseconds  
    function get_antrian () {
        //var   id = $("#txtFilterTotalProduksi").val();  
        var   id = 0;
        var   createdHTML = '';
        var   j = 0;
        var   bgcolor = "";
        $.getJSON("<?php echo site_url('leadtime_daily_check/get_data');?>", {id: id} ,function(responseData) {
          for(item in responseData) {
              j = eval(item) + 1;
              formData = responseData[item]; 
              if (formData.durasi_menit >= 15) {
                bgcolor = "bgcolor=\"#f85842\"";
              } else if (formData.durasi_menit >= 10 ) {
                bgcolor = "bgcolor=\"#f8e542\"";
              } else {
                bgcolor = "bgcolor=\"#6cf24d\"";
              }
              createdHTML += "<tr>";
              createdHTML += "<td "+bgcolor+">"+j+"</td>";
              createdHTML += "<td "+bgcolor+">"+formData.station_name+"</td>";
              createdHTML += "<td "+bgcolor+">"+formData.new_eq_num+"</td>";
              createdHTML += "<td "+bgcolor+">"+formData.shift+"</td>";
              createdHTML += "<td "+bgcolor+">"+formData.date_in+"</td>"; 
              createdHTML += "<td "+bgcolor+">"+formData.time_in+"</td>";
              createdHTML += "<td "+bgcolor+">"+formData.durasi+"</td>";
              createdHTML += "<td "+bgcolor+">"+formData.description+"</td>"; 
              createdHTML += "</tr>";
          }

          $("#list_body_data").html(createdHTML); 
        });
    } 

    $('#dynamic-table').on('click','a[data-confirm]',function(ev){ 
          var src = $(this).attr('src'); 
          var title = $(this).attr('title');
          if (!$('#dataConfirmModal').length){
            $('body').append('<div id="dataConfirmModal" class="modal fade" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><div class="modal-header no-padding"><div class="table-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="white">&times;</span></button>ONLINE CCTV</div></div><div class="modal-body" style="height:400px;"><img id="image0" class="thumbnailimgfullsize" title="http://210.23.68.3:86/" alt="http://210.23.68.3:86/" src="http://210.23.68.3:86/mjpg/video.mjpg"  height="350px"></div><div class="modal-footer no-margin-top"><button class="btn btn-sm btn-danger pull-right" data-dismiss="modal"><i class="ace-icon fa fa-times"></i>Close</button></div></div></div></div>');
          }
         // $('#dataConfirmModal').find('.modal-body').text($(this).attr('src')); 
          $('#image0').attr('src', src);
          $('#image0').attr('title', title);
          $('#image0').attr('alt', title);
          $('#dataConfirmModal').modal({show:true});
          return false;
    });
})
</script>