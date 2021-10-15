<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>


<style>
    select , .answer , .page-item{
        cursor: pointer;
    }
    .answer:hover {
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
    li span {
    display: block;
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
    }
    .explanation{
        display: block;
        background: #add8e6;
        border: 2px solid #48b4e0;
        padding: 12px 20px;
        border-radius: 4px;
        text-align: left;
        font-size: 17px;
    }    
</style>

<?php  $logged_in=$this->session->userdata('logged_in'); ?>
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
                <select class="form-control shadow-none " name="group" id="group_id" required >
                    <option value="" selected disabled>Group</option>
                    <?php
                        foreach($groups as $r){
                            echo "<option  value='".$r->group_id."'";
                            if($this->input->post('group_name') && $this->input->post('group_name') == $r->group_id) echo " selected ";
                            if($r->default_group == 1) echo "selected";
                            echo ">".$r->group_name."</option>";
                        }
                    ?>
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
                        foreach($languages as $r){ ?>
                        <option value="<?php echo $r->language_id;?>"    
                        <?php if($this->input->post('language') == $r->language_id) echo " selected "; ?>
                        ><?php echo $r->language;?></option>    
                        <?php }  ?>
                </select>
            </div>
        </div>
        <?php if($logged_in) {?>
            <div class="row ">
                <div class="form-group admin-features col-md-3">
                    <select class="form-control shadow-none " name="group" id="group_id" required >
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
    
    
    $(function() {                
        // Start : Quiz validation logic
        $("#quiz").on("click" , ".answer" , function(e){
            e.preventDefault();
                var answers_list =  $(this).parent('li').parent('ul').children().children();
                var selected_answers = [];
                var answers = [];
    
                //if wrong option is selected ,wrong option is highlighted in red 
                if($(this).attr("data-val")==='0'){ 
                        $(this).css({
                        background:RED_COLOR,
                        color:WHITE_COLOR 
                    });
                    $(this).append(CLOSE_ICON);
                // if wrong option is clicked , all options are disabled and correct option is highlighted in green and adding check icon
                    
                    $(answers_list).each(function (index, element) {
                        // console.log("$$$$$", element);
                        if($(element).attr("data-val")==='1'){ 
                            $(element).css({
                                background:GREEN_COLOR,
                                color:BLACK_COLOR 
                            });
                            $(element).append(CHECK_ICON);
                        }
                        $(element).css({pointerEvents:"none"});
                    });    
                    // on clicking the wrong option , show the explanation
                    $(this).parent('li').parent('ul').parent('div').parent('div').next(".explanation").attr("hidden", false);
                } else {
                /* 
                    if any one of the multiple correct option is selected, then
                    option highlighted in blue color indicationg that it is partially ccorrect 
                */
                    $(this).css({
                        background:BLUE_COLOR,
                        color:BLACK_COLOR 
                    });
                    $(this).attr("isActive", "true");
                }

                // fetchng list of all option value
                $(answers_list).each(function (index, element) {
                    if($(element).attr("data-val")){
                        answers.push($(element).attr("data-val"));
                    }
                });
                // fetching list after selecteing options
                $(answers_list).each(function (index , element){
                        selected_answers[index] = $(element).attr("isActive") ? "1" : "0";
                });
                //  if all the selected options are correct , highlight answers correct with green
                if(JSON.stringify(selected_answers)==JSON.stringify(answers)){
                    $(answers_list).each(function (index, element) {
                        
                        if( $(element).attr("isActive")==="true"){
                            $(element).css({
                                    background:GREEN_COLOR,
                                    color:BLACK_COLOR 
                            });
                            $(element).append(CHECK_ICON);
                        }
                            // disabling all options
                            $(element).css({pointerEvents:"none"});
                        
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
            load_quiz_data(1  , selected_group , selected_sub_group  , selected_question_level, selected_language);
            get_pagination_data(selected_group ,selected_sub_group  , selected_question_level, selected_language);
            
        }); 

        // on change of sub_group or question_level ,  fetching all quiz data
        $("#sub_group_id , #question_level_id, #language").change(function (e) { 
            selected_group = $("#group_id").val();
            selected_sub_group = $("#sub_group_id").val();
            selected_question_level = $("#question_level_id").val();
            selected_language = $("#language").val();
            load_quiz_data(1  , selected_group , selected_sub_group ,selected_question_level, selected_language );
            get_pagination_data(selected_group , selected_sub_group , selected_question_level, selected_language);
        }); 

        selected_group = $("#group_id").val();
        selected_sub_group = $("#sub_group_id").val();
        selected_question_level = $("#question_level_id").val();
        selected_language = $("#language").val();
        // console.log("selected_question_level" , selected_question_level);
        // on page load fetching quiz data , pages_count and filtering sub groups
        load_quiz_data(1, selected_group, selected_sub_group, selected_question_level, selected_language);
        get_pagination_data(selected_group, selected_sub_group, selected_question_level, selected_language);
        filter_sub_groups(); 
    });

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
    function get_pagination_data(group , sub_group , question_level, language){
        $(".pagination").remove();
        $.ajax({
            type: "GET",
            accepts: {
                contentType: "application/json"
            },
            url: "<?= base_url() ?>welcome/get_pagination_data/"+group+"/"+sub_group+"/"+question_level+"/"+language,
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
                     <li class="page-item ${setPageActive(i)} "><a class="page-link" onclick='load_quiz_data(${i} , ${group} , ${sub_group} , ${question_level}, ${language})'>${i}</a></li>
                     `);
                    i++;
                 }
             }
            }
        });
    }
  
    function getExplantionBlock(explantion, explanationImage){
            if(explantion!==""){
                return `<div class="explanation" hidden>
                                    <h5> Explanation:</h5>
                                        ${explantion}
                                        ${getImageBlock(explanationImage)}
                                </div>`
            } else {
                return "";
            }

        }
    
    
        function getImageBlock(image){
        if(image){
            return `<img src="${<?=base_url()?>}/assets/images/quiz/${image}.jpeg" width="50" height="50"/>`
        } else{
            return "";
        }
    }

    function setPageActive(i){
        if(i === 1) return "active";
        return "";
    }

    // api call to get quiz data and mount it on DOM
    function load_quiz_data(page , group , sub_group , question_level, language_id){
        // console.log(sub_group);
        $(".pagination li").removeClass("active");
        $(`.pagination li:nth-child(${page})`).addClass("active");
        $(".card-body").remove();
            $.ajax({
                type: "GET",
                accepts: {
                    contentType: "application/json"
                },
                url: "<?= base_url() ?>welcome/quiz/"+page+"/"+group+"/"+sub_group+"/"+question_level+"/"+language_id,
                dataType: "text",
                success: function (data) {
                    var question_answers_list =  JSON.parse(data);
                    // console.log(question_answers_list)
                    var q=(page-1)*10;
                    $.each(question_answers_list, function (indexInArray, valueOfElement) { 
                        
                        const {question , answers } = valueOfElement;
                        const {question_id , status} = question; 
                         $(".card").append(
                            `<div class="card-body" style="align-items:center;">
                                <h4 class="card-text">${++q +". "+question.question}</h4>
                                ${getImageBlock(question.question_image)}
                                <div class="row">
                                    <div class="col-md-11">
                                        <ul class="answers answers-${question_id}">
                                        </ul>
                                    </div>
                                    <?php if($logged_in) { ?>
                                    <div class="col-md-1 admin-features">
                                        <button class="btn btn-light round-button" onclick="update_question(${question_id})">${EDIT_ICON}</button> 
                                        <br/><br/>
                                        <button class="btn btn-light round-button"  onClick="toggle_question_status(${question_id})" >${ +status ? UNLOCK_ICON : LOCK_ICON }</button> 
                                        <br/><br/>
                                        <button class="btn btn-danger round-button"  onclick="delete_question(${question_id})"><i class="fa fa-trash" aria-hidden="true"></i> </button>
                                    </div>
                                    <?php } ?>
                                </div>
                                ${getExplantionBlock(question.explanation, question.explanation_image)}
         
                            </div>`);
                            
                            var i = 0;
                            var c ='a';
                            $.each(answers, function (indexInArray, option) { 
                                 $(".answers-"+question_id).append(
                                     `<li class="row" style=""justify->
                                        <span class="answer col-md-${option.answer_image ? '10' : '12'}" for=${question_id} data-val=${option.correct_option}> 
                                          ${String.fromCharCode(c.charCodeAt(0)+ i++)  +". "+option.answer}
                                        </span>
                                        ${ option.answer_image ?  getImageBlock(option.answer_image) : ""   }
                                     </li>`
                                 );
                            });
                    });
                }
            });
        }

        // API call to update a question and its details
        function update_question(question_id){
            window.location = `<?=base_url()?>welcome/update_question/${question_id}`
        }

        //  API call to delete a question   
        function delete_question(question_id){
            $.ajax({
                type: "DELETE",
                accepts: {
                    contentType: "application/json"
                },
                url: "<?= base_url() ?>welcome/delete_question/"+question_id,
                dataType: "text",
                success: function (response) {
                    const res =  JSON.parse(response);
                    load_quiz_data(1  , selected_group ,selected_sub_group , selected_question_level);
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
                url: "<?= base_url() ?>welcome/toggle_question_status/"+question_id,
                dataType: "text",
                success: function (response) {
                    const res =  JSON.parse(response);
                    // if(res[0]){
                    //     alert("Success");
                    // }
                    load_quiz_data(1  , selected_group ,selected_sub_group , selected_question_level);
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