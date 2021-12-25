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
        background-image: -webkit-linear-gradient(top,#f5f5f5,#f2f2f2);
        background-image: -moz-linear-gradient(top,#f9f9f9,#f2f2f2);
        background-image: -ms-linear-gradient(top,#f9f9f9,#f2f2f2);
        background-image: -o-linear-gradient(top,#f9f9f9,#f2f2f2);
        border: 1px solid #f2f2f2;
    }
    body.dragging, body.dragging * {
        cursor: move !important;
    }

    .dragged {
        position: absolute;
        opacity: 0.5;
        z-index: 2000;
    }
    ol>li {
        cursor: pointer;
        background: #dbdada;
        color: #222;
        border-radius: 4px;
        
        /* background-image: -webkit-linear-gradient(top,#f9f9f9,#f2f2f2);
        background-image: -moz-linear-gradient(top,#f9f9f9,#f2f2f2);
        background-image: -ms-linear-gradient(top,#f9f9f9,#f2f2f2);
        background-image: -o-linear-gradient(top,#f9f9f9,#f2f2f2); */
        border: 1px solid #f2f2f2;
        margin-top: 10px;
        margin-bottom: 10px;
        padding-left: 10px;
    }
    ol>li:nth-child(odd) {
        background-color : #d0e3b9;
    }
    #sortable li.placeholder{
        position: relative;
        /** More li styles **/
    }
    #sortable  li.placeholder:before{
        position: absolute;
        /** Define arrowhead **/
    }
</style>

<?php 
    $group = $this->input->post('group');
    $sub_group = $this->input->post('sub_group');
    $language = $this->input->post('language');
    $sequence = $sequence_info ? $sequence_info->sequence : '';
?>

<div class="container">
    <div class="card">
        <div class="card-header bg-primary text-white">
           <h4> Create Question Sequence </h4>
        </div>
        <div class="card-body">
            <form id="questions_sequence" action="<?= base_url('admin/questions_sequence') ?>" method="POST">
                <input type="hidden" id="question_sequence" name="question_sequence" value="<?= $sequence; ?>" />
                <input type="hidden" id="is_sequence_updated" name="is_sequence_updated" />
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
                    <div class="col-md-3 form-group" style="display:inline-flex; margin-top:2.5rem;">
                        <input  type="checkbox" name="enable_sorting" id="enable_sorting" style="width:25px;height:25px;">
                        <label for="enableSorting" style="padding-left:10px;">Change Sequence</label>
                    </div>
                    <div class="form-group col-md-3">
                        <button type="submit" class="btn btn-md btn-secondary btn-block">Get Questions</button>
                    </div>
                    <div class="form-group col-md-3">
                        <button type="submit" class="btn btn-md btn-primary btn-block" onclick="save_sequence();">Save Sequence</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body">
            <?php $questions = json_decode($questions); if($questions) { ?>
                <ol id="sortable">
                    <?php 
                            $i=1; 
                            foreach ($questions as $q) { ?>
                                <li class="question" question-id="<?php echo $q->question_id; ?>">
                                    <?php echo $q->question; ?>
                                </li>
                        <?php } ?>
                </ol>
            <?php } ?> 
            <!-- <?php if($group && !$questions) {?>
                <div class="row" style="margin-top:1rem;">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <p>No Questions found with the selected group, sub_group and language combination</p>
                        </div>
                    </div>
                </div>
            <?php } ?> -->
        </div>
    </div>
</div>

<script>

    <?php if(isset($status)) { ?>
        const status = <?php echo $status; ?>;
        const msg= '<?php echo $msg; ?>';
    <?php } ?>
    
    if(status==200){
        swal({
            title: "Success",
            text: msg,
            type: "success",
            timer: 2000
        });
    }
    
    function escapeSpecialChars(str) {
        return str.replace(/\n/g, "\\n").replace(/\r/g, "\\r").replace(/\t/g, "\\t");
    }
    
    $(function () {
        
        initGroupSelectize(<?php echo $group; ?>);
        let isSortingEnabled = false;
        $("#enable_sorting").change(function (e) { 
            e.preventDefault();
            isSortingEnabled = $(this).is(':checked') ? true : false;
            if(isSortingEnabled){
                $("#sortable").sortable({
                    vertical:true,
                    onMouseDown : ($item, container)=> {
                        console.log('updated!!');
                    }     
                });
            } else {
                $("#sortable").sortable("disable");
            }
        });
    });

    function initGroupSelectize(val){
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
        if(val){
            selectize[0].selectize.setValue(val);
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

    function save_sequence() {
        let question_sequence = '';
        $('.question').each(function (index, element) {
            const question_id = $(element).attr('question-id').toString();
            question_sequence = question_sequence.concat(`${question_id},`);
        });
        $("#is_sequence_updated").val(1);
        $("#question_sequence").val(question_sequence);
    }
</script>