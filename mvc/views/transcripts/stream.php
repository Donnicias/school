
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-flask"></i> <?=$this->lang->line('panel_title')?></h3>

       
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("transcripts/filter")?>"><?=$this->lang->line('filter')?></a></li>
            <li class="active"><?=$section?></li>
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
                        <a href="<?php echo base_url('transcripts/index') ?>">
                            <i class="glyphicon glyphicon-filter"></i> 
                            <?=$this->lang->line('filter_per_class')?>
                        </a>
                    </h5>
                <?php } ?>

                <div class="col-sm-6 col-sm-offset-3 list-group text-center">
                    <div class="list-group-item list-group-item-warning">
                        <form style="" class="form-horizontal" role="form" method="post" enctype="multipart/form-data">  

                            <?php 
                                if(form_error('examID')) 
                                    echo "<div class='form-group has-error' >";
                                else     
                                    echo "<div class='form-group' >";
                            ?>
                                <label for="examID" class="col-sm-2 col-sm-offset-2 control-label">
                                    <?=$this->lang->line("transcripts_exams")?>
                                </label>
                                <div class="col-sm-6">
                                    <?php
                                        $array = array("0" => $this->lang->line("transcripts_select_exam"));
                                        foreach ($exams as $exam) {
                                            $array[$exam->examID] = $exam->exam;
                                        }
                                        echo form_dropdown("examID", $array, set_value("examID"), "id='examID' class='form-control'");
                                    ?>
                                </div>
                            </div>

                            <?php 
                                if(form_error('name')) 
                                    echo "<div class='form-group has-error' >";
                                else     
                                    echo "<div class='form-group' >";
                            ?>
                                <label for="name_id" class="col-sm-2 col-sm-offset-2 control-label">
                                    <?=$this->lang->line("transcripts_classes")?>
                                </label>
                                <div class="col-sm-6">
                                    <?php
                                        $array = array("0" => $this->lang->line("transcripts_select_classes"));
                                        foreach ($classes as $classa) {
                                            $array[$classa->classesID] = $classa->classes;
                                        }
                                        echo form_dropdown("classesID", $array, set_value("classesID"), "id='classesID' class='form-control'");
                                    ?>
                                </div>
                            </div>

                            <?php 
                                if(form_error('sectionID')) 
                                    echo "<div class='form-group has-error' >";
                                else     
                                    echo "<div class='form-group' >";
                            ?>
                                <label for="subjectID" class="col-sm-2 col-sm-offset-2 control-label">
                                    <?=$this->lang->line('transcripts_section')?>
                                </label>
                                <div class="col-sm-6">
                                    <?php
                                        $array = array("0" => $this->lang->line("transcripts_select_section"));
                                        echo form_dropdown("sectionID", $array, set_value("sectionID", $array), "id='sectionID' class='form-control'");
                                    ?>
                                </div>
                            </div>
                        </form>
                    </div>
                    <br/>
                       <a href="<?=base_url("transcripts/streamtranscripts/$sectionID/$classID/$examID")?>" class=""><button type="button" class="btn btn-success"><i class="fa fa-file-pdf-o"></i> Print All Transcripts</button></a> 
                </div> <!-- col-sm-6 --> 
                

                <div id="hide-table">
                
                <strong>
                    <h2 class="text-center">Current Class: <?php echo $section;?> </h2>
                </strong>

                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th class="col-sm-2"><?=$this->lang->line('position')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('transcripts_photo')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('transcripts_name')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('transcripts_roll')?></th>
                                <?php
                                if ($classID==13 || $classID==14) {
                                    ?>
                                    <th class="col-sm-2"><?=$this->lang->line('transcripts_se')?></th>
                                    <th class="col-sm-2"><?=$this->lang->line('transcripts_tp')?></th>
                                    <th class="col-sm-2"><?=$this->lang->line('transcripts_grade')?></th>
                                    <?php
                                }else{
                                    ?>
                                    <th class="col-sm-2"><?=$this->lang->line('transcripts_tm')?></th>
                                    <th class="col-sm-2"><?=$this->lang->line('transcripts_grade')?></th>
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
                                            <td data-title="<?=$this->lang->line('transcripts_photo')?>">
                                                <?php $array = array(
                                                        "src" => base_url('uploads/images/'.$student->photo),
                                                        'width' => '35px',
                                                        'height' => '35px',
                                                        'class' => 'img-rounded'

                                                    );
                                                    echo img($array); 
                                                ?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('transcripts_name')?>">
                                                <?php echo ucwords(strtolower($student->name)); ?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('transcripts_roll')?>">
                                                <?php echo $student->roll; ?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('transcripts_tp')?>">
                                                <?php echo $student->se; ?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('transcripts_tp')?>">
                                                <?php echo $student->tp; ?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('transcripts_grade')?>">
                                                <?php echo $student->grade; ?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('action')?>">
                                                <?php 
                                                    if($usertype == "Admin" || $usertype == "Teacher") {
                                                        //echo btn_view('transcripts/view/'.$student->studentID."/".$set, $this->lang->line('view'));

                                                        ?>
                                                        <a href="<?=base_url("transcripts/view1/$classID/$examID/$student->studentID/$i/$student->tp/$totalstudents")?>" class=""><button type="button" class="btn btn-success btn-sm"><i class="fa fa-file-pdf-o"></i></button></a> 
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
                                            <td data-title="<?=$this->lang->line('transcripts_photo')?>">
                                                <?php $array = array(
                                                        "src" => base_url('uploads/images/'.$student->photo),
                                                        'width' => '35px',
                                                        'height' => '35px',
                                                        'class' => 'img-rounded'

                                                    );
                                                    echo img($array); 
                                                ?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('transcripts_name')?>">
                                                <?php echo ucwords(strtolower($student->name)); ?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('transcripts_roll')?>">
                                                <?php echo $student->roll; ?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('transcripts_tm')?>">
                                                <?php echo round($student->stotal,0); ?>
                                            </td>
                                            <?php
                                                echo "<td data-title='".$this->lang->line('transcripts_grade')."'>";
                                                $kgrades = $this->grade_m->get_grade(array("classesID"=>1,"subjectID"=>1));
                                                    $sccore=round($student->avg,0);
                                                    if(count($kgrades)) {
                                                                foreach ($kgrades as $grade) {
                                                                    if($grade->gradefrom <= $sccore && $grade->gradeupto >= $sccore) {
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
                                                        ?>
                                                       <a href="<?=base_url("transcripts/view1/$classID/$examID/$student->studentID/$i/$totalmarks/$totalstudents")?>" class=""><button type="button" class="btn btn-success btn-sm"><i class="fa fa-file-pdf-o"></i></button></a> 
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
$("#classesID").change(function() {
var id = $(this).val();
if(parseInt(id)) {
    if(id === '0') {
        $('#sectionID').val(0);
    } else {
        $.ajax({
            type: 'POST',
            url: "<?=base_url('marksheets/sectioncall')?>",
            data: {"id" : id},
            dataType: "html",
            success: function(data) {
               $('#sectionID').html(data);
            }
        });
    }
}
});

$('#sectionID').change(function() {
        var sectionID = $(this).val();
        var examID = $('#examID').val();
        var classesID = $('#classesID').val();
        if(sectionID == 0 ||classesID == 0 || examID== 0) {
            $('#hide-table').hide();
            alert("Hey? You must select exam, class and section");
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('transcripts/stream_list')?>",
                data:{sectionID:sectionID,classesID: classesID,examID: examID},
                dataType: "html",
                success: function(data) {
                    window.location.href = data;
                }
            });
        }
    });
</script>