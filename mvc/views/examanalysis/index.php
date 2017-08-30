
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-bar-chart"></i> <?=$this->lang->line('panel_title')?></h3>

       
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active">General Exam Analysis</li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
            <?php 
                $usertype = $this->session->userdata("usertype");
                if($usertype == "Admin") {
            ?>
                <h5 class="page-header">
                    <a href="<?php echo base_url('examanalysis/graph') ?>">
                        <i class="fa fa-plus"></i> 
                        <?=$this->lang->line('add_title')?>
                    </a>
                </h5>
            <?php } ?>
            <h4 class="page-header text-center">Average performance of classes in each exam</h4>  
                <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                    <thead>
                        <tr>
                            <th class="col-lg-1"><?=$this->lang->line('slno')?></th>
                            <th class="col-lg-3"><?=$this->lang->line('examanalysis_class')?></th>
                            <?php
                                foreach ($exams as $exam) {
                                ?>
                                <th class="col-lg-3"><?=$exam->exam;?></th>
                                <?php    
                                }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($classes)) {$i = 1; foreach($classes as $class) { ?>
                            <tr>
                                <td data-title="<?=$this->lang->line('slno')?>">
                                    <span class="badge"><?php echo $i; ?></span>
                                </td>
                                <td data-title="<?=$this->lang->line('exam_name')?>">
                                    <?php echo $class->classes; ?>
                                </td>
                                <?php
                                    foreach ($exams as $exam) {
                                    $value=$this->examanalysis_m->get_class_performance($class->classesID,$exam->examID);
                                        foreach ($value as $value) {
                                            if ($value->mark!='') {
                                               ?>
                                                    <td data-title="<?=$this->lang->line('exam_date')?>">
                                                        <?php echo $value->mark; ?>
                                                    </td>
                                                <?php 
                                            }else{
                                                ?>
                                                    <td data-title="<?=$this->lang->line('exam_date')?>">
                                                        <?php echo "_"; ?>
                                                    </td>
                                                <?php 
                                            }
                                        } 
                                    }
                                ?>
                            </tr>
                        <?php $i++; }} ?>
                    </tbody>
                </table>
            </div> <!-- col-sm-12 -->
            <h3 class="page-header text-center">Grades distributions per exam</h3>
            <?php
                $j=1;
                foreach ($exams as $exam) {
                ?>
                <div class="col-sm-12">
                    <h5 class="page-header text-center"><label class="text-info"><?=$j.'. '.$exam->exam;?></label></h5>  
                        <table id="example11" class="table table-striped table-bordered table-hover dataTable no-footer">
                            <thead>
                                <tr>
                                    <th class="col-lg-1"><?=$this->lang->line('slno')?></th>
                                    <th class="col-lg-3"><?=$this->lang->line('examanalysis_class')?></th>
                                    <?php
                                        foreach ($grades as $grade) {
                                        ?>
                                        <th class="col-lg-3"><?=$grade->grade;?></th>
                                        <?php    
                                        }
                                    ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(count($classes)) {$i = 1; foreach($classes as $class) { ?>
                                    <tr>
                                        <td data-title="<?=$this->lang->line('slno')?>">
                                            <span class="badge"><?php echo $i; ?></span>
                                        </td>
                                        <td data-title="<?=$this->lang->line('exam_name')?>">
                                            <?php echo $class->classes; ?>
                                        </td>
                                        <?php
                                            foreach ($grades as $grade) {
                                            $value=$this->examanalysis_m->get_grade_count($grade->gradefrom,$grade->gradeupto,$class->classesID,$exam->examID);
                                                if (count($value)>0) {
                                                   ?>
                                                        <td data-title="<?=$this->lang->line('exam_date')?>">
                                                            <?php echo count($value); ?>
                                                        </td>
                                                    <?php 
                                                }else{
                                                    ?>
                                                        <td data-title="<?=$this->lang->line('exam_date')?>">
                                                            <?php echo "_"; ?>
                                                        </td>
                                                    <?php 
                                                }
                                            }
                                        ?>
                                    </tr>
                                <?php $i++; }} ?>
                            </tbody>
                        </table>
                    </div> <!-- col-sm-12 -->
                <?php 
                $j++;   
                }
            ?>
        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->

