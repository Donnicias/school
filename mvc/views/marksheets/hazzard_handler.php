<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-file"></i> <?=$this->lang->line('panel_title')?></h3>

       
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active">Mark Sheets</li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <div class="col-sm-6 col-sm-offset-3 list-group">
                    <div class="list-group-item list-group-item-warning text-center">
                        <a href="<?=base_url("marksheets/index")?>"><input type="button" class="btn btn-success" style="margin-bottom:0px" value="Click to go back to mark sheets generation window" ></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>