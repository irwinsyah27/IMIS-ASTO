								<div class="row">
									<div class="col-xs-12"> 
										<div class="clearfix">
											<div class="pull-right tableTools-container"></div>
										</div>
										<div class="table-header">
											<?php if (isset($title) && $title<>"") echo $title;?>  
											<?php if  (_USER_ACCESS_LEVEL_VIEW == "1") { ?>
											<a class="btn btn-white btn-info btn-bold pull-right" href="<?php echo base_url('daily_absent/view');?>">
												<i class="ace-icon fa fa-list-alt bigger-120 blue"></i>
												List Data
											</a>
											<?php } ?>
										</div>

										<div class="clearfix">
											<div class="pull-right tableTools-container"></div>
										</div>

										<div class="row">
                                            <div > 
                                            <form action="<?php echo base_url('rci/edit_data');?>" method="post" name="frmData" id="frmData"  class="form-horizontal" role="form">
                                                 <div class="form-group">
                                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Nama </label>
                                                    <div class="col-sm-9">
                                                         <?php $this->mylib->selectbox("nrp",$rs["nrp"],$list_operator,"",'chosen-select');?>
                                                    </div>
                                                </div> 
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Tanggal </label>
                                                    <div class="col-sm-2">
                                                         <?php $this->mylib->inputdate("date_rc",$rs["date"]);?>
                                                    </div>
                                                </div>  
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Shift </label>
                                                    <div class="col-sm-1">
                                                         <?php $this->mylib->selectbox("shift",$rs["shift"], $list_shift," style=\"width: 200px;\"");?>
                                                    </div>
                                                </div>  
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> STA KM </label>
                                                    <div class="col-sm-1">
                                                         <?php $this->mylib->selectbox("sta_km",$rs["sta_km"], $list_sta_km," style=\"width: 200px;\"");?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> STA Meter </label>
                                                    <div class="col-sm-1">
                                                         <?php $this->mylib->selectbox("sta_meter",$rs["sta_meter"], $list_sta_meter," style=\"width: 200px;\"");?>
                                                    </div>
                                                </div>    
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Jenis Kerusakan </label>
                                                    <div class="col-sm-1">
                                                         <?php $this->mylib->selectbox("jenis_kerusakan",$rs["jenis_kerusakan"], $list_sta_km," style=\"width: 200px;\"");?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Severity </label>
                                                        <div class="col-sm-1">
                                                             <?php $this->mylib->selectbox("severity",$rs["severity"], $list_severity," style=\"width: 200px;\"");?>
                                                        </div>
                                                </div> 

                                                <div class="clearfix form-actions">
                                                    <div class="col-md-offset-3 col-md-9">
                                                        <?php if  (_USER_ACCESS_LEVEL_ADD == "1") { ?>
                                                        <input type="hidden" id="old_id" name="old_id" value="<?php echo $rs["rci_id"];?>">
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