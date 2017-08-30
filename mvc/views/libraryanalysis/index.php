<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-pie-chart"></i> <?=$this->lang->line('menu_library_analysis')?></h3>

       
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_books_statistics')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12 col-xs-12">

                <?php
                    $usertype = $this->session->userdata("usertype");
                    if($usertype == "Admin" || $usertype == "Librarian") {
                ?>
                    <h5 class="page-header">
                        <a href="<?php echo base_url('libraryanalysis/member_statistics') ?>">
                            <i class="fa fa-users"></i> 
                            <?=$this->lang->line('view_member_statistics')?>
                        </a>
                    </h5>
                <?php } ?>


                <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th class="col-sm-1"><?=$this->lang->line('slno')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('libraryanalysis_book')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('libraryanalysis_total_registered')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('libraryanalysis_total_borrowed')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('libraryanalysis_total_lost')?></th>
                                <th class="col-sm-1"><?=$this->lang->line('libraryanalysis_total_left')?></th>
                                <th class="col-sm-1"><?=$this->lang->line('libraryanalysis_borrowing_percentage')?></th>
                                <th class="col-sm-1"><?=$this->lang->line('libraryanalysis_loosing_percentage')?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if(count($books)) {$i = 1; foreach($books as $book) { 
                                $registered=$this->book_m->registered_books($book->book);
                                $lost=$this->book_m->lost_books($book->book);
                                $available=$this->book_m->available_books($book->book);
                                $borrowed=$this->book_m->borrowed_books($book->book);
                                ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('libraryanalysis_book')?>">
                                        <?php echo $book->book; ?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('libraryanalysis_total_registered')?>">
                                        <?php echo $registered->no; ?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('libraryanalysis_total_borrowed')?>">
                                        <?php echo $borrowed->no; ?>
                                    </td>
                                    
                                    <td data-title="<?=$this->lang->line('libraryanalysis_total_lost')?>">
                                        <?php echo $lost->no; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('libraryanalysis_total_left')?>">
                                        <?php echo ($registered->no-$borrowed->no-$lost->no); ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('libraryanalysis_borrowing_percentage')?>">
                                        <?php echo round($borrowed->no/$registered->no*100,0); ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('libraryanalysis_loosing_percentage')?>">
                                        <?php echo round($lost->no/$registered->no*100,0); ?>
                                    </td>
                                </tr>
                            <?php $i++; }} ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
        <div class="row well" style="margin: 10px;">
            <div class="col-sm-12">
            <h1 class="page-header text-center" style="font-style: bold;">PIE CHART SHOWING REGISTERED, AVAILABLE,BORROWED AND LOST BOOKS</h1>
                <script>
                <?php
                  $books = $this->book_m->get_order_by_book_overal_analysis_pie_chart_data();
                ?>
                    var chart = AmCharts.makeChart("chartdiv12", {
                      "type": "pie",
                      "startDuration": 0,
                       "theme": "light",
                      "addClassNames": true,
                      "legend":{
                        "position":"right",
                        "marginRight":100,
                        "autoMargins":false
                      },
                      "innerRadius": "30%",
                      "defs": {
                        "filter": [{
                          "id": "shadow",
                          "width": "200%",
                          "height": "200%",
                          "feOffset": {
                            "result": "offOut",
                            "in": "SourceAlpha",
                            "dx": 0,
                            "dy": 0
                          },
                          "feGaussianBlur": {
                            "result": "blurOut",
                            "in": "offOut",
                            "stdDeviation": 5
                          },
                          "feBlend": {
                            "in": "SourceGraphic",
                            "in2": "blurOut",
                            "mode": "normal"
                          }
                        }]
                      },
                      "dataProvider": [{
                            "category": "Available",
                            "value": <?php echo $books->available;?>
                        }, {
                            "category": "Borrowed",
                            "value": <?php echo $books->borrowed;?>
                        }, {
                            "category": "Registered",
                            "value": <?php echo $books->registered;?>
                        }, {
                            "category": "Lost",
                            "value": <?php echo $books->lost;?>
                        }],
                      "valueField": "value",
                      "titleField": "category",
                      "export": {
                        "enabled": true
                      }
                    });

                    chart.addListener("init", handleInit);

                    chart.addListener("rollOverSlice", function(e) {
                      handleRollOver(e);
                    });

                    function handleInit(){
                      chart.legend.addListener("rollOverItem", handleRollOver);
                    }

                    function handleRollOver(e){
                      var wedge = e.dataItem.wedge.node;
                      wedge.parentNode.appendChild(wedge);
                    }
                </script>
                <div id="chartdiv12"></div>
            </div>
        </div> 
    </div>
</div>
<style>
    #chartdiv12 {
      width: 100%;
      height: 500px;
      font-size: 11px;
    }
    .amcharts-pie-slice {
      transform: scale(1);
      transform-origin: 50% 50%;
      transition-duration: 0.3s;
      transition: all .3s ease-out;
      -webkit-transition: all .3s ease-out;
      -moz-transition: all .3s ease-out;
      -o-transition: all .3s ease-out;
      cursor: pointer;
      box-shadow: 0 0 30px 0 #000;
    }

    .amcharts-pie-slice:hover {
      transform: scale(1.1);
      filter: url(#shadow);
    }                           
</style>