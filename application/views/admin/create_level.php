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
    <form id="create_level" action="<?= base_url('admin/create_level') ?>" method="POST">
        <div class="card">
            <div class="card-header bg-primary text-white text-center">
                <h4> Create Group </h4>
            </div>
            <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <label for="group">Question Level Name<span class="star" style="color:red"> *</span></label>
                            <input type="text" class="form-control" name="level" id="level" placeholder="Enter Question Level" required>  
                        </div>
                        <div class="col">
                            <label for="group">Question Level Image</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="level_image" id="inputLevelImage" aria-describedby="inputGroupFileAddon01">
                                <label class="custom-file-label" for="inputLevelImage">Choose Image</label>
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