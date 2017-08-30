
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-flask"></i> <?=$this->lang->line('panel_title')?></h3>

       
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("reportcardss/index")?>"><?=$this->lang->line('menu_reportforms_print')?></a></li>
            <li class="active"><?=$class?></li>
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
                    <h5 class="page-header">
                        <a href="<?php echo base_url('reportcardss/filter') ?>">
                            <i class="glyphicon glyphicon-filter"></i> 
                            <?=$this->lang->line('filter_title')?>
                        </a>
                    </h5>
                <?php } ?>

                <div class="col-sm-6 col-sm-offset-3 list-group text-center">
                    <div class="list-group-item list-group-item-warning">
                        <form style="" class="form-horizontal" role="form" method="post" enctype="multipart/form-data">  
                            <?php 
                                if(form_error('name')) 
                                    echo "<div class='form-group has-error' >";
                                else     
                                    echo "<div class='form-group' >";
                            ?>
                                <label for="name_id" class="col-sm-2 col-sm-offset-2 control-label">
                                    <?=$this->lang->line("reportcardss_classes")?>
                                </label>
                                <div class="col-sm-6">
                                    <?php
                                        $array = array("0" => $this->lang->line("reportcardss_select_classes"));
                                        foreach ($classes as $classa) {
                                            $array[$classa->classesID] = $classa->classes;
                                        }
                                        echo form_dropdown("classesID", $array, set_value("classesID"), "id='classesID' class='form-control'");
                                    ?>
                                </div>
                            </div>
                            <?php
                                if(form_error('year')) 
                                    echo "<div class='form-group has-error' >";
                                else     
                                    echo "<div class='form-group' >";
                            ?>
                                <label for="year" class="col-sm-2 col-sm-offset-2 control-label">
                                    <?=$this->lang->line('reportcardss_year')?>
                                </label>
                                <div class="col-sm-6">
                                    <?php
                                        $array = array("0" => $this->lang->line("reportcardss_select_year"));
                                        if($years != 0) {
                                            foreach ($years as $yr) {
                                                $array[$yr->year] = $yr->year;
                                            }
                                        }
                                        echo form_dropdown("year", $array, set_value("year", $set_year), "id='year' class='form-control'");
                                    ?>
                                </div>
                            </div>

                            <?php 
                                if(form_error('termID')) 
                                    echo "<div class='form-group has-error' >";
                                else     
                                    echo "<div class='form-group' >";
                            ?>
                                <label for="termID" class="col-sm-2 col-sm-offset-2 control-label">
                                    <?=$this->lang->line('reportcardss_term')?>
                                </label>
                                <div class="col-sm-6">
                                    <?php
                                        $array = array("0" => $this->lang->line("reportcardss_select_term"));
                                        if($termss != 0) {
                                            foreach ($termss as $term) {
                                                $array[$term->term_id] = $term->term_name;
                                            }
                                        }
                                        echo form_dropdown("termID", $array, set_value("termID", $set_term), "id='termID' class='form-control'");
                                    ?>
                                </div>
                            </div>
                        </form>
                    </div>
                    <br/>
                       <a href="<?=base_url("reportcardss/alltranscripts/$classID/$termID/$year")?>" class=""><button type="button" class="btn btn-default active"><i class="fa fa-file-pdf-o" style="background-color:#500000;color:white;font-size:18px;"></i> Print All Report Cards</button></a> 
                </div> <!-- col-sm-6 --> 
                

                <div id="hide-table">
                    <strong>
                        <h2 class="text-center">Current Class: <?php echo $class;?></h2>
                    </strong>
                
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th class="col-sm-2"><?=$this->lang->line('position')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('reportcardss_photo')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('reportcardss_name')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('reportcardss_roll')?></th>
                                <?php
                                if ($classID==13 || $classID==14) {
                                    ?>
                                    <th class="col-sm-2"><?=$this->lang->line('reportcardss_se')?></th>
                                    <th class="col-sm-2"><?=$this->lang->line('reportcardss_tp')?></th>
                                    <th class="col-sm-2"><?=$this->lang->line('treportcardss_grade')?></th>
                                    <?php
                                }else{
                                    ?>
                                    <th class="col-sm-2"><?=$this->lang->line('reportcardss_avg')?></th>
                                    <th class="col-sm-2"><?=$this->lang->line('reportcardss_grade')?></th>
                                    <?php
                                }
                                ?>
                                
                                <th class="col-sm-2"><?=$this->lang->line('action')?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $totalstudents=count($student);
                            if($totalstudents) {
                                $i = 0; 
                                $j = 0; 
                                $ttmark=false;
                             foreach($student as $student) { ?>
                                <tr>
                                    
                                    <?php
                                        if ($classID==13 || $classID==14) {
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
                                            ?>
                                            <td data-title="<?=$this->lang->line('slno')?>">
                                                <span class="badge"><?php echo $i; ?></span>
                                            </td>
                                            <td data-title="<?=$this->lang->line('reportcardss_photo')?>">
                                                <?php $array = array(
                                                        "src" => base_url('uploads/images/'.$student->photo),
                                                        'width' => '35px',
                                                        'height' => '35px',
                                                        'class' => 'img-rounded'

                                                    );
                                                    echo img($array); 
                                                ?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('reportcardss_name')?>">
                                                <?php echo ucwords(strtolower($student->name)); ?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('reportcardss_roll')?>">
                                                <?php echo $student->roll; ?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('reportcardss_tp')?>">
                                                <?php echo $student->se; ?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('reportcardss_tp')?>">
                                                <?php echo round($student->tp,0); ?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('reportcardss_grade')?>">
                                                <?php echo $student->grade; ?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('action')?>">
                                                <?php 
                                                    if($usertype == "Admin" || $usertype == "Teacher") {
                                                        //echo btn_view('transcripts/view/'.$student->studentID."/".$set, $this->lang->line('view'));
                                                        $examIDs=preg_replace('/[ ,]+/', '-', trim($examsID));
                                                        $term=preg_replace('/[ ]+/', '-', trim($terms));
                                                        ?>
                                                        <a href="<?=base_url("reportcardss/view1/$classID/$examIDs/$student->studentID/$i/$student->tp/$totalstudents/$term/$year")?>" class=""><button type="button" class="btn btn-default active btn-sm"><i class="fa fa-file-pdf-o" style="background-color:#500000;color:white;font-size:18px;"></i></button></a> 
                                                        <?php
                                                    }

                                                ?>
                                            </td>
                                            <?php
                                        }else{
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
                                            ?>
                                            <td data-title="<?=$this->lang->line('slno')?>">
                                                <span class="badge"><?php echo $i; ?></span>
                                            </td>
                                            <td data-title="<?=$this->lang->line('reportcardss_photo')?>">
                                                <?php $array = array(
                                                        "src" => base_url('uploads/images/'.$student->photo),
                                                        'width' => '35px',
                                                        'height' => '35px',
                                                        'class' => 'img-rounded'

                                                    );
                                                    echo img($array); 
                                                ?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('reportcardss_name')?>">
                                                <?php echo ucwords(strtolower($student->name)); ?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('reportcardss_roll')?>">
                                                <?php echo $student->roll; ?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('reportcardss_tm')?>">
                                                <?php echo round($student->avg,0); ?>
                                            </td>
                                            <?php
                                                echo "<td data-title='".$this->lang->line('reportcardss_grade')."'>";
                                                    $sccore=round($student->avg,0);
                                                    $grades = $this->grade_m->get_overal_performance_grading();
                                                    if(count($grades)) {
                                                                foreach ($grades as $grade) {
                                                                    if($grade->gradeFrom <= $sccore && $grade->gradeTo >= $sccore) {
                                                                            echo $grade->grade;
                                                                    }
                                                                }
                                                            }
                                                echo "</td>";
                                            ?>

                                            <td data-title="<?=$this->lang->line('action')?>">
                                                <?php 
                                                $totalmarks=round($student->stotal,0);
                                                    if($usertype == "Admin" || $usertype == "Teacher") {
                                                        //echo btn_view('transcripts/view/'.$student->studentID."/".$set, $this->lang->line('view'));
                                                        $examIDs=preg_replace('/[ ,]+/', '-', trim($examsID));
                                                        $term=preg_replace('/[ ]+/', '-', trim($terms));
                                                        ?>
                                                       <a href="<?=base_url("reportcardss/view1/$classID/$examIDs/$student->studentID/$i/$totalmarks/$totalstudents/$term/$year")?>" class=""><button type="button" class="btn btn-default active btn-sm"><i class="fa fa-file-pdf-o" style="background-color:#500000;color:white;font-size:18px;"></i></button></a> 
                                                        <?php
                                                    }

                                                ?>
                                            </td>
                                            <?php
                                        }
                                        ?>
                                    
                                </tr>
                            <?php  }} ?>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('#termID').change(function() {
        var termID = $(this).val();
        var year = $('#year').val();
        var classesID = $('#classesID').val();
        if(classesID == 0 || year== 0  || termID== 0) {
            $('#hide-table').hide();
            alert("Hey? You must select class and year first before selecting term! Try again...");
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('reportcardss/reportcardss_list')?>",
                data:{classesID: classesID,year:year,termID:termID},
                dataType: "html",
                success: function(data) {
                    window.location.href = data;
                }
            });
        }
    });
</script>