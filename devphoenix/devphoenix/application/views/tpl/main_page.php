<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title><?php echo _TITLE;?></title>

		<meta name="description" content="overview &amp; stats" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="<?php echo _ASSET_TEMPLATE;?>assets/css/bootstrap.css" />
		<link rel="stylesheet" href="<?php echo _ASSET_TEMPLATE;?>components/font-awesome/css/font-awesome.css" />

		<!-- page specific plugin styles -->

		<link rel="stylesheet" href="<?php echo _ASSET_TEMPLATE;?>assets/css/chosen.css" />
		<link rel="stylesheet" href="<?php echo _ASSET_TEMPLATE;?>assets/css/datepicker.css" />
		<link rel="stylesheet" href="<?php echo _ASSET_TEMPLATE;?>assets/css/bootstrap-timepicker.css" />
		<link rel="stylesheet" href="<?php echo _ASSET_TEMPLATE;?>assets/css/daterangepicker.css" />
		<link rel="stylesheet" href="<?php echo _ASSET_TEMPLATE;?>assets/css/bootstrap-datetimepicker.css" />
		<link rel="stylesheet" href="<?php echo _ASSET_TEMPLATE;?>assets/css/colorpicker.css" />

		<link rel="stylesheet" href="<?php echo _ASSET_TEMPLATE;?>components/bootstrap-multiselect/dist/css/bootstrap-multiselect.css" />
		
		<!-- text fonts -->
		<link rel="stylesheet" href="<?php echo _ASSET_TEMPLATE;?>assets/css/ace-fonts.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="<?php echo _ASSET_TEMPLATE;?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="<?php echo _ASSET_TEMPLATE;?>assets/css/ace-part2.css" class="ace-main-stylesheet" />
		<![endif]-->
		<link rel="stylesheet" href="<?php echo _ASSET_TEMPLATE;?>assets/css/ace-skins.css" />
		<link rel="stylesheet" href="<?php echo _ASSET_TEMPLATE;?>assets/css/ace-rtl.css" />
		<link rel="stylesheet" href="<?php echo _ASSET_CSS;?>styles.css" />

		<style>
			
			.modal-dialog {
				width: 60%;
				height: 60%;
				margin: 30px auto;
				padding: 0;
			}
  
			.modal-content {
				height: auto;
				min-height: 60%;
				border-radius: 0;
			}
		
		</style>

		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="<?php echo _ASSET_TEMPLATE;?>assets/css/ace-ie.css" />
		<![endif]-->

		<!-- inline styles related to this page -->

		<!-- ace settings handler -->
		<script src="<?php echo _ASSET_TEMPLATE;?>assets/js/ace-extra.js"></script>
		<script src="<?php echo _ASSET_TEMPLATE;?>assets/js/ace-extra.js"></script>
		<script src="<?php echo _ASSET_LIBS;?>angular/angular.min.js"></script>
		<script src="<?php echo _ASSET_LIBS;?>angular/ui-bootstrap-tpls.min.js"></script>
		

		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

		<!--[if lte IE 8]>
		<script src="<?php echo _ASSET_TEMPLATE;?>components/html5shiv/dist/html5shiv.min.js"></script>
		<script src="<?php echo _ASSET_TEMPLATE;?>components/respond/dest/respond.min.js"></script>
		<![endif]--> 

        <link rel="stylesheet" href="<?php echo _ASSET_LIBS;?>leaflet-0.7.3/leaflet.css" />

	</head>

	<body class="no-skin">
		
		<!-- #section:basics/navbar.layout -->
		<?php $this->load->view(_TEMPLATE_PATH."navbar");?>
		<!-- /section:basics/navbar.layout -->

		<div class="main-container ace-save-state" id="main-container">
			<script type="text/javascript">
				try{ace.settings.loadState('main-container')}catch(e){}
			</script>

			<!-- #section:basics/sidebar -->
			<?php $this->load->view(_TEMPLATE_PATH."sidebar");?>
			<!-- /section:basics/sidebar -->

			<div class="main-content">
				<div class="main-content-inner">
					<!-- #section:basics/content.breadcrumbs -->
					<div class="breadcrumbs ace-save-state" id="breadcrumbs">
						<ul class="breadcrumb">
							<?php if (isset($breadcrumb) && $breadcrumb <> "") echo $breadcrumb;?>
						</ul><!-- /.breadcrumb -->

						<!-- #section:basics/content.searchbox -->
						<?php /*
						<div class="nav-search" id="nav-search">
							<form class="form-search">
								<span class="input-icon">
									<input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
									<i class="ace-icon fa fa-search nav-search-icon"></i>
								</span>
							</form>
						</div><!-- /.nav-search -->
						*/ ?>

						<!-- /section:basics/content.searchbox -->
					</div>

					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content">
						<!-- #section:settings.box -->
						 <?php # $this->load->view(_TEMPLATE_PATH."setting_box");?>
						<!-- /section:settings.box -->
						<?php /*
						<div class="page-header">
							<h1>
								Dashboard
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									overview &amp; stats
								</small>
							</h1>
						</div><!-- /.page-header -->
						*/ ?>

						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<?php 
								if (isset($sview) && $sview<>"") $this->load->view($sview);
								?>
								<!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
						</div><!-- /.row -->
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

					

			<?php if($this->session->flashdata('msg')){?>
				<div  id="_info_" class="modal fade" tabindex="-1">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header no-padding">
									<div class="table-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
											<span class="white">&times;</span>
										</button>
										Notifikasi
									</div>
								</div>

								<div class="modal-body" style="height:100px;">
									 <?php if($this->session->flashdata('stats')=='0')$a = 'error'; else $a = 'info';?>
									<div class="alert alert-<?php echo $a;?>">
										<p class="err-form" style="letter-spacing: 1px;"><?php echo strtoupper($this->session->flashdata('msg'));?></p>
									</div>
								</div>

								<div class="modal-footer no-margin-top">
									<button class="btn btn-sm btn-danger pull-right" data-dismiss="modal">
										<i class="ace-icon fa fa-times"></i>
										Close
									</button> 
								</div>
							</div>
						</div>
					</div>
			<?php } ?>


			<?php $this->load->view(_TEMPLATE_PATH."footer");?>

			<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
				<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
			</a>
		</div><!-- /.main-container -->

		<!-- basic scripts -->

		<!--[if !IE]> -->
		<script src="<?php echo _ASSET_TEMPLATE;?>components/jquery/dist/jquery.js"></script>
		<script src="<?php echo _ASSET_LIBS;?>jquery.marquee.min.js"></script> 

		<!-- <![endif]-->

		<!--[if IE]>
