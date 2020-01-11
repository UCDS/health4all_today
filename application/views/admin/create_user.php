<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<style>
    label{
        font-weight:bold;
    }
    .container{
        margin-top:5px;
    }
</style>

<div class="container">
        <?php if(isset($msg)){ ?>
            <div class="alert alert-success" id="alert-success" role="alert"><?php echo $msg;?></div>
        <?php
        }
        ?>
    <div class="card ">
        <div class="card-header bg-primary text-white">
           <h4> Create User </h4>
        </div>
      <div class="card-body">
      <form id="create_user" action="<?= base_url('admin/create_user') ?>" method="POST">
        <div class="row">
            <div class="form-group col-md-6">
                <label for="firstName">First Name<span class="star" style="color:red"> *</span></label>
                <input type="text" class="form-control" name="first_name" id="first_name" placeholder="First Name">
            </div>
            <div class="form-group col-md-6">
                <label for="lastName">Last Name<span class="star" style="color:red"> *</span></label>
                <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Last Name">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label for="email">Email<span class="star" style="color:red"> *</span></label>
                <input type="email" class="form-control" name="email" id="email" placeholder="email">
            </div>
            <div class="form-group col-md-6">
                <label for="phoneNumber">Phone Number<span class="star" style="color:red"> *</span></label>
                <input type="text" class="form-control" name="phone" id="phone" placeholder="Phone Number">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label for="gender">Gender<span class="star" style="color:red"> *</span></label> <br/>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" id="Male" value="1">
                    <label class="form-check-label" for="inlineRadio1">Male</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" id="Female" value="2">
                    <label class="form-check-label" for="inlineRadio2">Female</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" id="Other" value="3">
                    <label class="form-check-label" for="inlineRadio3">Other</label>
                </div>
            </div>
            <div class="form-group col-md-6">
                <label for="languageId">Select The Language<span class="star" style="color:red"> *</span></label>
                <select class="form-control"  name="language" id="language" required>
                    <option value="" selected disabled>--Select--</option>
                    <?php
                        foreach($languages as $r){ ?>
                        <option value="<?php echo $r->language_id;?>"    
                        <?php if($this->input->post('language') == $r->language_id) echo " selected "; ?>
                        ><?php echo $r->language;?></option>    
                        <?php }  ?>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label for="username">Username<span class="star" style="color:red"> *</span></label>
                <input type="text" class="form-control" name="username" id="password" placeholder="password">
            </div>
            <div class="form-group col-md-6">
                <label for="password">Password<span class="star" style="color:red"> *</span></label>
                <input type="password" class="form-control" name="password" id="password" placeholder="password">
            </div>
        </div>
        <div class="form-group">
            <label for="note">Note</label>
            <textarea class="form-control" id="note" name="note" rows="2" placeholder="Enter note here..."></textarea>
        </div>
        
        <button type="submit" class="btn btn-primary">Submit</button>
        </form>
      </div>
    
</div>

