<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-exclamation-triangle"></i> <?=$this->lang->line('panel_title')?></h3>

       
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_bookslost')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <?php
                    $usertype = $this->session->userdata("usertype");
                    if($usertype == "Admin" || $usertype == "Librarian") {
                ?>
                    <h5 class="page-header">
                        <a href="<?php echo base_url('book/add') ?>">
                            <i class="fa fa-plus"></i> 
                            <?=$this->lang->line('add_title')?>
                        </a>
                    </h5>
                <?php } ?>


                <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th class="col-sm-1"><?=$this->lang->line('slno')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('bookslost_photo')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('bookslost_name')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('bookslost_form')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('bookslost_lid')?></th>
                                <?php if($usertype == "Admin" || $usertype == "Librarian") { ?>
                                <th class="col-sm-1"><?=$this->lang->line('bookslost_total_lost')?></th>
                                <th class="col-sm-1"><?=$this->lang->line('action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($books)) {
                                $i = 1; foreach($books as $book) { 
                                    $studentDetails=$this->bookslost_m->get_student_details($book->lID);
                                ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('bookslost_photo')?>">
                                        <?php $array = array(
                                                "src" => base_url('uploads/images/'.$studentDetails->photo),
                                                'width' => '35px',
                                                'height' => '35px',
                                                'class' => 'img-rounded'

                                            );
                                            echo img($array); 
                                        ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('bookslost_name')?>">
                                        <?php echo $book->name; ?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('bookslost_form')?>">
                                        <?php echo $studentDetails->form; ?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('bookslost_lid')?>">
                                        <?php echo $book->lID; ?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('bookslost_total_lost')?>">
                                        <?php 
                                           echo $book->total;  
                                        ?>
                                    </td>

                                    <?php if($usertype == "Admin" || $usertype == "Librarian") { ?>
                                    <td data-title="<?=$this->lang->line('action')?>">
                                        <?php echo btn_view('bookslost/view/'.$book->lID.'/'.$studentDetails->studentID, $this->lang->line('view')). " "; ?>
                                    </td>
                                    <?php } ?>
                                </tr>
                            <?php $i++; }} ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
