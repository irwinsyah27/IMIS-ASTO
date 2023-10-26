<div class="row">
									<div class="col-xs-12"> 
										<div class="clearfix">
											<div class="pull-right tableTools-container"></div>
										</div>
										<div class="table-header">
											<?php if (isset($title) && $title<>"") echo $title;?>  
											<?php if  (_USER_ACCESS_LEVEL_VIEW == "1") { ?>
											<a class="btn btn-white btn-info btn-bold pull-right" href="<?php echo base_url('rci/view');?>">
												<i class="ace-icon fa fa-list-alt bigger-120 blue"></i>
												List Data
											</a>
											<?php } ?>
										</div>

										<div class="clearfix">
											<div class="pull-right tableTools-container"></div>
										</div>
 
										<div class="row">
                                            <div> 
                                            <form action="<?php echo base_url('rci/add_data');?>" method="post" name="frmData" id="frmData"  class="form-horizontal" role="form">
                                                 
                                                
                                                 <div class="form-group">
                                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Nama </label> 
                                                     <div class="col-sm-9">
                                                         <?php echo form_error('nrp', '<div class="col-sm-12 error">', '</div>'); ?>
                                                         <select name="nrp" id="nrp" class="chosen-select" style="width: 300px;">
                                                            <?php
                                                            FOREACH ($list_karyawan AS $l) {
                                                            ?>
                                                            <option value="<?php echo $l["nrp"];?>"><?php echo $l["nrp"]." - ".$l["nama"];?></option>
                                                            <?php
                                                            }
                                                            ?> 
                                                         </select> 
                                                     </div>
                                                </div>

                                                <div class="form-group">
                                                     <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Tanggal</label> 
                                                    <div class=" col-sm-3">
                                                        <div class=" input-group ">
                                                         <input class="form-control date-picker" name="date_rc" id="date_rc" type="text" data-date-format="YYYY-MM-DD HH:mm"  value="<?php echo date("Y-m-d H:i");?>" />
                                                        <span class="input-group-addon">
                                                            <i class="fa fa-calendar bigger-110"></i>
                                                        </span>
                                                        </div>
                                                    </div> 
                                                </div>  

                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Shift </label>
                                                    <div class="col-sm-1">
                                                         <?php $this->mylib->selectbox("shift","", $list_shift," style=\"width: 200px;\"");?>
                                                    </div>
                                                </div> 

                                                <div class="form-group">
                                                    <label class="control-label col-md-3">Lokasi</label>
                                                    <div class="col-md-9">
                                                        <?php $this->mylib->selectbox("master_lokasi_id","",$list_lokasi_road," style=\"width: 200px;\"");?> 
                                                    </div>
                                                </div>  

                                                 <div class="form-group">
                                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Jenis Kerusakan </label> 
                                                     <div class="col-sm-9">
                                                         <?php echo form_error('master_problem_road_id', '<div class="col-sm-12 error">', '</div>'); ?>
                                                         <select name="master_problem_road_id" id="master_problem_road_id" class="chosen-select" style="width: 600px;">
                                                            <?php
                                                            FOREACH ($list_problem_road AS $l) {
                                                            ?>
                                                            <option value="<?php echo $l["master_road_problem_id"];?>"><?php echo $l["kode"]." - ".$l["jenis_kerusakan"];?></option>
                                                            <?php
                                                            }
                                                            ?> 
                                                         </select> 
                                                     </div>
                                                </div>  

                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Severity </label>
                                                    <div class="col-sm-1">
                                                         <?php $this->mylib->selectbox("severity","", $list_severity," style=\"width: 200px;\"");?>
                                                    </div>
                                                </div> 
                                                
                                             </div>
                                                <div class="clearfix form-actions">
                                                    <div class="col-md-offset-3 col-md-9">
                                                        <?php if  (_USER_ACCESS_LEVEL_ADD == "1") { ?>
                                                        <button class="btn btn-info" type="button" id="SubmitData">
                                                            <i class="ace-icon fa fa-check bigger-110"></i>
                                                            Submit
                                                        </button>
                                                        <?php } ?>
                                                        <button class="btn" type="reset">
                                                            <i class="ace-icon fa fa-undo bigger-110"></i>
                                                            Reset
                                                        </button>
                                                    </div>
                                                </div> 
                                            </form>
                                        </div>
                                    </div>

									</div>
								</div>