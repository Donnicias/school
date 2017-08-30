<style type="text/css">
    .hide-table:last-child{
        page-break-after: auto;
    }
    .section:last-child{
        page-break-after: auto;
    }
</style>


<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-bar-chart"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("reportcards/index")?>"><?=$this->lang->line('menu_reportforms')?></a></li>
            <li class="active"><?=$this->lang->line('panel_title')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <?php 
                    $usertype = $this->session->userdata("usertype");
                    if($usertype == "Admin" || $usertype == "Teacher") {
                    if(count($results) > 0 ) { 
                    ?>

                    <div class="col-sm-12">
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#all" aria-expanded="true"><?=$this->lang->line("reportcards_all_examschedule")?></a></li>
                                <?php foreach ($sections as $key => $section) {
                                    echo '<li class=""><a data-toggle="tab" href="#'. $section->sectionID .'" aria-expanded="false">'. $this->lang->line("student_section")." ".$section->section. " ( ". $section->category." )".'</a></li>';
                                } ?>
                            </ul>

                            <div class="tab-content">
                                <div id="all" class="tab-pane active">

                                    <button class="btn-cs btn-sm-cs" onclick="printDiv('hide-table')"><span class="fa fa-print"></span> <?=$this->lang->line('print')?></button>
                                    <?php
                                     //echo btn_add_pdf('student/print_preview/'.$student->studentID."/".$set, $this->lang->line('pdf_preview'))

                                    ?>
                                    <div id="hide-table" style="height: 99%;">
                                    <h2 class="text-center">
                                        <img src="<?=base_url("uploads/images/site.PNG")?>" class="img" width="100px" ><br/>
                                    <?php //echo $classesID;
                                    $class = $this->db->select('classes')->from('classes')->where('classesID', $classesID)->limit(1)->get()->row_array();
                                    ?></h2>
                                    <b><h4 class="text-center"><?php 
                                    $term = $this->db->select('term_name')->from('terms')->where('term_id', $termID)->limit(1)->get()->row_array();
                                    echo $class['classes'].' / '.$term['term_name'].' / '.$year.' / End Term Exam Results <br/>';

                                    $examss=$this->reportforms_m->get_exams($examsID);
                                        $examss_no=count($examss);
                                        $i=1;
                                        echo "Exams Used (";
                                        foreach ($examss as $value) {
                                            echo $value->exam;
                                            if ($i<$examss_no) {
                                                echo ",";
                                            }
                                            $i++;
                                        }
                                        echo ")";
                                    ?></h4></b>
                                        <table id="#example1_wrapper" class="table table-striped table-bordered table-hover dataTable no-footer table-condensed small">
                                            <thead >
                                                <tr>
                                                    <th><strong><?=$this->lang->line('overallposition')?></strong></th>
                                                    <th><strong><?=$this->lang->line('position')?></strong></th>
                                                    <th><strong><?=$this->lang->line('kcpe')?></strong></th>
                                                    <th><strong><?=$this->lang->line('admno')?></strong></th>
                                                    <th><strong><?=$this->lang->line('reportcards_student')?></strong></th>
                                                     <?php
                                                        foreach ($subjects as $subject ) {?>
                                                            <th><strong><?php echo $subject->subject; ?></strong></th><?php
                                                            //echo $examID;
                                                        }
                                                    if ($classesID !=13 && $classesID !=14) {
                                                    ?>
                                                        <th><strong><?=$this->lang->line('reportcards_total')?></strong></th>
                                                        <th><strong><?=$this->lang->line('reportcards_average')?></strong></th>
                                                        <th><strong><?=$this->lang->line('reportcards_grade')?></strong></th>
                                                        <?php
                                                    }else{
                                                        ?>
                                                        <th><strong><?=$this->lang->line('subjectsentry')?></strong></th>
                                                        <th><strong><?=$this->lang->line('total_points')?></strong></th>
                                                        <th><strong><?=$this->lang->line('reportcards_grade')?></strong></th>

                                                <?php } ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                    $kcpe_total=0;
                                                    $subjectTotals=array();
													$as=0;
													$aminuses=0;
													$bpluses=0;
													$bplains=0;
													$bminuses=0;
													$cpluses=0;
													$cplains=0;
													$cminuses=0;
													$dpluses=0;
													$dplains=0;
													$dminuses=0;
													$es=0;
													$xes=0;
													$yes=0;
													$zes=0;
													$omss=0;
                                                if(count($results)>0) {
                                                    $i = 0; 
                                                    $j = 0; 
                                                    $ttmark=false;
                                                    $m = 0; 
                                                    $n = 0; 
                                                    $ttmarks=false;
                                                    $ttmarke=false;
													if ($classesID !=13 && $classesID !=14) {
														$sranking=$this->ranking_m->get_students_average_end_term($examsID,$classesID,$year,$terms);
													}else{
														$sranking=$this->ranking_m->get_studentss_average_end_term($examsID,$classesID,$year,$terms);
													}                                                    
                                                    $entry=count($sranking);
                                                    foreach($sranking as $student) {
                                                       if ($classesID !=13 && $classesID !=14) {
                                                        if ($ttmark != round($student->stotal,0)) {
                                                            $ttmark=round($student->stotal,0);
                                                            $i++;
                                                            if ($j>0) {
                                                                $i+=$j;
                                                                $j=0;
                                                            }
                                                        }else{
                                                            $j++;
                                                        }
													   
														$xandy="oa".$student->name;
														$xandy=array();

                                                        $oposition=$this->ranking_m->
                                                        get_class_position_end_term($examsID,$classesID,$student->sectionID,$student->roll,$year,$terms);
                                                    ?>
                                                    <tr>
                                                    <td data-title="<?=$this->lang->line('slno')?>">
                                                         <?php
															echo $i; 
														?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('slno')?>">
                                                        <?php
                                                            echo $oposition['position']; 
                                                        ?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('ranking_name')?>">
                                                        <?php echo $student->kcpe_mark;
                                                        $kcpe_total+=$student->kcpe_mark; ?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('ranking_name')?>">
                                                        <?php echo $student->roll; ?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('ranking_name')?>">
                                                        <?php echo ucwords(strtolower($student->name)); ?>
                                                    </td>
                                                    
                                                    <?php
                                                    $subjects_totals=0;
                                                        foreach ($subjects as $subject ) {
                                                            $grades=$this->grade_m->get_grade(array("classesID"=>$classesID,"subjectID"=>$subject->subjectID));
                                                            $mark=$this->ranking_m->get_subject_mark_end_terms($student->studentID,$examsID,$subject->subjectID,$year,$terms);
                                                            $stname="subjectTotals_".$subject->subject;
                                                            $stTotal="subjectNo_".$subject->subject;
                                                            ?>
                                                            <td data-title="<?=$this->lang->line('ranking_section');?>">
                                                                <?php 
                                                                foreach ($mark as $mark) {
                                                                    if (empty($mark->score)) {
                                                                        echo "-";
                                                                    }else if($mark->score !="X" && $mark->score !="Y" && $mark->score !="x" && $mark->score !="y" && $mark->score !="n" && $mark->score !="N"){
                                                                        //echo var_dump($mark->mark);
                                                                        if (is_numeric($mark->score)) {
                                                                            $score=round($mark->score,0);
																			if(count($grades)) {
																				foreach ($grades as $grade) {
																					if($grade->gradefrom <= $score && $grade->gradeupto >= $score) {
																							echo $score.' '.$grade->grade;
																					}
																				}
																			}
                                                                            $subjects_totals+=$score;
                                                                            @$subjectTotals[$stname]+=$score;
                                                                            @$subjectTotals[$stTotal]++;
                                                                        }else{
                                                                            $score=round($mark->score,0);
                                                                            echo $score.' Y';
                                                                            array_push($xandy, 'Y');
                                                                            $subjects_totals+=$score;
                                                                            @$subjectTotals[$stname]+=$score;
                                                                            @$subjectTotals[$stTotal]++;
                                                                        }
                                                                    }else if($mark->score ==="n" || $mark->score ==="N"){
                                                                    }else{
                                                                        echo strtoupper($mark->score);
                                                                        array_push($xandy, $mark->score);
                                                                    }
                                                                }
                                                                 ?>
                                                            </td>

                                                       <?php }
                                                            ?>
                                                            <td data-title="<?=$this->lang->line('total_points');?>"><?php
                                                                    echo round($subjects_totals,0);
                                                                 ?>
                                                            </td>
                                                            <?php
                                                            echo "<td data-title='".$this->lang->line('mark_avg')."'>";
                                                                echo round($student->avg,2);
                                                            echo "</td>";
                                                            echo "<td data-title='".$this->lang->line('mark_avg')."'>";
                                                             $grades11=$this->grade_m->get_overal_performance_grading();
                                                                $sccore=round($student->avg,0);
                                                                if(count($grades11)) {
                                                                    if (sizeof($xandy)>0) {
                                                                        if (in_array("Y", $xandy)) {
                                                                            if (in_array("X", $xandy)) {
                                                                                echo "X";
																				$xes++;
                                                                            }else if(in_array("x", $xandy)){
                                                                                echo "X";
																				$xes++;
                                                                            }else{
                                                                                foreach ($grades11 as $grade) {
                                                                                    if($grade->gradeFrom <= $sccore && $grade->gradeTo >= $sccore) {
                                                                                            echo $grade->grade.' (Y)';
																							$omss+=$grade->point;
																							$yes++;
                                                                                    }
                                                                                }
                                                                            }
                                                                        }else if(in_array("y", $xandy)){
                                                                            if (in_array("X", $xandy)) {
                                                                                echo "X";
																				$xes++;
                                                                            }else if(in_array("x", $xandy)){
                                                                                echo "X";
																				$xes++;
                                                                            }else{
                                                                                foreach ($grades11 as $grade) {
                                                                                    if($grade->gradeFrom <= $sccore && $grade->gradeTo >= $sccore) {
                                                                                            echo $grade->grade.' (Y)';
																							$omss+=$grade->point;
																							$yes++;
                                                                                    }
                                                                                }
                                                                            }
                                                                        }else if(in_array("X", $xandy)){
                                                                            echo "X";
																			$xes++;
                                                                        }else if(in_array("x", $xandy)){
                                                                            echo "X";
																			$xes++;
                                                                        }else{
                                                                            //print_r($xandy);
                                                                        }
                                                                    }else{
                                                                        //print_r($grades11);
                                                                        foreach ($grades11 as $grde) {
                                                                                if($grde->gradeFrom <= $sccore && $grde->gradeTo >= $sccore) {
                                                                                        echo $grde->grade;
																						$omss+=$grde->points;
																						if($grde->grade==="A"){
																							$as++;
																						}else if($grde->grade==="A-"){
                                                                                            $aminuses++;
                                                                                        }else if($grde->grade==="B+"){
                                                                                            $bpluses++;
                                                                                        }else if($grde->grade==="B"){
                                                                                            $bplains++;
                                                                                        }else if($grde->grade==="B-"){
                                                                                            $bminuses++;
                                                                                        }else if($grde->grade==="C+"){
                                                                                            $cpluses++;
                                                                                        }else if($grde->grade==="C"){
                                                                                            $cplains++;
                                                                                        }else if($grde->grade==="C-"){
                                                                                            $cminuses++;
                                                                                        }else if($grde->grade==="D+"){
                                                                                            $dpluses++;
                                                                                        }else if($grde->grade==="D"){
                                                                                            $dplains++;
                                                                                        }else if($grde->grade==="D-"){
                                                                                            $dminuses++;
                                                                                        }else{
                                                                                            $es++;
                                                                                        }
                                                                                }
                                                                            }
                                                                    }
                                                                            
                                                                }
                                                            echo "</td>";       
                                                        ?>
                                                    </tr>
													
                                                <?php }else{
                                                            $suebjectcount=$this->ranking_m->get_total_subjects_count_end_term($student->roll,$classesID,$year,$terms);
														if(count($suebjectcount)>0){
                                                            foreach ($suebjectcount as $keyvalue) {
                                                                //echo "processing results!  (".$keyvalue->sno.")<br/>";
                                                                if($keyvalue->sno ==7){
                                                                               $points=0;
                                                                               $spoint=$this->ranking_m->get_subjects_end_term($student->studentID,$examsID,$classesID,$year,$terms);
																				//print_r($spoint);
                                                                            foreach ($spoint as $spoint) {
                                                                                $grades2=$this->grade_m->get_grade(array("classesID"=>$classesID,"subjectID"=>$spoint->subjectID));
                                                                                $score=round($spoint->mark,0);
                                                                                    if(count($grades2)) {
                                                                                        foreach ($grades2 as $grade) {
                                                                                            if($grade->gradefrom <= $score && $grade->gradeupto >= $score) {
                                                                                                $points+=$grade->point;
                                                                                            }
                                                                                        }
                                                                                    }
                                                                            }
                                                                            $gradepoints=round($points/7,0);
                                                                             $grades3=$this->grade_m->get_overal_performance_grading();
                                                                            if(count($grades3)) {
                                                                                foreach ($grades3 as $grade) {
                                                                                    if($grade->points <= $gradepoints && $grade->points >= $gradepoints) {
                                                                                        $grade=$grade->grade;
                                                                                        //echo $grade->grade;
																						$array1= array(
																						"studentID"=>$student->studentID,
																						"classesID"=>$classesID,
																						"term_name"=>$student->term,
																						"year"=>$year
																						);
																						$array= array(
																						"studentID"=>$student->studentID,
                                                                                        "classesID"=>$classesID,
																						"total_points"=>$points,
																						"subject_entry"=>$keyvalue->sno,
																						"overall_grade"=>$grade,
																						"term_name"=>$student->term,
																						"year"=>$year
																						);
																						$check_content=$this->student_end_term_averages_m->get_student_end_term_average($array1);
																						if(count($check_content)){
																							$data= array(
																							"total_points"=>$points,
																							"subject_entry"=>$keyvalue->sno,
																							"overall_grade"=>$grade,
																							);
																							$array=array(
                                                                                            "classesID"=>$classesID,
																							"studentID"=>$student->studentID,
																							"term_name"=>$student->term,
																							"year"=>$year
																							);
																							if($this->student_end_term_averages_m->update_student_end_term_average($data, $array)){
																								//echo "record updated!";
																							}else{
																								echo "error!";
																							}
																							
																						}else{
																							$this->student_end_term_averages_m->save_student_end_term_average($array);
                                                                                            //echo "record saved!";
																						}
                                                                                    }
                                                                                }
                                                                            }
                                                                    }else if($keyvalue->sno<7){
                                                                            $points=0;
                                                                               $spoint=$this->ranking_m->get_subjects_end_term($student->studentID,$examsID,$classesID,$year,$terms);
                
                                                                            foreach ($spoint as $spoint) {
                                                                                $grades2=$this->grade_m->get_grade(array("classesID"=>$classesID,"subjectID"=>$spoint->subjectID));
                                                                                $score=round($spoint->mark,0);
                                                                                    if(count($grades2)) {
                                                                                        foreach ($grades2 as $grade) {
                                                                                            if($grade->gradefrom <= $score && $grade->gradeupto >= $score) {
                                                                                                $points+=$grade->point;
                                                                                            }
                                                                                        }
                                                                                    }
                                                                            }
																			
																				$array= array(
																				"studentID"=>$student->studentID,
																				"classesID"=>$classesID,
																				"total_points"=>$points,
																				"subject_entry"=>$keyvalue->sno,
																				"overall_grade"=>"Z",
																				"term_name"=>$student->term,
																				"year"=>$year
																				);
																				$array1= array(
																				"studentID"=>$student->studentID,
																				"classesID"=>$classesID,
																				"term_name"=>$student->term,
																				"year"=>$year
																				);
																				$check_content=$this->student_end_term_averages_m->get_student_end_term_average($array1);
																						if(count($check_content)){
																							$data= array(
																							"total_points"=>$points,
																							"subject_entry"=>$keyvalue->sno,
																							"overall_grade"=>$grade->grade,
																							);
																							$array=array(
                                                                                            "classesID"=>$classesID,
																							"studentID"=>$student->studentID,
																							"term_name"=>$student->term,
																							"year"=>$year
																							);
																							if($this->student_end_term_averages_m->update_student_end_term_average($data, $array)){
																								//echo "record updated!";
																							}else{
																								echo "error!";
																							}
																						}else{
																							$this->student_end_term_averages_m->save_student_end_term_average($array);
                                                                                            //echo "record saved!";
																						}
																						
																						
																						
																						
																						
																						
																						
																						
																						
                                                                    }else if($keyvalue->sno ==8){
                                                                        $sciences=$this->ranking_m->sciences_count_end_term($student->studentID,$examsID,$classesID);
                                                                            $sciences_count=count($sciences);
                                                                        $humanities=$this->ranking_m->humanities_count_end_term($student->studentID,$examsID,$classesID);
                                                                            $humanities_count=count($humanities);
                                                                        if ($sciences_count==2 && $humanities_count==2) {
                                                                            //echo "hello";
                                                                            $humanities_IDs=$this->ranking_m->humanities_IDs_end_term($student->studentID,$examsID,$classesID,$year,$terms);

                                                                            foreach ($humanities_IDs as $humanities_IDs) {
                                                                                $subjecth=$humanities_IDs->subjectID;
                                                                                $scoreeee=$humanities_IDs->mark;

                                                                            }
                                                                            $option1points=0;
                                                                                   $option1spoint=$this->ranking_m->get_option1_subjects_end_term($student->studentID,$examsID,$classesID,$subjecth,$year,$terms);
                                                                                foreach ($option1spoint as $option1spoint) {
                                                                                     $grades2=$this->grade_m->get_grade(array("classesID"=>$classesID,"subjectID"=>$option1spoint->subjectID));
                                                                                    $option1score=round($option1spoint->mark,0);
                                                                                        if(count($grades2)) {
                                                                                            foreach ($grades2 as $grade) {
                                                                                                if($grade->gradefrom <= $option1score && $grade->gradeupto >= $option1score) {
                                                                                                    $option1points+=$grade->point.',';
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                }
                                                                                $option1pointss=round($option1points/7,0);
                                                                                $grades3=$this->grade_m->get_overal_performance_grading();
                                                                            if(count($grades3)) {
                                                                                foreach ($grades3 as $grade) {
                                                                                    if($grade->points <= $option1pointss && $grade->points >= $option1pointss) {
																						$array= array(
																						"studentID"=>$student->studentID,
                                                                                        "classesID"=>$classesID,
																						"total_points"=>$option1points,
																						"subject_entry"=>$keyvalue->sno,
																						"overall_grade"=>$grade->grade,
																						"term_name"=>$student->term,
																						"year"=>$year
																						);
																						$array1= array(
																						"studentID"=>$student->studentID,
                                                                                        "classesID"=>$classesID,
																						"term_name"=>$student->term,
																						"year"=>$year
																						);
																						$check_content=$this->student_end_term_averages_m->get_student_end_term_average($array1);
																						if(count($check_content)){
																							$data= array(
																							"total_points"=>$option1points,
																							"subject_entry"=>$keyvalue->sno,
																							"overall_grade"=>$grade->grade,
																							);
																							$array=array(
                                                                                            "classesID"=>$classesID,
																							"studentID"=>$student->studentID,
																							"term_name"=>$student->term,
																							"year"=>$year
																							);
																							
																							if($this->student_end_term_averages_m->update_student_end_term_average($data, $array)){
																								//echo "record updated!";
																							}else{
																								echo "error!";
																							}
																						}else{
																							$this->student_end_term_averages_m->save_student_end_term_average($array);
                                                                                            //echo "record inserted!";
																						}
                                                                                    }
                                                                                }
                                                                            }
                                                                        }else{
                                                                            $sciences_IDs=$this->ranking_m->sciences_IDs_end_term($student->studentID,$examsID,$classesID);
                                                                                    foreach ($sciences_IDs as $sciences_IDs) {
                                                                                        $subject_s=$sciences_IDs->subjectID;
                                                                                    }
                                                                                    $option2points=0;
                                                                                   $option2spoint=$this->ranking_m->get_option2_subjects_end_term($student->studentID,$examsID,$classesID,$subject_s,$year,$terms);
                                                                                foreach ($option2spoint as $option2spoint) {
                                                                                    $grades2=$this->grade_m->get_grade(array("classesID"=>$classesID,"subjectID"=>$option2spoint->subjectID));
                                                                                    $option2score=round($option2spoint->mark,0);
                                                                                        if(count($grades2)) {
                                                                                            foreach ($grades2 as $grade) {
                                                                                                if($grade->gradefrom <= $option2score && $grade->gradeupto >= $option2score) {
                                                                                                    $option2points+=$grade->point.',';
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                }
                                                                                $option2pointss=round($option2points/7,0);
                                                                                $grades3=$this->grade_m->get_overal_performance_grading();
                                                                            if(count($grades3)) {
                                                                                foreach ($grades3 as $grade) {
                                                                                    if($grade->points <= $option2pointss && $grade->points >= $option2pointss) {
                                                                                        //echo $grade->grade;
																						
																						$array= array(
																						"studentID"=>$student->studentID,
                                                                                        "classesID"=>$classesID,
																						"total_points"=>$option2points,
																						"subject_entry"=>$keyvalue->sno,
																						"overall_grade"=>$grade->grade,
																						"term_name"=>$student->term,
																						"year"=>$year
																						);
																						$array1= array(
																						"studentID"=>$student->studentID,
                                                                                        "classesID"=>$classesID,
																						"term_name"=>$student->term,
																						"year"=>$year
																						);
																						
																						$check_content2=$this->student_end_term_averages_m->get_student_end_term_average($array1);
																						if(count($check_content2)){
																							$data= array(
																							"total_points"=>$option2points,
																							"subject_entry"=>$keyvalue->sno,
																							"overall_grade"=>$grade->grade,
																							);
																							$array=array(
                                                                                            "classesID"=>$classesID,
																							"studentID"=>$student->studentID,
																							"term_name"=>$student->term,
																							"year"=>$year
																							);
																							if($this->student_end_term_averages_m->update_student_end_term_average($data, $array)){
																								//echo "record updated!";
																							}else{
																								echo "error!";
																							}
																						}else{
																							$this->student_end_term_averages_m->save_student_end_term_average($array);
                                                                                           // echo "record saved!";
																						}
																						
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                    }else{
                                                                            //print_r($subjectcount);
                                                                    }
																	
																	
																	
																	
																	
																	
																	
																	
																	
																	
																	
																	
																	
																	
																	
																	
																	

                                                            } 
														}else{
															echo "No data found!";
														}
													}
												}
												
												}else{
													?>
													<div class="container">
													  <div class="jumbotron">
														<h1>No data found!</h1>
													  </div>
													</div>
													<?php
												}
												if ($classesID !=13 && $classesID !=14) {
													
												}else{
													$array=array(
													"f.classesID"=>$classesID,
													"f.year"=>date("Y")
													);
													$k=1;
													$studentsw_data=$this->ranking_m->gets_sstudents_end_term($classesID,$examsID,$year,$terms);
													//print_r($students_data);
													if(count($studentsw_data)){
													$i = 0; 
                                                    $j = 0; 
                                                    $ttmark=false;
													foreach($studentsw_data as $sdata){
														$form3and4a="f3and4".$sdata->name;
														$form3and4a=array();
														if ($ttmark != round($sdata->tp,0)) {
                                                            $ttmark=$sdata->tp;
                                                            $i++;
                                                            if ($j>0) {
                                                                $i+=$j;
                                                                $j=0;
                                                            }
                                                        }else{
                                                            $j++;
                                                        }

                                                        $coposition=$this->ranking_m->get_class_position_end_term($examsID,$classesID,$sdata->sectionID,$sdata->roll,$year,$terms);
														?>
														<tr>
                                                
                                                    <td data-title="<?=$this->lang->line('overallposition')?>">
                                                        <?php
															echo $i; 
														?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('position')?>">
                                                        <?php
                                                            echo $coposition['position']; 
                                                        ?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('kcpe')?>">
                                                        <?php echo $sdata->kcpe_mark; 
                                                        $kcpe_total+=$sdata->kcpe_mark;
                                                        ?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('admno')?>">
                                                        <?php echo $sdata->roll; ?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('ranking_name')?>">
                                                        <?php echo ucwords(strtolower($sdata->name)); ?>
                                                    </td>
                                                    
                                                    <?php
													
                                                        foreach ($subjects as $subject ) {
                                                            $markss=$this->ranking_m->get_subject_mark_end_terms($sdata->studentID,$examsID,$subject->subjectID,$year,$terms);
                                                            $grades4=$this->grade_m->get_grade(array("classesID"=>$classesID,"subjectID"=>$subject->subjectID));
                                                            $stname="subjectTotals_".$subject->subject;
                                                            $stTotal="subjectNo_".$subject->subject;
                                                            ?>
                                                            <td data-title="<?=$this->lang->line('ranking_section');?>">
                                                                <?php 

                                                                foreach ($markss as $markss) {
                                                                    
                                                                    if (empty($markss->score)) {
                                                                        //echo "-";
                                                                    }else if($markss->score !="X" && $markss->score !="Y" && $markss->score !="x" && $markss->score !="y" && $markss->score !="n" && $markss->score !="N"){
                                                                        
                                                                        if(count($grades4)) {
																			if (is_numeric($markss->score)) {
																				$scoress=round($markss->score,0);
																				if(count($grades4)) {
																					foreach ($grades4 as $grade) {
																						if($grade->gradefrom <= $scoress && $grade->gradeupto >= $scoress) {
																								echo $scoress.' '.$grade->grade;
																						}
																					}
																				}
                                                                                @$subjectTotals[$stname]+=$scoress;
                                                                                @$subjectTotals[$stTotal]++;
																			}else{
																				$scoress=round($markss->score,0);
																				echo $scoress.' Y';
																				array_push($form3and4a, 'Y');
                                                                                @$subjectTotals[$stname]+=$scoress;
                                                                                @$subjectTotals[$stTotal]++;
																			}
                                                                        }
                                                                    }else if($markss->score ==="n" || $markss->score ==="N"){

                                                                    }else{
                                                                        echo strtoupper($markss->score);
																		array_push($form3and4a, $markss->score);
                                                                    }
                                                                }
                                                                 ?>
                                                            </td>

                                                       <?php }
                                                            ?>
                                                            <td data-title="<?=$this->lang->line('subjectsentry');?>"><?php
                                                                    echo $sdata->se;
                                                                 ?>
                                                            </td>
                                                            <?php
                                                            echo "<td data-title='".$this->lang->line('total_points')."'>";
                                                                echo round($sdata->tp,0);
                                                            echo "</td>";
															echo "<td data-title='".$this->lang->line('ranking_grade')."'>";
                                                            $grades34544=$this->grade_m->get_overal_performance_grading();
                                                            $pointz=round($sdata->tp/7,0);
                                                            $sgradz=null;
                                                            if(count($grades34544)) {
                                                                foreach ($grades34544 as $gradet) {
                                                                    if($gradet->points <= $pointz && $gradet->points >= $pointz) {
                                                                        $sgradz=$gradet->grade;
                                                                    }
                                                                }
                                                            }
																if (sizeof($form3and4a)>0) {
																	if (in_array("Y", $form3and4a)) {
																		if (in_array("X", $form3and4a)) {
																			echo "X";
                                                                            $xes++;
																		}else if(in_array("x", $form3and4a)){
																			echo "X";
                                                                            $xes++;
																		}else{
																			echo $sdata->grade." Y";
                                                                            $yes++;
																		}
																	}else if(in_array("y", $form3and4a)){
																		if (in_array("X", $form3and4a)) {
																			echo "X";
                                                                            $xes++;
																		}else if(in_array("x", $form3and4a)){
																			echo "X";
                                                                            $xes++;
																		}else{
																			echo $sgradz." (Y)";
                                                                            $yes++;
																		}
																	}else if(in_array("X", $form3and4a)){
																		echo "X";
                                                                        $xes++;
																	}else if(in_array("x", $form3and4a)){
																		echo "X";
                                                                        $xes++;
																	}else{
																		//print_r($form3and4a);
																	}
																}else{
                                                                    if ($sdata->se < 7) {
                                                                        echo "Z";
                                                                        $zes++;
                                                                    }else{
                                                                        echo $sgradz;
                                                                        if($sgradz==="A"){
                                                                                            $as++;
                                                                        }else if($sgradz==="A-"){
                                                                            $aminuses++;
                                                                        }else if($sgradz==="B+"){
                                                                            $bpluses++;
                                                                        }else if($sgradz==="B"){
                                                                            $bplains++;
                                                                        }else if($sgradz==="B-"){
                                                                            $bminuses++;
                                                                        }else if($sgradz==="C+"){
                                                                            $cpluses++;
                                                                        }else if($sgradz==="C"){
                                                                            $cplains++;
                                                                        }else if($sgradz==="C-"){
                                                                            $cminuses++;
                                                                        }else if($sgradz==="D+"){
                                                                            $dpluses++;
                                                                        }else if($sgradz==="D"){
                                                                            $dplains++;
                                                                        }else if($sgradz==="D-"){
                                                                            $dminuses++;
                                                                        }else{
                                                                            $es++;
                                                                        }
                                                                    }
																	
																}
                                                            echo "</td>";         
                                                        ?>
                                                    </tr>
														<?php
													}
												}else{
													echo "no result set found";
												}
												}
                                                $subjects_no=$this->ranking_m->get_subject_count($classesID);
                                                $no=5;
                                                foreach ($subjects_no as $sub) {
                                                   $no+=$sub->subjectID;
                                                }
                                                ?>
                                                
                                                <?php
                                                if (count($students)>0) {
                                                    
                                                ?>
                                                <tr><td colspan="5" class="text-right"><strong>Subject Totals</strong></td>
                                                    <?php
                                                    foreach ($subjects as $subject ) {
                                                        $subject_total_mark="subjectTotals_".$subject->subject;
                                                       ?>
                                                        <td data-title="<?=$this->lang->line('ranking_subject_totals');?>"><strong><i>
                                                            <?php 
                                                            echo $subjectTotals[$subject_total_mark];
                                                             ?>
                                                        </i></strong></td>
                                                        <?php
                                                   }
                                                    ?>
                                                </tr>
                                                <tr>
                                                    <td colspan="5" class="text-right"><strong>Subject Averages</strong></td>
                                                    <?php
                                                    foreach ($subjects as $subject ) {
                                                        $subject_total_mark="subjectTotals_".$subject->subject;
                                                        $stTotal="subjectNo_".$subject->subject;
                                                       ?>
                                                        <td data-title="<?=$this->lang->line('ranking_subject_averages');?>"><strong><i>
                                                            <?php 
                                                             echo round(($subjectTotals[$subject_total_mark]/$subjectTotals[$stTotal]),2);
                                                             ?>
                                                        </i></strong></td>
                                                        <?php
                                                   }
                                                    ?>
                                                </tr>
												<tr>
                                                    <td colspan="5" class="text-right"><strong>Subject Mean Grades</strong></td>
                                                    <?php
                                                    foreach ($subjects as $subject ) {
                                                        $subject_total_mark="subjectTotals_".$subject->subject;
                                                        $stTotal="subjectNo_".$subject->subject;
                                                       ?>
                                                        <td data-title="<?=$this->lang->line('ranking_subject_mean_grade');?>"><strong><i>
                                                            <?php 
                                                                $savg= round(($subjectTotals[$subject_total_mark]/$subjectTotals[$stTotal]),0);
                                                                $grades4s=$this->grade_m->get_grade(array("classesID"=>$classesID,"subjectID"=>$subject->subjectID));
                                                                if(count($grades4s)) {
                                                                    foreach ($grades4s as $grade) {
                                                                        if($grade->gradefrom <= $savg && $grade->gradeupto >= $savg) {
                                                                            echo $grade->grade;
                                                                        }
                                                                    }
                                                                }
                                                             ?>
                                                        </i></strong></td>
                                                        <?php
                                                   }
                                                    ?>
                                                </tr>
                                                <?php
                                            }else{
                                                    echo '<tr><td colspan="'.$no.'">No data is available.</td></tr>';
                                                    }?>
                                            </tbody>
                                             <table class="table table-striped table-bordered table-hover table-condensed">
											 <h3 class="text-center page-header"><strong>Class Exam Analysis (Overall Grades Distribution)</strong></h3>
                                                    <thead>
														<th><strong>A</strong></th>
														<th><strong>A-</strong></th>
														<th><strong>B+</strong></th>
														<th><strong>B</strong></th>
														<th><strong>B-</strong></th>
														<th><strong>C+</strong></th>
														<th><strong>C</strong></th>
														<th><strong>C-</strong></th>
														<th><strong>D+</strong></th>
														<th><strong>D</strong></th> 
														<th><strong>D-</strong></th>
														<th><strong>E</strong></th>
														<th><strong>X</strong></th>
														<th><strong>Y</strong></th>
														<th><strong>Z</strong></th>
                                                        <th><strong>Entry</strong></th>
                                                        <?php
                                                            if($classesID !=13 && $classesID !=14){
                                                                ?>
                                                                <th><strong>Total Marks</strong></th>
                                                                <th><strong>Average</strong></th>
																<th><strong>MSS</strong></th>
                                                                <th><strong>Grade</strong></th>
                                                                <?php
                                                            }else{
                                                                ?>
                                                                <th><strong>Mean</strong></th>
                                                                <th><strong>Grade</strong></th>
                                                                <?php
                                                            }
                                                        ?>
                                                    </thead>
													<tbody>
														<td><?php echo $as;?></td>
														<td><?php echo $aminuses;?></td>
														<td><?php echo $bpluses;?></td>
														<td><?php echo $bplains;?></td>
														<td><?php echo $bminuses;?></td>
														<td><?php echo $cpluses;?></td>
														<td><?php echo $cplains;?></td>
														<td><?php echo $cminuses;?></td>
														<td><?php echo $dpluses;?></td>
														<td><?php echo $dplains;?></td>
														<td><?php echo $dminuses;?></td>
														<td><?php echo $es;?></td>
														<td><?php echo $xes;?></td>
														<td><?php echo $yes;?></td>
														<td><?php echo $zes;?></td>
                                                        <td><?php echo $entry;?></td>
                                                        <?php
                                                            if($classesID !=13 && $classesID !=14){
                                                            $ototal=$this->ranking_m->get_overall_average($examsID,$classesID,$year);
                                                                ?>
                                                                    <td><strong>
                                                                <?php
                                                                foreach ($ototal as $ototal) {
                                                                    echo number_format((float)($ototal->mark*$entry*11),0,'.','');
                                                                }
                                                                ?>
                                                                </strong></td>
                                                                 <?php 
                                                             $oavg=$this->ranking_m->get_overall_average($examsID,$classesID,$year);
                                                            foreach ($oavg as $oavg) {
                                                                ?><td><strong>
                                                                <?php
                                                                    echo number_format((float)(($oavg->mark)*11),2,'.','');
                                                                ?>
                                                                </strong></td>
																<td><strong><?php echo round($omss/$entry,3);?></strong></td>
																<?php
                                                                    $osavg= round($omss/$entry,0);
                                                                    $grades4sw=$this->grade_m->get_overal_performance_grading();
                                                                    if(count($grades4sw)) {
                                                                        foreach ($grades4sw as $grade) {
                                                                            if($grade->points <= $osavg && $grade->points >= $osavg) {
                                                                                
                                                                                ?>
                                                                                <td><strong><?php echo $grade->grade;?></strong></td>
                                                                                <?php
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                             ?>
                                                                <?php
                                                            }else{
																$omean=$this->ranking_m->get_mean_end_term($examsID,$classesID,$year,$terms);
																foreach($omean as $mean){
																	$pmean=round($mean->mean,2);
																	$gmean=round($mean->mean,0);
																}
                                                                $grades4s=$this->grade_m->get_overal_performance_grading();
                                                                if(count($grades4s)) {
                                                                    foreach ($grades4s as $grade) {
                                                                        if($grade->points <= $gmean && $grade->points >= $gmean) {
                                                                            $ograde=$grade->grade;
                                                                        }
                                                                    }
                                                                }
                                                                ?>
                                                                <td><strong><?php echo $pmean;?></strong></td>
                                                                <td><strong><?php echo $ograde;?></strong></td>
                                                                <?php
                                                            }
                                                        ?>
													</tbody>
                                             </table>
                                             <table class="table table-striped table-bordered table-hover table-condensed">
                                             <h3 class="text-center page-header"><strong>Class KCPE Analysis (Class KCPE Deviation)</strong></h3>
                                                 <thead>
                                                    <th colspan="4" class="text-center"><strong>
                                                        KCPE
                                                    </strong>
                                                    </th>
                                                    <th colspan="2" class="text-center"><strong>
                                                        Class End Term
                                                    </strong>
                                                    </th>
                                                 </thead>
                                                 <thead>
                                                        <th><strong>Total</strong></th>
                                                        <th><strong>Average</strong></th>
                                                        <th><strong>MSS</strong></th>
                                                        <th><strong>Grade</strong></th>
                                                        <?php
                                                            if($classesID !=13 && $classesID !=14){
                                                                ?>
                                                                <th><strong>Average</strong></th>
                                                                <th><strong>Grade</strong></th>
                                                                <?php
                                                            }else{
                                                                ?>
                                                                <th><strong>Mean</strong></th>
                                                                <th><strong>Grade</strong></th>
                                                                <?php
                                                            }
                                                        ?>
                                                        <th><strong>Deviation</strong></th>
                                                    </thead>
                                                    <tbody>
                                                        <td><?php echo $kcpe_total;?></td>
                                                        <td><?php echo round(($kcpe_total/$entry)/5,4);?></td>
                                                        <td><?php echo round((($kcpe_total/$entry)/5)*0.12,4);?></td>
                                                        <td><?php 
                                                        $grades4s1=$this->grade_m->get_overal_performance_grading();
                                                        $averageKcpeMark=round((($kcpe_total/$entry)/5)*0.12,0);
                                                                if(count($grades4s1)) {
                                                                    foreach ($grades4s1 as $grade) {
                                                                        if($grade->points <= $averageKcpeMark && $grade->points >= $averageKcpeMark) {
                                                                            echo $grade->grade;
                                                                        }
                                                                    }
                                                                }
                                                        ?></td>
                                                        <?php
                                                            if($classesID !=13 && $classesID !=14){
                                                            $ototal=$this->ranking_m->get_overall_average($examsID,$classesID,$year);
                                                             $oavg=$this->ranking_m->get_overall_average($examsID,$classesID,$year);
                                                            foreach ($oavg as $oavg) {
                                                                ?>
                                                                <td><strong><?php echo round($omss/$entry,3);?></strong></td>
                                                                <?php
                                                                    $osavg= round($omss/$entry,0);
                                                                    $grades4s=$this->grade_m->get_overal_performance_grading();
                                                                    if(count($grades4s)) {
                                                                        foreach ($grades4s as $grade) {
                                                                            if($grade->points <= $osavg && $grade->points >= $osavg) {
                                                                                
                                                                                ?>
                                                                                <td><strong><?php echo $grade->grade;?></strong></td>
                                                                                <?php
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                             ?>
                                                             <td>
                                                                <strong>
                                                                <?php
                                                                echo round($omss/$entry,3)-round((($kcpe_total/$entry)/5)*0.12,4);
                                                                ?></strong>
                                                            </td>
                                                                <?php

                                                            }else{
                                                                $omean=$this->ranking_m->get_mean_end_term($examsID,$classesID,$year,$terms);
                                                                foreach($omean as $mean){
                                                                    $pmean=round($mean->mean,2);
                                                                    $gmean=round($mean->mean,0);
                                                                }
                                                                $grades4s=$this->grade_m->get_overal_performance_grading();
                                                                if(count($grades4s)) {
                                                                    foreach ($grades4s as $grade) {
                                                                        if($grade->points <= $gmean && $grade->points >= $gmean) {
                                                                            $ograde=$grade->grade;
                                                                        }
                                                                    }
                                                                }
                                                                ?>
                                                                <td><strong><?php echo $pmean;?></strong></td>
                                                                <td><strong><?php echo $ograde;?></strong></td>
                                                                <td>
                                                                    <strong>
                                                                    <?php
                                                                    echo $pmean-round((($kcpe_total/$entry)/5)*0.12,4);
                                                                    ?></strong>
                                                                </td>
                                                               <?php 
                                                            }
                                                        ?>
                                                        
                                                    </tbody>
                                             </table>
                                        </table>
										
                                    </div>
                                </div>

                                <?php foreach ($sections as $section) {
                                    $ssubjectTotals=array();
                                    $ssubjects_no=$this->ranking_m->get_subject_count($classesID);
                                                $sno=5+count($ssubjects_no);
									$sas=0;
									$saminuses=0;
									$sbpluses=0;
									$sbplains=0;
									$sbminuses=0;
									$scpluses=0;
									$scplains=0;
									$scminuses=0;
									$sdpluses=0;
									$sdplains=0;
									$sdminuses=0;
									$ses=0;
									$sxes=0;
									$syes=0;
									$szes=0;
                                                
                                    ?>
                                    
                                        <div id="<?=$section->sectionID?>" class="tab-pane">
                                        <button class="btn-cs btn-sm-cs" onclick="javascript:printDiv('hide-table_<?=$section->sectionID?>')"><span class="fa fa-print"></span> <?=$this->lang->line('print')?> </button>
                                        <?php
                                         //echo btn_add_pdf('student/print_preview/'.$student->studentID."/".$set, $this->lang->line('pdf_preview'))
                                        ?>
                                        <div id="hide-table_<?=$section->sectionID?>" class="section">
                                        <h2 class="text-center">
                                            <img src="<?=base_url("uploads/images/site.PNG")?>" class="img" width="100px" ><br/>
                                        <?php //echo $classesID;
                                        $sect = $this->db->select('section')->from('section')->where('sectionID', $section->sectionID)->limit(1)->get()->row_array();
                                        
                                        ?></h2>
                                        <b><h4 class="text-center"><?php
										
										$termm = $this->db->select('term_name')->from('terms')->where('term_id', $termID)->limit(1)->get()->row_array();
										echo $sect['section'].' / '.$termm['term_name'].' / '.$year.' / End Term Exam Results <br/>';

										$examss=$this->reportforms_m->get_exams($examsID);
											$examss_no=count($examss);
											$i=1;
											echo "Exams Used (";
											foreach ($examss as $value) {
												echo $value->exam;
												if ($i<$examss_no) {
													echo ",";
												}
												$i++;
											}
											echo ")";
										
                                        ?></h4></b>
                                        
                                                <?php 
													if($classesID !=13 && $classesID !=14){
														$mss=0;
														?>
														<table id="example2" class="table table-striped table-bordered table-hover table-condensed">
														<thead>
															<tr>
																<th><strong><?=$this->lang->line('position')?></strong></th>
																<th><strong><?=$this->lang->line('overallposition')?></strong></th>
																<th><strong><?=$this->lang->line('kcpe')?></strong></th>
																<th><strong><?=$this->lang->line('admno')?></strong></th>
																<th><strong><?=$this->lang->line('reportcards_student')?></strong></th>
																 <?php
																	foreach ($subjects as $subject ) {?>
																		<th><strong><?php echo $subject->subject; ?></strong></th><?php
																	}
																	?>
																<th><strong><?=$this->lang->line('reportcards_total')?></strong></th>
                                                                <th><strong><?=$this->lang->line('reportcards_average')?></strong></th>
                                                                <th><strong><?=$this->lang->line('reportcards_grade')?></strong></th>
															</tr>
														</thead>
														<tbody>
														<?php
														$sstudents=$this->ranking_m->get_sstudent_end_term($classesID,$section->sectionID,$examsID,$year,$terms);
                                                if(count($sstudents)) {
                                                    $i = 0; 
                                                    $j = 0; 
                                                    $tmark=false;
													$p=0;
													$q=0;
													$tmarkk=false;
													$sentry =count($sstudents);
                                                    foreach($sstudents as $s_tudents) {
                                                        $studentArray="sa".$s_tudents->name;
                                                        $studentArray=array();
                                                    if ($tmark != round($s_tudents->studenttotal,0)) {
                                                        $tmark=round($s_tudents->studenttotal,0);
                                                        $i++;
                                                        if ($j>0) {
                                                            $i+=$j;
                                                            $j=0;
                                                        }
                                                    }else{
                                                        $j++;
                                                    }
                                                    $overalranking=$this->ranking_m->get_overall_position_end_term($examsID,$classesID,$s_tudents->roll,$year,$terms);
                                                    ?>
                                                    <tr>
                                                    <td data-title="<?=$this->lang->line('slno')?>">
                                                        <?php echo $i; ?>
                                                    </td>

                                                    <td data-title="<?=$this->lang->line('overallposition')?>"> <?php 
                                                           echo $overalranking['position'];
                                                        ?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('kcpe')?>">
                                                        <?php echo $s_tudents->kcpe_mark; ?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('ranking_name')?>">
                                                        <?php echo $s_tudents->roll; ?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('ranking_name')?>">
                                                        <?php echo ucwords(strtolower($s_tudents->name)); ?>
                                                    </td>
                                                    
                                                    <?php
													$section_student_total_marks='';
                                                        foreach ($subjects as $subject ) {
                                                            $stm="subjectTotalMark_".$subject->subject;
                                                            $mark=$this->ranking_m->get_subject_mark_end_terms($s_tudents->studentID,$examsID,$subject->subjectID,$year,$terms);
                                                            $grades4=$this->grade_m->get_grade(array("classesID"=>$classesID,"subjectID"=>$subject->subjectID));
                                                            ?>
                                                            <td data-title="<?=$this->lang->line('ranking_section');?>">
                                                                <?php 

                                                                foreach ($mark as $mark) {
                                                                    if (empty($mark->score)) {
                                                                        echo "-";
                                                                    }else if($mark->score !="X" && $mark->score !="Y" && $mark->score !="x" && $mark->score !="y" && $mark->score !="n" && $mark->score !="N"){
                                                                        //echo $mark->mark;
                                                                        if (is_numeric($mark->score)) {
                                                                            $sscore=round($mark->score,0);
                                                                            if(count($grades4)) {
                                                                                foreach ($grades4 as $grade) {
                                                                                    if($grade->gradefrom <= $sscore && $grade->gradeupto >= $sscore) {
                                                                                        echo $sscore.' '.$grade->grade;
                                                                                    }
                                                                                }
                                                                            }
																			$section_student_total_marks+=$sscore;
                                                                            @$ssubjectTotals[$stm]+=$sscore;
                                                                        }else{
                                                                            $sscore=round($mark->score,0);
                                                                            echo $sscore.' Y';
                                                                            array_push($studentArray, 'Y');
																			$section_student_total_marks+=$sscore;
                                                                            @$ssubjectTotals[$stm]+=$sscore;
                                                                        }
                                                                        
                                                                    }else if($mark->score ==="n" || $mark->score ==="N"){

                                                                    }else{
																		array_push($studentArray, $mark->score);
                                                                        echo strtoupper($mark->score);
                                                                    }
                                                                }
                                                                 ?>
                                                            </td>

                                                       <?php }
                                                       ?>
                                                            <td data-title="<?=$this->lang->line('ranking_section');?>"><?php
                                                                echo round($section_student_total_marks,0);
                                                                 ?>
                                                            </td>
                                                            <?php
                                                            echo "<td data-title='".$this->lang->line('mark_grade')."'>";
                                                                echo round($s_tudents->avg,2);
                                                            echo "</td>";
                                                            echo "<td data-title='".$this->lang->line('mark_avg')."'>";
                                                                $ssccore=round($s_tudents->avg,0);
                                                                $grades5=$this->grade_m->get_overal_performance_grading();
                                                                if(count($grades5)) {
                                                                    if (sizeof($studentArray)>0){
                                                                        if (in_array("Y", $studentArray)) {
                                                                            if (in_array("X", $studentArray)) {
                                                                                echo "X";
                                                                                $sxes++;
                                                                            }else if(in_array("x", $studentArray)){
                                                                                echo "X";
                                                                                $sxes++;
                                                                            }else{
                                                                                foreach ($grades5 as $grade) {
                                                                                    if($grade->gradeFrom <= $sccore && $grade->gradeTo >= $sccore) {
                                                                                            echo $grade->grade.' (Y)';
                                                                                            $syes++;
																							$mss+=$grade->points;
                                                                                    }
                                                                                }
                                                                            }
                                                                        }else if(in_array("y", $studentArray)){
                                                                            if (in_array("X", $studentArray)) {
                                                                                echo "X";
                                                                                $sxes++;
                                                                            }else if(in_array("x", $studentArray)){
                                                                                echo "X";
                                                                                $sxes++;
                                                                            }else{
                                                                                foreach ($grades5 as $grade) {
                                                                                    if($grade->gradeFrom <= $sccore && $grade->gradeTo >= $sccore) {
                                                                                            echo $grade->grade.' (Y)';
                                                                                            $syes++;
																							$mss+=$grade->points;
                                                                                    }
                                                                                }
                                                                            }
                                                                        }else if(in_array("X", $studentArray)){
                                                                            echo "X";
                                                                            $sxes++;
                                                                        }else if(in_array("x", $studentArray)){
                                                                            echo "X";
                                                                            $sxes++;
                                                                        }else{
                                                                            //print_r($studentArray);
                                                                        }
                                                                    }else{
                                                                        foreach ($grades5 as $grade) {
                                                                                if($grade->gradeFrom <= $ssccore && $grade->gradeTo >= $ssccore) {
                                                                                        echo $grade->grade;
																						$mss+=$grade->points;
                                                                                        if($grade->grade==="A"){
                                                                                            $sas++;
                                                                                        }else if($grade->grade==="A-"){
                                                                                            $saminuses++;
                                                                                        }else if($grade->grade==="B+"){
                                                                                            $sbpluses++;
                                                                                        }else if($grade->grade==="B"){
                                                                                            $sbplains++;
                                                                                        }else if($grade->grade==="B-"){
                                                                                            $sbminuses++;
                                                                                        }else if($grade->grade==="C+"){
                                                                                            $scpluses++;
                                                                                        }else if($grade->grade==="C"){
                                                                                            $scplains++;
                                                                                        }else if($grade->grade==="C-"){
                                                                                            $scminuses++;
                                                                                        }else if($grade->grade==="D+"){
                                                                                            $sdpluses++;
                                                                                        }else if($grade->grade==="D"){
                                                                                            $sdplains++;
                                                                                        }else if($grade->grade==="D-"){
                                                                                            $sdminuses++;
                                                                                        }else{
                                                                                            $ses++;
                                                                                        }
                                                                                }
                                                                            }
                                                                    }
                                                                            
                                                                 }
                                                            echo "</td>";
															?>
														</tr>
														
															<?php
															}
														}
														?>
														<tr><td colspan="5" class="text-right"><strong>Subject Totals</strong></td>
															<?php
															foreach ($subjects as $subject ) {
                                                                $sstm="subjectTotalMark_".$subject->subject;
															   ?>
																<td data-title="<?=$this->lang->line('ranking_subject_totals');?>"><strong><i>
																	<?php 
																	echo $ssubjectTotals[$sstm];
																	 ?>
																</i></strong></td>
																<?php
														   }
															?>
														</tr>
														<tr>
															<td colspan="5" class="text-right"><strong>Subject Averages</strong></td>
															<?php
															foreach ($subjects as $subject ) {
                                                                $sstm="subjectTotalMark_".$subject->subject;
															   ?>
																<td data-title="<?=$this->lang->line('ranking_subject_averages');?>"><strong><i>
																	<?php 
																	 echo round(($ssubjectTotals[$sstm])/$sentry,2);
																	 ?>
																</i></strong></td>
																<?php
														   }
															?>
														</tr>
														<tr>
															<td colspan="5" class="text-right"><strong>Subject Mean Grades</strong></td>
															<?php
															foreach ($subjects as $subject ) {
                                                                $sstm="subjectTotalMark_".$subject->subject;
															   ?>
																<td data-title="<?=$this->lang->line('ranking_subject_mean_grade');?>"><strong><i>
																	<?php 
																		$ssavg= round(($ssubjectTotals[$sstm])/$sentry,0);
																		$grades4ss=$this->grade_m->get_overal_performance_grading();
																		if(count($grades4ss)) {
																			foreach ($grades4ss as $grade) {
																				if($grade->gradeFrom <= $ssavg && $grade->gradeTo >= $ssavg) {
																					echo $grade->grade;
																				}
																			}
																		}
																	 ?>
																</i></strong></td>
																<?php
														   }
															?>
														</tr>
														 <?php 
												

                                                $subjects_no=$this->ranking_m->get_subject_count($classesID);
                                                $no=5;
                                                foreach ($subjects_no as $sub) {
                                                   $no+=$sub->subjectID;
                                                }
                                                ?>
                                                
                                                <?php
                                                ?>
													</tbody>
													<table class="table table-striped table-bordered table-hover table-condensed">
													 <h3 class="text-center page-header"><strong>Class Exam Analysis (Grades Distribution Per Stream)</strong></h3>
															<thead>
																<th><strong>A</strong></th>
																<th><strong>A-</strong></th>
																<th><strong>B+</strong></th>
																<th><strong>B</strong></th>
																<th><strong>B-</strong></th>
																<th><strong>C+</strong></th>
																<th><strong>C</strong></th>
																<th><strong>C-</strong></th>
																<th><strong>D+</strong></th>
																<th><strong>D</strong></th> 
																<th><strong>D-</strong></th>
																<th><strong>E</strong></th>
																<th><strong>X</strong></th>
																<th><strong>Y</strong></th>
																<th><strong>Z</strong></th>
																<th><strong>Entry</strong></th>
                                                                <th><strong>Total Marks</strong></th>
                                                                <th><strong>Average</strong></th>
																<th><strong>MSS</strong></th>
                                                                <th><strong>Grade</strong></th> 
															</thead>
															<tbody>
																<td><?php echo $sas;?></td>
																<td><?php echo $saminuses;?></td>
																<td><?php echo $sbpluses;?></td>
																<td><?php echo $sbplains;?></td>
																<td><?php echo $sbminuses;?></td>
																<td><?php echo $scpluses;?></td>
																<td><?php echo $scplains;?></td>
																<td><?php echo $scminuses;?></td>
																<td><?php echo $sdpluses;?></td>
																<td><?php echo $sdplains;?></td>
																<td><?php echo $sdminuses;?></td>
																<td><?php echo $ses;?></td>
																<td><?php echo $sxes;?></td>
																<td><?php echo $syes;?></td>
																<td><?php echo $szes;?></td>
																<td><?php echo $sentry;?></td>
																<?php
                                                            $sototal=$this->ranking_m->get_section_total_end_term($examsID,$classesID,$section->sectionID,$year,$terms);
                                                                ?>
                                                                    <td><strong>
                                                                <?php
                                                                foreach ($sototal as $ototal) {
                                                                    echo number_format((float)($ototal->mark),0,'.','');
                                                                }
                                                                ?>
                                                                </strong></td>
                                                                 <?php 
                                                             $soavg=$this->ranking_m->get_section_average_end_term($examsID,$classesID,$section->sectionID,$terms,$year);
                                                            foreach ($soavg as $soavg) {
                                                                ?><td><strong>
                                                                <?php
                                                                    echo number_format((float)(($soavg->mark)*11),2,'.','');
                                                                ?>
                                                                </strong></td>
																<td><strong><?php echo round($mss/$sentry,3);?></strong></td>
																<?php
                                                                    $wsoavg= number_format((float)($soavg->mark),0,'.','');
                                                                    $grades41s=$this->grade_m->get_overal_performance_grading();
                                                                    if(count($grades41s)) {
                                                                        foreach ($grades41s as $grade) {
                                                                            if($grade->gradeFrom <= $wsoavg && $grade->gradeTo >= $wsoavg) {
                                                                                
                                                                                ?>
                                                                                <td><strong><?php echo $grade->grade;?></strong></td>
                                                                                <?php
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                        ?>
														</tbody>
													 </table>
                                        </table>
														<?php
                                                        }else{
															?>
															<table id="example2" class="table table-striped table-bordered table-hover table-condensed small">
															<thead>
																<tr>
																	<th><strong><?=$this->lang->line('position')?></strong></th>
																	<th><strong><?=$this->lang->line('overallposition')?></strong></th>
																	<th><strong><?=$this->lang->line('kcpe')?></strong></th>
																	<th><strong><?=$this->lang->line('admno')?></strong></th>
																	<th><strong><?=$this->lang->line('reportcards_student')?></strong></th>
																	 <?php
																		foreach ($subjects as $subject ) {?>
																			<th><strong><?php echo $subject->subject; ?></strong></th><?php
																		}
																		?>
																	<th><strong><?=$this->lang->line('subjectsentry')?></strong></th>
																	<th><strong><?=$this->lang->line('total_points')?></strong></th>
																	<th><strong><?=$this->lang->line('reportcards_grade')?></strong></th>
																</tr>
															</thead>
															<tbody>
															<?php
															$dataaa=array(
																"classesID"=>$classesID,
																"sectionID"=>$section->sectionID,
																"examID"=>$examsID
																);
																
															$rankeddata=$this->ranking_m->get_sstudents_end_term($classesID,$section->sectionID,$examsID,$year,$terms);
															//print_r($rankeddata);
													if(count($rankeddata)){
														$i = 0; 
                                                        $j = 0; 
                                                        $ttmark=false;
														$tmarkk=false;
													$sentry =count($rankeddata);
													foreach($rankeddata as $rankeddata){
														$form3and4a="sf3and4".$sdata->name;
														$form3and4a=array();
                                                        if ($ttmark != $rankeddata->tp) {
                                                            $ttmark=$rankeddata->tp;
                                                            $i++;
                                                            if ($j>0) {
                                                                $i+=$j;
                                                                $j=0;
                                                            }
                                                        }else{
                                                            $j++;
                                                        }
                                                        $oranking=$this->ranking_m->get_overall_position_end_term($examsID,$classesID,$rankeddata->roll,$year,$terms);;
														?>
														<tr>
                                                    <td data-title="<?=$this->lang->line('slno')?>">
                                                        <?php echo $i; ?>
                                                    </td>

                                                    <td data-title="<?=$this->lang->line('overallposition')?>"> <?php 
                                                            echo $oranking['position'];
                                                        ?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('kcpe')?>">
                                                        <?php echo $rankeddata->kcpe_mark; ?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('admno')?>">
                                                        <?php echo $rankeddata->roll; ?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('ranking_name')?>">
                                                        <?php echo ucwords(strtolower($rankeddata->name)); ?>
                                                    </td>
                                                    
                                                    <?php
													
                                                        foreach ($subjects as $subject ) {
                                                            $marks_s=$this->ranking_m->get_subject_mark_end_terms($rankeddata->studentID,$examsID,$subject->subjectID,$year,$terms);
                                                            $grades7=$this->grade_m->get_grade(array("classesID"=>$classesID,"subjectID"=>$subject->subjectID));
                                                            $stm="subjectTotalMark_".$subject->subject;
                                                            $sstTotal="ssubjectNo_".$subject->subject;
                                                            ?>
                                                            <td data-title="<?=$this->lang->line('ranking_section');?>">
                                                                <?php 

                                                                foreach ($marks_s as $marks_s) {
                                                                    
                                                                    if (empty($marks_s->score)) {
                                                                        //echo "-";
                                                                    }else if($marks_s->score !="X" && $marks_s->score !="Y" && $marks_s->score !="x" && $marks_s->score !="y" && $marks_s->score !="n" && $marks_s->score !="N"){
                                                                        
																		if (is_numeric($marks_s->score)) {
																			$s_coress=round($marks_s->score,0);
																			if(count($grades7)) {
																				foreach ($grades7 as $grade) {
																					if($grade->gradefrom <= $s_coress && $grade->gradeupto >= $s_coress) {
																							echo $s_coress.' '.$grade->grade;
																					}
																				}
																			}
                                                                            @$ssubjectTotals[$stm]+=$s_coress;
                                                                            @$ssubjectTotals[$sstTotal]++;
																		}else{
																			$s_coress=round($marks_s->score,0);
																			echo $scoress.' Y';
																			array_push($form3and4a, 'Y');
                                                                            @$ssubjectTotals[$stm]+=$s_coress;
                                                                            @$ssubjectTotals[$sstTotal]++;
																		}
																		
                                                                    }else if($marks_s->score ==="n" || $marks_s->score ==="N"){

                                                                    }else{
                                                                        echo strtoupper($marks_s->score);
																		array_push($form3and4a, strtoupper($marks_s->score));
                                                                    }
                                                                }
                                                                 ?>
                                                            </td>

                                                       <?php }
                                                            ?>
                                                            <td data-title="<?=$this->lang->line('total_points');?>"><?php
                                                                    echo $rankeddata->se;
                                                                 ?>
                                                            </td>
                                                            <?php
                                                            echo "<td data-title='".$this->lang->line('mark_avg')."'>";
                                                                echo round($rankeddata->tp);
                                                            echo "</td>";
															echo "<td data-title='".$this->lang->line('mark_avg')."'>";

																if (sizeof($form3and4a)>0) {
																	if (in_array("Y", $form3and4a)) {
																		if (in_array("X", $form3and4a)) {
																			echo "X";
																			$sxes++;
																		}else if(in_array("x", $form3and4a)){
																			echo "X";
																			$sxes++;
																		}else{
																			echo $rankeddata->grade." Y";
																		}
																	}else if(in_array("y", $form3and4a)){
																		if (in_array("X", $form3and4a)) {
																			echo "X";
																			$sxes++;
																		}else if(in_array("x", $form3and4a)){
																			echo "X";
																			$sxes++;
																		}else{
																			echo $rankeddata->grade." (Y)";
																		}
																	}else if(in_array("X", $form3and4a)){
																		echo "X";
																		$sxes++;
																	}else if(in_array("x", $form3and4a)){
																		echo "X";
																		$sxes++;
																	}else{
																		//print_r($form3and4a);
																	}
																}else{
                                                            $sgrades34544=$this->grade_m->get_overal_performance_grading();
                                                            $spointz=round($rankeddata->tp/7,0);
                                                            $ssgradz=null;
                                                            if(count($sgrades34544)) {
                                                                foreach ($sgrades34544 as $gradet) {
                                                                    if($gradet->points <= $spointz && $gradet->points >= $spointz) {
                                                                        $ssgradz=$gradet->grade;
                                                                    }
                                                                }
                                                            }
																	if ($rankeddata->se < 7) {
                                                                        echo "Z";
                                                                        $szes++;
                                                                    }else{
                                                                        echo $ssgradz;
                                                                        if($ssgradz==="A"){
                                                                            $sas++;
                                                                        }else if($ssgradz==="A-"){
                                                                            $saminuses++;
                                                                        }else if($ssgradz==="B+"){
                                                                            $sbpluses++;
                                                                        }else if($ssgradz==="B"){
                                                                            $sbplains++;
                                                                        }else if($ssgradz==="B-"){
                                                                            $sbminuses++;
                                                                        }else if($ssgradz==="C+"){
                                                                            $scpluses++;
                                                                        }else if($ssgradz==="C"){
                                                                            $scplains++;
                                                                        }else if($ssgradz==="C-"){
                                                                            $scminuses++;
                                                                        }else if($ssgradz==="D+"){
                                                                            $sdpluses++;
                                                                        }else if($ssgradz==="D"){
                                                                            $sdplains++;
                                                                        }else if($ssgradz==="D-"){
                                                                            $sdminuses++;
                                                                        }else{
                                                                            $ses++;
                                                                        }
                                                                    }
																}
                                                            echo "</td>";         
                                                        ?>
                                                    </tr>
														<?php
													}
												}

												?>
													<tr><td colspan="5" class="text-right"><strong>Subject Totals</strong></td>
														<?php
														foreach ($subjects as $subject ) {
                                                            $sstm="subjectTotalMark_".$subject->subject;
														   ?>
															<td data-title="<?=$this->lang->line('ranking_subject_totals');?>"><strong><i>
																<?php 
																    echo $ssubjectTotals[$sstm];
																 ?>
															</i></strong></td>
															<?php
													   }
														?>
													</tr>
													<tr>
														<td colspan="5" class="text-right"><strong>Subject Averages</strong></td>
														<?php
														foreach ($subjects as $subjectt ) {
                                                            $sstm="subjectTotalMark_".$subjectt->subject;
                                                            $ssrtTotal="ssubjectNo_".$subjectt->subject;
														   ?>
															<td data-title="<?=$this->lang->line('ranking_subject_averages');?>"><strong><i>
																<?php 
																 $ssub_avg=round($ssubjectTotals[$sstm]/$ssubjectTotals[$ssrtTotal],2);
																    echo $ssub_avg; 
																 ?>
															</i></strong></td>
															<?php
													   }
														?>
													</tr>
													<tr>
														<td colspan="5" class="text-right"><strong>Subject Mean Grades</strong></td>
														<?php
														foreach ($subjects as $subject ) {
                                                            $sstm="subjectTotalMark_".$subject->subject;
                                                            $sstTotal="ssubjectNo_".$subject->subject;
														   ?>
															<td data-title="<?=$this->lang->line('ranking_subject_mean_grade');?>"><strong><i>
																<?php 
																	$ssavg= round($ssubjectTotals[$sstm]/$ssubjectTotals[$sstTotal],0);
																	$grades4ss=$this->grade_m->get_grade(array("classesID"=>1,"subjectID"=>1));
																	if(count($grades4ss)) {
																		foreach ($grades4ss as $grade) {
																			if($grade->gradefrom <= $ssavg && $grade->gradeupto >= $ssavg) {
																				echo $grade->grade;
																			}
																		}
																	}
																 ?>
															</i></strong></td>
															<?php
													   }
														?>
													</tr>
													 <?php 
												

                                                $subjects_no=$this->ranking_m->get_subject_count($classesID);
                                                $no=5;
                                                foreach ($subjects_no as $sub) {
                                                   $no+=$sub->subjectID;
                                                }
                                                ?>
                                                
                                                <?php
                                                ?>
                                            </tbody>
											<table class="table table-striped table-bordered table-hover table-condensed">
											 <h3 class="text-center page-header"><strong>Class Exam Analysis (Grades Distribution Per Stream)</strong></h3>
                                                    <thead>
														<th><strong>A</strong></th>
														<th><strong>A-</strong></th>
														<th><strong>B+</strong></th>
														<th><strong>B</strong></th>
														<th><strong>B-</strong></th>
														<th><strong>C+</strong></th>
														<th><strong>C</strong></th>
														<th><strong>C-</strong></th>
														<th><strong>D+</strong></th>
														<th><strong>D</strong></th> 
														<th><strong>D-</strong></th>
														<th><strong>E</strong></th>
														<th><strong>X</strong></th>
														<th><strong>Y</strong></th>
														<th><strong>Z</strong></th>
														<th><strong>Entry</strong></th>
														<th><strong>Mean</strong></th>
														<th><strong>Grade</strong></th>
                                                    </thead>
													<tbody>
														<td><?php echo $sas;?></td>
                                                        <td><?php echo $saminuses;?></td>
                                                        <td><?php echo $sbpluses;?></td>
                                                        <td><?php echo $sbplains;?></td>
                                                        <td><?php echo $sbminuses;?></td>
                                                        <td><?php echo $scpluses;?></td>
                                                        <td><?php echo $scplains;?></td>
                                                        <td><?php echo $scminuses;?></td>
                                                        <td><?php echo $sdpluses;?></td>
                                                        <td><?php echo $sdplains;?></td>
                                                        <td><?php echo $sdminuses;?></td>
                                                        <td><?php echo $ses;?></td>
                                                        <td><?php echo $sxes;?></td>
                                                        <td><?php echo $syes;?></td>
                                                        <td><?php echo $szes;?></td>
														<td><?php echo $sentry;?></td>
														<?php
															$somean=$this->ranking_m->get_section_mean_end_term($examsID,$classesID,$section->sectionID,$terms,$year);
															foreach($somean as $somean){
																$spmean=round($somean->mean,2);
																$sgmean=round($somean->mean,0);
															}
															$grades4s11=$this->grade_m->get_overal_performance_grading();
															if(count($grades4s11)) {
																foreach ($grades4s11 as $grade) {
																	if($grade->points <= $sgmean && $grade->points >= $sgmean) {
																		$sograde=$grade->grade;
																	}
																}
															}
														?>
														<td><strong><?php echo $spmean;?></strong></td>
														<td><strong><?php echo $sograde;?></strong></td>
                                                               
													</tbody>
                                             </table>
                                        </table>
										<?php
											}
											?>		
                                               
                                    </div>
                                </div>
                                <?php } ?>
                            </div>

                        </div> <!-- nav-tabs-custom -->
                    </div> <!-- col-sm-12 for tab -->

                <?php }else{ ?>
					  <div class="alert alert-danger text-center" role="alert"><strong><h1>No data found!</h1></strong></div>

                <?php }
				} elseif($usertype == "Student" || $usertype == "Parent") { ?>
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

    </script>
