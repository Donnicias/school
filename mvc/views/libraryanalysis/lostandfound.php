

<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-thumbs-up"></i> <?=$this->lang->line('menu_library_analysis')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_lost_and_found_books')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row well" style="margin: 20px 5px;">
            <div class="col-sm-12 col-xs-12">
                <!-- Styles -->
                    <style>
                    #chartdiv124 {
                        width       : 100%;
                        height      : 500px;
                        font-size   : 11px;
                    }                           
                    </style>
                    <!-- Chart code -->
                    <?php
                        $booksnotfound = $this->data_books_statistics_m->get_total_not_found_books();
                        $booksfound =$this->data_books_statistics_m->get__total_lost_and_found_books();
                        $bookslost =$this->data_books_statistics_m->get_total_lost_books();
                    ?>
                    <script>
                    var chart1 = AmCharts.makeChart("chartdiv124", {
                        "type": "pie",
                        "theme": "light",
                        "innerRadius": "40%",
                        //"gradientRatio": [-0.4, -0.4, -0.4, -0.4, -0.4, -0.4, 0, 0.1, 0.2, 0.1, 0, -0.2, -0.5],
                         "legend":{
                            "position":"right",
                            "marginRight":100,
                            "autoMargins":false
                          },
                        "dataProvider": [{
                            "country": "Total Books Lost",
                            "litres": <?php echo $bookslost->total_lost;?>
                          }, {
                            "country": "Total Not Found",
                            "litres": <?php echo $booksnotfound->total_not_found;?>
                          }, {
                            "country": "Total Found",
                            "litres": <?php echo $booksfound->total_found;?>
                          }],
                        "balloonText": "[[value]]",
                        "valueField": "litres",
                        "titleField": "country",
                        "balloon": {
                            "drop": true,
                            "adjustBorderColor": false,
                            "color": "#FFFFFF",
                            "fontSize": 16
                        },
                        "export": {
                            "enabled": true
                        }
                    });
                    </script>
                <h1 class="page-header text-center">GRAPHICAL SUMMARY OF LOST, FOUND AND MISSING BOOKS</h1>
                <div id="chartdiv124"></div>
            </div>
            <div class="col-sm-12">
                
            </div>
        </div> 
    </div>
</div>
