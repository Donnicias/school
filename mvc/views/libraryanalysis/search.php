
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-pie-chart"></i> <?=$this->lang->line('menu_library_analysis')?></h3>

       
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_borrowing')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row well"  style="margin: 5px;">
            <div class="col-sm-12">

                <div class="col-sm-6 col-sm-offset-3 list-group">
                    <div class="list-group-item list-group-item-warning">
                        <form style="" class="form-horizontal" role="form" method="post">  
                            <div class="form-group">              
                                <label for="classesID" class="col-sm-2 col-sm-offset-2 control-label">
                                    <?=$this->lang->line("libraryanalysis_classes")?>
                                </label>
                                <div class="col-sm-6">
                                    <?php
                                        $array = array("0" => $this->lang->line("libraryanalysis_select_class"));
                                        foreach ($classes as $classa) {
                                            $array[$classa->classesID] = $classa->classes;
                                        }
                                        echo form_dropdown("classesID", $array, set_value("classesID"), "id='classesID' class='form-control'");
                                    ?>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div> <!-- col-sm-12 -->
            <div class="col-sm-12">
                <h1 class="page-header text-center">LIBRARY BOOK INVENTORY TRACKING</h1>

                <style>
                    #chartdiv {
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

                    <!-- Chart code -->
                    <script>
                    <?php
                        $current_borrowings=$this->libraryanalysis_m->get_school_current_borrowing();
                        $total_lost=$this->libraryanalysis_m->get_school_total_lost();
                        $available_books=$this->libraryanalysis_m->get_school_available_books();
                        $total_books=$this->libraryanalysis_m->get_school_total_books();
                    ?>
                    var chart = AmCharts.makeChart("chartdiv", {
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
                        "country": "Total Books Registered",
                        "litres": <?php echo $total_books->total_books;?>
                      }, {
                        "country": "Total Borrowings",
                        "litres": <?php echo $current_borrowings->borrowed;?>
                      }, {
                        "country": "Total Available",
                        "litres": <?php echo $available_books->available;?>
                      }, {
                        "country": "Total Books Lost",
                        "litres": <?php echo $total_lost->lost;?>
                      }],
                      "valueField": "litres",
                      "titleField": "country",
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

                    <!-- HTML -->
                    <div id="chartdiv"></div>
            </div>
        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->

<script type="text/javascript">
    $('#classesID').change(function() {
        var classesID = $(this).val();
        if(classesID == 0) {
            $('#hide-table').hide();
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('libraryanalysis/student_list')?>",
                data: "id=" + classesID,
                dataType: "html",
                success: function(data) {
                    window.location.href = data;
                }
            });
        }
    });
</script>