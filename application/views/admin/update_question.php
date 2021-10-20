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
    input[type='checkbox']{
        cursor: pointer;
    }
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        margin: 0; 
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
           <h4> Update Question ID :<?php echo $question_id?> </h4>
        </div>
      <div class="card-body">
      <!-- <?php echo form_open("admin/update_question",array('role'=>'form','class'=>'form-custom' , 'id'=>'update_question', "question_id"=>$question_id)); ?>  -->
      <form id="update_question" action="<?php echo base_url('welcome/update_question/').$question_id ?>" method="POST">
        <div class="form-group">
            <label for="Question">Question<span class="star" style="color:red"> *</span></label>
            <!-- <input type="text" class="form-control" name="question" placeholder="Question" value="<?php echo $question_details[0]->question ;?>"  required> -->
            <textarea  class="form-control" name="question" placeholder="Question"  rows="1"  required ><?php echo $question_details[0]->question ;?></textarea>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label for="levelOfQuestion">Select The Level of Question<span class="star" style="color:red"> *</span></label>
                <select class="form-control" name="question_level" required>
                    <option value="" selected disabled>--Select--</option>
                    <?php
                        foreach($question_levels as $r){ ?>
                        <option value="<?php echo $r->level_id;?>"    
                        <?php if($this->input->post('level') == $r->level_id || $question_details[0]->level_id ==$r->level_id ) echo " selected "; ?>
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
                        <?php if($this->input->post('language') == $r->language_id || $question_details[0]->language_id ==$r->language_id) echo " selected "; ?>
                        ><?php echo $r->language;?></option>    
                        <?php }  ?>
                </select>
            </div>
        </div>
        <div class="question_images_wrapper">
            <div class="row">
                <div class="form-group col-md-5">
                    <label for="questionImage">Select Question Image <span class="star" style="color:red"> *</span></label>
                    <select class="form-control" name="question_image" id="question_image" required onChange="showImagePreview('question_image', 'questionImagePreview')">
                    <option  selected value='NULL'>--Select--</option>
                    <?php
                        foreach($images_list as $r){
                            echo "<option value='".$r."'";
                            if($this->input->post('question_image') == $r || $question_details[0]->question_image ==$r) echo " selected ";
                            echo ">".$r."</option>";
                        }
                    ?>
                    </select>
                </div>
                <div class="form-group col-md-1">
                    <label for="questionImageWidth">Width</label>
                    <input class="form-control" type="number" name="question_image_width" id="question_image_width" value=<?= $question_details[0]->question_image_width; ?> min=<?= $display_max_width[0]->lower_range;?> max=<?= $display_max_width[0]->upper_range; ?>/>
                </div>
                <div class="form-group col-md-5">
                    <label for="explanationImage">Select Explanation Question Image <span class="star" style="color:red"> *</span></label>
                    <select class="form-control" name="explanation_image" id="explanation_image" required onChange="showImagePreview('explanation_image', 'explanationImagePreview')">
                    <option  selected value='NULL'>--Select--</option>
                    <?php
                        foreach($images_list as $r){
                            echo "<option value='".$r."'";
                            if($this->input->post('explanation_image') == $r || $question_details[0]->explanation_image ==$r ) echo " selected ";
                            echo ">".$r."</option>";
                        }
                    ?>
                    </select>
                </div>
                <div class="form-group col-md-1">
                    <label for="explanationImageWidth">Width</label>
                    <input class="form-control" type="number" name="explanation_image_width" id="explanation_image_width" value=<?= $question_details[0]->explanation_image_width; ?> min=<?= $display_max_width[0]->lower_range;?> max=<?= $display_max_width[0]->upper_range; ?> />
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
        <div class="form-group">
            <label for="explanation">Question Explanation</label>
            <textarea class="form-control" name="question_explanation" rows="4"><?php echo $question_details[0]->explanation;?></textarea>
        </div>
        <label for="answerFields">Answers Options<span class="star" style="color:red"> *</span></label> 
        <label for="answerFields"> (<span style="color:green">&#10004;</span> for the correct option )</label> 
        <div class="answer_options_wrapper">
            <div class="row">
                <div class="form-group col-md-4 offset-4 ">
                    <button type="button" class="btn btn-primary add_fields" id="add_fields">Add answer option</i></button>
                </div>
            </div>
        </div>
            <button type="submit" class="btn btn-md btn-primary btn-block" onClick="$(#update_question).reset()">Submit</button>
        </form>
      </div>    
</div>

<script>
     // function to filter sub_groups based on a selected group 
     function filter_sub_groups(group , id, selected_sub_group=''){
        // fetching list of all subgroups
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
            $(`#${id}`).append($(`<option ${ selected_sub_group && 'selected'} ></option>`).val(sub_group_id).html(sub_group));
        });
    }

    function getPopulatedOptions(imagesList, selectedImage) {
        console.log("selectedImage", selectedImage);
        let options = ""
        imagesList.forEach((imageName,index, arr) => {
            options = options+`<option value=${imageName} ${ selectedImage === imageName ? 'selected' :''} >${imageName}</option>`
        });
        return options;
    }

    function showImagePreview(imageSrc, previewImageId){
        var selectedImageName = $(`#${imageSrc}`).val();
        var imagePath = `<?= base_url() ?>assets/images/quiz/${selectedImageName}.jpeg`;
        $(`#${previewImageId}`).attr("src", imagePath);
    }

    $(function() {
        var answer_options_wrapper    = $(".answer_options_wrapper"); //Input fields answer_options_wrapper
        var answer_details = <?php echo $answer_details;?>;
        if(answer_details){
            console.log(answer_details)
            const ImagesList =  <?=json_encode($images_list); ?>;
            answer_details.forEach((element, index) => {     
                console.log(element);
                $(answer_options_wrapper).append(`
                        <div class="row">
                            <div class="form-group col-md-6">
                                <textarea name="answer_option[${element.answer_option_id}]" class="form-control" ${[0,1].includes(index) ? "required" :''} rows='1' >${element.answer} </textarea>
                            </div>
                            <div class="form-group col-md-4">
                                <select class="form-control" id='answer_option_[${element.answer_option_id}]' name="answer_option_image[${element.answer_option_id}]"  required>
                                    <option  selected name='NULL' value='NULL'>Select Image</option>
                                    ${ getPopulatedOptions(ImagesList, element.answer_image) }
                                </select>
                            </div>
                            <div class="form-group col-md-1">
                            <input type="hidden" name="correct_option[${element.answer_option_id}]" value="0" />
                            <input type="checkbox" name="correct_option[${element.answer_option_id}]" value="1" ${ element.correct_option==="1" && "checked"}  style="width: 25px;height: 25px;">
                            </div>
                            ${ 
                                ![0,1].includes(index) ? `
                                <div class='form-group col-md-1'>
                                    <button type="button" class="btn btn-danger remove_field"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                </div>` : ``
                            }  
                        </div>
                    `);
                }
            ); 
        }
        
        var grouping_details = <?php echo json_encode($grouping_details) ?>;
        filter_sub_groups('group_1', 'sub_group_1',grouping_details[0].sub_group_id);
        
        var add_button = $("#add_fields"); //Add button class or ID
        let x = 0; //Initial input field is set to 1
        //When user click on add input button
        $(add_button).click(function(e){
            e.preventDefault();
            
            $(answer_options_wrapper).append(`
                <div class="row">
                    <div class="form-group col-md-6">
                        <textarea name="new_answer_option[]" class="form-control" rows="1"></textarea>
                    </div>
                    <div class="form-group col-md-4">
                        <select class="form-control" name="new_answer_option_image[]"  required>
                            <option selected name='NULL' value='NULL'>Select Image</option>
                            <?php
                                foreach($images_list as $r){
                                    echo "<option value='".$r."'";
                                    echo $r;
                                    echo ">".$r."</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group col-md-1">
                    <input type="hidden" name="new_correct_option[]" value="" />
                    <input type="checkbox" id="option_${x}" name="new_correct_option[${x}]" value="1" style="width: 25px;height: 25px;">
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
        //when user click on remove button in groupAndSubGroup row
        $(groups_wrapper).on("click",".removeGroupAndSubGroup", function(e){ 
            e.preventDefault();
            $(this).parent().parent('div').remove();
            x--; 
        });

        // calling function to showPreview of question and explanation image
        showImagePreview('question_image', 'questionImagePreview');
        showImagePreview('explanation_image', 'explanationImagePreview');
        
        /* Autoclosing alert box  */
        $("#alert-success").fadeTo(2000, 500).slideUp(500, function(){
            $("#alert-success").slideUp(500);
        });
});
   
</script>