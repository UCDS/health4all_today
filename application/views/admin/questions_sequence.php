<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>


<style>
    label{
        font-weight:bold;
    }
    .card{
        margin-top:2rem;
    }
    .card-header{
        text-align:center;
    }
    select{
        cursor: pointer;
    }
    .question-tile {
        cursor: pointer;
        margin-top: 3px;
        margin-top: 3px;
        padding: 12px 20px;
        background: #f2f2f2;
        color: #222;
        border-radius: 4px;
        text-align: left;
        font-size: 17px;
        background-image: -webkit-linear-gradient(top,#f9f9f9,#f2f2f2);
        background-image: -moz-linear-gradient(top,#f9f9f9,#f2f2f2);
        background-image: -ms-linear-gradient(top,#f9f9f9,#f2f2f2);
        background-image: -o-linear-gradient(top,#f9f9f9,#f2f2f2);
        border: 1px solid #f2f2f2;
        width:100%;
    }
    .question-position{
        margin-top: 3px;
        /* margin-right:1px; */
        padding: 12px 20px;
        background: #f2f2f2;
        color: #222;
        border-radius: 4px;
        text-align: center;
        font-size: 17px;
        font-weight:bold;
        background-image: -webkit-linear-gradient(top,#f9f9f9,#f2f2f2);
        background-image: -moz-linear-gradient(top,#f9f9f9,#f2f2f2);
        background-image: -ms-linear-gradient(top,#f9f9f9,#f2f2f2);
        background-image: -o-linear-gradient(top,#f9f9f9,#f2f2f2);
        border: 1px solid #f2f2f2;
    }
</style>

<div class="container">
    <div class="card">
        <div class="card-header bg-primary text-white">
           <h4> Create Question Sequence </h4>
        </div>
        <div class="card-body">
            <form id="questions_sequence" action="<?= base_url('admin/questions_sequence') ?>" method="POST">
                <div class="row">
                    <div class="form-group col-md-3">
                        <label for="groupId">Group <span class="star" style="color:red"> *</span></label>
                        <select id="group_id" name="group" style=""  placeholder="Group" onchange="filter_sub_groups();">		
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="subGroupId">Sub Group</label>
                        <select class="form-control" name="sub_group" id="sub_group_id">
                        <option value="0" selected>Sub Group</option>
                        </select>        
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="language">Language</label>
                        <select class="form-control"  name="language" id="language" required>
                            <option value="0" selected >Language</option>
                            <?php
                                foreach($languages as $r){ ?>
                                <option value="<?php echo $r->language_id;?>"    
                                <?php if($this->input->post('language') == $r->language_id) echo " selected "; ?>
                                ><?php echo $r->language;?></option>    
                            <?php }  ?>
                        </select>
                    </div>  
                    <div class="form-group col-md-3" style="margin-top:2rem;">
                        <button type="submit" class="btn btn-md btn-primary btn-block">Submit</button>
                    </div>
                </div>
            </form>
            <?php 
                if(isset($questions)) {
                    $i=1; 
                    foreach (json_decode($questions) as $q) { ?>
                    <div class="row">
                        <div class="col-md-1 col-sm-1 col-xs-1 col-lg-1">
                            <?php echo $i++; ?>
                        </div>
                        <div class="col-md-11 col-sm-11 col-xs-11 col-lg-11 question-tile" question-id="<?php echo $q->question_id; ?>">
                            <?php echo $q->question; ?>
                        </div>
                    </div>
                <?php } 
                } 
            ?>
        </div>
    </div>
</div>

<script>
    
    function escapeSpecialChars(str) {
        return str.replace(/\n/g, "\\n").replace(/\r/g, "\\r").replace(/\t/g, "\\t");
    }
    
    $(function () {
        
        initGroupSelectize();
    });

    function initGroupSelectize(){
        var groups = JSON.parse(escapeSpecialChars('<?php echo json_encode($groups); ?>'));
        var selectize = $('#group_id').selectize({
            valueField: 'group_id',
	        labelField: 'group_name',
            sortField: 'group_name',
            searchField: ['group_name'],
            options: groups,
            create: false,
            render: {
                option: function(item, escape) {
                    return `<div>
                                <span class="title">
                                    <span class="option-name">${escape(item.group_name)}</span>
                                </span>
                            </div>`;
                }
    	    },
            load: function(query, callback) {
                if (!query.length) return callback();
            },

        });
        if($('#group_id').attr("data-previous-value")){
		    selectize[0].selectize.setValue($('#group_id').attr("data-previous-value"));
	    }
    }

    // function to filter sub_groups based on a selected group 
    function filter_sub_groups(){
        // fetching list of all subgroups
        var sub_groups = <?php echo json_encode($sub_groups); ?>;
        var selected_group = $("#group_id").val();
        var filtered_sub_groups;
        $('#sub_group_id').empty().append(`<option value="0" selected>Sub Group</option>`);
             filtered_sub_groups = $.grep(sub_groups , function(v){
                return v.group_id == selected_group;
            }) ;
            // iterating the selected sub groups
        $.each(filtered_sub_groups, function (indexInArray, valueOfElement) { 
            const {sub_group_id ,sub_group} = valueOfElement;
            $("#sub_group_id").append($('<option></option>').val(sub_group_id).html(sub_group));
        });
    }
</script>