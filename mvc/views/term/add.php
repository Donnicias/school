
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-systemadmin"></i> <?=$this->lang->line('panel_title')?></h3>

       
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("term/index")?>"><?=$this->lang->line('menu_term_setting')?></a></li>
            <li class="active"><?=$this->lang->line('menu_add')?> <?=$this->lang->line('menu_add_term')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-8">
                <form class="form-horizontal" role="form" method="post" >
                   
                    <?php 
                        if(form_error('term_name')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="name_id" class="col-sm-2 control-label">
                            <?=$this->lang->line("term_name")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="term_name" name="term_name" value="<?=set_value('term_name')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('term_name'); ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("add_term")?>" >
                        </div>
                    </div>

                </form>
            </div>    
        </div>
    </div>
</div>