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
        get_overspeed ();
    });

    get_overspeed();
    setInterval(get_overspeed, <?php echo $var_refresh;?>); //time in milliseconds

    function get_overspeed () {
        var   id = $("#txtFilterTotalProduksi").val();
        var   createdHTML = '';
        var   j = 0;
        $.getJSON("<?php echo site_url('monitoring_speed_unit/get_data');?>", {id: id} ,function(responseData) {
          for(item in responseData) {
              j = eval(item) + 1;
              formData = responseData[item];

              var bgcolor = "bgcolor=\"548235\"";
              if (formData.total_over_speed > 0) bgcolor="bgcolor=\"ff0000\"";

              //var _1 = ""; var _2 = ""; var _3 = "";
              //if (formData.speed <= 30 )
              //_1 = formData.average + " km/jam";
              //else if (formData.speed >= 57)
              //if (formData.max_over_speed > 0) {
              //   _3 = formData.max_over_speed + " km/jam";
              // } else {
              //   _3 = "-";
              // }

              //else  _2 = formData.speed + " km/jam";

              createdHTML += "<tr>";
              createdHTML += "<td>"+j+"</td>";
              createdHTML += "<td>"+formData.nama_operator+"</td>";
              createdHTML += "<td>"+formData.unit+"</td>";
              createdHTML += "<td>"+formData.average +"</td>";
              createdHTML += "<td>"+formData.max_speed +"</td>";
              createdHTML += "<td>"+formData.total_over_speed +"</td>";
              createdHTML += "</tr>";
          }

          $("#list_body_data").html(createdHTML);
        });
    }
    /*
    setInterval(function()
    {
        $.ajax({
          type:"post",
          url:"<?php echo site_url('monitoring_speed_unit/get_data');?>",
          datatype:"html",
          success:function(data)
          {
              $("#list_body_data").html(data);
          }
        });
    }, 3000); //time in milliseconds
    */

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
