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
</style>


<!-- Begin page content -->
<main role="main" class="flex-shrink-0">
  <div class="container">
	<div class="jumbotron">
	<h4><?php echo $banner_text; ?></h4>
	<hr class="my-4">
	</div>
  </div>
  <div class="container">
        <div class="card">
           <div class="card-header bg-primary text-white" style="text-align:center">
            <b>START ANSWERING THE QUESTIONS....</b>
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
							foreach($answer_options as $a) 
						{ if($a->question_id == $value->question_id) { ?>
						<li>
							<span class="answer" for=<?php echo $a->question_id ?> data-val=<?php echo $a->correct_option;?>> 
								<?php echo $a->answer?> 
							</span>
						</li>   
						<?php }  } ?>
						</ul>
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

        // option validation and response  
        $(".answer").click(function(e){
            e.preventDefault();
                if($(this).attr("data-val")==='0'){
                        $(this).css({
                        background:"#ff4d4d",
                        color:"#FFFFFF" 
                    });
                } else {
                    $(this).css({
                        background:"#90ee90",
                        color:"#FFFFFF" 
                    });
                }      
            $(this).parent('li').siblings().children().off('click');     
        })

    });
</script>