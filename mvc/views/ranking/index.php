
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-bar-chart"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active">Ranking</li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <?php 
                    $usertype = $this->session->userdata("usertype");
                    if($usertype == "Admin" || $usertype == "Teacher") {
                        ?>

                <div class="col-sm-6 col-sm-offset-3 list-group">
                    <div class="list-group-item list-group-item-warning text-center">
                        <a href="<?=base_url("ranking/index")?>"><input type="button" class="btn btn-success" style="margin-bottom:0px" value="Go Back" ></a>
                    </div>
                </div>

                <?php if(count($results) > 0 ) { 

                    ?>

                    <div class="col-sm-12">

                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#all" aria-expanded="true"><?=$this->lang->line("ranking_all_examschedule")?></a></li>
                                <?php foreach ($sections as $key => $section) {
                                    echo '<li class=""><a data-toggle="tab" href="#'. $section->sectionID .'" aria-expanded="false">'. $this->lang->line("student_section")." ".$section->section. " ( ". $section->category." )".'</a></li>';
                                } ?>
                            </ul>

                            <div class="tab-content">
                                <div id="all" class="tab-pane active">

                                    <button class="btn-cs btn-sm-cs" onclick="javascript:printDiv('hide-table')"><span class="fa fa-print"></span> <?=$this->lang->line('print')?> </button>
                                    <?php
                                     //echo btn_add_pdf('student/print_preview/'.$student->studentID."/".$set, $this->lang->line('pdf_preview'))
                                    ?>
                                    <div id="hide-table">
                                    <h2 class="text-center"><?php //echo $classesID;
                                    $class = $this->db->select('classes')->from('classes')->where('classesID', $classesID)->limit(1)->get()->row_array();
                                    echo $class['classes'];
                                    ?></h2>
                                    <h2 class="text-center"><?php 
                                    $exam = $this->db->select('exam')->from('exam')->where('examID', $examID)->limit(1)->get()->row_array();
                                    echo $exam['exam'].' Exam Results';
                                    ?></h2>
                                        <table id="#example1_wrapper" class="table table-striped table-bordered table-hover dataTable no-footer">
                                            <thead >
                                                <tr>
                                                    <th><?=$this->lang->line('position')?></th>
                                                    <th><?=$this->lang->line('ranking_student')?></th>
                                                     <?php
                                                        foreach ($subjects as $subject ) {?>
                                                            <th><?php echo $subject->subject; ?></th><?php
                                                            //echo $examID;
                                                        }
                                                    ?>
                                                    <th><?=$this->lang->line('ranking_total')?></th>
                                                    <th><?=$this->lang->line('ranking_average')?></th>
                                                    <th><?=$this->lang->line('ranking_grade')?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if(count($results)) {
                                                    $i = 0; 
                                                    $j = 0; 
                                                    $tmark=false;
                                                    $sranking=$this->ranking_m->get_students_average($examID,$classesID);
                                                    foreach($sranking as $student) { 
                                                        if ($tmark != $student->stotal) {
                                                            $tmark=$student->stotal;
                                                            $i++;
                                                            if ($j>0) {
                                                                $i+=$j;
                                                                $j=0;
                                                            }
                                                        }else{
                                                            $j++;
                                                        }
                                                    ?>
                                                    <tr>
                                                
                                                    <td data-title="<?=$this->lang->line('slno')?>">
                                                        <span class="badge"> <?php echo $i; ?></span>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('ranking_name')?>">
                                                        <?php echo $student->name; ?>
                                                    </td>
                                                    
                                                    <?php
                                                        foreach ($subjects as $subject ) {
                                                            $mark=$this->ranking_m->get_subject_mark($student->studentID,$examID,$subject->subjectID);
                                                            ?>
                                                            <td data-title="<?=$this->lang->line('ranking_section');?>">
                                                                <?php 

                                                                foreach ($mark as $mark) {
                                                                    
                                                                    if (empty($mark->mark)) {
                                                                        echo "-";
                                                                    }else{
                                                                        $score=round((($mark->mark)/($mark->out_of))*100,0);
                                                                        if(count($grades)) {
                                                                            foreach ($grades as $grade) {
                                                                                if($grade->gradefrom <= $score && $grade->gradeupto >= $score) {
                                                                                        echo $score.' '.$grade->grade;
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                                 ?>
                                                            </td>

                                                       <?php }?>
                                                        <td data-title="<?=$this->lang->line('ranking_section');?>">
                                                            <?php 
                                                                echo $student->stotal;
                                                             ?>
                                                        </td>
                                                        <?php
                                                        $student_avg=$this->ranking_m->get_student_average($student->studentID,$examID,$classesID);
                                                        if(count($grades)) {
                                                            foreach ($grades as $grade) {
                                                                if($grade->gradefrom <= $student->avg && $grade->gradeupto >= $student->avg) {
                                                                    echo "<td data-title='".$this->lang->line('mark_grade')."'>";
                                                                        echo number_format((float)($student->avg),2,'.','');
                                                                    echo "</td>";
                                                                    echo "<td data-title='".$this->lang->line('mark_grade')."'>";
                                                                        echo $grade->grade;
                                                                    echo "</td>";
                                                                    break;
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                    </tr>

                                                <?php }} 

                                                $subjects_no=$this->ranking_m->get_subject_count($classesID);
                                                $no=5;
                                                foreach ($subjects_no as $sub) {
                                                   $no+=$sub->subjectID;
                                                }
                                                ?>
                                                <!-- <tr><td colspan="<?php //echo $no;?>"></td></tr> -->
                                                <?php
                                                if (count($students)>0) {
                                                    
                                                ?>
                                                <tr>
                                                    <td colspan="2" class="text-right"><strong>Totals</strong></td>
                                                    <?php
                                                    foreach ($subjects as $subject ) {
                                                        ?>
                                                        <td data-title="<?=$this->lang->line('ranking_section');?>">
                                                            <?php 
                                                            $sub_totall=0;
                                                            foreach ($students as $student) {
                                                                $sub_total=$this->ranking_m->get_subject_total($student->studentID,$examID,$subject->subjectID,$classesID);
                                                                foreach ($sub_total as $sub_total) {
                                                                        $sub_totall+=$sub_total->mark;
                                                                    }
                                                            }
                                                            echo $sub_totall;
                                                             ?>
                                                        </td>
                                                        <?php
                                                    }
                                                    ?>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" class="text-right"><strong>Averages</strong></td>
                                                    <?php
                                                    foreach ($subjects as $subject ) {
                                                       ?>
                                                        <td data-title="<?=$this->lang->line('ranking_section');?>">
                                                            <?php 
                                                            $sub_avgg=0;
                                                            $k=0;
                                                            foreach ($students as $student) {
                                                                 $sub_avg=$this->ranking_m->get_subject_average($student->studentID,$examID,$subject->subjectID,$classesID);
                                                                foreach ($sub_avg as $sub_avg) {
                                                                        $sub_avgg+=$sub_avg->mark;
                                                                    }
                                                                    $k++;
                                                            }
                                                            echo number_format((float)($sub_avgg/$k),2,'.','');
                                                             ?>
                                                        </td>
                                                        <?php
                                                    }
                                                    ?>
                                                </tr>
                                                <?php
                                            }else{
                                                    echo '<tr><td colspan="'.$no.'">No data is available.</td></tr>';
                                                    }?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <?php foreach ($sections as $section) {
                                    $ssubjects_no=$this->ranking_m->get_subject_count($classesID);
                                                $sno=5+count($ssubjects_no);
                                                
                                    ?>
                                    
                                        <div id="<?=$section->sectionID?>" class="tab-pane">
                                        <button class="btn-cs btn-sm-cs" onclick="javascript:printDiv('hide-table_<?=$section->sectionID?>')"><span class="fa fa-print"></span> <?=$this->lang->line('print')?> </button>
                                        <?php
                                         //echo btn_add_pdf('student/print_preview/'.$student->studentID."/".$set, $this->lang->line('pdf_preview'))
                                        ?>
                                        <div id="hide-table_<?=$section->sectionID?>">
                                        <h2 class="text-center"><?php //echo $classesID;
                                        $sect = $this->db->select('section')->from('section')->where('sectionID', $section->sectionID)->limit(1)->get()->row_array();
                                        echo $sect['section'];
                                        ?></h2>
                                        <h2 class="text-center"><?php 
                                        $exam = $this->db->select('exam')->from('exam')->where('examID', $examID)->limit(1)->get()->row_array();
                                        echo $exam['exam'].' Exam Results';
                                        ?></h2>
                                        <table id="example2" class="table table-striped table-bordered table-hover dataTable no-footer">
                                            <thead>
                                                <tr>
                                                    <th><?=$this->lang->line('position')?></th>
                                                    <th><?=$this->lang->line('ranking_student')?></th>
                                                     <?php
                                                        foreach ($subjects as $subject ) {?>
                                                            <th><?php echo $subject->subject; ?></th><?php
                                                            //echo $examID;
                                                        }
                                                    ?>
                                                    <th><?=$this->lang->line('ranking_total')?>
                                                    <th><?=$this->lang->line('ranking_average')?>
                                                    <th><?=$this->lang->line('ranking_grade')?></th>
                                                    <?php if($usertype == "Admin") { ?>
                                                    <?php } ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 

                                                $sstudents=$this->ranking_m->get_sstudent($classesID,$section->sectionID,$examID);
                                                if(count($sstudents)) {
                                                    $i = 0; 
                                                    $j = 0; 
                                                    $tmark=false;
                                                    foreach($sstudents as $s_tudents) { 

                                                    if ($tmark != $s_tudents->stotal) {
                                                        $tmark=$s_tudents->stotal;
                                                        $i++;
                                                        if ($j>0) {
                                                            $i+=$j;
                                                            $j=0;
                                                        }
                                                    }else{
                                                        $j++;
                                                    }
                                                    ?>
                                                    <tr>
                                                
                                                    <td data-title="<?=$this->lang->line('slno')?>">
                                                        <span class="badge"> <?php echo $i; ?></span>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('ranking_name')?>">
                                                        <?php echo $s_tudents->name; ?>
                                                    </td>
                                                    
                                                    <?php
                                                        foreach ($subjects as $subject ) {
                                                            $mark=$this->ranking_m->get_subject_mark($s_tudents->studentID,$examID,$subject->subjectID);
                                                            ?>
                                                            <td data-title="<?=$this->lang->line('ranking_section');?>">
                                                                <?php 

                                                                foreach ($mark as $mark) {
                                                                    if (empty($mark->mark)) {
                                                                        echo "-";
                                                                    }else{
                                                                        echo $mark->mark;
                                                                    }
                                                                }
                                                                 ?>
                                                            </td>

                                                       <?php }

                                                       $total=$this->ranking_m->get_student_total($s_tudents->studentID,$examID,$classesID);?>
                                                        <td data-title="<?=$this->lang->line('ranking_section');?>">
                                                            <?php 
                                                            foreach ($total as $total) {
                                                                echo $total->mark;
                                                            }
                                                             ?>
                                                        </td>
                                                        <?php
                                                        $student_avg=$this->ranking_m->get_student_average($s_tudents->studentID,$examID,$classesID);
                                                        if(count($grades)) {
                                                            foreach ($grades as $grade) {
                                                                foreach ($student_avg as $avg) {
                                                                if($grade->gradefrom <= $avg->mark && $grade->gradeupto >= $avg->mark) {
                                                                    echo "<td data-title='".$this->lang->line('mark_grade')."'>";
                                                                        echo number_format((float)($avg->mark),2,'.','');
                                                                    echo "</td>";
                                                                    echo "<td data-title='".$this->lang->line('mark_grade')."'>";
                                                                        echo $grade->grade;
                                                                    echo "</td>";
                                                                    break;
                                                                }
                                                            }
                                                            }
                                                        }
                                                        ?>
                                                    </tr>

                                                <?php }} 

                                                $subjects_no=$this->ranking_m->get_subject_count($classesID);
                                                $no=5;
                                                foreach ($subjects_no as $sub) {
                                                   $no+=$sub->subjectID;
                                                }
                                                ?>
                                                <!-- <tr><td colspan="<?php //echo $no;?>"></td></tr> -->
                                                <?php
                                                if (count($sstudents)>0) {
                                                    
                                                ?>
                                                <tr>
                                                    <td colspan="2" class="text-right"><strong>Totals</strong></td>
                                                    <?php
                                                    foreach ($subjects as $subject ) {?>
                                                        <td data-title="<?=$this->lang->line('ranking_section');?>">
                                                            <?php 
                                                                $ssub_totall=0;
                                                                foreach ($sstudents as $student_s) {
                                                                    $sub_total=$this->ranking_m->get_ssubject_total($student_s->studentID,$examID,$subject->subjectID,$classesID);
                                                                    foreach ($sub_total as $sub_total) {
                                                                            $ssub_totall+=$sub_total->mark;
                                                                        }
                                                                }
                                                                echo $ssub_totall;
                                                                 ?>
                                                        </td>
                                                        <?php
                                                    }
                                                    ?>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" class="text-right"><strong>Averages</strong></td>
                                                    <?php
                                                    foreach ($subjects as $subject ) {?>
                                                        <td data-title="<?=$this->lang->line('ranking_section');?>">
                                                            <?php 
                                                                $sub_avgg=0;
                                                                $k=0;
                                                                foreach ($sstudents as $student) {
                                                                     $sub_avg=$this->ranking_m->get_ssubject_average($student->studentID,$examID,$subject->subjectID,$classesID);
                                                                    foreach ($sub_avg as $sub_avg) {
                                                                            $sub_avgg+=$sub_avg->mark;
                                                                        }
                                                                        $k++;
                                                                }
                                                                echo number_format((float)($sub_avgg/$k),2,'.','');
                                                                 ?>
                                                        </td>
                                                        <?php
                                                    }
                                                    ?>
                                                </tr>
                                                <?php
                                            }else{
                                                echo '<tr>
                                                    <td colspan="'.$sno.'"> No data available.</td>
                                                </tr>';
                                            }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>

                        </div> <!-- nav-tabs-custom -->
                    </div> <!-- col-sm-12 for tab -->

                <?php } else { 
                    ?>
                    <div class="col-sm-12">

                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#all" aria-expanded="true"><?=$this->lang->line("ranking_all_examschedule")?></a></li>
                            </ul>


                            <div class="tab-content">
                                <div id="all" class="tab-pane active">
                                    <div id="hide-table">
                                        <table id="example3" class="table table-striped table-bordered table-hover dataTable no-footer">
                                            <thead>
                                                <tr>
                                                    <th><?=$this->lang->line('slno')?></th>
                                                    <th><?=$this->lang->line('ranking_student')?></th>
                                                     <?php
                                                        foreach ($subjects as $subject ) {?>
                                                            <th><?php echo $subject->subject; ?></th><?php
                                                            //echo $examID;
                                                        }
                                                    ?>
                                                    <th><?=$this->lang->line('ranking_total')?>
                                                    <th><?=$this->lang->line('ranking_average')?>
                                                    <th><?=$this->lang->line('ranking_grade')?></th>
                                                    <?php if($usertype == "Admin") { ?>
                                                    <?php } ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if(count($results)) {$i = 1; foreach($students as $student) { ?>
                                                    <tr>
                                                
                                                    <td data-title="<?=$this->lang->line('slno')?>">
                                                        <span class="badge"> <?php echo $i; ?></span>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('ranking_name')?>">
                                                        <?php echo $student->name; ?>
                                                    </td>
                                                    
                                                    <?php
                                                        foreach ($subjects as $subject ) {
                                                            $mark=$this->ranking_m->get_subject_mark($student->studentID,$examID,$subject->subjectID);
                                                            ?>
                                                            <td data-title="<?=$this->lang->line('ranking_section');?>">
                                                                <?php 

                                                                foreach ($mark as $mark) {
                                                                    echo $mark->mark;
                                                                }
                                                                 ?>
                                                            </td>

                                                       <?php }

                                                       $total=$this->ranking_m->get_student_total($student->studentID,$examID,$classesID);?>
                                                        <td data-title="<?=$this->lang->line('ranking_section');?>">
                                                            <?php 
                                                            foreach ($total as $total) {
                                                                echo $total->mark;
                                                            }
                                                             ?>
                                                        </td>
                                                        <?php
                                                        $student_avg=$this->ranking_m->get_student_average($student->studentID,$examID,$classesID);
                                                        if(count($grades)) {
                                                            foreach ($grades as $grade) {
                                                                foreach ($student_avg as $avg) {
                                                                if($grade->gradefrom <= $avg->mark && $grade->gradeupto >= $avg->mark) {
                                                                    echo "<td data-title='".$this->lang->line('mark_grade')."'>";
                                                                        echo $avg->mark;
                                                                    echo "</td>";
                                                                    echo "<td data-title='".$this->lang->line('mark_grade')."'>";
                                                                        echo $grade->grade;
                                                                    echo "</td>";
                                                                    break;
                                                                }
                                                            }
                                                            }
                                                        }
                                                        ?>
                                                    </tr>

                                                <?php $i++; }} 

                                                $subjects_no=$this->ranking_m->get_subject_count($classesID);
                                                $no=5;
                                                foreach ($subjects_no as $sub) {
                                                   $no+=$sub->subjectID;
                                                }
                                                ?>
                                                

                                                <!-- <tr><td colspan="<?php //echo $no;?>"></td></tr> -->
                                                <?php
                                                if (count($results)>0) {
                                                ?>
                                                <tr>
                                                    <td colspan="2" class="text-right"><strong>Totals</strong></td>
                                                    <?php
                                                    foreach ($subjects as $subject ) {
                                                        $sub_total=$this->ranking_m->get_subject_total($subject->subjectID,$examID,$classesID);?>
                                                        <td data-title="<?=$this->lang->line('ranking_section');?>">
                                                            <?php 
                                                            foreach ($sub_total as $sub_total) {
                                                                echo $sub_total->mark;
                                                            }
                                                             ?>
                                                        </td>
                                                        <?php
                                                    }
                                                    ?>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" class="text-right"><strong>Averages</strong></td>
                                                    <?php
                                                    foreach ($subjects as $subject ) {
                                                        $sub_avg=$this->ranking_m->get_subject_average($subject->subjectID,$examID,$classesID);?>
                                                        <td data-title="<?=$this->lang->line('ranking_section');?>">
                                                            <?php 
                                                            foreach ($sub_avg as $sub_avg) {
                                                                echo $sub_avg->mark;
                                                            }
                                                             ?>
                                                        </td>
                                                        <?php
                                                    }
                                                    ?>
                                                </tr>
                                                <?php
                                            }else{

                                                    echo '<tr><td colspan="'.$no.'">No data is available.</td></tr>';
                                                    }?>
                                            </tbody>
                                        </table>
                                    </div>    

                                </div>
                            </div>
                        </div> <!-- nav-tabs-custom -->
                    </div>
                <?php } ?>


                <?php } elseif($usertype == "Student" || $usertype == "Parent") { ?>
                <div id="hide-table">
                    <table id="example5" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th><?=$this->lang->line('slno')?></th>
                                <th><?=$this->lang->line('ranking_student')?></th>
                                 <?php
                                    foreach ($subjects as $subject ) {?>
                                        <th><?php echo $subject->subject; ?></th><?php
                                        //echo $examID;
                                    }
                                ?>
                                <th><?=$this->lang->line('ranking_total')?>
                                <th><?=$this->lang->line('ranking_average')?>
                                <th><?=$this->lang->line('ranking_grade')?></th>
                                <?php if($usertype == "Admin") { ?>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($results)) {$i = 1; foreach($students as $student) { ?>
                                <tr>
                            
                                <td data-title="<?=$this->lang->line('slno')?>">
                                    <span class="badge"> <?php echo $i; ?></span>
                                </td>
                                <td data-title="<?=$this->lang->line('ranking_name')?>">
                                    <?php echo $student->name; ?>
                                </td>
                                
                                <?php
                                    foreach ($subjects as $subject ) {
                                        $mark=$this->ranking_m->get_subject_mark($student->studentID,$examID,$subject->subjectID);
                                        ?>
                                        <td data-title="<?=$this->lang->line('ranking_section');?>">
                                            <?php 

                                            foreach ($mark as $mark) {
                                                echo $mark->mark;
                                            }
                                             ?>
                                        </td>

                                   <?php }

                                   $total=$this->ranking_m->get_student_total($student->studentID,$examID,$classesID);?>
                                    <td data-title="<?=$this->lang->line('ranking_section');?>">
                                        <?php 
                                        foreach ($total as $total) {
                                            echo $total->mark;
                                        }
                                         ?>
                                    </td>
                                    <?php
                                    $student_avg=$this->ranking_m->get_student_average($student->studentID,$examID,$classesID);
                                    if(count($grades)) {
                                        foreach ($grades as $grade) {
                                            foreach ($student_avg as $avg) {
                                            if($grade->gradefrom <= $avg->mark && $grade->gradeupto >= $avg->mark) {
                                                echo "<td data-title='".$this->lang->line('mark_grade')."'>";
                                                    echo $avg->mark;
                                                echo "</td>";
                                                echo "<td data-title='".$this->lang->line('mark_grade')."'>";
                                                    echo $grade->grade;
                                                echo "</td>";
                                                break;
                                            }
                                        }
                                        }
                                    }
                                    ?>
                                </tr>

                            <?php $i++; }} 

                            $subjects_no=$this->ranking_m->get_subject_count($classesID);
                            $no=5;
                            foreach ($subjects_no as $sub) {
                               $no+=$sub->subjectID;
                            }
                            ?>
                            <!-- <tr><td colspan="<?php //echo $no;?>"></td></tr> -->
                            <?php
                            if (count($students)>0) {
                            ?>
                            <tr>
                                <td colspan="2" class="text-right"><strong>Totals</strong></td>
                                <?php
                                foreach ($subjects as $subject ) {
                                    $sub_total=$this->ranking_m->get_subject_total($subject->subjectID,$examID,$classesID);?>
                                    <td data-title="<?=$this->lang->line('ranking_section');?>">
                                        <?php 
                                        foreach ($sub_total as $sub_total) {
                                            echo $sub_total->mark;
                                        }
                                         ?>
                                    </td>
                                    <?php
                                }
                                ?>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-right"><strong>Averages</strong></td>
                                <?php
                                foreach ($subjects as $subject ) {
                                    $sub_avg=$this->ranking_m->get_subject_average($subject->subjectID,$examID,$classesID);?>
                                    <td data-title="<?=$this->lang->line('ranking_section');?>">
                                        <?php 
                                        foreach ($sub_avg as $sub_avg) {
                                            echo $sub_avg->mark;
                                        }
                                         ?>
                                    </td>
                                    <?php
                                }
                                ?>
                            </tr>
                            <?php
                        }else{

                                echo '<tr><td colspan="'.$no.'">No data is available.</td></tr>';
                                }?>
                        </tbody>
                    </table>
                </div>
                <?php } ?>

            </div> <!-- col-sm-12 -->
            
        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->
    

<script type="text/javascript">
    // $('#classesID').change(function() {
    //     var classesID = $(this).val();
    //     if(classesID == 0) {
    //         $('#hide-table').hide();
    //         $('.nav-tabs-custom').hide();
    //     } else {
    //         $.ajax({
    //             type: 'POST',
    //             url: "<?//=base_url('examschedule/ranking_list')?>",
    //             data: "id=" + classesID,
    //             dataType: "html",
    //             success: function(data) {
    //                 window.location.href = data;
    //             }
    //         });
    //     }
    // });
</script>
<script language="javascript" type="text/javascript">
        function printDiv(divID) {
            alert("hey");
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
                    url: "<?=base_url('student/send_mail')?>",
                    data: 'to='+ to + '&subject=' + subject + "&id=" + id+ "&message=" + message+ "&set=" + set,
                    dataType: "html",
                    success: function(data) {
                        location.reload();
                    }
                });
            }
        });
    </script>
