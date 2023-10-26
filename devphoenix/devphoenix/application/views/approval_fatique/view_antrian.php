								<div class="table-header">
									<div class="row"> 
										<div class="col-sm-12"> 
											<div>
												<?php echo $title;?> 
											</div> 
										</div>
									</div>
								</div>
								<div class="">
									<div class="row">
										<div class="col-sm-12">
											<table>
												<tr> 
													<td>Posisi Absensi di Mesin &nbsp; &nbsp;</td>
													<td>
														<select name="terminals_id" id="terminals_id" multiple class="multiselect"> 
															<?php
															if (count($list_terminal) > 0 ) {
																FOREACH ($list_terminal AS $l) {
																	?>
																	<option value="<?php echo $l["terminals_id"];?>"><?php echo $l["name"];?></option>
																	<?php
																}
															}
															?>
														</select> 
													</td>   
													<td>
														<span class="input-group-btn">
															<button id="btnFilter" name="btnFilter"  type="button" class="btn btn-purple btn-sm">
																<span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
																Filter
															</button>
														</span>
													</td> 
												</tr>
											</table> 
										</div> 
									</div>
  								</div>
											 <!-- PAGE CONTENT BEGINS -->  
								<div class="row">
									<div class="col-sm-12">  
										<table id="dynamic-table" class="table table-striped table-bordered table-hover">
											<thead class="thin-border-bottom">
												<tr>
													<th align="center">NRP</th>  
													<th align="center">NAMA</th> 
													<th align="center">TGL</th>  
													<th align="center">SHIFT</th>  
													<th align="center">LAMA TIDUR KEMARIN</th> 
													<th align="center">LAMA TIDUR HARI INI</th>  
													<th align="center">Rekomendasi</th>  
													<th align="center">Quesioner</th>    
													<th align="center"></th>  
												</tr> 
											</thead>

											<tbody id="list_body_data">
												<?php 
												$no = 0;
												if (count($rs)>0) {
													FOREACH ($rs AS $l) {   
														$status_fatique 	= "";
														$bg_fatique 		= ""; 
														$bg_warna 			= "";
														$status_merah 		= ""; 

														$persen_1 = $this->mylib->get_prosentase_lama_tidur($l["lama_tdr_kemarin"]);
														$persen_2 = $this->mylib->get_prosentase_lama_tidur($l["lama_tdr_sekarang"]);

	 													if ($l["apakah_sedang_minum_obat"]	=="Y") { 
	 														$status_merah .= "Minum obat, ";  
	 													} 

	 													if ($l["apakah_sedang_ada_masalah"]	=="Y") { 
	 														if ($status_merah <> "") $status_merah .= ",<br>";
	 														$status_merah .= "Ada masalah ";  
	 													} 

	 													if ($l["apakah_siap_bekerja"]		=="T") { 
	 														if ($status_merah <> "") $status_merah .= ",<br>";
	 														$status_merah .= "Tidak siap bekerja";  
	 													}  
 
														if ($l["rekomendasi"] == 3)$bg_fatique = "bgcolor=green";
														if ($l["rekomendasi"] == 2)$bg_fatique = "bgcolor=yellow";
														if ($l["rekomendasi"] == 1)$bg_fatique = "bgcolor=red";

	 													if ($status_merah <> "") $bg_warna = "bgcolor=red";
												?>
												<tr> 
													<td><?php echo $l["nrp"];?></td>  
													<td><?php echo $l["nama"];?></td>  
													<td><?php echo $l["tanggal_pra_job"];?></td>  
													<td><?php echo $l["shift"];?></td>  
													<td><?php echo $l["lama_tdr_kemarin"];?></td>
													<td><?php echo $l["lama_tdr_sekarang"];?></td> 
													<td <?php echo $bg_fatique;?>><?php echo $l["label_rekomendasi"];?></td> 
													<td <?php echo $bg_warna;?>><?php echo $status_merah;?></td> 
													<td><a href="<?php echo _URL."approval_fatique/edit/".$l["pra_job_id"]."/3";?>">Disetujui</a> 
														<br><br>
														<a href="<?php echo _URL."approval_fatique/edit/".$l["pra_job_id"]."/2";?>">Butuh pengawasan</a> 
														<br><br> 
														<a href="<?php echo _URL."approval_fatique/edit/".$l["pra_job_id"]."/1";?>">Tidak Boleh Bekerja </a>
													</td>
														  
												</tr> 
												<?php	
													}
												}
												?> 
											</tbody>
										</table>  
									</div><!-- /.col -->
								</div><!-- /.row -->   
 