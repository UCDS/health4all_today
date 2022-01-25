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
    <div class="card ">
        <div class="card-header bg-primary text-white">
            <h4> Update Users </h4>
        </div>
        <div class="card-body">
            <div class="row" style="margin-top:1rem;">
                <div class="col-md-6">
                    <select id="username" name="username" placeholder="Search username" onchange="getUserInformation(this);">
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
                </div>
                <div class="tab-pane fade" id="user-functions" role="tabpanel" aria-labelledby="user-functions-tab">
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
                console.log(response);
            }
        });
    }

    function initUsersListSelectize(){
        var users =  <?php echo json_encode($users_list) ?>;
        var selectize = $('#username').selectize({
            valueField: 'user_id',
	        labelField: 'full_name',
            sortField: 'full_name',
            searchField: ['full_name, username'],
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