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
   
</style>
<div class="container">
        <?php if(isset($msg)){ ?>
            <div class="alert alert-success" id="alert-success" role="alert"><?php echo $msg;?></div>
        <?php
        }
        ?>
    <form id="create_language" action="<?= base_url('admin/create_language') ?>" method="POST">
        <div class="card">
            <div class="card-header bg-primary text-white text-center">
                <h4> Create Language </h4>
            </div>
            <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="group">Language Name<span class="star" style="color:red"> *</span></label>
                            <input type="text" class="form-control" name="language" id="language" placeholder="Enter language Name" required>  
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