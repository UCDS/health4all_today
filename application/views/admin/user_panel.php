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
    .panel-info {
        margin-top:1rem;
        text-align:center;
        justify-content:center;
    }
    select {
        cursor: pointer;
    }
    
</style>
<div class="container">
    <div class="card ">
        <div class="card-header bg-primary text-white">
            <h4> Update Users </h4>
        </div>
        <div class="card-body">
            <div class="row" style="margin-top:1rem;margin-bottom:0.5rem; justify-content:center">
                <div class="col-md-6">
                    <select id="search_username" name="username" placeholder="Search username" onchange="getUserInformation();">
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
                            <input type="number" class="form-control" name="phone" id="phone"  pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==12) return false;"  placeholder="Phone Number">
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
                            <input type="email" class="form-control" name="email" id="email" maxlength ="100" placeholder="email">
                        </div>
                        <div class="form-group col-md-4 col-lg-2 col-xs-12" style="display:inline-flex; margin-top: 2.2rem;">
                            <input  type="checkbox" name="status" id="status" style="width:25px;height:25px;">
                            <label for="user-satus" style="padding-left:10px;">User Status</label>
                        </div>
                        <div class="form-group col-md-6 col-lg-6 col-xs-12">
                            <label for="note">Note</label>
                            <textarea class="form-control" id="note" name="note" rows="2" placeholder="Enter note here..."></textarea>
                        </div>
                        <div class="form-group col-md-12 col-lg-12 col-xs-12">
                            <button class="btn btn-md btn-primary btn-block" type="button" onclick="updateUserInformation()">Submit</button>
                        </div>
                    </div>
                    <div class="row">
                            <div class="col-md-6">
                                <b> Created By :</b> 
                                <span id="user-info-created-by"></span>
                            </div>
                            <div class="col-md-6">
                                <b> Last Updated By :</b> 
                                <span id="user-info-updated-by"></span>
                            </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="user-functions" role="tabpanel" aria-labelledby="user-functions-tab">
                    <div class="row" style="margin-top:1rem;" >
                        <div class="col-md-12 form-group">
                            <button type="button" id="add-user-function" class="btn btn-primary" data-toggle="modal" data-target="#addUserFunctionModal">
                                Add user function
                            </button>
                        </div>
                        <div class="col-md-12 form-group" id="user-functions-container">
                            <table id="user-functions-table" class="table table-bordered table-striped" >
                                <thead>
                                    <tr>
                                        <th style="text-align:center">#</th>
                                        <th style="text-align:center">User Function</th>
                                        <th style="text-align:center">View</th>
                                        <th style="text-align:center">Add</th>
                                        <th style="text-align:center">Edit</th>
                                        <th style="text-align:center">Remove</th>
                                        <th style="text-align:center">Status</th>
                                        <th style="text-align:center">Actions</th>
                                        <!-- <th style="text-align:center">Created by</th>
                                        <th style="text-align:center">Updated by</th> -->
                                    </tr>
                                </thead>
                                <tbody id="user-functions-data">
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row panel-info" id="no-user-selected-alert">
                <div class="col-md-10 alert alert-info">Please select a user !</div>
            </div>
            <div class="row panel-info" id="no-authorized-user-func-alert">
                <div class="col-md-10 alert alert-info"><b>No authorized</b> user functions exists!</div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addUserFunctionModal" tabindex="-1" role="dialog" aria-labelledby="addUserFunctionModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserFunctionModalTitle">Access to New User Function</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="add_new_user_function"  method="POST">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <select name="unauthorized_functions" id="unauthorized-functions" class="form-control"></select>
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="View">View</label> <input type="checkbox" class="user-function-checkbox" name="View" id="new-user-func-view">
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="Add">Add</label> <input type="checkbox" class="user-function-checkbox" name="Add" id="new-user-func-add">
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="Edit">Edit</label> <input type="checkbox" class="user-function-checkbox" name="Edit" id="new-user-func-edit">
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="Remove">Remove</label> <input type="checkbox" class="user-function-checkbox" name="Remove" id="new-user-func-remove">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onClick="addNewUserFunction()">Submit</button>
            </div>
            </div>
        </div>
    </div>
</div>

