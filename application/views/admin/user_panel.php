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
    .user-function-checkbox{
        width:20px;
        height:20px;
    }
    
</style>
<div class="container">
    <div class="card ">
        <div class="card-header bg-primary text-white">
            <h4> Update Users </h4>
        </div>
        <div class="card-body">
            <div class="row" style="margin-top:1rem;margin-bottom:0.5rem;">
                <div class="col-md-6">
                    <select id="search_username" name="username" placeholder="Search username" onchange="getUserInformation(this);">
                    </select>
                </div>
            </div>
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="auth-update-tab" data-toggle="tab" href="#auth-update" role="tab" aria-controls="auth-update" aria-selected="true">User Info</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="user-functions-tab" data-toggle="tab" href="#user-functions" role="tab" aria-controls="user-functions" aria-selected="false">User Authorization</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Contact</a>
                </li>
            </ul>
            <div class="tab-content" id="userTabsContent">
                <div class="tab-pane fade show active" id="auth-update" role="tabpanel" aria-labelledby="auth-update-tab">
                    <div class="row">
                        <div class="form-group col-md-6 col-lg-3 col-xs-12">
                            <label for="firstName">First Name<span class="star" style="color:red"> *</span></label>
                            <input type="text" class="form-control" name="first_name" id="first_name" placeholder="First Name">
                        </div>
                        <div class="form-group col-md-6 col-lg-3 col-xs-12">
                            <label for="lastName">Last Name<span class="star" style="color:red"> *</span></label>
                            <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Last Name">
                        </div>
                        <div class="form-group col-md-6 col-lg-3 col-xs-12">
                            <label for="phoneNumber">Phone Number<span class="star" style="color:red"> *</span></label>
                            <input type="text" class="form-control" name="phone" id="phone" placeholder="Phone Number">
                        </div>
                        <div class="form-group col-md-6 col-lg-3 col-xs-12">
                            <label for="languageId">Select default language<span class="star" style="color:red"> *</span></label>
                            <select class="form-control"  name="default_language" id="default_language" required>
                                <option value="" selected disabled>Default language</option>
                                <?php
                                    foreach($languages as $r){ ?>
                                    <option value="<?php echo $r->language_id;?>"    
                                    <?php if($this->input->post('language') == $r->language_id) echo " selected "; ?>
                                    ><?php echo $r->language;?></option>    
                                    <?php }  ?>
                            </select>
                        </div>
                        <div class="form-group col-md-6 col-lg-3 col-xs-12">
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
                        <div class="form-group col-md-6 col-lg-3 col-xs-12">
                            <label for="username">Username<span class="star" style="color:red"> *</span></label>
                            <input type="text" class="form-control" name="username" id="username" placeholder="username">
                        </div>
                        <div class="form-group col-md-6 col-lg-4 col-xs-12">
                            <label for="email">Email<span class="star" style="color:red"> *</span></label>
                            <input type="email" class="form-control" name="email" id="email" placeholder="email">
                        </div>
                        <div class="form-group col-md-4 col-lg-2 col-xs-12" style="display:inline-flex; margin-top: 2.2rem;">
                            <input  type="checkbox" name="status" id="status" style="width:25px;height:25px;">
                            <label for="user-satus" style="padding-left:10px;">User Status</label>
                        </div>
                        <div class="form-group col-md-6 col-lg-6 col-xs-12">
                            <label for="note">Note</label>
                            <textarea class="form-control" id="note" name="note" rows="2" placeholder="Enter note here..."></textarea>
                        </div>
                        <div class="form-group col-md-6 col-lg-6 col-xs-12">
                            <button class="btn btn-md btn-primary btn-block" type="button" onclick="updateUserInformation()">Submit</button>
                        </div>
                    </div>
                    <div class="row">
                            <div class="col-md-6">
                                <b> Created By :</b> 
                            </div>
                            <div class="col-md-6">
                                <b> Last Updated By :</b> 
                            </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="user-functions" role="tabpanel" aria-labelledby="user-functions-tab">
                    <div class="row" style="margin-top:1rem;" >
                        <table id="table-sort" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="text-align:center">#</th>
                                    <th style="text-align:center">User function name</th>
                                    <th style="text-align:center">View</th>
                                    <th style="text-align:center">Add</th>
                                    <th style="text-align:center">Edit</th>
                                    <th style="text-align:center">Remove</th>
                                    <th style="text-align:center">Status</th>
                                    <th style="text-align:center">Created by</th>
                                    <th style="text-align:center">Updated by</th>
                                </tr>
                            </thead>
                            <tbody id="user-functions-data">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    $(function () {
        initUsersListSelectize();
    });

    function getUserInformation(element) {
        const userId = $(element).val();
        $.ajax({
            type: "GET",
            accepts: {
                contentType: "application/json"
            },
            url: "<?= base_url() ?>admin/user/"+userId,
            dataType: "text",
            success: function (response) {
                const data = JSON.parse(response);
                populateUserPersonalInfo(data.user_info);
                poupulateUserFunctionsInfo(data.user_functions);
            }
        });
    }

    function updateUserInformation(){
        const userId = $("#search_username").val();
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?= base_url() ?>admin/update_user/"+userId,
            data: {
                first_name: $('#first_name').val(),
                last_name: $('#last_name').val(),
                phone: $('#phone').val(),
                default_language_id: $('#fefault_language').val(),
                note: $('#note').val(),
                email: $('#email').val(),
                active_status: $('#status').is(':checked') ? 1 : 0,
            },
            success: function (response) {
                    swal({
                        title: response?.statusCode === 200 ? "Success" : "Failed",
                        text: response?.statusText,
                        type: response?.statusCode === 200 ? "success" : "error",
                        timer: 2000
                    })
            }
        });
    }
    
    function populateUserPersonalInfo(userInfo) {
        $("#username").val(userInfo?.username);
        $("#first_name").val(userInfo?.first_name);
        $("#last_name").val(userInfo?.last_name);
        $("#phone").val(userInfo?.phone);
        $("#email").val(userInfo?.email);
        $("#default_language").val(userInfo?.default_language_id);
        $("#note").val(userInfo?.note);
        $("#status").prop("checked", !!userInfo?.active_status);
    }

    function poupulateUserFunctionsInfo(userFunctions) {
        /* removing previous data */
        $("#user-functions-data").empty();
        $.each(userFunctions, function (index, userFunction) { 
             $("#user-functions-data").append(`
                <tr>
                    <td style="text-align:center;">${index+1}</td>
                    <td style="text-align:center;">${userFunction.user_function_display}</td>
                    <td style="text-align:center;"><input class="user-function-checkbox" type="checkbox" name="" id="" ${userFunction.view ? 'checked' : ''} /></td>
                    <td style="text-align:center;"><input class="user-function-checkbox" type="checkbox" name="" id="" ${userFunction.add ? 'checked' : ''} /></td>
                    <td style="text-align:center;"><input class="user-function-checkbox" type="checkbox" name="" id="" ${userFunction.edit ? 'checked' : ''} /></td>
                    <td style="text-align:center;"><input class="user-function-checkbox" type="checkbox" name="" id="" ${userFunction.remove ? 'checked' : ''} /></td>
                    <td style="text-align:center;"><input class="user-function-checkbox" type="checkbox" name="" id="" ${userFunction.active ? 'checked' : ''} /></td>
                    <td>To be filled</td>
                    <td>To be filled</td>
                </tr>
             `);
        });
    }
    

    function initUsersListSelectize(){
        var users =  <?php echo json_encode($users_list) ?>;
        var selectize = $('#search_username').selectize({
            valueField: 'user_id',
	        labelField: 'full_name',
            sortField: 'full_name',
            searchField: ['full_name', 'username'],
            options: users,
            create: false,
            render: {
                option: function(item, escape) {
                    return `<div>
                                <span class="title">
                                    <span class="option-name">${escape(item.full_name)}</span>
                                </span>
                            </div>`;
                }
    	    },
            load: function(query, callback) {
                if (!query.length) return callback();
            },

        });
    }
</script>