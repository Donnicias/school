<?php
    if(count($student)) {
        $usertype = $this->session->userdata("usertype");

        if($usertype == "Admin" || $usertype == "Teacher") {
?>
    <div class="well">
        <div class="row">
            <div class="col-sm-6">
                <button class="btn-cs btn-sm-cs" onclick="javascript:printDiv('printablediv')"><span class="fa fa-print"></span> <?=$this->lang->line('print')?> </button>
                <?php
                 //echo btn_add_pdf('transcripts/print_preview/'.$student->studentID."/".$set, $this->lang->line('pdf_preview'))
                ?>
                
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li><a href="<?=base_url("transcripts/index/$classID/$examID")?>"><?=$this->lang->line('menu_transcripts')?></a></li>
                    <li class="active"><?=$class.' Transcripts'?></li>
                </ol>
            </div>
        </div>
    </div>
      <style>
.profile-view-head{
    background: -webkit-linear-gradient(180deg,#2F4F4F, lightgray)transparent; /* For Safari 5.1 to 6.0 */
  background: -o-linear-gradient(180deg,#2F4F4F, lightgray)transparent; /* For Opera 11.1 to 12.0 */
  background: -moz-linear-gradient(180deg,#2F4F4F, lightgray)transparent; /* For Firefox 3.6 to 15 */
  background: linear-gradient(180deg,#2F4F4F, lightgray)transparent; /* Standard syntax */
}
</style>
    <div id="printablediv">
    <?php } 
    $i = 0; 
    $j = 0; 
    $ttmark=false;
    $totalstudents=count($student);
    foreach($student as $student) {
		
        
                            if ($classID==13 || $classID==14) {
								if($student->se >0){
								?>
								<section class="panel" style="border-bottom:1px solid green;border-bottom-right-radius: 5px;border-bottom-left-radius: 5px;">
            <div class="profile-view-head" style="border-top:5px solid green;border-top-right-radius: 5px;border-top-left-radius: 5px;">
            <br/><br/>
                <span class="text-left">
                    <h1>KIARENI ELCK SECONDARY SCHOOL</h1>
                    <h4>Official Transcript</h4>
                    <h6><?php echo $term.'/'.$exam.'/'.date("Y");?></h6>
                </span>
                <span class="text-center">
                    <a class="text-right">
                        <?=img(base_url('uploads/images/'.$student->photo))?>
                    </a>
                    <strong><h4><?=$student->name?></h4>
                    <p><?=$this->lang->line("student_classes")." ".$classess->classes?></p>
                    <p><?="Adm NO: ".$student->roll?></p></strong>
                </span>
            </div>
            <div class="panel-body profile-view-dis">
                <div class="col-lg-12">
                    <div id="hide-table">
                        <?php

                                echo "<table class=\"table table-striped table-bordered table-condensed\">";
                                    
                                        echo "<caption>";
                                            //echo "<h3>". $exam->exam."</h3>";
                                        echo "</caption>";

                                        echo "<thead>";
                                            echo "<tr>";
                                                echo "<th><b>";
                                                    echo $this->lang->line("transcripts_subject_code");
                                                echo "</b></th>";
                                                echo "<th><b/>";
                                                    echo $this->lang->line("transcripts_subject_name");
                                                echo "</b></th>";
                                                if(count($grades)) {
                                                    echo "<th><b>";
                                                        echo $this->lang->line("transcripts_score");
                                                    echo "</b></th>";
                                                    echo "<th><b>";
                                                        echo $this->lang->line("transcripts_remarks");
                                                    echo "</b></th>";
                                                }
                                                echo "<th><b>";
                                                    echo $this->lang->line("transcripts_initials");
                                                echo "</b></th>";
                                            echo "</tr>";
                                        echo "</thead>";

                                echo "<tbody>";
                                if ($ttmark != $student->tp) {
                                        $ttmark=$student->tp;
                                        $i++;
                                        if ($j>0) {
                                            $i+=$j;
                                            $j=0;
                                        }
                                    }else{
                                        $j++;
                                    }
                                foreach ($subjects as $subject ) {
                                    echo "<tr>";
                                    
                                    $mark=$this->ranking_m->get_subjects_marks($student->studentID,$examID,$subject->subjectID);
                                    foreach ($mark as $mark) {                   
                                        if (empty($mark->mark)) {
                                            echo "-";
                                        }else if($mark->mark !="X" && $mark->mark !="Y" && $mark->mark !="x" && $mark->mark !="y" && $mark->mark !="n" && $mark->mark !="N"){
                                            $score=round((($mark->mark)/($mark->out_of))*100,0);
                                            if(count($grades)) {
                                                foreach ($grades as $grade) {
                                                    if($grade->gradefrom <= $score && $grade->gradeupto >= $score) {
                                                            echo "<td data-title='".$this->lang->line('transcripts_subject_code')."'>";
                                                                echo $subject->subject_code;
                                                            echo "</td>";
                                                            echo "<td data-title='".$this->lang->line('transcripts_subject')."'>";
                                                                echo $subject->fullsubjectname;
                                                            echo "</td>";

                                                            echo "<td data-title='".$this->lang->line('transcripts_grade')."'>";
                                                                echo $score.' '.$grade->grade;
                                                            echo "</td>";
                                                            if ($subject->subject_code ==102) {
                                                               echo "<td data-title='".$this->lang->line('transcripts_'.$subject->subject_code)."'>";
                                                                    echo $this->lang->line('transcripts_'.$grade->note);
                                                                echo "</td>"; 
                                                            }else{
                                                                echo "<td data-title='".$this->lang->line('transcripts_highest_mark')."'>";
                                                                echo $grade->note;
                                                            echo "</td>";
                                                            }
                                                            echo "<td data-title='".$this->lang->line('transcripts_highest_mark')."'><i>";
                                                                echo $subject->initials;
                                                            echo "</i></td>";
                                                    }
                                                }
                                            }
                                        }else if($mark->mark ==="n" || $mark->mark ==="N"){

                                        }else{
                                            echo strtoupper($mark->mark);
                                        }
                                    }
                                    // if(count($grades)) {
                                    //     foreach ($grades as $grade) {
                                    //         if($grade->gradefrom <= $transcripts->mark && $grade->gradeupto >= $transcripts->mark) {
                                    //             echo "<td data-title='".$this->lang->line('transcripts_grade')."'>";
                                    //                 echo $transcripts->mark.' '.$grade->grade;
                                    //             echo "</td>";
                                    //             if ($transcripts->sc ==102) {
                                    //                echo "<td data-title='".$this->lang->line('transcripts_'.$transcripts->sc)."'>";
                                    //                     echo $this->lang->line('transcripts_'.$grade->note);
                                    //                 echo "</td>"; 
                                    //             }else{
                                    //                 echo "<td data-title='".$this->lang->line('transcripts_highest_mark')."'>";
                                    //                 echo $grade->note;
                                    //             echo "</td>";
                                    //             }
                                                
                                    //             break;
                                    //         }
                                    //     }
                                    // }
                                    echo "</tr>";
                                }
                                echo "<tr>";
                                    echo "<td colspan=\"2\" class=\"text-right\">";
                                        echo "<b>Exam summary (".$term.'/'.$exam.")</b>";
                                    echo "</td>";
                                     $avgpoints=round(($student->tp/7),0);
                                        if(count($grades)) {
                                            foreach ($grades as $grade) {
                                                if($grade->point <= $avgpoints && $grade->point >= $avgpoints) {
                                                        $sgrade=$grade->grade;
                                                }
                                            }
                                        }
                                    echo "<td colspan=\"3\" >";
                                        echo "<span><b>Total Points:</b> ".$student->tp."</span> <span> &nbsp  &nbsp  &nbsp&nbsp  &nbsp  &nbsp<b>Mean Grade:</b> ".$student->grade."</span> <span>  &nbsp  &nbsp &nbsp  &nbsp  &nbsp &nbsp <b>Overall Position:</b> ".$i.'/'.$totalstudents;
                                    echo "</span></td>";
                                echo "</tr>";
								echo "</tbody>";
                    echo "</table>";
                ?>
                <?php
					if($student->se == 7){
						echo "<br/><br/><br/>";
					}else if($student->se == 6){
                        echo "<br/><br/><br/><br/><br/>";
                    }else if($student->se == 5){
                        echo "<br/><br/><br/><br/><br/><br/>";
                    }else if($student->se == 4){
                        echo "<br/><br/><br/><br/><br/><br/><br/>";
                    }else if($student->se == 3){
                        echo "<br/><br/><br/><br/><br/><br/><br/><br/><br/>";
                    }else if($student->se == 2){
                        echo "<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>";
                    }else if($student->se == 1){
                       echo "<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>";
                    }else if($student->se== 0){
                        echo "<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>";
                    }else{
                        echo "<br/><br/>";
                    }
                    ?>
                    <br/><br/><br/><br/><br/>                           
                    <p class="pull-left"><strong>Signature ----------------------------------------------------------</strong></p>
                    <p class="pull-right"><strong><?php echo date("D M Y");?></strong></p><br/>
                    <br/><br/><br/><br/>
                    <p class="text-center"><i>*this is not final document certificate but summary of student score on specific Exam done.</i></p><br/><br/>
                   
                
            </div>
        </div>
        </div>
							</section><?php }
                            }else{?>
							<section class="panel" style="border-bottom:1px solid green;border-bottom-right-radius: 5px;border-bottom-left-radius: 5px;">
            <div class="profile-view-head" style="border-top:5px solid green;border-top-right-radius: 5px;border-top-left-radius: 5px;">
            <br/><br/>
                <span class="text-left">
                    <h1>KIARENI ELCK SECONDARY SCHOOL</h1>
                    <h4>Official Transcript</h4>
                    <h6><?php echo $term.'/'.$exam.'/'.date("Y");?></h6>
                </span>
                <span class="text-center">
                    <a class="text-right">
                        <?=img(base_url('uploads/images/'.$student->photo))?>
                    </a>
                    <strong><h4><?=$student->name?></h4>
                    <p><?=$this->lang->line("student_classes")." ".$classess->classes?></p>
                    <p><?="Adm NO: ".$student->roll?></p></strong>
                </span>
            </div>
            <div class="panel-body profile-view-dis">
                <div class="col-lg-12">
                    <div id="hide-table">
                        <?php

                                echo "<table class=\"table table-striped table-bordered table-condensed\">";
                                    
                                        echo "<caption>";
                                            //echo "<h3>". $exam->exam."</h3>";
                                        echo "</caption>";

                                        echo "<thead>";
                                            echo "<tr>";
                                                echo "<th><b>";
                                                    echo $this->lang->line("transcripts_subject_code");
                                                echo "</b></th>";
                                                echo "<th><b/>";
                                                    echo $this->lang->line("transcripts_subject_name");
                                                echo "</b></th>";
                                                if(count($grades)) {
                                                    echo "<th><b>";
                                                        echo $this->lang->line("transcripts_score");
                                                    echo "</b></th>";
                                                    echo "<th><b>";
                                                        echo $this->lang->line("transcripts_remarks");
                                                    echo "</b></th>";
                                                }
                                                echo "<th><b>";
                                                    echo $this->lang->line("transcripts_initials");
                                                echo "</b></th>";
                                            echo "</tr>";
                                        echo "</thead>";

                                echo "<tbody>";
                                if ($ttmark != round(($student->stotal),0)) {
                                        $ttmark=round(($student->stotal),0);
                                        $i++;
                                        if ($j>0) {
                                            $i+=$j;
                                            $j=0;
                                        }
                                    }else{
                                        $j++;
                                    }
                                foreach ($subjects as $subject ) {
                                    echo "<tr>";
                                    
                                    $mark=$this->ranking_m->get_subjects_marks($student->studentID,$examID,$subject->subjectID);
                                    foreach ($mark as $mark) {                   
                                        if (empty($mark->mark)) {
												echo "<td data-title='".$this->lang->line('transcripts_subject_code')."'>";
													echo $subject->subject_code;
												echo "</td>";
												echo "<td data-title='".$this->lang->line('transcripts_subject')."'>";
													echo $subject->fullsubjectname;
												echo "</td>";

												echo "<td data-title='".$this->lang->line('transcripts_grade')."'>";
													echo '-';
												echo "</td>";
												echo "<td data-title='".$this->lang->line('transcripts_highest_mark')."'>";
													echo 'Missing Marks';
												echo "</td>";
												echo "<td data-title='".$this->lang->line('transcripts_highest_mark')."'><i>";
													echo $subject->initials;
												echo "</i></td>";
                                        }else if($mark->mark !="X" && $mark->mark !="Y" && $mark->mark !="x" && $mark->mark !="y" && $mark->mark !="n" && $mark->mark !="N"){
                                            $score=round((($mark->mark)/($mark->out_of))*100,0);
                                            if(count($grades)) {
                                                foreach ($grades as $grade) {
                                                    if($grade->gradefrom <= $score && $grade->gradeupto >= $score) {
                                                            echo "<td data-title='".$this->lang->line('transcripts_subject_code')."'>";
                                                                echo $subject->subject_code;
                                                            echo "</td>";
                                                            echo "<td data-title='".$this->lang->line('transcripts_subject')."'>";
                                                                echo $subject->fullsubjectname;
                                                            echo "</td>";

                                                            echo "<td data-title='".$this->lang->line('transcripts_grade')."'>";
                                                                echo $score.' '.$grade->grade;
                                                            echo "</td>";
                                                            if ($subject->subject_code ==102) {
                                                               echo "<td data-title='".$this->lang->line('transcripts_'.$subject->subject_code)."'>";
                                                                    echo $this->lang->line('transcripts_'.$grade->note);
                                                                echo "</td>"; 
                                                            }else{
                                                                echo "<td data-title='".$this->lang->line('transcripts_highest_mark')."'>";
                                                                echo $grade->note;
                                                            echo "</td>";
                                                            }
                                                            echo "<td data-title='".$this->lang->line('transcripts_highest_mark')."'><i>";
                                                                echo $subject->initials;
                                                            echo "</i></td>";
                                                    }
                                                }
                                            }
                                        }else if($mark->mark ==="n" || $mark->mark ==="N"){
                                            echo "<td data-title='".$this->lang->line('transcripts_subject_code')."'>";
													echo $subject->subject_code;
												echo "</td>";
												echo "<td data-title='".$this->lang->line('transcripts_subject')."'>";
													echo $subject->fullsubjectname;
												echo "</td>";

												echo "<td data-title='".$this->lang->line('transcripts_grade')."'>";
													echo '';
												echo "</td>";
												echo "<td data-title='".$this->lang->line('transcripts_highest_mark')."'>";
													echo 'Not taking';
												echo "</td>";
												echo "<td data-title='".$this->lang->line('transcripts_highest_mark')."'><i>";
													echo $subject->initials;
												echo "</i></td>";
                                        }else{
                                            echo "<td data-title='".$this->lang->line('transcripts_subject_code')."'>";
													echo $subject->subject_code;
												echo "</td>";
												echo "<td data-title='".$this->lang->line('transcripts_subject')."'>";
													echo $subject->fullsubjectname;
												echo "</td>";

												echo "<td data-title='".$this->lang->line('transcripts_grade')."'>";
													echo 'X';
												echo "</td>";
												echo "<td data-title='".$this->lang->line('transcripts_highest_mark')."'>";
													echo 'Not Done';
												echo "</td>";
												echo "<td data-title='".$this->lang->line('transcripts_highest_mark')."'><i>";
													echo $subject->initials;
												echo "</i></td>";
                                        }
                                    }
                                    // if(count($grades)) {
                                    //     foreach ($grades as $grade) {
                                    //         if($grade->gradefrom <= $transcripts->mark && $grade->gradeupto >= $transcripts->mark) {
                                    //             echo "<td data-title='".$this->lang->line('transcripts_grade')."'>";
                                    //                 echo $transcripts->mark.' '.$grade->grade;
                                    //             echo "</td>";
                                    //             if ($transcripts->sc ==102) {
                                    //                echo "<td data-title='".$this->lang->line('transcripts_'.$transcripts->sc)."'>";
                                    //                     echo $this->lang->line('transcripts_'.$grade->note);
                                    //                 echo "</td>"; 
                                    //             }else{
                                    //                 echo "<td data-title='".$this->lang->line('transcripts_highest_mark')."'>";
                                    //                 echo $grade->note;
                                    //             echo "</td>";
                                    //             }
                                                
                                    //             break;
                                    //         }
                                    //     }
                                    // }

                                    echo "</tr>";
                                }
                                echo "<tr>";
                                    echo "<td colspan=\"2\" class=\"text-right\">";
                                        echo "<b>Exam summary (".$term.'/'.$exam.")</b>";
                                    echo "</td>";
									$totalmarks=round(($student->stotal),0);
                                     $avgpoints=round(($totalmarks/11),0);
                                        if(count($grades)) {
                                            foreach ($grades as $grade) {
                                                if($grade->gradefrom <= $avgpoints && $grade->gradeupto >= $avgpoints) {
                                                        $sgrade=$grade->grade;
                                                }
                                            }
                                        }
                                    echo "<td colspan=\"3\" >";
                                        echo "<span><b>Total Marks:</b> ".$totalmarks."</span> <span> &nbsp  &nbsp  &nbsp&nbsp  &nbsp  &nbsp<b>Mean Grade:</b> ".$sgrade."</span> <span>  &nbsp  &nbsp &nbsp  &nbsp  &nbsp &nbsp <b>Overall Position:</b> ".$i.'/'.$totalstudents;
                                    echo "</span></td>";
                                echo "</tr>";
								echo "</tbody>";
                    echo "</table>";
                ?>
                <br/><br/>
                    <p class="pull-left"><strong>Signature ----------------------------------------------------------</strong></p>
                    <p class="pull-right"><strong><?php echo date("D M Y");?></strong></p><br/><br/> <br/>
                    
                    <p class="text-center"><i>*this is not final document certificate but summary of student score on specific Exam done.</i></p>
                    <?php
                ?>
                
            </div>
        </div>
        </div>
    </section><?php
                            }
	
}
    ?>
</div>
<?php

} ?>

<?php if($usertype == "Admin" || $usertype == "Teacher") { ?>
    <script language="javascript" type="text/javascript">
        function printDiv(divID) {
            //Get the HTML of div
            var divElements = document.getElementById(divID).innerHTML;
            //Get the HTML of whole page
            var oldPage = document.body.innerHTML;

            //Reset the page's HTML with div's HTML only
            document.body.innerHTML =
              "<html><head><title></title></head><body>" +
              divElements + "</body>";

            //Print Page
            window.print();

            //Restore orignal HTML
            document.body.innerHTML = oldPage;
        }
        function closeWindow() {
            location.reload();
        }


        function check_email(email) {
            var status = false;
            var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
            if (email.search(emailRegEx) == -1) {
                $("#to_error").html('');
                $("#to_error").html("<?=$this->lang->line('mail_valid')?>").css("text-align", "left").css("color", 'red');
            } else {
                status = true;
            }
            return status;
        }

        $("#send_pdf").click(function(){
            var to = $('#to').val();
            var subject = $('#subject').val();
            var message = $('#message').val();
            var id = "<?=$student->studentID;?>";
            var set = "<?=$set;?>";
            var error = 0;

            if(to == "" || to == null) {
                error++;
                $("#to_error").html("");
                $("#to_error").html("<?=$this->lang->line('mail_to')?>").css("text-align", "left").css("color", 'red');
            } else {
                if(check_email(to) == false) {
                    error++
                }
            }

            if(subject == "" || subject == null) {
                error++;
                $("#subject_error").html("");
                $("#subject_error").html("<?=$this->lang->line('mail_subject')?>").css("text-align", "left").css("color", 'red');
            } else {
                $("#subject_error").html("");
            }

            if(error == 0) {
                $.ajax({
                    type: 'POST',
                    url: "<?=base_url('transcripts/send_mail')?>",
                    data: 'to='+ to + '&subject=' + subject + "&id=" + id+ "&message=" + message+ "&set=" + set,
                    dataType: "html",
                    success: function(data) {
                        location.reload();
                    }
                });
            }
        });
    </script>
    <?php } ?>
<?php
    $usertype = $this->session->userdata('usertype');
    if($usertype == "Parent" || $usertype == "Student") {
?>
    <script language="javascript" type="text/javascript">
        var url = window.location.href;
        $("a[href$='"+url+"']").parent().addClass('active');
    </script>

<?php } ?>
