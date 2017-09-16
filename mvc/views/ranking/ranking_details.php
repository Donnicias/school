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
            <li><a href="<?=base_url("ranking/index")?>"><?=$this->lang->line('menu_ranking')?></a></li>
            <li class="active"><?=$this->lang->line('ranking_details')?></li>
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
					$mss=0;
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
                                    <b><h3 class="text-center"><?php 
                                    $exam = $this->db->select('exam')->from('exam')->where('examID', $examID)->limit(1)->get()->row_array();
                                    echo $class['classes'].'/'.$term_name.'/'.$exam['exam'].'/'.$year.' Exam Results';
                                    ?></h3></b>
                                        <table id="#example1_wrapper" class="table table-striped table-bordered table-hover dataTable no-footer table-condensed small">
                                            <thead >
                                                <tr>
                                                    <th><strong><?=$this->lang->line('overallposition')?></strong></th>
                                                    <th><strong><?=$this->lang->line('position')?></strong></th>
                                                    <th><strong><?=$this->lang->line('kcpe')?></strong></th>
                                                    <th><strong><?=$this->lang->line('admno')?></strong></th>
                                                    <th><strong><?=$this->lang->line('ranking_student')?></strong></th>
                                                     <?php
                                                        foreach ($subjects as $subject ) {?>
                                                            <th><strong><?php echo $subject->subject; ?></strong></th><?php
                                                            //echo $examID;
                                                        }
                                                    if ($classesID !=13 && $classesID !=14) {
                                                    ?>
                                                        <th><strong><?=$this->lang->line('ranking_total')?></strong></th>
                                                        <th><strong><?=$this->lang->line('ranking_average')?></strong></th>
                                                        <th><strong><?=$this->lang->line('ranking_grade')?></strong></th>
                                                        <?php
                                                    }else{
                                                        ?>
                                                        <th><strong><?=$this->lang->line('subjectsentry')?></strong></th>
                                                        <th><strong><?=$this->lang->line('total_points')?></strong></th>
                                                        <th><strong><?=$this->lang->line('ranking_grade')?></strong></th>

                                                <?php } ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
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
													
                                                    $i = 0; 
                                                    $j = 0; 
                                                    $ttmark=false;
													$m = 0; 
													$n = 0; 
													$ttmarks=false;
													$ttmarke=false;
													if ($classesID !=13 && $classesID !=14) {
														$sranking=$this->ranking_m->get_students_average($examID,$classesID,$year,$term_name);
													}else{
														$sranking=$this->ranking_m->get_studentss_average($examID,$classesID,$year,$term_name);
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
													   $stotalmarks=0;
														$xandy="oa".$student->name;
														$xandy=array();

                                                        $oposition=$this->ranking_m->get_class_position($examID,$classesID,$student->sectionID,$student->roll,$year,$term_name);
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
                                                        <?php echo $student->kcpe_mark; ?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('ranking_name')?>">
                                                        <?php echo $student->roll; ?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('ranking_name')?>">
                                                        <?php echo ucwords(strtolower($student->name)); ?>
                                                    </td>
                                                    
                                                    <?php
                                                        foreach ($subjects as $subject ) {
                                                            $grades=$this->grade_m->get_grade(array("classesID"=>$classesID,"subjectID"=>$subject->subjectID));
                                                            $mark=$this->ranking_m->get_subject_mark($student->studentID,$examID,$subject->subjectID,$year,$term_name);
                                                            
                                                            ?>
                                                            <td data-title="<?=$this->lang->line('ranking_section');?>">
                                                                <?php 

                                                                foreach ($mark as $mark) {
                                                                    
                                                                    if (empty($mark->score)) {
                                                                        echo "-";
                                                                    }else if($mark->score !="X" && $mark->score !="Y" && $mark->score !="x" && $mark->score !="y" && $mark->score !="n" && $mark->score !="N"){
                                                                        //echo var_dump($mark->mark);
                                                                        if (is_numeric($mark->score)) {
                                                                            $score=$mark->score;
																			if(count($grades)) {
																				foreach ($grades as $grade) {
																					if($grade->gradefrom <= $score && $grade->gradeupto >= $score) {
																							echo $score.' '.$grade->grade;
																					}
																				}
																			}
																			$stotalmarks+=$score;
                                                                        }else{
                                                                            $score=$mark->score;
                                                                            echo $score.' Y';
                                                                            array_push($xandy, 'Y');
																			$stotalmarks+=$score;
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
                                                                    echo $stotalmarks;
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
																							$yes++;
																							$mss+=$grade->points;
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
																							$yes++;
																							$mss+=$grade->points;
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
																						$mss+=$grde->points;
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
                                                            $subjectcount=$this->ranking_m->get_total_subject_count($student->roll,$examID,$classesID,$year,$term_name);

                                                            foreach ($subjectcount as $keyvalue) {
                                                                if($keyvalue->sno ==7){
                                                                               $points=0;
                                                                               $spoint=$this->ranking_m->get_subjects($student->studentID,$examID,$classesID,$year,$term_name);
																				//print_r($spoint);
                                                                            foreach ($spoint as $spoint) {
                                                                                $grades2=$this->grade_m->get_grade(array("classesID"=>$classesID,"subjectID"=>$spoint->subjectID));
                                                                                $score=$spoint->mark;
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
																						"roll"=>$student->studentID,
																						"examID"=>$examID,
																						"classesID"=>$classesID,
																						"term"=>$student->term,
																						"year"=>$year
																						);
																						$array= array(
																						"roll"=>$student->studentID,
																						"examID"=>$examID,
                                                                                        "classesID"=>$classesID,
																						"average"=>$ttmarke,
																						"total_points"=>$points,
																						"subject_entry"=>$keyvalue->sno,
																						"overall_grade"=>$grade,
																						"term"=>$student->term,
																						"year"=>$year
																						);
																						$check_content=$this->student_averages_m->get_student_average($array1);
																						if(count($check_content)){
																							$data= array(
																							"average"=>$ttmarke,
																							"total_points"=>$points,
																							"subject_entry"=>$keyvalue->sno,
																							"overall_grade"=>$grade,
																							);
																							$array=array(
                                                                                            "classesID"=>$classesID,
																							"roll"=>$student->studentID,
																							"examID"=>$examID,
																							"term"=>$student->term,
																							"year"=>$year
																							);
																							if($this->student_averages_m->update_student_average($data, $array)){
																								//echo "record updated!";
																							}else{
																								echo "error!";
																							}
																							
																						}else{
																							$this->student_averages_m->save_student_average($array);
																						}
                                                                                    }
                                                                                }
                                                                            }
                                                                    }else if($keyvalue->sno<7){
                                                                            $points=0;
                                                                               $spoint=$this->ranking_m->get_subjects($student->studentID,$examID,$classesID,$year,$term_name);
                
                                                                            foreach ($spoint as $spoint) {
                                                                                $grades2=$this->grade_m->get_grade(array("classesID"=>$classesID,"subjectID"=>$spoint->subjectID));
                                                                                $score=$spoint->mark;
                                                                                    if(count($grades2)) {
                                                                                        foreach ($grades2 as $grade) {
                                                                                            if($grade->gradefrom <= $score && $grade->gradeupto >= $score) {
                                                                                                $points+=$grade->point;
                                                                                            }
                                                                                        }
                                                                                    }
                                                                            }
																			
																				$array= array(
																				"roll"=>$student->studentID,
																				"examID"=>$examID,
																				"classesID"=>$classesID,
																				"average"=>$ttmarke,
																				"total_points"=>$points,
																				"subject_entry"=>$keyvalue->sno,
																				"overall_grade"=>"Z",
																				"term"=>$student->term,
																				"year"=>date("Y")
																				);
																				$array1= array(
																				"roll"=>$student->studentID,
																				"examID"=>$examID,
																				"classesID"=>$classesID,
																				"term"=>$student->term,
                                                                                "year"=>$year
																				);
																				$check_content=$this->student_averages_m->get_student_average($array1);
																						if(count($check_content)){
																							$data= array(
																							"average"=>$ttmarke,
																							"total_points"=>$points,
																							"subject_entry"=>$keyvalue->sno,
																							"overall_grade"=>$grade->grade,
																							);
																							$array=array(
                                                                                            "classesID"=>$classesID,
																							"roll"=>$student->studentID,
																							"examID"=>$examID,
																							"term"=>$student->term,
                                                                                            "year"=>$year
																							);
																							if($this->student_averages_m->update_student_average($data, $array)){
																								//echo "record updated!";
																							}else{
																								echo "error!";
																							}
																						}else{
																							$this->student_averages_m->save_student_average($array);
																						}
                                                                    }else if($keyvalue->sno ==8){
                                                                        $sciences=$this->ranking_m->sciences_count($student->studentID,$examID,$classesID);
                                                                        foreach ($sciences as $keyvalues) {
                                                                            $sciences_count=$keyvalues->total_s;
                                                                        }
                                                                        $humanities=$this->ranking_m->humanities_count($student->studentID,$examID,$classesID);
                                                                        foreach ($humanities as $keyvalueh) {
                                                                            $humanities_count=$keyvalueh->total_h;
                                                                            
                                                                        }
                                                                        if ($sciences_count==2 && $humanities_count==2) {
                                                                            $humanities_IDs=$this->ranking_m->humanities_IDS($student->studentID,$examID,$classesID);

                                                                            foreach ($humanities_IDs as $humanities_IDs) {
                                                                                $subjecth=$humanities_IDs->subjectID;
                                                                                

                                                                            }
                                                                            $option1points=0;
                                                                                   $option1spoint=$this->ranking_m->get_option1_subjects($student->studentID,$examID,$classesID,$subjecth,$year,$term_name);
                                                                                foreach ($option1spoint as $option1spoint) {
                                                                                     $grades2=$this->grade_m->get_grade(array("classesID"=>$classesID,"subjectID"=>$option1spoint->subjectID));
                                                                                    $option1score=$option1spoint->mark;
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
																						"roll"=>$student->studentID,
																						"examID"=>$examID,
                                                                                        "classesID"=>$classesID,
																						"average"=>$ttmarke,
																						"total_points"=>$option1points,
																						"subject_entry"=>$keyvalue->sno,
																						"overall_grade"=>$grade->grade,
																						"term"=>$student->term,
                                                                                        "year"=>$year
																						);
																						$array1= array(
																						"roll"=>$student->studentID,
																						"examID"=>$examID,
                                                                                        "classesID"=>$classesID,
																						"term"=>$student->term,
																						"year"=>$year
																						);
																						$check_content=$this->student_averages_m->get_student_average($array1);
																						if(count($check_content)){
																							$data= array(
																							"average"=>$ttmarke,
																							"total_points"=>$option1points,
																							"subject_entry"=>$keyvalue->sno,
																							"overall_grade"=>$grade->grade,
																							);
																							$array=array(
                                                                                            "classesID"=>$classesID,
																							"roll"=>$student->studentID,
																							"examID"=>$examID,
																							"term"=>$student->term,
																							"year"=>$year
																							);
																							//print_r($data);
																							//print_r($array);
																							//$returns=$this->student_averages_m->update_student_averages($ttmark,$option1points,$keyvalue->sno,$grade->grade,
																							//$classesID,$student->studentID,$examID,$student->term,date("Y"));
																							//print_r($returns);
																							if($this->student_averages_m->update_student_average($data, $array)){
																								//echo "record updated!";
																							}else{
																								echo "error!";
																							}
																						}else{
																							$this->student_averages_m->save_student_average($array);
																						}
                                                                                    }
                                                                                }
                                                                            }
                                                                        }else{
                                                                            $sciences_IDs=$this->ranking_m->sciences_IDS($student->studentID,$examID,$classesID);
                                                                                    foreach ($sciences_IDs as $sciences_IDs) {
                                                                                        $subject_s=$sciences_IDs->subjectID;
                                                                                    }
                                                                                    $option2points=0;
                                                                                   $option2spoint=$this->ranking_m->get_option2_subjects($student->studentID,$examID,$classesID,$subject_s,$year,$term_name);
                                                                                foreach ($option2spoint as $option2spoint) {
                                                                                    $grades2=$this->grade_m->get_grade(array("classesID"=>$classesID,"subjectID"=>$option2spoint->subjectID));
                                                                                    $option2score=$option2spoint->mark;
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
																						"roll"=>$student->studentID,
																						"examID"=>$examID,
                                                                                        "classesID"=>$classesID,
																						"average"=>$ttmarke,
																						"total_points"=>$option2points,
																						"subject_entry"=>$keyvalue->sno,
																						"overall_grade"=>$grade->grade,
																						"term"=>$student->term,
																						"year"=>$year
																						);
																						$array1= array(
																						"roll"=>$student->studentID,
																						"examID"=>$examID,
                                                                                        "classesID"=>$classesID,
																						"term"=>$student->term,
																						"year"=>$year
																						);
																						
																						$check_content2=$this->student_averages_m->get_student_average($array1);
																						if(count($check_content2)){
																							$data= array(
																							"average"=>$ttmarke,
																							"total_points"=>$option2points,
																							"subject_entry"=>$keyvalue->sno,
																							"overall_grade"=>$grade->grade,
																							);
																							$array=array(
                                                                                            "classesID"=>$classesID,
																							"roll"=>$student->studentID,
																							"examID"=>$examID,
																							"term"=>$student->term,
																							"year"=>$year
																							);
																							if($this->student_averages_m->update_student_average($data, $array)){
																								echo "record updated!";
																							}else{
																								echo "error!";
																							}
																						}else{
																							$this->student_averages_m->save_student_average($array);
																						}
																						
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                    }else{
                                                                            //print_r($subjectcount);
                                                                    }

                                                            } 
														}
													//echo $student->term;
												}
												if ($classesID !=13 && $classesID !=14) {
													
												}else{
													$array=array(
													"f.classesID"=>$classesID,
													"f.year"=>date("Y")
													);
													$k=1;
													$studentsw_data=$this->ranking_m->gets_sstudents($classesID,$examID,$year,$term_name);
													//print_r($students_data);

													if(count($studentsw_data)){
													$i = 0; 
                                                    $j = 0; 
                                                    $ttmark=false;
													foreach($studentsw_data as $sdata){
														$form3and4a="f3and4".$sdata->name;
														$form3and4a=array();
														if ($ttmark != $sdata->tp) {
                                                            $ttmark=$sdata->tp;
                                                            $i++;
                                                            if ($j>0) {
                                                                $i+=$j;
                                                                $j=0;
                                                            }
                                                        }else{
                                                            $j++;
                                                        }

                                                        $coposition=$this->ranking_m->get_class_position($examID,$classesID,$sdata->sectionID,$sdata->roll,$year,$term_name);
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
                                                        <?php echo $sdata->kcpe_mark; ?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('admno')?>">
                                                        <?php echo $sdata->roll; ?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('ranking_name')?>">
                                                        <?php echo ucwords(strtolower($sdata->name)); ?>
                                                    </td>
                                                    
                                                    <?php
													
                                                        foreach ($subjects as $subject ) {
                                                            $markss=$this->ranking_m->get_subject_mark($sdata->studentID,$examID,$subject->subjectID,$year,$term_name);
                                                            $grades4=$this->grade_m->get_grade(array("classesID"=>$classesID,"subjectID"=>$subject->subjectID));
                                                            ?>
                                                            <td data-title="<?=$this->lang->line('ranking_section');?>">
                                                                <?php 

                                                                foreach ($markss as $markss) {
                                                                    
                                                                    if (empty($markss->score)) {
                                                                        echo "-";
                                                                    }else if($markss->score !="X" && $markss->score !="Y" && $markss->score !="x" && $markss->score !="y" && $markss->score !="n" && $markss->score !="N"){
                                                                        
                                                                        if(count($grades4)) {
																			if (is_numeric($markss->score)) {
																				$scoress=$markss->score;
																				if(count($grades4)) {
																					foreach ($grades4 as $grade) {
																						if($grade->gradefrom <= $scoress && $grade->gradeupto >= $scoress) {
																								echo $scoress.' '.$grade->grade;
																						}
																					}
																				}
																			}else{
																				$scoress=$markss->score;
																				echo $scoress.' Y';
																				array_push($form3and4a, 'Y');
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
                                                                echo $sdata->tp;
                                                            echo "</td>";
															echo "<td data-title='".$this->lang->line('ranking_grade')."'>";
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
																			echo $sdata->grade." (Y)";
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
                                                                        echo $sdata->grade;
                                                                        if($sdata->grade==="A"){
                                                                                            $as++;
                                                                        }else if($sdata->grade==="A-"){
                                                                            $aminuses++;
                                                                        }else if($sdata->grade==="B+"){
                                                                            $bpluses++;
                                                                        }else if($sdata->grade==="B"){
                                                                            $bplains++;
                                                                        }else if($sdata->grade==="B-"){
                                                                            $bminuses++;
                                                                        }else if($sdata->grade==="C+"){
                                                                            $cpluses++;
                                                                        }else if($sdata->grade==="C"){
                                                                            $cplains++;
                                                                        }else if($sdata->grade==="C-"){
                                                                            $cminuses++;
                                                                        }else if($sdata->grade==="D+"){
                                                                            $dpluses++;
                                                                        }else if($sdata->grade==="D"){
                                                                            $dplains++;
                                                                        }else if($sdata->grade==="D-"){
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
                                                       ?>
                                                        <td data-title="<?=$this->lang->line('ranking_subject_totals');?>"><strong><i>
                                                            <?php 
                                                             $sub_total=$this->ranking_m->get_subject_total($examID,$subject->subjectID,$classesID,$year,$term_name);
                                                            foreach ($sub_total as $sub_total) {
                                                                    echo number_format((float)($sub_total->mark),0,'.','');
                                                                }
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
                                                       ?>
                                                        <td data-title="<?=$this->lang->line('ranking_subject_averages');?>"><strong><i>
                                                            <?php 
                                                             $sub_avg=$this->ranking_m->get_subject_average($examID,$subject->subjectID,$classesID);
                                                            foreach ($sub_avg as $sub_avg) {
                                                                    echo number_format((float)($sub_avg->mark),2,'.','');
                                                                }
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
                                                       ?>
                                                        <td data-title="<?=$this->lang->line('ranking_subject_mean_grade');?>"><strong><i>
                                                            <?php 
                                                             $sub_avg=$this->ranking_m->get_subject_average($examID,$subject->subjectID,$classesID);
                                                            foreach ($sub_avg as $sub_avg) {
                                                                    $savg= number_format((float)($sub_avg->mark),0,'.','');
                                                                    $grades4s=$this->grade_m->get_grade(array("classesID"=>1,"subjectID"=>1));
                                                                    if(count($grades4s)) {
                                                                        foreach ($grades4s as $grade) {
                                                                            if($grade->gradefrom <= $savg && $grade->gradeupto >= $savg) {
                                                                                echo $grade->grade;
                                                                            }
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
                                                            $ototal=$this->ranking_m->get_overall_total($examID,$classesID);
                                                                ?>
                                                                    <td><strong>
                                                                <?php
                                                                foreach ($ototal as $ototal) {
                                                                    echo number_format((float)($ototal->mark),0,'.','');
                                                                }
                                                                ?>
                                                                </strong></td>
                                                                 <?php 
                                                             $oavg=$this->ranking_m->get_overall_average($examID,$classesID);
                                                            foreach ($oavg as $oavg) {
                                                                ?><td><strong>
                                                                <?php
                                                                    echo number_format((float)(($oavg->mark)*11),2,'.','');
                                                                ?>
                                                                </strong></td>
																<td><strong><?php echo round($mss/$entry,3);?></strong></td>
																<?php
                                                                    $osavg= number_format((float)($oavg->mark),0,'.','');
                                                                    $grades4s=$this->grade_m->get_grade(array("classesID"=>1,"subjectID"=>1));
                                                                    if(count($grades4s)) {
                                                                        foreach ($grades4s as $grade) {
                                                                            if($grade->gradefrom <= $osavg && $grade->gradeupto >= $osavg) {
                                                                                
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
																$omean=$this->ranking_m->get_mean($examID,$classesID);
																foreach($omean as $mean){
																	$pmean=round($mean->mean,2);
																	$gmean=round($mean->mean,0);
																}
                                                                $grades4s=$this->grade_m->get_grade(array("classesID"=>1,"subjectID"=>1));
                                                                if(count($grades4s)) {
                                                                    foreach ($grades4s as $grade) {
                                                                        if($grade->point <= $gmean && $grade->point >= $gmean) {
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
                                        </table>
										
                                    </div>
                                </div>

                                <?php foreach ($sections as $section) {
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
                                        <b><h3 class="text-center"><?php 
                                        $exam = $this->db->select('exam')->from('exam')->where('examID', $examID)->limit(1)->get()->row_array();
                                        echo $sect['section'].'/'.$term_name.'/'.$exam['exam'].'/'.$year.' Exam Results';
                                        ?></h3></b>
                                        
                                                <?php 
												$smss=0;
													if($classesID !=13 && $classesID !=14){
														?>
														<table id="example2" class="table table-striped table-bordered table-hover table-condensed">
														<thead>
															<tr>
																<th><strong><?=$this->lang->line('position')?></strong></th>
																<th><strong><?=$this->lang->line('overallposition')?></strong></th>
																<th><strong><?=$this->lang->line('kcpe')?></strong></th>
																<th><strong><?=$this->lang->line('admno')?></strong></th>
																<th><strong><?=$this->lang->line('ranking_student')?></strong></th>
																 <?php
																	foreach ($subjects as $subject ) {?>
																		<th><strong><?php echo $subject->subject; ?></strong></th><?php
																	}
																	?>
																<th><strong><?=$this->lang->line('ranking_total')?></strong></th>
                                                                <th><strong><?=$this->lang->line('ranking_average')?></strong></th>
                                                                <th><strong><?=$this->lang->line('ranking_grade')?></strong></th>
															</tr>
														</thead>
														<tbody>
														<?php
														$sstudents=$this->ranking_m->get_sstudent($classesID,$section->sectionID,$examID,$year,$term_name);
                                                if(count($sstudents)) {
                                                    $i = 0; 
                                                    $j = 0; 
                                                    $tmark=false;
													$p=0;
													$q=0;
													$tmarkk=false;
													$sentry =count($sstudents);
                                                    foreach($sstudents as $s_tudents) {
                                                        $ssubjecttotals=0;
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
                                                    $overalranking=$this->ranking_m->get_overall_position($examID,$classesID,$s_tudents->roll,$year,$term_name);
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
													
                                                        foreach ($subjects as $subject ) {
                                                            $mark=$this->ranking_m->get_subject_mark($s_tudents->studentID,$examID,$subject->subjectID,$year,$term_name);
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
                                                                            $sscore=$mark->score;
                                                                            if(count($grades4)) {
                                                                                foreach ($grades4 as $grade) {
                                                                                    if($grade->gradefrom <= $sscore && $grade->gradeupto >= $sscore) {
                                                                                            echo $sscore.' '.$grade->grade;
                                                                                    }
                                                                                }
                                                                            }
																			$ssubjecttotals+=$sscore;
                                                                        }else{
                                                                            $sscore=$mark->score;
                                                                            echo $sscore.' Y';
                                                                            array_push($studentArray, 'Y');
																			$ssubjecttotals+=$sscore;
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
                                                                echo $ssubjecttotals;
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
																							$smss+=$grade->points;
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
																							$smss+=$grade->points;
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
																						$smss+=$grade->points;
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
															   ?>
																<td data-title="<?=$this->lang->line('ranking_subject_totals');?>"><strong><i>
																	<?php 
																	$ssub_total=$this->ranking_m->get_ssubject_total($examID,$subject->subjectID,$classesID,$section->sectionID,$year,$term_name);
																		foreach ($ssub_total as $sub_total) {
																		   echo number_format((float)($sub_total->mark),0,'.','');
																		}
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
															   ?>
																<td data-title="<?=$this->lang->line('ranking_subject_averages');?>"><strong><i>
																	<?php 
																	 $ssub_avg=$this->ranking_m->get_ssubject_average($examID,$subject->subjectID,$classesID,$section->sectionID,$year,$term_name);
																	foreach ($ssub_avg as $sub_avg) {
																			echo number_format((float)($sub_avg->mark),2,'.','');
																		}
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
															   ?>
																<td data-title="<?=$this->lang->line('ranking_subject_mean_grade');?>"><strong><i>
																	<?php 
																	 $ssub_avg=$this->ranking_m->get_ssubject_average($examID,$subject->subjectID,$classesID,$section->sectionID,$year,$term_name);
																	foreach ($ssub_avg as $sub_avg) {
																			$ssavg= number_format((float)($sub_avg->mark),0,'.','');
																			$grades4ss=$this->grade_m->get_grade(array("classesID"=>$classesID,"subjectID"=>$subject->subjectID));
																			if(count($grades4ss)) {
																				foreach ($grades4ss as $grade) {
																					if($grade->gradefrom <= $ssavg && $grade->gradeupto >= $ssavg) {
																						echo $grade->grade;
																					}
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
                                                            $sototal=$this->ranking_m->get_section_total($examID,$classesID,$section->sectionID);
                                                                ?>
                                                                    <td><strong>
                                                                <?php
                                                                foreach ($sototal as $ototal) {
                                                                    echo number_format((float)($ototal->mark),0,'.','');
                                                                }
                                                                ?>
                                                                </strong></td>
                                                                 <?php 
                                                             $soavg=$this->ranking_m->get_section_average($examID,$classesID,$section->sectionID);
                                                            foreach ($soavg as $soavg) {
                                                                ?><td><strong>
                                                                <?php
                                                                    echo number_format((float)(($soavg->mark)*11),2,'.','');
                                                                ?>
                                                                </strong></td>
																<td><strong><?php echo round($smss/$sentry,3);?></strong></td>
																<?php
                                                                    $wsoavg= number_format((float)($soavg->mark),0,'.','');
                                                                    $grades4s=$this->grade_m->get_grade(array("classesID"=>1,"subjectID"=>1));
                                                                    if(count($grades4s)) {
                                                                        foreach ($grades4s as $grade) {
                                                                            if($grade->gradefrom <= $wsoavg && $grade->gradeupto >= $wsoavg) {
                                                                                
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
															<table id="example2" class="table table-striped table-bordered table-hover table-condensed">
															<thead>
																<tr>
																	<th><strong><?=$this->lang->line('position')?></strong></th>
																	<th><strong><?=$this->lang->line('overallposition')?></strong></th>
																	<th><strong><?=$this->lang->line('kcpe')?></strong></th>
																	<th><strong><?=$this->lang->line('admno')?></strong></th>
																	<th><strong><?=$this->lang->line('ranking_student')?></strong></th>
																	 <?php
																		foreach ($subjects as $subject ) {?>
																			<th><strong><?php echo $subject->subject; ?></strong></th><?php
																		}
																		?>
																	<th><strong><?=$this->lang->line('subjectsentry')?></strong></th>
																	<th><strong><?=$this->lang->line('total_points')?></strong></th>
																	<th><strong><?=$this->lang->line('ranking_grade')?></strong></th>
																</tr>
															</thead>
															<tbody>
															<?php
															$dataaa=array(
																"classesID"=>$classesID,
																"sectionID"=>$section->sectionID,
																"examID"=>$examID
																);
																
															$rankeddata=$this->ranking_m->get_sstudents($classesID,$section->sectionID,$examID,$year,$term_name);
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
                                                        $oranking=$this->ranking_m->get_overall_position($examID,$classesID,$rankeddata->roll,$year,$term_name);
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
                                                            $marks_s=$this->ranking_m->get_subject_mark($rankeddata->studentID,$examID,$subject->subjectID,$year,$term_name);
                                                            $grades7=$this->grade_m->get_grade(array("classesID"=>$classesID,"subjectID"=>$subject->subjectID));
                                                            ?>
                                                            <td data-title="<?=$this->lang->line('ranking_section');?>">
                                                                <?php 

                                                                foreach ($marks_s as $marks_s) {
                                                                    
                                                                    if (empty($marks_s->score)) {
                                                                        echo "-";
                                                                    }else if($marks_s->score !="X" && $marks_s->score !="Y" && $marks_s->score !="x" && $marks_s->score !="y" && $marks_s->score !="n" && $marks_s->score !="N"){
                                                                        
																		if (is_numeric($marks_s->score)) {
																			$s_coress=$marks_s->score;
																			if(count($grades7)) {
																				foreach ($grades7 as $grade) {
																					if($grade->gradefrom <= $s_coress && $grade->gradeupto >= $s_coress) {
																							echo $s_coress.' '.$grade->grade;
																					}
																				}
																			}
																		}else{
																			$s_coress=$marks_s->score;
																			echo $scoress.' Y';
																			array_push($form3and4a, 'Y');
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
                                                                echo $rankeddata->tp;
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
																	if ($rankeddata->se < 7) {
                                                                        echo "Z";
                                                                        $szes++;
                                                                    }else{
                                                                        echo $rankeddata->grade;
                                                                        if($rankeddata->grade==="A"){
                                                                            $sas++;
                                                                        }else if($rankeddata->grade==="A-"){
                                                                            $saminuses++;
                                                                        }else if($rankeddata->grade==="B+"){
                                                                            $sbpluses++;
                                                                        }else if($rankeddata->grade==="B"){
                                                                            $sbplains++;
                                                                        }else if($rankeddata->grade==="B-"){
                                                                            $sbminuses++;
                                                                        }else if($rankeddata->grade==="C+"){
                                                                            $scpluses++;
                                                                        }else if($rankeddata->grade==="C"){
                                                                            $scplains++;
                                                                        }else if($rankeddata->grade==="C-"){
                                                                            $scminuses++;
                                                                        }else if($rankeddata->grade==="D+"){
                                                                            $sdpluses++;
                                                                        }else if($rankeddata->grade==="D"){
                                                                            $sdplains++;
                                                                        }else if($rankeddata->grade==="D-"){
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
														   ?>
															<td data-title="<?=$this->lang->line('ranking_subject_totals');?>"><strong><i>
																<?php 
																$ssub_total=$this->ranking_m->get_ssubject_total($examID,$subject->subjectID,$classesID,$section->sectionID,$year,$term_name);
																	foreach ($ssub_total as $ssub_total) {
																	   echo number_format((float)($ssub_total->mark),0,'.','');
																	}
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
														   ?>
															<td data-title="<?=$this->lang->line('ranking_subject_averages');?>"><strong><i>
																<?php 
																 $ssub_avg=$this->ranking_m->get_ssubject_average($examID,$subject->subjectID,$classesID,$section->sectionID,$year,$term_name);
																foreach ($ssub_avg as $ssub_avg) {
																		echo number_format((float)($ssub_avg->mark),2,'.','');
																	}
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
														   ?>
															<td data-title="<?=$this->lang->line('ranking_subject_mean_grade');?>"><strong><i>
																<?php 
																 $ssub_avg=$this->ranking_m->get_ssubject_average($examID,$subject->subjectID,$classesID,$section->sectionID,$year,$term_name);
																foreach ($ssub_avg as $ssub_avg) {
																		$ssavg= number_format((float)($ssub_avg->mark),0,'.','');
																		$grades4ss=$this->grade_m->get_grade(array("classesID"=>$classesID,"subjectID"=>$subject->subjectID));
																		if(count($grades4ss)) {
																			foreach ($grades4ss as $grade) {
																				if($grade->gradefrom <= $ssavg && $grade->gradeupto >= $ssavg) {
																					echo $grade->grade;
																				}
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
															$somean=$this->ranking_m->get_section_mean($examID,$classesID,$section->sectionID);
															foreach($somean as $somean){
																$spmean=round($somean->mean,2);
																$sgmean=round($somean->mean,0);
															}
															$grades4s=$this->grade_m->get_grade(array("classesID"=>1,"subjectID"=>1));
															if(count($grades4s)) {
																foreach ($grades4s as $grade) {
																	if($grade->point <= $sgmean && $grade->point >= $sgmean) {
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

                                   $total=$this->ranking_m->get_student_total($student->studentID,$examID,$classesID,$year,$term_name);?>
                                    <td data-title="<?=$this->lang->line('ranking_section');?>">
                                        <?php 
                                        foreach ($total as $total) {
                                            echo $total->score;
                                        }
                                         ?>
                                    </td>
                                    <?php
                                    $student_avg=$this->ranking_m->get_student_average($student->studentID,$examID,$classesID,$year,$term_name);
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
