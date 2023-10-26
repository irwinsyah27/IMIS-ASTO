<script type="text/javascript"> 

jQuery(function($) { 
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
 
    $( "#btnFilter" ).click(function() {   
        getantrian ();  
    }); 

    getantrian();
    setInterval(getantrian, 20000); //time in milliseconds  . 20 sec
    function getantrian () {  
      var data = "terminals_id=" + $("#terminals_id").val();

        $.ajax({
          type:"post",
          data: data,
          url:"<?php echo site_url('approval_fatique/get_data_antrian');?>",
          datatype:"html",
          success:function(data)
          {
              $("#list_body_data").html(data);
          }
        });
    }
})
</script>