<script>
    const LOCK_ICON = "<i class='fa fa-lock' aria-hidden='true'></i>";
    const UNLOCK_ICON = "<i class='fa fa-unlock-alt' aria-hidden='true'></i>";
    const EDIT_ICON = "<i class='fa fa-pencil' aria-hidden='true'></i>";
    const TRASH_ICON = "<i class='fa fa-trash' aria-hidden='true'></i>";

    $(function () {
        initUsersListSelectize();
        handleDisplayTabs();
    });

    /* shows tab-content when a user is selected */
    function handleDisplayTabs(){
        const isUserSelected = !!$("#search_username").val();
        if(!isUserSelected){
            $(".tab-content").hide();
            $("#no-user-selected-alert").show();
        } else {
            $(".tab-content").show();
            $("#no-user-selected-alert").hide();
        }
        $("#no-authorized-user-func-alert").hide();
    }
    
    function getUserInformation() {
        handleDisplayTabs();
        const userId = $("#search_username").val();
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
                poupulateUserFunctionsInfo(data.user_functions, data.user_unauthorized_functions);
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
                default_language_id: $('#default_language').val(),
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
                    getUserInformation();
            }
        });
    }

    function addNewUserFunction(){
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?= base_url() ?>admin/add_user_function_link/",
            data: {
                view: $('#new-user-func-view').is(':checked') ? 1 : 0,
                add: $('#new-user-func-add').is(':checked') ? 1 : 0,
                edit: $('#new-user-func-edit').is(':checked') ? 1 : 0,
                remove: $('#new-user-func-remove').is(':checked') ? 1 : 0,
                user_id: $('#search_username').val(),
                function_id: $('#unauthorized-functions').val(),
            },
            success: function (response) {
                    swal({
                        title: response?.statusCode === 200 ? "Success" : "Failed",
                        text: response?.statusText,
                        type: response?.statusCode === 200 ? "success" : "error",
                        timer: 2000
                    })
                    getUserInformation();
            }
        });
    }
    
    function deleteUserFunction(link_id){
        swal({
            title: "Are you sure?",
            text: "You want to delete the user function access!",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes, delete it!",
            cancelButtonClass: "btn btn-outline-secondary",
            cancelButtonText: "Cancel!",
            closeOnConfirm: false,
            closeOnCancel: false
            },
            function(isConfirm) {
            if (isConfirm) {
                $.ajax({
                    type: "DELETE",
                    accepts: {
                        contentType: "application/json"
                    },
                    url: "<?= base_url() ?>admin/remove_user_function_link/"+link_id,
                    dataType: "text",
                    success: function (response) {
                        getUserInformation()
                        swal({
                            title: "Success",
                            text: "User function has been deleted!",
                            type: "success",
                            timer: 2000
                        });
                    }
                });
            } else {
                swal({
                    title: "Cancelled",
                    text: "User function is safe!",
                    type: "error",
                    timer: 2000
                })
            }
        });  
    }


    function populateUserPersonalInfo(userInfo) {
        const {
            created_user_first_name, created_user_last_name, created_datetime, last_updated_user_first_name, last_updated_user_last_name, updated_datetime 
            } = userInfo;
        $("#username").val(userInfo?.username);
        $("#first_name").val(userInfo?.first_name);
        $("#last_name").val(userInfo?.last_name);
        $("#phone").val(userInfo?.phone);
        $("#email").val(userInfo?.email);
        $("#default_language").val(userInfo?.default_language_id);
        $("#note").val(userInfo?.note);
        $("#status").prop("checked", isChecked(userInfo?.active_status));
        $("#user-info-created-by").empty();
        $("#user-info-updated-by").empty();
        $("#user-info-created-by").append(`${created_user_first_name || ''} ${created_user_last_name || ''}, ${formatTimestamp(created_datetime)}`);
        $("#user-info-updated-by").append(`${last_updated_user_first_name || ''} ${last_updated_user_last_name || ''}, ${formatTimestamp(updated_datetime)} `);
    }

    function poupulateUserFunctionsInfo(userFunctions, user_unauthorized_functions)  {
        const hasDeleteUserFunctionAccess = <?php echo $remove_user_function_access; ?>;
        if(userFunctions.length == 0){
            /* if user functons list is empty, showing a information box */
            $("#no-authorized-user-func-alert").show();
            $("#user-functions-container").hide();
        } else {
            /* removing previous data */
            $("#user-functions-container").show();
            $("#user-functions-data").empty();
            $.each(userFunctions, function (index, userFunction) { 
                 $("#user-functions-data").append(`
                    <tr>
                        <td style="text-align:center;">${index+1}</td>
                        <td style="text-align:left;">${userFunction.user_function_display}</td>
                        <td style="text-align:center;"><input class="user-function-checkbox" type="checkbox" name="" id="" ${isChecked(userFunction.view)} /></td>
                        <td style="text-align:center;"><input class="user-function-checkbox" type="checkbox" name="" id="" ${isChecked(userFunction.add)} /></td>
                        <td style="text-align:center;"><input class="user-function-checkbox" type="checkbox" name="" id="" ${isChecked(userFunction.edit)} /></td>
                        <td style="text-align:center;"><input class="user-function-checkbox" type="checkbox" name="" id="" ${isChecked(userFunction.remove)} /></td>
                        <td style="text-align:center;"><button  class='btn ${isChecked(userFunction.active) ? 'user-function-enabled btn-success' : 'user-function-disabled btn-danger'} round-button'  onClick="toggle_question_status(${userFunction.link_id})" >${ isChecked(userFunction.active) ? UNLOCK_ICON : LOCK_ICON }</button> </td>
                        <td style="text-align:center;">
                            <button class='btn round-button edit-user-function-access'>${EDIT_ICON}</button>
                            ${ hasDeleteUserFunctionAccess == 1 ? `<button class='btn btn-danger round-button delete-user-function-access' onClick='deleteUserFunction(${userFunction.link_id})'>${TRASH_ICON}</button>`: ''}
                        </td>
                        
                    </tr>
                 `);
            });
        }

        /* removing previous options */
        $("#unauthorized-functions").empty();
        $.each(user_unauthorized_functions, function (indexInArray, userFunction) { 
             $("#unauthorized-functions").append(`<option value='${userFunction.user_function_id}'>${userFunction.user_function_display}</option>`);
        });

        /*  tooltips  */
        tippy(".user-function-enabled", {
            content :   'user function enabled'
        });
        tippy(".user-function-disabled", {
            content :   'user function disabled'
        });
        tippy(".edit-user-function-access", {
            content :   'Edit user function values'
        });
        tippy(".delete-user-function-access", {
            content :   'Delete user function access'
        });
    }

    function isChecked(val){
        return val==='1' ? 'checked' : '';
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
    
    function formatTimestamp(timeStamp){
        if(timeStamp == null) return '';
        const date = new Date(timeStamp);
        let formattedDate = date.toDateString();
        formattedDate = formattedDate.substr(formattedDate.indexOf(' ') + 1)
        const time = date.toLocaleTimeString();
        return formattedDate+' '+time;
    }
    
    // tooltips
    tippy("#add-user-function", {
        content :   'add user function'
    });
    
</script>