<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>


<style>
    .answer {
        cursor: pointer;
    }
    .answers {
        list-style:none;
    }
    .card-footer{
        color:blue;
        height:4rem;
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
    select{
        cursor:pointer;
    }
    
</style>


<!-- Begin page content -->
<main role="main" class="flex-shrink-0">
  <div class="container">
	<div class="jumbotron" style="padding-top:1rem;padding-bottom:1rem;">
	<h4 ><?php echo $banner_text; ?></h4>
	<hr >
	</div>
  </div>
  <div class="container">
        <div class="row">
            <div class="form-group col-md-3">
                <select class="form-control shadow-none " name="group" id="group_id" required>
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
                <option value="" selected disabled>Sub Group</option>
                </select>        
            </div>
            <div class="form-group col-md-3">
                <select class="form-control" name="question_level" id="question_level_id" required>
                    <option value="" selected disabled>Question Level</option>
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
                    <option value="" selected disabled>Language</option>
                    <?php
                        foreach($languages as $r){ ?>
                        <option value="<?php echo $r->language_id;?>"    
                        <?php if($this->input->post('language') == $r->language_id) echo " selected "; ?>
                        ><?php echo $r->language;?></option>    
                        <?php }  ?>
                </select>
            </div>
        </div>
  </div>
  <div class="container" id="quiz" style="margin-top:15px;">
        <div class="card">
           <div class="card-header bg-primary text-white" style="text-align:center">
            <b>START ANSWERING THE QUESTIONS !</b>
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
    $(function() {
        
        // $("#quiz").on("load", ".explanation", function (e) {
        //     $(this).hide();
        // });
        
        // Start : Quiz validation logic
        $("#quiz").on("click" , ".answer" , function(e){
            e.preventDefault();
                var answers_list =  $(this).parent('li').parent('ul').children().children();
                var selected_answers = [];
                var answers = [];
                // var all_correct_options_picked = false;
                //if wrong option is selected , option highlighted in red and disabling other options
                if($(this).attr("data-val")==='0'){ 
                        $(this).css({
                        background:RED_COLOR,
                        color:WHITE_COLOR 
                    });
                    // if wrong option is clicked , all options are disabled and correct option is highlighted in green 
                    
                    $(answers_list).each(function (index, element) {
                        // console.log("$$$$$", element);
                        if($(element).attr("data-val")==='1'){ 
                            $(element).css({
                                background:GREEN_COLOR,
                                color:BLACK_COLOR 
                            });
                        }
                        $(element).css({pointerEvents:"none"});
                    });    
                    // on clicking the wrong option , show the explanation
                    $(this).parent('li').parent('ul').next(".explanation").show();
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
                        }
                        if($(element).attr("data-val")==='0'){
                            $(element).css({pointerEvents:"none"});
                        }
                    });
                    $(answers_list).parent().parent().next(".explanation").show();
                }
        });
        // End : Quiz validation logic

                
        // on selecting a group , filtering all the sub_groups based of the selected group
        $("#group_id").change(function (e) { 
            filter_sub_groups();
            selected_group = $("#group_id").val();
            selected_sub_group = $("#sub_group_id").val();
            selected_question_level = $("#question_level_id").val();
            load_quiz_data(1  , selected_group , selected_sub_group  , selected_question_level);
            get_pages_count(selected_group ,selected_sub_group  , selected_question_level);
            
        }); 

        $("#sub_group_id , #question_level_id").change(function (e) { 
            selected_group = $("#group_id").val();
            selected_sub_group = $("#sub_group_id").val();
            selected_question_level = $("#question_level_id").val();
            load_quiz_data(1  , selected_group , selected_sub_group ,selected_question_level );
            get_pages_count(selected_group , selected_sub_group , selected_question_level);
        }); 

        selected_group = $("#group_id").val();
        selected_sub_group = $("#sub_group_id").val();
        selected_question_level = $("#question_level_id").val();
        console.log("selected_question_level" , selected_question_level);
        load_quiz_data(1  , selected_group ,selected_sub_group , selected_question_level);
        get_pages_count(selected_group , selected_sub_group , selected_question_level);
        filter_sub_groups(); 
    });

    function filter_sub_groups(){
        // fetching list of all subgroups
        var sub_groups = <?php echo json_encode($sub_groups); ?>;
        var selected_group = $("#group_id").val();
        var filtered_sub_groups;
        $('#sub_group_id').empty().append(`<option value="" selected disabled>Sub Group</option>`);
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
    function get_pages_count(group , sub_group , question_level){
        $(".pagination").remove();
        $.ajax({
            type: "GET",
            accepts: {
                contentType: "application/json"
            },
            url: "<?= base_url() ?>welcome/pages_count/"+group,
            dataType: "text",
            success: function (response) {
             var pages_count = JSON.parse(response);
            //  console.log("pages count" , pages_count)
             if(pages_count>1){
                 var i=1;
                 $(".card-footer").append(
                    `  <ul class="pagination justify-content-center"></ul>`       
                 );
                 while(i<=pages_count){
                     $(".pagination").append(`
                     <li class="page-item"><a class="page-link" onclick='load_quiz_data(${i} , ${group} , ${sub_group} , ${question_level})'>${i}</a></li>
                     `);
                    i++;
                 }
             }
            }
        });
    }
    
    // api call to get quiz data and mount it on DOM
    function load_quiz_data(page , group , sub_group , question_level){
        if(sub_group===null)
            sub_group="all";
        if(question_level===null)
            question_level="all";
        
        // console.log(sub_group);
        $(".card-body").remove();
            $.ajax({
                type: "GET",
                accepts: {
                    contentType: "application/json"
                },
                url: "<?= base_url() ?>welcome/quiz/"+page+"/"+group+"/"+sub_group+"/"+question_level,
                dataType: "text",
                success: function (data) {
                    var question_answers_list =  JSON.parse(data);
                    // console.log(question_answers_list)
                    var q=(page-1)*10;
                    $.each(question_answers_list, function (indexInArray, valueOfElement) { 
                        
                        const {question , answers} = valueOfElement;
                        const question_id = question.question_id; 
                         $(".card").append(
                            `<div class="card-body" style="align-items:center">
                            <h4 class="card-text">${++q +". "+question.question}</h4>
                                <div class="row">
                                    <div class="col">
                                    <ul class="answers answers-${question_id}">
                                    </ul>
                                    </div>
                                </div>
                                <div class="explanation" hidden name= "Question_explanation_"+${indexInArray} >
                                    <h5>Question ${question_id}  Explanation:</h5>
                                        ${question.explanation}
                                </div>
                            </div>`);
                            
                            var i = 0;
                            var c ='a';
                            $.each(answers, function (indexInArray, option) { 
                                 $(".answers-"+question_id).append(
                                     `<li>
                                        <span class="answer" for=${question_id} data-val=${option.correct_option}> 
                                          ${String.fromCharCode(c.charCodeAt(0)+ i++)  +". "+option.answer}
                                        </span>
                                     </li>`
                                 );
                            });
                    });
                }
            });
        }
</script>