								<div class="row">
									<div class="col-xs-12"> 
										<div class="clearfix">
											<div class="pull-right tableTools-container"></div>
										</div>
										<div class="table-header">
											<?php if (isset($title) && $title<>"") echo $title;?>  
											 
										</div>

										<!-- div.table-responsive -->

										<!-- div.dataTables_borderWrap -->
										<div>
											 <!-- PAGE CONTENT BEGINS -->  
								<div class="row">
									<div class="col-sm-12">
										<div id="list_data">
										<table class="table table-striped table-bordered table-hover"> 
											<?php
											$no = 0;
											if (count($rs)>0) {
												FOREACH ($rs AS $r) {
													$no += 1;
													if  ($no == 1) echo "<tr>";
													echo '<td><a target="new" href="'.$r["ip_address"].'" rel="nofollow">
<img id="image0" class="thumbnailimgfullsize" title="'.$r["description"].'" alt="'.$r["description"].'" src="'.$r["ip_address"].'mjpg/video.mjpg"  width="400px">
</a> </td>';
													if  ($no == 2) {
														echo "</tr>";
														$no = 0;
													}
												}
												if  ($no == 1) {
														echo "<td></td></tr>"; 
												}
											}
											?> 
										</table> 
										</div>
									</div><!-- /.col -->
								</div><!-- /.row --> 
 