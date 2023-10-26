<?php
  $var_refresh            = "5000";  
?>
<script type="text/javascript">
var BASE_URL = '<?php echo _URL_ADMIN; ?>';
var SESSION_USERNAME = '<?php echo $_SESSION["username"]; ?>'

jQuery(function($) { 
    $('div.demo marquee').marquee('pointer').mouseover(function () {
            $(this).trigger('stop');
        }).mouseout(function () {
            $(this).trigger('start');
        }).mousemove(function (event) {
            if ($(this).data('drag') == true) {
                this.scrollLeft = $(this).data('scrollX') + ($(this).data('x') - event.clientX);
            }
        }).mousedown(function (event) {
            $(this).data('drag', true).data('x', event.clientX).data('scrollX', this.scrollLeft);
        }).mouseup(function () {
            $(this).data('drag', false);
        });
        

    jsonGetListDataScheduleServiceByTgl();
    setInterval(jsonGetListDataScheduleServiceByTgl, <?php echo $var_refresh;?>); //time in milliseconds  

    $('.multiselect').multiselect({
         enableFiltering: true,
         enableHTML: true,
         buttonClass: 'btn btn-white btn-primary',
         templates: {
          button: '<button type="button" class="multiselect dropdown-toggle" data-toggle="dropdown"><span class="multiselect-selected-text"></span> &nbsp;<b class="fa fa-caret-down"></b></button>',
          ul: '<ul class="multiselect-container dropdown-menu"></ul>',
          filter: '<li class="multiselect-item filter"><div class="input-group"><span class="input-group-addon"><i class="fa fa-search"></i></span><input class="form-control multiselect-search" type="text"></div></li>',
          filterClearBtn: '<span class="input-group-btn"><button class="btn btn-default btn-white btn-grey multiselect-clear-filter" type="button"><i class="fa fa-times-circle red2"></i></button></span>',
          li: '<li><a tabindex="0"><label></label></a></li>',
              divider: '<li class="multiselect-item divider"></li>',
              liGroup: '<li class="multiselect-item multiselect-group"><label></label></li>'
         }
    });


    function jsonGetListDataScheduleServiceByTgl () {
        var   id = '<?php echo date("Y-m-d");?>'; 
        var   createdHTML = '';
        var   j = 0;
        var   masuk = '';
        $.getJSON("<?php echo site_url('breakdown/schedule_breakdown');?>", {id: id} ,function(responseData) {
          for(item in responseData) {
              j = eval(item) + 1;
              formData = responseData[item]; 
              if (formData.keterangan_ps == null ) formData.keterangan_ps = '';
              if (formData.masuk == null ) formData.masuk = ''; 
              createdHTML += "<tr>"; 
              createdHTML += "<td>"+formData.unit+"</td>";
              createdHTML += "<td>"+formData.keterangan_ps+"</td>";
              createdHTML += "<td>"+formData.masuk+"</td>"; 
              createdHTML += "</tr>";
          }

          $("#list_schedule_breakdown").html(createdHTML); 
        });
    }

    setInterval(function()
    {  
        data = "breakdown=" + $("#breakdown").val() +"&type=" + $("#type").val();
        $.ajax({
          type:"post",
              data: data,
          url:"<?php echo site_url('leadtime_breakdown/get_data');?>",
          datatype:"html",
          success:function(data)
          {
              $("#list_data").html(data);
          }
        });
    }, 3000); //time in milliseconds 


    function hideReport() {
        setTimeout(function(){
            $('#modal').modal('hide');
        }, 300000); // tampil selama 5 menit
    }


    if (SESSION_USERNAME == "cs") {
          // show flash report
        setInterval(function(){
            d = new Date();
          $("#img-report").attr("src",'<?php echo base_url();?>' + "/assets/img-report/report.jpg?"+d.getTime());
            $('#modal').modal('show');
            hideReport();
        }, 600000) //  10 menit
    }

    getSummaryByTypeReady();
    setInterval(getSummaryByTypeReady, 3000); //time in milliseconds  
    function getSummaryByTypeReady () {
      $.ajax({
          type:"post",
          url:"<?php echo site_url('leadtime_breakdown/getSummaryByTypeReady');?>",
          datatype:"html",
          success:function(data)
          {
              $("#list_summary_type_ready").html(data);
          }
      });
    }
 


    getUnitReadyToday();
    setInterval(getUnitReadyToday, 3000); //time in milliseconds  
    function getUnitReadyToday () {
        var   id = '<?php echo date("Y-m-d");?>'; 
        var   createdHTML = '';
        var   j = 0; 
        $.getJSON("<?php echo site_url('leadtime_breakdown/getUnitReadyToday');?>", {id: id} ,function(responseData) {
          for(item in responseData) {
              j = eval(item) + 1;
              formData = responseData[item];  
              createdHTML += "<tr>"; 
              createdHTML += "<td>"+formData.new_eq_num+"</td>";
              createdHTML += "<td>"+formData.time_out+"</td>"; 
              createdHTML += "</tr>";
          }
          $("#list_summary_ready_today").html(createdHTML); 
        });
    }
 
})
</script>