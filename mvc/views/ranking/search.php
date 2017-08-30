
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
                <div class="col-sm-6 col-sm-offset-3 list-group">
                    <div class="list-group-item list-group-item-warning">
                        <form class="form-horizontal" role="form"  method="post" >
                            <?php 
                                if(form_error('examID')) 
                                    echo "<div class='form-group has-error' >";
                                else     
                                    echo "<div class='form-group' >";
                            ?>
                                <label for="examID" class="col-sm-2 col-sm-offset-2 control-label">
                                    <?=$this->lang->line('ranking_exam')?>
                                </label>
                                <div class="col-sm-6">
                                    <?php
                                        $array = array("0" => $this->lang->line("ranking_select_exam"));
                                        foreach ($exams as $exam) {
                                            $array[$exam->examID] = $exam->exam;
                                        }
                                        echo form_dropdown("examID", $array, set_value("examID", $set_exam), "id='examID' name='examID' class='form-control'");
                                    ?>
                                </div>
                            </div>


                            <?php 
                                if(form_error('classesID')) 
                                    echo "<div class='form-group has-error' >";
                                else     
                                    echo "<div class='form-group' >";
                            ?>
                                <label for="classesID" class="col-sm-2 col-sm-offset-2 control-label">
                                    <?=$this->lang->line('ranking_classes')?>
                                </label>
                                <div class="col-sm-6">
                                    <?php
                                        $array = array("0" => $this->lang->line("ranking_select_classes"));
                                        foreach ($classes as $classa) {
                                            $array[$classa->classesID] = $classa->classes;
                                        }
                                        echo form_dropdown("classesID", $array, set_value("classesID", $set_classes), "id='classesID' name='classesID' class='form-control'");
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
                                    <?=$this->lang->line('ranking_year')?>
                                </label>
                                <div class="col-sm-6">
                                    <?php
                                        $array = array("0" => $this->lang->line("ranking_select_year"));
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
                                    <?=$this->lang->line('ranking_term')?>
                                </label>
                                <div class="col-sm-6">
                                    <?php
                                        $array = array("0" => $this->lang->line("ranking_select_term"));
                                        if($terms != 0) {
                                            foreach ($terms as $term) {
                                                $array[$term->term_id] = $term->term_name;
                                            }
                                        }
                                        echo form_dropdown("termID", $array, set_value("termID", $set_term), "id='termID' class='form-control'");
                                    ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-4 col-sm-8">
                                    <input type="submit" class="btn btn-success" style="margin-bottom:0px" value="<?=$this->lang->line("view_rank")?>">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div> <!-- col-sm-12 -->
            
        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->