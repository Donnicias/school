<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-flask"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("examoutofsettings/index")?>"><?=$this->lang->line('menu_examoutofsettings')?></a></li>
            <li class="active"><?=$this->lang->line('menu_edit')?> <?=$this->lang->line('menu_subjectmarkoutof')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <form class="form-horizontal" role="form" method="post">
                    <?php 
                    if (count($subject_data)) {
                        foreach ($subject_data as $data) {
                        if(form_error('term_year')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="year" class="col-sm-2 control-label">
                            <?=$this->lang->line("examoutofsettings_year")?>
                        </label>
                        <div class="col-sm-6">
                           <input type="text" class="form-control" id="term_year" name="term_year" value="<?=set_value('term_year', $year);?>"  readonly="">
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('term_year'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('term_name')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="term_name" class="col-sm-2 control-label">
                            <?=$this->lang->line("examoutofsettings_term")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="term_name" name="term_name" value="<?=set_value('term_name', $term_name);?>"  readonly="">
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('term_name'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('class')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="class" class="col-sm-2 control-label">
                            <?=$this->lang->line("classesID")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="class" name="class" value="<?=set_value('class', $data->class);?>" readonly="">
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('class'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('subject')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="subject" class="col-sm-2 control-label">
                            <?=$this->lang->line("subjectID")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="subject" name="subject" value="<?=set_value('subject', $data->subject)?>"  readonly="">
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('subject'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('subjectMark')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="subjectMark" class="col-sm-2 control-label">
                            <?=$this->lang->line("subjectMark")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="subjectMark" name="subjectMark" value="<?=set_value('subjectMark', $data->subjectMark)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('subjectMark'); ?>
                        </span>
                    </div>

                    <?php
                     }
                    }
                    ?>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("update_examoutofmarks")?>" >
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>