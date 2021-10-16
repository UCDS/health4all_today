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
        <form id="upload_image" action="<?= base_url('admin/upload_image') ?>" method="POST">
        <div class="card">
            <div class="card-header bg-primary text-white text-center">
                <h4> Upload image to Quiz Gallery </h4>
            </div>
            <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <label for="group">Image name<span class="star" style="color:red"> *</span></label>
                            <input type="text" class="form-control" name="image_name" id="image_name" placeholder="Enter image name" required>  
                        </div>
                        <div class="col">
                            <label for="group">Image<span class="star" style="color:red"> *</span></label>
                            <div class="form-group">
                                <input type="file" accept="image/*" name="image" id="image" class="form-control" onchange="encodeImgtoBase64(this)" required>
                                <input type="hidden" name="image_val" id="image_val">
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

<script>
    function encodeImgtoBase64(element) {
    var file = element.files[0];
    var reader = new FileReader();
    reader.onloadend = function() {
      $("#image_val").val(reader.result);
      $("#base64Img").attr("src", reader.result);
    }
    reader.readAsDataURL(file);
  }
</script>