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
           <h4> Create Question </h4>
        </div>
      <div class="card-body">
      <!-- <?php echo form_open("admin/create_question",array('role'=>'form','class'=>'form-custom' , 'id'=>'create_question')); ?>  -->
      <form id="create_question" action="<?= base_url('admin/create_question') ?>" method="POST">
        <div class="form-group">
            <label for="Question">Question<span class="star" style="color:red"> *</span></label>
            <input type="text" class="form-control" name="question" placeholder="Question" required>
        </div>
<!--         <div class="row">
            <div class="form-group col-md-3">
                <label for="questionImage">Question Image</label>
                <input type="file" class="form-control-file" name="question_image">
            </div>
            <div class="form-group col-md-3" >
                <label for="explanationImage">Explanation Image</label>
                <input type="file" class="form-control-file"  name="explanation_image">
            </div>
            <div class="form-group col-md-4">
                <label for="defaultQuestionId">Default Question ID</label>
                <input type="number" class="form-control" name="default_question_id" placeholder="Enter the default question id">
            </div>
        </div> -->
        <div class="row">
            <div class="form-group col-md-6">
                <label for="levelOfQuestion">Select The Level of Question<span class="star" style="color:red"> *</span></label>
                <select class="form-control" name="question_level" required>
                    <option value="" selected disabled>--Select--</option>
                    <?php
                        foreach($question_levels as $r){ ?>
                        <option value="<?php echo $r->level_id;?>"    
                        <?php if($this->input->post('level') == $r->level_id) echo " selected "; ?>
                        ><?php echo $r->level;?></option>    
                        <?php }  ?>
                </select>
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
        <div class="groups_wrapper">
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="groupId">Select Group <span class="star" style="color:red"> *</span></label>
                    <select class="form-control" name="group[]" id="group_1" onChange="filter_sub_groups('group_1' , 'sub_group_1')" required>
                    <option  selected disabled>--Select--</option>
                    <?php
                        foreach($groups as $r){
                            echo "<option value='".$r->group_id."'";
                            if($this->input->post('group_name') && $this->input->post('group_name') == $r->group_id) echo " selected ";
                            echo ">".$r->group_name."</option>";
                        }
                    ?>
                </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="subGroupId">Select Sub Group</label>
                    <select class="form-control" name="sub_group[]" id="sub_group_1">
                        <option value="" selected >--Select--</option>
                    </select>
                </div>
                <div class="form-group col-md-1">
                <label for="">Add</label>  
                    <button type="button" class="btn btn-primary" id="addGroupAndSubGroup"><i class="fa fa-plus" aria-hidden="true"></i></button>
                </div>
            </div>    
        </div>
        <div class="question_images_wrapper">
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="questionImage">Select Question Image <span class="star" style="color:red"> *</span></label>
                    <select class="form-control" name="question_image" id="question_image" required onChange="showImagePreview('question_image', 'questionImagePreview')">
                    <option  selected disabled>--Select--</option>
                    <?php
                        foreach($images_list as $r){
                            echo "<option value='".$r."'";
                            if($this->input->post('question_image') && $this->input->post('question_image') == $r) echo " selected ";
                            echo ">".$r."</option>";
                        }
                    ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="explanationImage">Select Explanation Question Image <span class="star" style="color:red"> *</span></label>
                    <select class="form-control" name="explanation_image" id="explanation_image" required onChange="showImagePreview('explanation_image', 'explanationImagePreview')">
                    <option  selected disabled>--Select--</option>
                    <?php
                        foreach($images_list as $r){
                            echo "<option value='".$r."'";
                            if($this->input->post('explanation_image') && $this->input->post('explanation_image') == $r) echo " selected ";
                            echo ">".$r."</option>";
                        }
                    ?>
                    </select>
                </div>
            </div>    
            <div class="row" style="text-align:center;margin-bottom:10px;">
                <div class="col-md-6">
                    <label for="questionImagePreview">Question Image preview </label> <br>
                    <img id="questionImagePreview" src="" width="250" height="250">
                </div>
                <div class="col-md-6">
                    <label for="explanationImagePreview">Explanation Image preview </label> <br>
                    <img id="explanationImagePreview" src="" width="250" height="250" >
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="explanation">Question Explanation</label>
            <textarea class="form-control" name="question_explanation" rows="2"></textarea>
        </div>
        <label for="answerFields">Answers Options<span class="star" style="color:red"> *</span></label> 
        <label for="answerFields"> (<span style="color:green">&#10004;</span> for the correct option )</label> 
        <div class="answer_options_wrapper">
            <div class="row">
                <div class="form-group col-md-6">
                    <input type="text" class="form-control" name="answer_option[]" required/>
                </div>
                <div class="form-group col-md-4">
                    <select class="form-control" name="answer_option_image[0]"  required>
                        <option  selected disabled>Select Image</option>
                        <?php
                            foreach($images_list as $r){
                                echo "<option value='".$r."'";
                                if($this->input->post('explanation_image') && $this->input->post('explanation_image') == $r) echo " selected ";
                                echo ">".$r."</option>";
                            }
                        ?>
                    </select>
                </div>
                <div class="form-group col-md-1">
                    <input type="hidden" name="correct_option[]" value="0" />
                    <input type="checkbox" id="option_0" name="correct_option[0]" value="1" style="width: 25px;height: 25px;" />
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <input type="text" class="form-control" name="answer_option[]" required/>
                </div>
                <div class="form-group col-md-4">
                    <select class="form-control" name="answer_option_image[1]"  required>
                        <option  selected disabled>Select Image</option>
                        <?php
                            foreach($images_list as $r){
                                echo "<option value='".$r."'";
                                if($this->input->post('explanation_image') && $this->input->post('explanation_image') == $r) echo " selected ";
                                echo ">".$r."</option>";
                            }
                        ?>
                    </select>
                </div>
                <div class="form-group col-md-1">
                    <input type="hidden" name="correct_option[]" value="0" />
                    <input type="checkbox"  id="option_1" name="correct_option[1]" value="1"  style="width: 25px;height: 25px;">
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-primary add_fields" id="add_fields"><i class="fa fa-plus" aria-hidden="true"></i></button>
                </div>
            </div>
        </div>
            <button type="submit" class="btn btn-md btn-primary btn-block" onClick="$(#create_question).reset()">Submit</button>
        </form>
      </div>    
</div>

<script>
    $(function() {

        var answer_options_wrapper    = $(".answer_options_wrapper"); //Input fields answer_options_wrapper
        var add_button = $("#add_fields"); //Add button class or ID
        var x = 2; //Initial input field is set to 1
        //When user click on add input button
        $(add_button).click(function(e){
                e.preventDefault();
                
                $(answer_options_wrapper).append(`
                    <div class="row">
                        <div class="form-group col-md-6">
                            <input type="text" name="answer_option[]" class="form-control" /> 
                        </div>
                        <div class="form-group col-md-4">
                            <select class="form-control" id='answer_option_${x}' name="answer_option_image[${x}]"  required>
                                <option  selected disabled>Select Image</option>
                                <?php
                                    foreach($images_list as $r){
                                        echo "<option value='".$r."'";
                                        if($this->input->post('explanation_image') && $this->input->post('explanation_image') == $r) echo " selected ";
                                        echo ">".$r."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-1">
                        <input type="hidden" name="correct_option[]" value="" />
                        <input type="checkbox" id='option_${x}' name="correct_option[${x}]" value="1" style="width: 25px;height: 25px;">
                        </div>
                        <div class="form-group col-md-1">
                            <button type="button" class="btn btn-danger remove_field"><i class="fa fa-trash" aria-hidden="true"></i></button>
                        </div>
                    </div>
                `);
                x++ //input field increment
            });
        
        //when user click on remove button in answer options
        $(answer_options_wrapper).on("click",".remove_field", function(e){ 
            e.preventDefault();
            $(this).parent().parent('div').remove();
            x--; 
        });

        var count=2;
        var groups_wrapper = $(".groups_wrapper"); //Groups and subgroup wrapper 
        var addGroupAndSubGroup = $("#addGroupAndSubGroup");
        $(addGroupAndSubGroup).click(function (e) { 
            $(groups_wrapper).append(`<div class="row">
                    <div class="form-group col-md-4">
                    <label for="group">Select Group <span class="star" style="color:red"> *</span></label>
                    <select class="form-control" name="group[]" id="group_${count}" onChange="filter_sub_groups('group_${count}' , 'sub_group_${count}')" required>
                    <option value="" selected disabled>--Select--</option>
                    <?php
                        foreach($groups as $r){
                            echo "<option value='".$r->group_id."'";
                            if($this->input->post('group_name') && $this->input->post('group_name') == $r->group_id) echo " selected ";
                            echo ">".$r->group_name."</option>";
                        }
                    ?>
                </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="subGroup">Select Sub Group</label>
                    <select class="form-control" name="sub_group[]" id="sub_group_${count}">
                        <option value="0" selected >--Select--</option>
                    </select>
                </div>
                <div class="form-group col-md-1">
                <label for="">Remove</label>  
                    <button class="btn btn-danger removeGroupAndSubGroup" ><i class="fa fa-trash" aria-hidden="true"></i></button>
                </div>
                </div`);
                count++;   
        });
        //when user click on remove button in answer options
        $(groups_wrapper).on("click",".removeGroupAndSubGroup", function(e){ 
            e.preventDefault();
            $(this).parent().parent('div').remove();
            x--; 
        });

});
    // function to filter sub_groups based on a selected group 
    function filter_sub_groups(group , id){
        // fetching list of all subgroups
        // alert("#############");
        var sub_groups = <?php echo json_encode($sub_groups); ?>;
        var selected_group = $(`#${group}`).val();
        var filtered_sub_groups;
        $(`#${id}`).empty().append(`<option  selected>Sub Group</option>`);
             filtered_sub_groups = $.grep(sub_groups , function(v){
                return v.group_id == selected_group;
            }) ;
            // iterating the selected sub groups
        $.each(filtered_sub_groups, function (indexInArray, valueOfElement) { 
            const {sub_group_id ,sub_group} = valueOfElement;
            $(`#${id}`).append($('<option></option>').val(sub_group_id).html(sub_group));
        });
    }

    function showImagePreview(imageSrc, previewImageId){
        var selectedImageName = $(`#${imageSrc}`).val();
        var imagePath = `<?= base_url() ?>assets/images/quiz/${selectedImageName}.jpeg`;
        $(`#${previewImageId}`).attr("src", imagePath);
    }

</script>