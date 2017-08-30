<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-flask"></i> <?=$this->lang->line('panel_title')?></h3>

       
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_examoutofsettings')?></li>
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
                        <a href="<?php echo base_url('examoutofsettings/add') ?>">
                            <i class="fa fa-plus"></i> 
                            <?=$this->lang->line('add_title')?>
                        </a>
                    </h5>
                <?php } ?>

                <div class="col-sm-6 col-sm-offset-3 list-group">
                    <div class="list-group-item list-group-item-warning">
                        <form style="" class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
                        <?php
                                if(form_error('year')) 
                                    echo "<div class='form-group has-error' >";
                                else     
                                    echo "<div class='form-group' >";
                            ?>
                                <label for="year" class="col-sm-2 col-sm-offset-2 control-label">
                                    <?=$this->lang->line('examoutofsettings_year')?>
                                </label>
                                <div class="col-sm-6">
                                    <?php
                                        $array = array("0" => $this->lang->line("examoutofsettings_select_year"));
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
                                if(form_error('term_id')) 
                                    echo "<div class='form-group has-error' >";
                                else     
                                    echo "<div class='form-group' >";
                            ?>
                                <label for="term_id" class="col-sm-2 col-sm-offset-2 control-label">
                                    <?=$this->lang->line('examoutofsettings_term')?>
                                </label>
                                <div class="col-sm-6">
                                    <?php
                                        $array = array("0" => $this->lang->line("examoutofsettings_select_term"));
                                        foreach ($terms as $term) {
                                            $array[$term->term_id] = $term->term_name;
                                        }
                                        echo form_dropdown("term_id", $array, set_value("term_id", $set_term), "id='term_id' class='form-control'");
                                    ?>
                                </div>
                            </div>

                            <?php 
                                if(form_error('examID')) 
                                    echo "<div class='form-group has-error' >";
                                else     
                                    echo "<div class='form-group' >";
                            ?>
                                <label for="examID" class="col-sm-2 col-sm-offset-2 control-label">
                                    <?=$this->lang->line("examoutofsettings_exam")?>
                                </label>
                                <div class="col-sm-6">
                                    <?php
                                        $array = array("0" => $this->lang->line("examoutofsettings_select_exam"));
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
                                    <?=$this->lang->line("examoutofsettings_classes")?>
                                </label>
                                <div class="col-sm-6">
                                    <?php
                                        $array = array("0" => $this->lang->line("examoutofsettings_select_classes"));
                                        foreach ($classes as $classa) {
                                            $array[$classa->classesID] = $classa->classes;
                                        }
                                        echo form_dropdown("classesID", $array, set_value("classesID"), "id='classesID' class='form-control'");
                                    ?>
                                </div>
                            </div>

                        </form>
                    </div>
                </div> <!-- col-sm-6 --> 

                <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th class="col-sm-2"><?=$this->lang->line('slno')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('examoutofsettings_subject')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('examoutofsettings_term')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('examoutofsettings_year')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('examoutofsettings_examoutof')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('action')?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($subjects)) {
                                $i = 1; foreach($subjects as $subject) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('examoutofsettings_subject')?>">
                                        <?php 
                                            echo $subject->subject; 
                                        ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('examoutofsettings_term')?>">
                                        <?php echo $subject->term_name; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('examoutofsettings_year')?>">
                                        <?php echo $subject->year; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('examoutofsettings_examoutof')?>">
                                        <?php echo $subject->out_of; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('action')?>">
                                        <?php
                                        if ($subject->mark !='') {
                                            ?>
                                            <label class="label label-info">Not Editable!</label>
                                            <?php
                                        }else{
                                            echo btn_edit('examoutofsettings/edit/'.$subject->classesID."/".$subject->subjectID."/".$examID."/".$term_id."/".$year, $this->lang->line('edit'));
                                        }
                                        
                                        echo btn_delete('examoutofsettings/delete/'.$subject->classesID."/".$subject->subjectID."/".$examID."/".$term_id."/".$year, $this->lang->line('delete'));
                                        ?>
                                    </td>
                                </tr>
                            <?php $i++; }} ?>
                            
                        </tbody>
                    </table>
                </div>


            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('#classesID').change(function() {
        var classesID = $(this).val();
        var examID = $('#examID').val();
        var term_id = $('#term_id').val();
        var year = $('#year').val();
        if(classesID == 0 && examID == 0  && term_id == 0  && year == 0 )  {
            $('#hide-table').hide();
            alert("You must select both an exam and class first");
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('examoutofsettings/examoutofsettings_list')?>",
                data: {"classesID":classesID,"examID":examID,"term_id":term_id,"year":year},
                dataType: "html",
                success: function(data) {
                    window.location.href = data;
                }
            });
        }
    });
</script>