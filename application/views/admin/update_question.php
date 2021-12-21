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
    input[type=number] {
        font-size:0.90rem;
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
      <form id="update_question" action="<?php echo base_url('home/update_question/').$question_id ?>" method="POST" onsubmit="return validateForm()">
        <div class="row">
            <div class="col-md-5">
                <label for="Question">Question<span class="star" style="color:red"> *</span></label>
                <textarea  class="form-control" name="question" placeholder="Question"  rows="2"  required ><?php echo $question_details[0]->question ;?></textarea>
            </div>
            <div class="col-md-5">
                <label for="explanation">Question Explanation</label>
                <textarea class="form-control" name="question_explanation" rows="2"><?php echo $question_details[0]->explanation;?></textarea>
            </div>
            <div class="col-md-1">
                <label for="transliterate">Add Transliterate</label> <br>
                <button type="button" class="btn btn-primary btn-block" id="addTransliterate"><i class="fa fa-plus" aria-hidden="true"></i></button>
            </div>
        </div>
        <div class="form-group">
        </div>
        <div class="form-group">
        </div>
        <div class="transliterate_wrapper">
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
                    <select name="question_image" id="question_image" onChange="showImagePreview('question_image', 'questionImagePreview')">
                    </select>
                </div>
                <div class="form-group col-md-1">
                    <label for="questionImageWidth">Width</label>
                    <input class="form-control" type="number" name="question_image_width" id="question_image_width" value=<?= $question_details[0]->question_image_width; ?> min=<?= $display_max_width[0]->lower_range;?> max=<?= $display_max_width[0]->upper_range; ?>/>
                </div>
                <div class="form-group col-md-5">
                    <label for="explanationImage">Select Explanation Question Image <span class="star" style="color:red"> *</span></label>
                    <select name="explanation_image" id="explanation_image" onChange="showImagePreview('explanation_image', 'explanationImagePreview')">
                    </select>
                </div>
                <div class="form-group col-md-1">
                    <label for="explanationImageWidth">Width</label>
                    <input class="form-control" type="number" name="explanation_image_width" id="explanation_image_width" value=<?= $question_details[0]->explanation_image_width; ?> min=<?= $display_max_width[0]->lower_range;?> max=<?= $display_max_width[0]->upper_range; ?> />
                </div>
            </div>
            <div class="row" style="text-align:center;margin-bottom:30px;">
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

    function escapeSpecialChars(str) {
        return str.replace(/\n/g, "\\n").replace(/\r/g, "\\r").replace(/\t/g, "\\t");
    }

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

    function getPopulatedImageOptions(imagesList, selectedImage) {
        let options = ""
        imagesList.forEach((imageName,index, arr) => {
            options = options+`<option value=${imageName} ${ selectedImage === imageName ? 'selected' :''} >${imageName}</option>`
        });
        return options;
    }

    function getPopulatedLanguageOptions(languagesList, selectedLanguage){
        let options = ""
        languagesList.forEach((option,index, arr) => {
            options = options+`<option value=${option.language_id} ${ selectedLanguage === option.language_id ? 'selected' :''} >${option.language}</option>`
        });
        return options;
    }

    function showImagePreview(imageSrc, previewImageId){
        let selectedImageName = $(`#${imageSrc}`).val();
        if(!selectedImageName){    
            $(`#${previewImageId}`).hide();
        }  else {
            let imagePath = `<?= base_url() ?>assets/images/quiz/${selectedImageName}.jpeg`;
            $(`#${previewImageId}`).attr("src", imagePath).show();
        }
    }

    function validateForm() {
        let hasAtleastOneCorrectOption = false;
        $("input[name*='correct_option']").each(function (index, element) {
            if($(this).is(':checked')){
                hasAtleastOneCorrectOption = true;
                return false;
            }
        });
        if(!hasAtleastOneCorrectOption){
            swal({
                title: "Error",
                text: "Please select atleast one correct option",
                type: "error"
            });
        }
        return hasAtleastOneCorrectOption ? true : false;
    }

    function initImageSelectize(id, value) {
        let imagesList = JSON.parse(escapeSpecialChars('<?php echo json_encode($images_list); ?>'));
        imagesList = imagesList.map(function(x) { return { image: x }; })
        var selectize = $(`#${id}`).selectize({
            valueField: 'image',
	        labelField: 'image',
            searchField: 'image',
            options: imagesList,
            create: false,
            render: {
                option: function(item, escape) {
                    return `<div>
                                <span class="title">
                                    <span class="option-name">${escape(item.image)}</span>
                                </span>
                            </div>`;
                }
    	    },
            load: function(query, callback) {
                if (!query.length) return callback();
            },
        });
        if(value){
		    selectize[0].selectize.setValue(value);
	    }
    }

    $(function() {

        /* on page load, initializing image search filters */
        initImageSelectize('question_image', '<?php echo $question_details[0]->question_image; ?>');
        initImageSelectize('explanation_image', '<?php echo $question_details[0]->explanation_image; ?>');

        var answer_options_wrapper    = $(".answer_options_wrapper"); //Input fields answer_options_wrapper
        var answer_details = <?php echo $answer_details;?>;
        // console.log(answer_details);
        if(answer_details){
            // console.log(answer_details)
            const ImagesList =  <?=json_encode($images_list); ?>;
            answer_details.forEach((element, index) => {     
                // console.log(element);
                $(answer_options_wrapper).append(`
                        <div class="row">
                            <div class="form-group col-md-5">
                                <textarea name="answer_option[${element.answer_option_id}]" class="form-control" ${[0,1].includes(index) ? "required" :''} rows='1' >${element.answer} </textarea>
                            </div>
                            <div class="form-group col-md-4">
                                <select id='answer_option_image_${element.answer_option_id}' name="answer_option_image[${element.answer_option_id}]">
                                </select>
                            </div>
                            <div class="form-group col-md-1">
                                <input class="form-control" type="number" name="answer_option_image_width[${element.answer_option_id}]" value=${element.answer_image_width} placeholder="width" min=<?= $display_max_width[0]->lower_range;?> max=<?= $display_max_width[0]->upper_range; ?> />
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
                    initImageSelectize(`answer_option_image_${element.answer_option_id}`, element.answer_image);
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
                    <div class="form-group col-md-5">
                        <textarea name="new_answer_option[]" class="form-control" rows="1"></textarea>
                    </div>
                    <div class="form-group col-md-4">
                        <select  id="new_answer_option_image_${x}" name="new_answer_option_image[]" >
                        </select>
                    </div>
                    <div class="form-group col-md-1">
                        <input class="form-control" type="number" name="new_answer_option_image_width[]" placeholder="width" value=<?= $display_max_width[0]->value; ?> min=<?= $display_max_width[0]->lower_range;?> max=<?= $display_max_width[0]->upper_range; ?> />
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
            initImageSelectize(`new_answer_option_image_${x}`)
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

        // fetching and populating transliterate data
        var trasnliterate_wrapper = $(".transliterate_wrapper"); //Input fields answer_options_wrapper
        var tranliterate_details = <?php echo $tranliterate_details;?>;
        // console.log(tranliterate_details);
        const languages =  <?=json_encode($languages); ?>;
        tranliterate_details.forEach((element, index) => {
            $(trasnliterate_wrapper).append(`
            <div class="row form-group">
                <div class="col-md-4">
                <label for="QuestionTransliterate">Question Transliterate<span class="star" style="color:red"> *</span></label>
                    <textarea class="form-control"  rows="2" name="question_transliterate[${element.question_transliterate_id}]">${element.question_transliterate}</textarea>
                </div>
                <div class="col-md-4">
                    <label for="ExplanationTransliterate">Explanation Transliterate<span class="star" style="color:red"> *</span></label>
                    <textarea class="form-control" rows="2" name="explanation_transliterate[${element.question_transliterate_id}]">${element.explanation_transliterate}</textarea>
                </div>
                <div class="col-md-2">
                    <label for="languageId">Language<span class="star" style="color:red"> *</span></label>
                    <select class="form-control"  name="transliterate_language[${element.question_transliterate_id}]" id="language" required>
                        <option value="" selected disabled>--Select--</option>
                        ${getPopulatedLanguageOptions(languages, element.language_id)}
                    </select>
                </div>
                <div class='form-group col-md-1'>
                    <label>remove</label>
                    <button type="button" class="btn btn-danger remove_field"><i class="fa fa-trash" aria-hidden="true"></i></button>
                </div>
            `);
        });

        var addTransliterate = $("#addTransliterate");
        $(addTransliterate).click(function (e) { 
            e.preventDefault();
            $(trasnliterate_wrapper).append(`
            <div class="row form-group">
                <div class="col-md-4">
                <label for="QuestionTransliterate">Question Transliterate<span class="star" style="color:red"> *</span></label>
                    <textarea class="form-control"  rows="2" name="new_question_transliterate[]"></textarea>
                </div>
                <div class="col-md-4">
                    <label for="ExplanationTransliterate">Explanation Transliterate<span class="star" style="color:red"> *</span></label>
                    <textarea class="form-control" rows="2" name="new_explanation_transliterate[]"></textarea>
                </div>
                <div class="col-md-2">
                    <label for="languageId">Language<span class="star" style="color:red"> *</span></label>
                    <select class="form-control"  name="new_transliterate_language[]" id="language" required>
                        <option value="" selected disabled>--Select--</option>
                            <?php
                                foreach($languages as $r){
                                    echo "<option value='".$r->language_id."'";
                                    echo ">".$r->language."</option>";
                                }
                            ?>
                    </select>
                </div>
                <div class='form-group col-md-1'>
                    <label>remove</label>
                    <button type="button" class="btn btn-danger remove_field"><i class="fa fa-trash" aria-hidden="true"></i></button>
                </div>
            `);
        });
        //when user click on remove button in question transliterate row
        $(trasnliterate_wrapper).on("click",".remove_field", function(e){ 
            e.preventDefault();
            $(this).parent().parent('div').remove();
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