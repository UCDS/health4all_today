<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<style>
    label{
        font-weight:bold;
    }
    .container{
        margin-top:15px;
    }
    .custom-file-input{
        cursor:pointer;
    }
   
</style>

<div class="container">
        <?php if(isset($msg)){ ?>
            <div class="alert alert-success" id="alert-success" role="alert"><?php echo $msg;?></div>
        <?php
        }
        ?>
    <form id="create_group" action="<?= base_url('admin/create_sub_group') ?>" method="POST">
        <div class="card">
            <div class="card-header bg-primary text-white text-center">
                <h4> Create Group </h4>
            </div>
            <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <label for="subGroupId">Select  Group<span class="star" style="color:red"> *</span></label>
                            <select class="form-control" name="group_id" id="group_id" required>
                                <option value="" selected disabled>--Select--</option>
                                <?php
                                    foreach($groups as $r){
                                        echo "<option value='".$r->group_id."'";
                                        if($this->input->post('group_name') && $this->input->post('group_name') == $r->group_id) echo " selected ";
                                        echo ">".$r->group_name."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col">
                            <label for="group">Sub Group Name<span class="star" style="color:red"> *</span></label>
                            <input type="text" class="form-control" name="sub_group" id="sub_group" placeholder="Sub Group Name" required>  
                        </div>
                        <div class="col">
                            <label for="group">Sub Group Image</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="sub_group_image" id="inputGroupImage" aria-describedby="inputGroupFileAddon01">
                                <label class="custom-file-label" for="inputGroupImage">Choose Image</label>
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