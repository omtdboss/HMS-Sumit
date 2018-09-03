<?php 
	// This is Dashboard at admin side!!!!!!!!! 
	$role='patient';
	$patient_type='outpatient';
	$obj_bloodbank=new Hmgtbloodbank();
	$diagnosis_obj=new Hmgt_dignosis();
	?>
	<script type="text/javascript">
$(document).ready(function() {
	$('#out_patient_form').validationEngine();
	$('#birth_date').datepicker({
		  changeMonth: true,
	        changeYear: true,
	        yearRange:'-65:+0',
	        onChangeMonthYear: function(year, month, inst) {
	            $(this).val(month + "/" + year);
	        }
                    
                }); 
} );
</script>
     <?php 	
	if($active_tab == 'addoutpatient')
	 {
        	$edit=0;
				if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit'){
					
					$edit=1;
					$user_info = get_userdata($_REQUEST['outpatient_id']);
					$doctordetail=get_guardianby_patient($_REQUEST['outpatient_id']);
					$diagnosis=$diagnosis_obj->get_last_diagnosis_by_patient($_REQUEST['outpatient_id']);
					$doctor = get_userdata($doctordetail['doctor_id']);
				}
				else
				{
				  $lastpatient_id=get_lastpatient_id($role);
				 $nodate=substr($lastpatient_id,0,-4);
				 $patientno=substr($nodate,1);
				 $patientno+=1;
				$newpatient='P'.$patientno.date("my");
				}
				
				?>
		
       <div class="panel-body">	
        <form name="out_patient_form" action="" method="post" class="form-horizontal" id="out_patient_form" enctype="multipart/form-data">
         <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<input type="hidden" name="role" value="<?php echo $role;?>"  />
		<input type="hidden" name="patient_type" value="<?php echo $patient_type;?>"  />
		<input type="hidden" name="diagnosis_id" value="<?php if(!empty($diagnosis)) echo $diagnosis->diagnosis_id;?>"  />
		<input type="hidden" name="user_id" value="<?php if(isset($_REQUEST['outpatient_id'])) echo $_REQUEST['outpatient_id'];?>"  />
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="roll_id"><?php _e('Patient Id','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="patient_id" class="form-control validate[required]" type="text" 
				value="<?php if($edit){ echo $user_info->patient_id;}else echo $newpatient;?>" readonly name="patient_id">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="first_name"><?php _e('First Name','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="first_name" class="form-control validate[required,custom[onlyLetterSp]] text-input" type="text" value="<?php if($edit){ echo $user_info->first_name;}elseif(isset($_POST['first_name'])) echo $_POST['first_name'];?>" name="first_name">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="middle_name"><?php _e('Middle Name','hospital_mgt');?></label>
			<div class="col-sm-8">
				<input id="middle_name" class="form-control " type="text"  value="<?php if($edit){ echo $user_info->middle_name;}elseif(isset($_POST['middle_name'])) echo $_POST['middle_name'];?>" name="middle_name">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="last_name"><?php _e('Last Name','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="last_name" class="form-control validate[required,custom[onlyLetterSp]] text-input" type="text"  value="<?php if($edit){ echo $user_info->last_name;}elseif(isset($_POST['last_name'])) echo $_POST['last_name'];?>" name="last_name">
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="gender"><?php _e('Gender','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
			<?php $genderval = "male"; if($edit){ $genderval=$user_info->gender; }elseif(isset($_POST['gender'])) {$genderval=$_POST['gender'];}?>
				<label class="radio-inline">
			     <input type="radio" value="male" class="tog validate[required]" name="gender"  <?php  checked( 'male', $genderval);  ?>/><?php _e('Male','hospital_mgt');?>
			    </label>
			    <label class="radio-inline">
			      <input type="radio" value="female" class="tog validate[required]" name="gender"  <?php  checked( 'female', $genderval);  ?>/><?php _e('Female','hospital_mgt');?> 
			    </label>
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="birth_date"><?php _e('Date of birth','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="birth_date" class="form-control validate[required]" type="text"  name="birth_date" 
				value="<?php if($edit){ echo $user_info->birth_date;}elseif(isset($_POST['birth_date'])) echo $_POST['birth_date'];?>">
			</div>
		</div>		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="blood_group"><?php _e('Blood Group','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<?php if($edit){ $userblood=$user_info->blood_group; }elseif(isset($_POST['blood_group'])){$userblood=$_POST['blood_group'];}else{$userblood='';}?>
				<select id="blood_group" class="form-control validate[required]" name="blood_group">
				<option value=""><?php _e('Select Blood Group','hospital_mgt');?></option>
				<?php foreach(blood_group() as $blood){ ?>
						<option value="<?php echo $blood;?>" <?php selected($userblood,$blood);  ?>><?php echo $blood; ?> </option>
				<?php } ?>
			</select>
			</div>
		</div>
		<?php if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit'){?>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="patient_convert"><?php  _e(' Convert into in patient','hospital_mgt');?></label>
				<div class="col-sm-8">
				<input type="checkbox"  name="patient_convert" value="inpatient">
				
				</div>
		</div>
		<?php }?>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="symptoms"><?php _e('Symptoms','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<textarea id="symptoms" class="form-control validate[required]" name="symptoms"> <?php if($edit){ echo $doctordetail['symptoms'];}elseif(isset($_POST['symptoms'])) echo $_POST['symptoms'];?></textarea>
			</div>
		</div>
		<!--<div class="form-group">
			<label class="col-sm-2 control-label" for="medicine"><?php _e('Medicine','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<textarea id="medicine" class="form-control validate[required]" name="medicine"> <?php //if($edit){ echo $user_info['medicine'];}elseif(isset($_POST['medicine'])) echo $_POST['medicine'];?></textarea>
			</div>
		</div>-->
		<div class="form-group">
			<label class="col-sm-2 control-label" for="diagnosis"><?php _e('Diagnosis Report','hospital_mgt');?></label>
			<div class="col-sm-6">
				<input type="file" class="form-control file" name="diagnosis">
			</div>
			<?php if($edit){ ?>
			
			<div class="col-sm-2">
			<?php if($diagnosis->attach_report!=""){?>
			<a href="<?php echo content_url().'/uploads/hospital_assets/'.$diagnosis->attach_report;?>" class="btn btn-default"><i class="fa fa-download"></i><?php _e('View Report','hospital_mgt');?></a>
			<?php }
			else{?>
				<a href="#" class="btn btn-default"><i class="fa fa-download"></i><?php _e('No Report','hospital_mgt');?></a>
			<?php }?>
			</div>
			<?php  }?>
		</div>
		
			<div class="form-group">
			<label class="col-sm-2 control-label" for="doctor"><?php _e('Assign Doctor','hospital_mgt');?></label>
			<div class="col-sm-8">
				<?php if($edit){ $doctorid=$doctor->ID; }elseif(isset($_POST['doctor'])){$doctorid=$_POST['doctor'];}else{$doctorid='';}?>
				<select name="doctor" class="form-control">
				
				<option ><?php _e('select Doctor','hospital_mgt');?></option>
				<?php $get_doctor = array('role' => 'doctor');
					$doctordata=get_users($get_doctor);
					 if(!empty($doctordata))
					 {
						foreach($doctordata as $retrieved_data){?>
						<option value="<?php echo $retrieved_data->ID; ?>" <?php selected($doctorid,$retrieved_data->ID);?>><?php echo $retrieved_data->display_name;?></option>
						<?php }
					 }?>
					 
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="address"><?php _e('Home Town Address','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="address" class="form-control validate[required]" type="text"  name="address" 
				value="<?php if($edit){ echo $user_info->address;}elseif(isset($_POST['address'])) echo $_POST['address'];?>">
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="city_name"><?php _e('City','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="city_name" class="form-control validate[required]" type="text"  name="city_name" 
				value="<?php if($edit){ echo $user_info->city_name;}elseif(isset($_POST['city_name'])) echo $_POST['city_name'];?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="state_name"><?php _e('State','hospital_mgt');?></label>
			<div class="col-sm-8">
				<input id="state_name" class="form-control" type="text"  name="state_name" 
				value="<?php if($edit){ echo $user_info->state_name;}elseif(isset($_POST['state_name'])) echo $_POST['state_name'];?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="state_name"><?php _e('Country','hospital_mgt');?></label>
			<div class="col-sm-8">
				<input id="country_name" class="form-control" type="text"  name="country_name" 
				value="<?php if($edit){ echo $user_info->country_name;}elseif(isset($_POST['country_name'])) echo $_POST['country_name'];?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="zip_code"><?php _e('Zip Code','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="zip_code" class="form-control  validate[required,custom[onlyLetterNumber]]" type="text"  name="zip_code" 
				value="<?php if($edit){ echo $user_info->zip_code	;}elseif(isset($_POST['zip_code'])) echo $_POST['zip_code'];?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label " for="mobile"><?php _e('Mobile Number','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-1">
			
			<input type="text" readonly value="+<?php echo hmgt_get_countery_phonecode(get_option( 'hmgt_contry' ));?>"  class="form-control" name="phonecode">
			</div>
			<div class="col-sm-7">
				<input id="mobile" class="form-control validate[,custom[phone]] text-input" type="text"  name="mobile" 
				value="<?php if($edit){ echo $user_info->mobile;}elseif(isset($_POST['mobile'])) echo $_POST['mobile'];?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label " for="phone"><?php _e('Phone','hospital_mgt');?></label>
			<div class="col-sm-8">
				<input id="phone" class="form-control validate[,custom[phone]] text-input" type="text"  name="phone" 
				value="<?php if($edit){ echo $user_info->phone;}elseif(isset($_POST['phone'])) echo $_POST['phone'];?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label " for="email"><?php _e('Email','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="email" class="form-control validate[required,custom[email]] text-input" type="text"  name="email" 
				value="<?php if($edit){ echo $user_info->user_email;}elseif(isset($_POST['email'])) echo $_POST['email'];?>">
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="username"><?php _e('User Name','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="username" class="form-control validate[required]" type="text"  name="username" 
				value="<?php if($edit){ echo $user_info->user_login;}elseif(isset($_POST['username'])) echo $_POST['username'];?>" <?php if($edit) echo "readonly";?>>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="password"><?php _e('Password','hospital_mgt');?><?php if(!$edit) {?><span class="require-field">*</span><?php }?></label>
			<div class="col-sm-8">
				<input id="password" class="form-control <?php if(!$edit) echo 'validate[required]';?>" type="password"  name="password" value="">
			</div>
		</div>
	
			<div class="form-group">
			<label class="col-sm-2 control-label" for="photo"><?php _e('Image','hospital_mgt');?></label>
			<div class="col-sm-2">
				<input type="text" id="hmgt_user_avatar_url" class="form-control" name="hmgt_user_avatar"  
				value="<?php if($edit)echo esc_url( $user_info->hmgt_user_avatar );elseif(isset($_POST['hmgt_user_avatar'])) echo $_POST['hmgt_user_avatar']; ?>" />
			</div>	
				<div class="col-sm-3">
       				 <input id="upload_user_avatar_button" type="button" class="button" value="<?php _e( 'Upload image', 'hospital_mgt' ); ?>" />
       				 <span class="description"><?php _e('Upload image', 'hospital_mgt' ); ?></span>
       		
			</div>
			<div class="clearfix"></div>
			
			<div class="col-sm-offset-2 col-sm-8">
                     <div id="upload_user_avatar_preview" >
	                     <?php if($edit) 
	                     	{
	                     	if($user_info->hmgt_user_avatar == "")
	                     	{?>
	                     	<img alt="" src="<?php echo get_option( 'hmgt_patient_thumb' ); ?>">
	                     	<?php }
	                     	else {
	                     		?>
					        <img style="max-width:100%;" src="<?php if($edit)echo esc_url( $user_info->hmgt_user_avatar ); ?>" />
					        <?php 
	                     	}
	                     	}
					        else {
					        	?>
					        	<img alt="" src="<?php echo get_option( 'hmgt_patient_thumb' ); ?>">
					        	<?php 
					        }?>
    				</div>
   		 </div>
		</div>
		<div class="col-sm-offset-2 col-sm-8">
        	
        	<input type="submit" value="<?php if($edit){ _e('Save Patient','hospital_mgt'); }else{ _e('Add Patient','hospital_mgt');}?>" name="save_outpatient" class="btn btn-success"/>
        </div>
        </form>
        </div>
        
     <?php 
	 }
	 ?>