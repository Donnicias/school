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
                <button class="btn-cs btn-sm-cs" data-toggle="modal" data-target="#mail"><span class="fa fa-envelope-o"></span> <?=$this->lang->line('mail')?></button>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li><a href="<?=base_url("transcripts/index/$set")?>"><?=$this->lang->line('menu_transcripts')?></a></li>
                    <li class="active"><?=$this->lang->line('view')?></li>
                </ol>
            </div>
        </div>
    </div>

    <?php } ?>
<style>
#tb{
    background: -webkit-linear-gradient(180deg,#2F4F4F, lightgray)transparent; /* For Safari 5.1 to 6.0 */
  background: -o-linear-gradient(180deg,#2F4F4F, lightgray)transparent; /* For Opera 11.1 to 12.0 */
  background: -moz-linear-gradient(180deg,#2F4F4F, lightgray)transparent; /* For Firefox 3.6 to 15 */
  background: linear-gradient(180deg,#2F4F4F, lightgray)transparent; /* Standard syntax */
}
</style>
    <div id="printablediv">
        <section class="panel" style="border-bottom:1px solid green;border-bottom-right-radius: 5px;border-bottom-left-radius: 5px;">
            <div class="profile-view-head" id="tb" style="border-top:5px solid green;border-top-right-radius: 5px;border-top-left-radius: 5px;">
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
                    <p><?=$this->lang->line("student_classes")." ".$classes->classes?></p>
                    <p><?="Adm NO: ".$student->roll?></p></strong>
                </span>
            </div>
            <div class="panel-body profile-view-dis">
                    <?php if($transcriptss && $exams) { ?>
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
                                                            echo "<th><b>";
                                                                echo $this->lang->line("transcripts_initials");
                                                            echo "</b></th>";
                                                        }
                                                    echo "</tr>";
                                                echo "</thead>";

                                        echo "<tbody>";
                                    if ($classID==13 || $classID==14) {
                                        foreach ($transcriptss as $transcripts) {
                                        echo "<tr>";
                                            echo "<td data-title='".$this->lang->line('transcripts_subject_code')."'>";
                                                echo $transcripts->sc;
                                            echo "</td>";
                                            echo "<td data-title='".$this->lang->line('transcripts_subject')."'>";
                                                echo $transcripts->subject;
                                            echo "</td>";
                                            if(count($grades)) {
                                                foreach ($grades as $grade) {
                                                    if($grade->gradefrom <= $transcripts->mark && $grade->gradeupto >= $transcripts->mark) {
                                                        echo "<td data-title='".$this->lang->line('transcripts_grade')."'>";
                                                            echo $transcripts->mark.' '.$grade->grade;
                                                        echo "</td>";
                                                        if ($transcripts->sc ==102) {
                                                           echo "<td data-title='".$this->lang->line('transcripts_'.$transcripts->sc)."'>";
                                                                echo $this->lang->line('transcripts_'.$grade->note);
                                                            echo "</td>"; 
                                                        }else{
                                                            echo "<td data-title='".$this->lang->line('transcripts_highest_mark')."'>";
                                                            echo $grade->note;
                                                        echo "</td>";
                                                        }
                                                        break;
                                                    }
                                                }
                                            }
                                            echo "<td data-title='".$this->lang->line('transcripts_highest_mark')."'><i>";
                                                echo $transcripts->initials;
                                            echo "</i></td>";
                                            echo "</tr>";
                                        }
                                        echo "<tr>";
                                            echo "<td colspan=\"2\" class=\"text-right\">";
                                                echo "<b>Exam summary (".$term.'/'.$exam.")</b>";
                                            echo "</td>";
                                             $avgpoints=round(($totalpoints/7),0);
                                                if(count($grades)) {
                                                    foreach ($grades as $grade) {
                                                        if($grade->point <= $avgpoints && $grade->point >= $avgpoints) {
                                                                $sgrade=$grade->grade;
                                                        }
                                                    }
                                                }
                                            echo "<td colspan=\"3\" >";
                                                echo "<span><b>Total Points:</b> ".$totalpoints."</span> <span> &nbsp  &nbsp  &nbsp&nbsp  &nbsp  &nbsp<b>Mean Grade:</b> ".$sgrade."</span> <span>  &nbsp  &nbsp &nbsp  &nbsp  &nbsp &nbsp <b>Overall Position:</b> ".$position.'/'.$totalstudents;
                                            echo "</span></td>";
                                        echo "</tr>";
                                    }else{
                                        foreach ($transcriptss as $transcripts) {
                                                echo "<tr>";
                                                    echo "<td data-title='".$this->lang->line('transcripts_subject_code')."'>";
                                                        echo $transcripts->sc;
                                                    echo "</td>";
                                                    echo "<td data-title='".$this->lang->line('transcripts_subject')."'>";
                                                        echo $transcripts->subject;
                                                    echo "</td>";
                                                    if(count($grades)) {
                                                        foreach ($grades as $grade) {
                                                            if($grade->gradefrom <= $transcripts->mark && $grade->gradeupto >= $transcripts->mark) {
                                                                echo "<td data-title='".$this->lang->line('transcripts_grade')."'>";
                                                                    echo $transcripts->mark.' '.$grade->grade;
                                                                echo "</td>";
                                                                if ($transcripts->sc ==102) {
                                                                   echo "<td data-title='".$this->lang->line('transcripts_'.$transcripts->sc)."'>";
                                                                        echo $this->lang->line('transcripts_'.$grade->note);
                                                                    echo "</td>"; 
                                                                }else{
                                                                    echo "<td data-title='".$this->lang->line('transcripts_highest_mark')."'>";
                                                                    echo $grade->note;
                                                                echo "</td>";
                                                                }
                                                                
                                                                break;
                                                            }
                                                        }
                                                    }
                                                    echo "<td data-title='".$this->lang->line('transcripts_highest_mark')."'><i>";
                                                        echo $transcripts->initials;
                                                    echo "</i></td>";
                                                echo "</tr>";
                                        }
                                         echo "<tr>";
                                            echo "<td colspan=\"2\" class=\"text-right\">";
                                                echo "<b>Exam summary (".$term.'/'.$exam.")</b>";
                                            echo "</td>";
                                             $avgpoints=round(($totalpoints/11),0);
                                                if(count($grades)) {
                                                    foreach ($grades as $grade) {
                                                        if($grade->gradefrom <= $avgpoints && $grade->gradeupto >= $avgpoints) {
                                                                $ssgrade=$grade->grade;
                                                        }
                                                    }
                                                }
                                            echo "<td colspan=\"3\" >";
                                                echo "<span><b>Total Marks: ".$totalpoints."/1100</b></span> <span> &nbsp  &nbsp  &nbsp&nbsp  &nbsp  &nbsp<b>Mean Grade: ".$ssgrade."</b></span> <span>  &nbsp  &nbsp &nbsp  &nbsp  &nbsp &nbsp <b>Overall Position: ".$position.'/'.$totalstudents;
                                            echo "</b></span></td>";
                                        echo "</tr>";
                                    }
                                echo "</tbody>";
                            echo "</table>";
                        ?>
                        <?php
                        if ($classID==13 || $classID==14) {
                            ?>
                            <br/><br/><br/><br/><br/>                           
                            <p class="pull-left"><strong>Signature ----------------------------------------------------------</strong></p>
                            <p class="pull-right"><strong><?php echo date("D M Y");?></strong></p><br/>
                            <br/><br/><br/><br/>
                            <p class="text-center"><i>*this is not final document certificate but summary of student score on specific Exam done.</i></p><br/><br/>
                            <?php
                        }else{
                            ?>
                            <br/><br/><br/><br/> 
                            <p class="pull-left"><strong>Signature ----------------------------------------------------------</strong></p>
                            <p class="pull-right"><strong><?php echo date("D M Y");?></strong></p><br/>
                            <br/>
                            <p class="text-center"><i>*this is not final document certificate but summary of student score on specific Exam done.</i></p>
                            <?php
                        }
                        ?>
                        
                    </div>
                </div>
            <?php } ?>
        </div>
    </section>
</div>
<!-- email modal starts here -->
<form class="form-horizontal" role="form" action="<?=base_url('teacher/send_mail');?>" method="post">
    <div class="modal fade" id="mail">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"><?=$this->lang->line('mail')?></h4>
            </div>
            <div class="modal-body">

                <?php
                    if(form_error('to'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                ?>
                    <label for="to" class="col-sm-2 control-label">
                        <?=$this->lang->line("to")?>
                    </label>
                    <div class="col-sm-6">
                        <input type="email" class="form-control" id="to" name="to" value="<?=set_value('to')?>" >
                    </div>
                    <span class="col-sm-4 control-label" id="to_error">
                    </span>
                </div>

                <?php
                    if(form_error('subject'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                ?>
                    <label for="subject" class="col-sm-2 control-label">
                        <?=$this->lang->line("subject")?>
                    </label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="subject" name="subject" value="<?=set_value('subject')?>" >
                    </div>
                    <span class="col-sm-4 control-label" id="subject_error">
                    </span>

                </div>

                <?php
                    if(form_error('message'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                ?>
                    <label for="message" class="col-sm-2 control-label">
                        <?=$this->lang->line("message")?>
                    </label>
                    <div class="col-sm-6">
                        <textarea class="form-control" id="message" style="resize: vertical;" name="message" value="<?=set_value('message')?>" ></textarea>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                <input type="button" id="send_pdf" class="btn btn-success" value="<?=$this->lang->line("send")?>" />
            </div>
        </div>
      </div>
    </div>
</form>
<!-- email end here -->

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
