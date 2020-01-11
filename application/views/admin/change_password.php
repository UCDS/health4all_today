<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>


<script>
$(function(){
    $(".confirm_error").hide();
	$('#change_password').on('submit',function(){
		if($("#password").val()!=$("#confirm_password").val()){
			$(".confirm_error").show();
			return false;
		}
	});
});			
</script>
<style>
    .container{
        margin-top:15px;
    }
</style>
<div class="container" >
	<?php if(isset($msg)){ ?>
		<div class="alert alert-info"><?php echo $msg;?></div>
	<?php
	}
	?>
	<?php echo form_open('admin/change_password',array('role'=>'form','class'=>'form-horizontal','id'=>'change_password')); ?>
	<div class="card w-100">
		<div class="card-header bg-secondary text-center text-white">
			<h4>Change Password</h4>
		</div>
		<div class="card-body">
            <div class="row" >
                <div class="form-group col-md-12">
                    <div class="col-md-3">
                        <label for="old_password" class="control-label">Old Password</label>
                    </div>
                    <div class="col-md-6">
                        <input type="password" class="form-control" placeholder="Old Password" id="old_password" name="old_password" required autofocus/>
                    </div>
                </div>	
                <div class="form-group col-md-12">
                    <div class="col-md-3">
                        <label for="password" class="control-label">Password</label>
                    </div>
                    <div class="col-md-6">
                        <input type="password" class="form-control" placeholder="Password" id="password" name="password" required />
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <div class="col-md-3">
                        <label for="password" class="control-label">Confirm Password</label>
                    </div>
                    <div class="col-md-6">
                        <input type="password" class="form-control" placeholder="Confirm Password" id="confirm_password" name="confirm_password" required />
                        <div class="alert alert-danger confirm_error "  >New Password and Confirm Password should match</div>
                    </div>
                </div>
            </div>
		</div>
		<div class="col-md-12">
				<button class="btn btn-md btn-primary btn-block" type="submit" value="submit">Submit</button>
		</div>
        <br>
	</div>	
	</form>
</div>
</div>