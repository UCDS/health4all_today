<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>


<style>
    select , .answer , .page-item{
        cursor: pointer;
    }
    .answer-option:hover {
        background:#b3cccc;
    }
    .answers {
        list-style:none;
    }
    .card-footer{
        color:blue;
        height:4rem;
    }
    .round-button{
        border-radius:100%;
        border: solid 1px;
    }
    .answer-option {
        cursor: pointer;
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
    .option-name, .item {
        font-size:1rem;
    }

    li>span.answer {
    display: block;
    margin-top: 3px;
    padding: 12px 20px;
    color: #222;
    }
    
    .explanation{
        justify-content: center;
        margin:inherit;
        margin-left:12px;
        background: #add8e6;
        border: 2px solid #48b4e0;
        padding: 12px 20px;
        border-radius: 4px;
        text-align: left;
        font-size: 17px;
    }    
</style>

<?php  
    $logged_in=$this->session->userdata('logged_in'); 
    $default_language_id = $logged_in['default_language_id'];
    $defaut_group = '';
    foreach($groups as $r){
        if($r->default_group == 1) {
            $defaut_group = $r;
        }
    }
?>
<!-- Begin page content -->
<main role="main" class="flex-shrink-0">
  <div class="container">
	<div class="jumbotron" style="padding-top:1rem;padding-bottom:1rem;">
	<h4 ><?php echo $banner_text[0]->banner_text;?></h4>
	</div>
  </div>
  <div class="container">
        <div class="row">
            <div class="form-group col-md-3">
                <select id="group_id" name="group" style=""  placeholder="       --Select Group--                     ">		
                </select>
            </div>
            <div class="form-group col-md-3">
                <select class="form-control" name="sub_group" id="sub_group_id">
                <option value="0" selected>Sub Group</option>
                </select>        
            </div>
            <div class="form-group col-md-3">
                <select class="form-control" name="question_level" id="question_level_id" required>
                    <option value="0" selected>Question Level</option>
                    <?php
                        foreach($question_levels as $r){ ?>
                        <option value="<?php echo $r->level_id;?>"    
                        <?php if($this->input->post('level') == $r->level_id) echo " selected "; ?>
                        ><?php echo $r->level;?></option>    
                        <?php }  ?>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-control"  name="language" id="language" required>
                    <option value="0" selected >Language</option>
                    <?php
                        if($logged_in) {
                            foreach($user_languages as $r){ ?>
                            <option value="<?php echo $r->language_id;?>"    
                            <?php if($this->input->post('language') == $r->language_id || $default_language_id === $r->language_id) echo " selected "; ?>
                            ><?php echo $r->language;?></option>    
                        <?php } 
                        } else {
                            foreach($languages as $r){ ?>
                            <option value="<?php echo $r->language_id;?>"    
                            <?php if($this->input->post('language') == $r->language_id || $default_quiz_language[0]->value == $r->language_id) echo " selected "; ?>
                            ><?php echo $r->language;?></option>    
                        <?php } } ?>
                </select>
            </div>
        </div>
        <div class="row">
            <?php if($display_images[0]->value) { ?>
                    <div class="col-md-3" style="display:inline-flex; margin-top:10px;">
                        <input  type="checkbox" name="show_images" id="show_images" style="width:25px;height:25px;" <?php echo $user_display_images[0]->value ? 'checked' :''  ?>>
                        <label for="showImages" style="padding-left:10px;">Show Images</label>
                    </div>
            <?php }  if($display_transliterate[0]->value) { ?>
                    <div class="col-md-3 form-group" style="display:inline-flex; margin-top:10px;">
                        <input  type="checkbox" name="show_transliterate" id="show_transliterate" style="width:25px;height:25px;" <?php echo $user_display_transliterate[0]->value ? 'checked' :''  ?> onchange="toggleTranslateLanguage()"/>
                        <label for="showImages" style="padding-left:10px;">Show Transliterate</label>
                    </div>
                    <div class="col-md-3 form-group">
                        <select class="form-control"  name="language" id="transliterate_language" required>
                            <option value="0" selected >Transliterate Language</option>
                            <?php
                                foreach($languages as $r){ ?>
                                <option value="<?php echo $r->language_id;?>"    
                                <?php if($this->input->post('language') == $r->language_id) echo " selected "; ?>
                                ><?php echo $r->language;?></option>    
                            <?php }  ?>
                        </select>
                    </div>    
            <?php } ?>
            <div class="col-md-3 form-group">
                <button type="button" class='btn btn-primary btn-block' onclick='loadData()' >Submit</button>                        
            </div>
        </div>
        <?php if($logged_in) {?>
            <div class="row ">
                <div class="form-group admin-features col-md-3">
                    <select class="form-control shadow-none " name="questions_status_toggle" id="questions_status_toggle" required >
                        <option value="0" selected>All Questions</option>
                        <option value="1">Archived Questions</option>
                        <option value="2">Un Archived Questions</option>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <button class="btn btn-info" id="toggleView"> User View</button>
                </div>
            </div>

        <?php } ?>
  </div>
  <div class="container" id="quiz" style="margin-top:15px;">
        <div class="card">
           <div class="card-header bg-primary text-white" style="text-align:center">
            <b>ANSWER THE <span id="no_of_questions"></span> QUESTIONS !</b>
           </div>
        </div>
        <div class="card-footer">
        </div>
	</div>
</main>


<script>
    const BLACK_COLOR = "#000";
    const WHITE_COLOR = "#FFFFFF";
    const RED_COLOR = "#ff4d4d";
    const BLUE_COLOR = "#add8e6"
    const GREEN_COLOR = "#90ee90";
    const CHECK_ICON =  "<i class='fa fa-check' aria-hidden='true'></i>";
    const CLOSE_ICON = "<i class='fa fa-close' aria-hidden='true'></i>";
    const LOCK_ICON = "<i class='fa fa-lock' aria-hidden='true'></i>";
    const UNLOCK_ICON = "<i class='fa fa-unlock-alt' aria-hidden='true'></i>";
    const EDIT_ICON = "<i class='fa fa-pencil' aria-hidden='true'></i>";
    const GROUP = 'gp';
    const SUB_GROUP = 'sgp';
    const LEVEL = 'lvl';
    const LANGUAGE = 'lg';
    const SHOW_IMAGES = 'img';
    const PAGE_NUMBER = 'page';
 
    function escapeSpecialChars(str) {
        return str.replace(/\n/g, "\\n").replace(/\r/g, "\\r").replace(/\t/g, "\\t");
    }

    function updateSemanticUrl(queryObj) {

        let queryParams = new URLSearchParams(window.location.search);
        // Set new or modify existing page value
        for (const prop in queryObj) {
            queryParams.set(prop, queryObj[prop]);
        }
        // Replace current querystring with the new one
        history.replaceState(null, null, "?" + queryParams.toString());
    }
    
    function getQueryParamvalue(key){
        let queryParams = new URLSearchParams(window.location.search);
        return queryParams.get(key);
    }
    
    $(function() {
        // onload setting the default group value and initializing the search filter for group
        $('#group_id').attr("data-previous-value", <?php echo $defaut_group->group_id; ?>);
        initGroupSelectize();
        // onload call to show/hide transliterate language dropdown
        toggleTranslateLanguage();

        // Start : Quiz validation logic
        $("#quiz").on("click" , ".answer" , function(e){
            e.preventDefault();
                var answers_list =  $(this).parent('li').parent('ul').children().children();
                var selected_answers = [];
                var answers = [];
    
                //if wrong option is selected ,wrong option is highlighted in red 
                if($(this).attr("data-val")==='0'){ 
                        $(this).parent().css({
                        background:RED_COLOR,
                        color:WHITE_COLOR 
                    });
                    $(this).append(CLOSE_ICON);
                // if wrong option is clicked , all options are disabled and correct option is highlighted in green and adding check icon
                    
                    $(answers_list).each(function (index, element) {
                        // console.log("$$$$$", element);
                        if($(element).attr("data-val")==='1'){ 
                            $(element).parent().css({
                                background:GREEN_COLOR,
                                color:BLACK_COLOR 
                            });
                            $(element).append(CHECK_ICON);
                        }
                        $(element).parent().css({pointerEvents:"none"});
                    });    
                    // on clicking the wrong option , show the explanation
                    $(this).parent('li').parent('ul').parent('div').parent('div').next(".explanation").attr("hidden", false);
                } else {
                /* 
                    if any one of the multiple correct option is selected, then
                    option highlighted in blue color indicationg that it is partially ccorrect 
                */
                    $(this).parent().css({
                        background:BLUE_COLOR,
                        color:BLACK_COLOR 
                    });
                    $(this).parent().attr("isActive", "true");
                }

                // fetchng list of all option value
                $(answers_list).each(function (index, element) {
                    if($(element).attr("data-val")){
                        answers.push($(element).attr("data-val"));
                    }
                });
                // console.log("ANSWERS:", answers);
                // fetching list after selecteing options
                $(answers_list).filter('.answer').each(function (index , element){
                        selected_answers[index] = $(element).parent().attr("isActive") ? "1" : "0";
                });
                // console.log("SELECTED ANSWERS:", selected_answers);
                //  if all the selected options are correct , highlight answers correct with green
                if(JSON.stringify(selected_answers)==JSON.stringify(answers)){
                    $(answers_list).filter('.answer').each(function (index, element) {
                        if( $(element).parent().attr("isActive")==="true"){
                            $(element).parent().css({
                                    background:GREEN_COLOR,
                                    color:BLACK_COLOR 
                            });
                            $(element).append(CHECK_ICON);
                        }
                            // disabling all options
                            $(element).parent().css({pointerEvents:"none"});
                        
                    });
                    $(this).parent('li').parent('ul').parent('div').parent('div').next(".explanation").attr("hidden", false);
                }
        });
        // End : Quiz validation logic

                
        // on selecting a group , filtering all the sub_groups based on the selected group and fetching all quiz data
        $("#group_id").change(function (e) { 
            filter_sub_groups();
            selected_group = $("#group_id").val();
            selected_sub_group = $("#sub_group_id").val();
            selected_question_level = $("#question_level_id").val();
            selected_language = $("#language").val();
            selected_transliterate_language = $("#transliterate_language").val();
            show_images = $("#show_images").is(':checked');
            // load_quiz_data(1  , selected_group , selected_sub_group  , selected_question_level, selected_language, selected_transliterate_language, show_images);
            // get_pagination_data(selected_group ,selected_sub_group  , selected_question_level, selected_language, selected_transliterate_language, show_images);
        }); 

        // on change of sub_group or question_level ,  fetching all quiz data
        // $("#sub_group_id , #question_level_id, #language, #show_images, #transliterate_language").change(function (e) { 
        //     selected_group = $("#group_id").val();
        //     selected_sub_group = $("#sub_group_id").val();
        //     selected_question_level = $("#question_level_id").val();
        //     selected_language = $("#language").val();
        //     selected_transliterate_language = $("#transliterate_language").val();
        //     show_images = $("#show_images").is(':checked');
        //     load_quiz_data(1  , selected_group , selected_sub_group ,selected_question_level, selected_language, selected_transliterate_language, show_images );
        //     get_pagination_data(selected_group , selected_sub_group , selected_question_level, selected_language, selected_transliterate_language, show_images);
        // }); 

        /* updating dropdown values based on query params, if value exists */
        getQueryParamvalue(GROUP) && $("#group_id").data('selectize').setValue(getQueryParamvalue(GROUP));
        getQueryParamvalue(SUB_GROUP) && $("#sub_group_id").val(getQueryParamvalue(SUB_GROUP));
        getQueryParamvalue(LEVEL) && $("#question_level_id").val(getQueryParamvalue(LEVEL));
        getQueryParamvalue(LANGUAGE) && $("#language").val(getQueryParamvalue(LANGUAGE));
        getQueryParamvalue(SHOW_IMAGES) && $("#show_images").prop("checked",getQueryParamvalue(SHOW_IMAGES));
        
        selected_group = $("#group_id").val();
        selected_sub_group = $("#sub_group_id").val();
        selected_question_level = $("#question_level_id").val();
        selected_language = $("#language").val();
        selected_transliterate_language = $("#transliterate_language").val();
        show_images = $("#show_images").is(':checked');
        page_number = getQueryParamvalue(PAGE_NUMBER) || 1;
        // TODO: refactor the page number reset to 1 logic
        $("#sub_group_id , #question_level_id, #language,#group_id").change(function (e) { 
            updateSemanticUrl({[PAGE_NUMBER] : 1});
        });
        updateSemanticUrl({
            [GROUP] :selected_group,
            [SUB_GROUP]:selected_sub_group,
            [LEVEL]:selected_question_level,
            [LANGUAGE]:selected_language,
            [SHOW_IMAGES]:show_images,
            [PAGE_NUMBER]:page_number
        });
        // console.log("selected_question_level" , selected_question_level);
        // on page load fetching quiz data , pages_count and filtering sub groups
        load_quiz_data(page_number, selected_group, selected_sub_group, selected_question_level, selected_language, selected_transliterate_language, show_images);
        get_pagination_data(selected_group, selected_sub_group, selected_question_level, selected_language, selected_transliterate_language, show_images);
        filter_sub_groups();
        /* updating the sub_group dropdown value again, as the filter_sub_groups() method will re-render the dropdown */
        getQueryParamvalue(SUB_GROUP) && $("#sub_group_id").val(getQueryParamvalue(SUB_GROUP));
    });

    function loadData() {
        if(!$("#group_id").val()) {
            swal({
                    title: "Please select group",
                    // text: "Question is safe!",
                    type: "info",
                    // timer: 2000
            });
            return;
        }
        selected_group = $("#group_id").val();
        selected_sub_group = $("#sub_group_id").val();
        selected_question_level = $("#question_level_id").val();
        selected_language = $("#language").val();
        selected_transliterate_language = $("#transliterate_language").val();
        show_images = $("#show_images").is(':checked');
        page_number = getQueryParamvalue(PAGE_NUMBER) || 1;
        updateSemanticUrl({
            [GROUP] : selected_group,
            [SUB_GROUP] :selected_sub_group,
            [LEVEL] : selected_question_level,
            [LANGUAGE] : selected_language,
            [SHOW_IMAGES]:show_images,
            [PAGE_NUMBER]:page_number
        });
        load_quiz_data(page_number, selected_group, selected_sub_group, selected_question_level, selected_language, selected_transliterate_language, show_images);
        get_pagination_data(selected_group, selected_sub_group, selected_question_level, selected_language, selected_transliterate_language, show_images);
    }

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

    // function to show and hide the tranlate_language dropdown 
    function toggleTranslateLanguage() {
        show_transliterate = $("#show_transliterate").is(':checked');
        if(show_transliterate){
            $("#transliterate_language").show();
        } else {
            $("#transliterate_language").hide();
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

    //api call to get pages count and to mount pagination on DOM 
    function get_pagination_data(group , sub_group , question_level, language, transliterate_language, show_images){
        $(".pagination").remove();
        $.ajax({
            type: "GET",
            accepts: {
                contentType: "application/json"
            },
            url: "<?= base_url() ?>home/get_pagination_data/"+group+"/"+sub_group+"/"+question_level+"/"+language,
            dataType: "text",
            success: function (response) {
             var data = JSON.parse(response);
             var {questions_count , pages_count} = data;
             $("#no_of_questions").text(questions_count);
             if(pages_count>1){
                 var i=1;
                 $(".card-footer").append(
                    `  <ul class="pagination justify-content-center"></ul>`       
                 );
                 while(i<=pages_count){
                     $(".pagination").append(`
                     <li class="page-item ${setPageActive(i)} "><a class="page-link" onclick='load_quiz_data(${i} , ${group} , ${sub_group} , ${question_level}, ${language}, ${transliterate_language}, ${show_images})'>${i}</a></li>
                     `);
                    i++;
                 }
             }
            }
        });
    }
  
    function getExplantionBlock(explantion, explanationImage, explanationImageWIdth, explanationTransliterate, displayImage){
            if(explantion!==""){
                return `<div class="explanation row" hidden>
                                <div class="col-md-${ displayImage && explanationImage ? <?= $bootstrap_question_col_values[0]->lower_range; ?> :'12'}"> 
                                    <h5> Explanation:</h5>
                                    <span> ${explantion} </span>
                                    <br/><br/>
                                    <span style='color:#5d16db'>
                                    ${explanationTransliterate ? explanationTransliterate :''}
                                    </span>
                                </div>
                                <div class="col-md-<?= $bootstrap_question_col_values[0]->upper_range; ?>" style="text-align:center">
                                    ${getImageBlock(explanationImage, explanationImageWIdth, displayImage)}
                                </div>
                            </div>
                        </div>`
            } else {
                return "";
            }

    }
    
    function getImageBlock(image, width, displayImage){
        // console.log(image)
        if(image && displayImage){
            return `<img src=<?=base_url()?>assets/images/quiz/${image}.jpeg width="${width}" style="max-width:-webkit-fill-available" />`
        } else{
            return "";
        }
    }

    function setPageActive(i){
        if(i === 1) return "active";
        return "";
    }

    // api call to get quiz data and mount it on DOM
    function load_quiz_data(page , group , sub_group , question_level, language_id, transliterate_language,  show_images){
        /* updating page number in the semantic URL */
        updateSemanticUrl({[PAGE_NUMBER] : page});
        // console.log(sub_group);
        $(".pagination li").removeClass("active");
        $(`.pagination li:nth-child(${page})`).addClass("active");
        $(".card-body").remove();
            $.ajax({
                type: "GET",
                accepts: {
                    contentType: "application/json"
                },
                url: "<?= base_url() ?>home/quiz_questions/"+page+"/"+group+"/"+sub_group+"/"+question_level+"/"+language_id+"/"+transliterate_language,
                dataType: "text",
                success: function (data) {
                    var question_answers_list =  JSON.parse(data);
                    // console.log(question_answers_list)
                    var q=(page-1)*10;
                    const show_transliterate = $("#show_transliterate").is(':checked');
                    $.each(question_answers_list, function (indexInArray, valueOfElement) { 
                        
                        const {question , answers, transliterate} = valueOfElement;
                        // console.log(transliterate);
                        let questionTransliterate, explanationTransliterate;
                        if(transliterate){
                            questionTransliterate = transliterate.question_transliterate ;
                            explanationTransliterate = transliterate.explanation_transliterate;
                        }
                        const {question_id , status} = question; 
                        const displayImage = <?php echo $display_images[0]->value; ?> && show_images;
                         $(".card").append(
                            `<div class="card-body" style="align-items:center;">
                                
                                <div class="row">
                                    <div class="col-md-${ displayImage && question.question_image ? '<?= $bootstrap_question_col_values[0]->lower_range; ?>': '12'}">
                                        <h4 class="card-text">${++q +". "+question.question}</h4>
                                            ${show_transliterate ? `
                                                <div class="question-transliterate-${question_id}">
                                                ${  questionTransliterate ? questionTransliterate : '' }
                                                </div>
                                            ` : ''}
                                        <br/>
                                    </div>
                                    ${question.question_image && 
                                        `<div class="col-md-<?= $bootstrap_question_col_values[0]->upper_range; ?>" style="text-align:center">
                                            ${getImageBlock(question.question_image, question.question_image_width, displayImage)}
                                        </div>`
                                    }
                                </div>
                                <div class="row">
                                    <div class="col-md-<?php echo $logged_in ? '11' : '12' ?>">
                                        <ul class="answers answers-${question_id}">
                                        </ul>
                                    </div>
                                    <?php if($logged_in) { ?>
                                    <div class="col-md-1 admin-features">
                                        <?php if($edit_question_access) { ?>
                                            <button class="btn btn-light round-button" onclick="update_question(${question_id})">${EDIT_ICON}</button> 
                                        <?php } if($edit_question_status_access) { ?>
                                            <br/><br/>
                                            <button class="btn btn-light round-button"  onClick="toggle_question_status(${question_id})" >${ +status ? UNLOCK_ICON : LOCK_ICON }</button> 
                                        <?php } if($remove_question_access) { ?>
                                            <br/><br/>
                                            <button class="btn btn-danger round-button"  onclick="delete_question(${question_id})"><i class="fa fa-trash" aria-hidden="true"></i> </button>
                                        <?php } ?>
                                    </div>
                                    <?php } ?>
                                </div>
                                ${getExplantionBlock(question.explanation, question.explanation_image, question.explanation_image_width, explanationTransliterate, displayImage)}
         
                            </div>`);
                            
                            var i = 0;
                            var c ='a';
                            $.each(answers, function (indexInArray, option) { 
                                 $(".answers-"+question_id).append(
                                     `<li class="row answer-option">
                                        <span class="answer col-md-${displayImage && option.answer_image ? <?= $bootstrap_question_col_values[0]->lower_range; ?> :'12'}" for=${question_id} data-val=${option.correct_option}> 
                                          ${String.fromCharCode(c.charCodeAt(0)+ i++)  +". "+option.answer}
                                        </span>
                                            ${ option.answer_image ? `<span class="col-md-<?= $bootstrap_question_col_values[0]->upper_range; ?>" style="text-align:center"> ${getImageBlock(option.answer_image, option.answer_image_width, displayImage )} </span>` : "" }
                                     </li>`
                                 );
                            });
                    });
                }
            });
    }

    // API call to update a question and its details
    function update_question(question_id){
        window.location = `<?=base_url()?>home/update_question/${question_id}`
    }

        //  API call to delete a question   
    function delete_question(question_id){
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this question!",
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
                    url: "<?= base_url() ?>home/delete_question/"+question_id,
                    dataType: "text",
                    success: function (response) {
                        const res =  JSON.parse(response);        
                        selected_group = $("#group_id").val();
                        selected_sub_group = $("#sub_group_id").val();
                        selected_question_level = $("#question_level_id").val();
                        selected_language = $("#language").val();
                        selected_transliterate_language = $("#transliterate_language").val();
                        show_images = $("#show_images").is(':checked');
                        load_quiz_data(1  , selected_group, selected_sub_group, selected_question_level, selected_language, selected_transliterate_language, show_images);
                        get_pagination_data(selected_group, selected_sub_group, selected_question_level, selected_language, selected_transliterate_language, show_images);
                        swal({
                            title: "Success",
                            text: "Question has been deleted!",
                            type: "success",
                            timer: 2000
                        });
                    },
                    error: function(){

                    }
                });
            } else {
                swal({
                    title: "Cancelled",
                    text: "Question is safe!",
                    type: "error",
                    timer: 2000
                })
            }
            });  
    }

    // API call to Archive a question 
    function toggle_question_status(question_id){
        $.ajax({
            type: "PUT",
            accepts: {
                contentType: "application/json"
            },
            url: "<?= base_url() ?>home/toggle_question_status/"+question_id,
            dataType: "text",
            success: function (response) {
                const res =  JSON.parse(response);
                if(res[0]){
                    swal({
                        title: "Status Updated",
                        text: `Question ${res[1]}`,
                        type: "success",
                        timer: 2000
                    })
                }
                selected_group = $("#group_id").val();
                selected_sub_group = $("#sub_group_id").val();
                selected_question_level = $("#question_level_id").val();
                selected_language = $("#language").val();
                selected_transliterate_language = $("#transliterate_language").val();
                show_images = $("#show_images").is(':checked');
                load_quiz_data(1  , selected_group , selected_sub_group  , selected_question_level, selected_language, selected_transliterate_language, show_images);
                get_pagination_data(selected_group ,selected_sub_group  , selected_question_level, selected_language, selected_transliterate_language, show_images);
            }
        });
    }
        
    //Toggle  Between user and admin view
    function toggleView(){
        
    }

    $(function () {
        $("#toggleView").on('click', function () {
        $(".admin-features").toggle();  
        $(this).text(function(i, text){
            return text === "ADMIN VIEW" ? "USER VIEW" : "ADMIN VIEW";
        });
        });   
    });
         
       
</script>