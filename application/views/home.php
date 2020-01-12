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
    .answers li span {
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
                <select class="form-control shadow-none" name="group" id="groupId" required>
                    <option value="" selected disabled>Group</option>
                    <?php
                        foreach($groups as $r){
                            echo "<option  value='".$r->group_id."'";
                            if($this->input->post('group_name') && $this->input->post('group_name') == $r->group_id) echo " selected ";
                            echo ">".$r->group_name."</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="form-group col-md-3">
                <select class="form-control" name="sub_group" id="subGroupId">
                    <option value="" selected disabled>Sub Group</option>
                    <?php
                        foreach($sub_groups as $r){
                            echo "<option value='".$r->sub_group_id."'";
                            if($this->input->post('sub_group') && $this->input->post('sub_group') == $r->sub_group_id) echo " selected ";
                            echo ">".$r->sub_group."</option>";
                        }
                    ?>
                </select>        
            </div>
            <div class="form-group col-md-3">
                <select class="form-control" name="question_level" required>
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
  <div class="container">
        <div class="card">
           <div class="card-header bg-primary text-white" style="text-align:center">
            <b>START ANSWERING THE QUESTIONS !</b>
           </div>
           <?php 
            foreach($questions as $q=>$value) { 
                ?> 
            <div class="card-body" style="align-items:center">
                <h4 class="card-text"><?php echo 1+$q.". ".$value->question;?></h4>
                <div class="row">
					<div class="col">
						<ul class="answers">
						<?php
                         $i = 'a';
							foreach($answer_options as $a) 
						{ if($a->question_id == $value->question_id) { ?>
						<li>
							<span class="answer" for=<?php echo $a->question_id ?> data-val=<?php echo $a->correct_option;?>> 
								<?php echo $i++.". ".$a->answer?> 
							</span>
						</li>   
						<?php }  } ?>
						</ul>
                       <?php if($value->explanation) {?> 
                        <div class="explanation" name=<?php echo "Question_explanation_". $q++; ?> >
                         <h5><?php echo "Question ". $q++ ." Explanation:";?></h5>
                        <?php echo $value->explanation; ?>
                        </div>
                       <?php }?>
					</div>
                    <!-- TODO: Explanation block , image ... -->
					<!-- <div class="col-6">
						
					</div> -->
            	</div>
            </div>
           <?php } ?>
        </div>  
	</div>
</main>


<script>
    $(function() {
        $(".explanation").hide();
        // option validation and response  
        $(".answer").click(function(e){
            e.preventDefault();
                var answers_list =  $(this).parent('li').parent('ul').children().children();
                var selected_answers = [];
                var answers = [];
                var all_correct_options_picked = false;
                //if wrong option is selected , option highlighted in red and disabling other options
                if($(this).attr("data-val")==='0'){ 
                        $(this).css({
                        background:"#ff4d4d",
                        color:"#FFFFFF" 
                    });
                    // if wrong option is clicked , all other options are disabled and correct option is highlighted in green 
                    $(this).parent('li').siblings().children().off('click');   
                    $(answers_list).each(function (index, element) {
                        // console.log("########", element);
                        if($(element).attr("data-val")==='1'){ 
                            $(element).css({
                                background:"#90ee90",
                                color:"#000" 
                            });
                        }
                    });    
                    // on clicking the wrong option , show the explanation
                    $(this).parent('li').parent('ul').next(".explanation").show();
                } else {
                /* 
                    if any one of the multiple is correct option is selected then,
                    option highlighted in blue color indicationg that it is partially ccorrect 
                */
                    $(this).css({
                        background:"#add8e6",
                        color:"#000" 
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
                        // console.log(element)
                        if( $(element).attr("isActive")==="true"){
                            $(element).css({
                                    background:"#90ee90",
                                    color:"#000" 
                            });
                        }
                        if($(element).attr("data-val")==='0'){
                            $(element).off('click'); 
                        }
                    });
                    $(answers_list).parent().parent().next(".explanation").show();
                }
        })

    });
</script>