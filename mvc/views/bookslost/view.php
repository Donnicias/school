<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-exclamation-triangle"></i> <?=$this->lang->line('panel_title')?></h3>

       
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("bookslost/index")?>"><?=$this->lang->line('bookslost_bookslost')?></a></li>
            <li class="active"><?=$this->lang->line('bookslost_details')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <?php
                    $usertype = $this->session->userdata("usertype");
                ?>

                <h2 class="page-header text-center">
                    <?php $array = array(
                                        "src" => base_url('uploads/images/'.$studentDetails->photo),
                                        'width' => '100px',
                                        'height' => '100px',
                                        'class' => 'img-circle'

                                    );
                                    echo img($array); 
                                ?><br/>
                                Name: <?php echo $studentDetails->name;?><br/>
                                Form: <?php echo $studentDetails->form;?><br/>
                </h2>
                <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th class="col-sm-1"><?=$this->lang->line('slno')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('bookslost_book')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('bookslost_isbn')?></th>
                                <?php if($usertype == "Admin" || $usertype == "Librarian") { ?>
                                <th class="col-sm-1"><?=$this->lang->line('bookslost_date_lost')?></th>
                                <th class="col-sm-1"><?=$this->lang->line('bookslost_date_found')?></th>
                                <th class="col-sm-1"><?=$this->lang->line('bookslost_status')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($books)) {
                                $i = 1; foreach($books as $book) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('bookslost_book')?>">
                                        <?php echo $book->book; ?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('bookslost_isbn')?>">
                                        <?php echo $book->serial_no; ?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('bookslost_date_lost')?>">
                                        <?php 
                                            if ($book->lost_date !="") {
                                                echo $book->lost_date; 
                                            }else{
                                                echo "---";
                                            }
                                        ?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('bookslost_date_found')?>">
                                        <?php 
                                            if ($book->found_date !="") {
                                                echo $book->found_date; 
                                            }else{
                                                echo "---";
                                            } 
                                        ?>
                                    </td>

                                    <?php if($usertype == "Admin" || $usertype == "Librarian") { ?>
                                    <td data-title="<?=$this->lang->line('bookslost_status')?>">
                                        <?php 
                                        if ($book->found_status ==1) {
                                            ?>
                                            <label class="label label-info"><strong>Found</strong></label>
                                            <?php
                                        }else{
                                            ?>
                                            <label class="label label-danger"><strong>Not Found</strong></label>
                                            <?php
                                        } ?>
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