<script src="<?php echo _ASSET_TEMPLATE;?>components/jquery.1x/dist/jquery.js"></script>
<![endif]-->
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='<?php echo _ASSET_TEMPLATE;?>components/_mod/jquery.mobile.custom/jquery.mobile.custom.js'>"+"<"+"/script>");
		</script>
		<script src="<?php echo _ASSET_TEMPLATE;?>components/bootstrap/dist/js/bootstrap.js"></script>

		<!-- page specific plugin scripts -->
		<script src="<?php echo _ASSET_TEMPLATE;?>components/datatables/media/js/jquery.dataTables.js"></script>
		<script src="<?php echo _ASSET_TEMPLATE;?>components/_mod/datatables/jquery.dataTables.bootstrap.js"></script>
		<script src="<?php echo _ASSET_TEMPLATE;?>components/datatables.net-buttons/js/dataTables.buttons.js"></script>
		<script src="<?php echo _ASSET_TEMPLATE;?>components/datatables.net-buttons/js/buttons.flash.js"></script>
		<script src="<?php echo _ASSET_TEMPLATE;?>components/datatables.net-buttons/js/buttons.html5.js"></script>
		<script src="<?php echo _ASSET_TEMPLATE;?>components/datatables.net-buttons/js/buttons.print.js"></script>
		<script src="<?php echo _ASSET_TEMPLATE;?>components/datatables.net-buttons/js/buttons.colVis.js"></script>
		<script src="<?php echo _ASSET_TEMPLATE;?>components/datatables.net-select/js/dataTables.select.js"></script>

		<!--[if lte IE 8]>
		  <script src="<?php echo _ASSET_TEMPLATE;?>components/ExplorerCanvas/excanvas.js"></script>
		<![endif]-->
		<script src="<?php echo _ASSET_TEMPLATE;?>components/_mod/jquery-ui.custom/jquery-ui.custom.js"></script>
		<script src="<?php echo _ASSET_TEMPLATE;?>components/jqueryui-touch-punch/jquery.ui.touch-punch.js"></script>
		<script src="<?php echo _ASSET_TEMPLATE;?>components/_mod/easypiechart/jquery.easypiechart.js"></script>
		<script src="<?php echo _ASSET_TEMPLATE;?>components/jquery.sparkline/index.js"></script>
		<script src="<?php echo _ASSET_TEMPLATE;?>components/_mod/bootstrap-multiselect/bootstrap-multiselect.js"></script>
		<!--
		<script src="<?php echo _ASSET_TEMPLATE;?>components/Flot/jquery.flot.js"></script>
		<script src="<?php echo _ASSET_TEMPLATE;?>components/Flot/jquery.flot.pie.js"></script>
		<script src="<?php echo _ASSET_TEMPLATE;?>components/Flot/jquery.flot.resize.js"></script>
	-->

		<script src="<?php echo _ASSET_TEMPLATE;?>assets/js/date-time/bootstrap-datepicker.js"></script>
		<script src="<?php echo _ASSET_TEMPLATE;?>assets/js/date-time/bootstrap-timepicker.js"></script>
		<script src="<?php echo _ASSET_TEMPLATE;?>assets/js/date-time/moment.js"></script>
		<script src="<?php echo _ASSET_TEMPLATE;?>assets/js/date-time/daterangepicker.js"></script>
		<script src="<?php echo _ASSET_TEMPLATE;?>assets/js/date-time/bootstrap-datetimepicker.js"></script>

		<script src="<?php echo _ASSET_TEMPLATE;?>assets/js/chosen.jquery.js"></script>

		<!-- ace scripts -->
		<script src="<?php echo _ASSET_TEMPLATE;?>assets/js/src/elements.scroller.js"></script>
		<script src="<?php echo _ASSET_TEMPLATE;?>assets/js/src/elements.colorpicker.js"></script>
		<script src="<?php echo _ASSET_TEMPLATE;?>assets/js/src/elements.fileinput.js"></script>
		<script src="<?php echo _ASSET_TEMPLATE;?>assets/js/src/elements.typeahead.js"></script>
		<script src="<?php echo _ASSET_TEMPLATE;?>assets/js/src/elements.wysiwyg.js"></script>
		<script src="<?php echo _ASSET_TEMPLATE;?>assets/js/src/elements.spinner.js"></script>
		<script src="<?php echo _ASSET_TEMPLATE;?>assets/js/src/elements.treeview.js"></script>
		<script src="<?php echo _ASSET_TEMPLATE;?>assets/js/src/elements.wizard.js"></script>
		<script src="<?php echo _ASSET_TEMPLATE;?>assets/js/src/elements.aside.js"></script>
		<script src="<?php echo _ASSET_TEMPLATE;?>assets/js/src/ace.js"></script>
		<script src="<?php echo _ASSET_TEMPLATE;?>assets/js/src/ace.basics.js"></script>
		<script src="<?php echo _ASSET_TEMPLATE;?>assets/js/src/ace.scrolltop.js"></script>
		<script src="<?php echo _ASSET_TEMPLATE;?>assets/js/src/ace.ajax-content.js"></script>
		<script src="<?php echo _ASSET_TEMPLATE;?>assets/js/src/ace.touch-drag.js"></script>
		<script src="<?php echo _ASSET_TEMPLATE;?>assets/js/src/ace.sidebar.js"></script>
		<script src="<?php echo _ASSET_TEMPLATE;?>assets/js/src/ace.sidebar-scroll-1.js"></script>
		<script src="<?php echo _ASSET_TEMPLATE;?>assets/js/src/ace.submenu-hover.js"></script>
		<script src="<?php echo _ASSET_TEMPLATE;?>assets/js/src/ace.widget-box.js"></script>
		<script src="<?php echo _ASSET_TEMPLATE;?>assets/js/src/ace.settings.js"></script>
		<script src="<?php echo _ASSET_TEMPLATE;?>assets/js/src/ace.settings-rtl.js"></script>
		<script src="<?php echo _ASSET_TEMPLATE;?>assets/js/src/ace.settings-skin.js"></script>
		<script src="<?php echo _ASSET_TEMPLATE;?>assets/js/src/ace.widget-on-reload.js"></script>
		<script src="<?php echo _ASSET_TEMPLATE;?>assets/js/src/ace.searchbox-autocomplete.js"></script>



		<script src="<?php echo _ASSET_LIBS; ?>highcharts/highcharts.js"></script>
		<script src="<?php echo _ASSET_LIBS; ?>highcharts/modules/exporting.js"></script>
		 
		 <script src="<?php echo _ASSET_LIBS; ?>highcharts/highcharts-more.js"></script>
		 <script src="<?php echo _ASSET_LIBS; ?>highcharts/modules/solid-gauge.js"></script>
		 

		<link rel="stylesheet" href="<?php echo _ASSET_LIBS;?>leaflet-0.7.3/leaflet.js" />
		<link rel="stylesheet" href="<?php echo _ASSET_LIBS;?>bootbox.min.js" /> 

		<!-- inline scripts related to this page -->
		<?php
		if (isset($js) && $js<>"") $this->load->view($js);
		?>


		<?php if($this->session->flashdata('error')== true){ ?>
			<script type="text/javascript">setTimeout(function(){$('.alert').fadeOut('slow');}, 2000);</script>
		<?php } ?>
		<?php if($this->session->flashdata('msg')== true){ ?>
			<script type="text/javascript">
		    $(window).load(function(){
		        $('#_info_').modal('show');
		    });
			setTimeout(function(){$('#_info_').modal('hide');}, 2000);
		</script>
		<?php } ?>
	</body>
</html>
