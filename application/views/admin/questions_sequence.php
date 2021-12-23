<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>


<style>
  .card {
    margin : 4px;
  }
</style>

<div class="container">
    <div class="row">
        <div class="form-group col-md-3">
                <select id="group_id" name="group" style=""  placeholder="Group" onchange="filter_sub_groups();">		
                </select>
        </div>
        <div class="form-group col-md-3">
            <select class="form-control" name="sub_group" id="sub_group_id">
            <option value="0" selected>Sub Group</option>
            </select>        
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