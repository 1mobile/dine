            <aside class="right-side 
            <?php
                if(isset($sideBarHide) && $sideBarHide == true){
                    echo ' strech';
                }
            ?>
            ">                
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        <?php
                            if(isset($page_title))
                                echo $page_title;
                        ?>
                        <?php
                            if(isset($page_subtitle)){
                                echo "
                                    <small>
                                        ".$page_subtitle."
                                    </small>
                                ";
                            }
                        ?>
                    </h1>
                    <!-- <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="#">Examples</a></li>
                        <li class="active">Blank page</li>
                    </ol> -->
                </section>

                <!-- Main content -->
                <section class="content <?php if(isset($page_no_padding) && $page_no_padding) echo ' no-padding'; ?>">
                    <?php 
                        if(isset($code))
                            echo $code; 
                    ?>
                </section><!-- /.content -->
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->
        <?php
            if(isset($js))
                echo $js;
        ?> 
        <?php 
            if(isset($add_js)){
                if(is_array($add_js)){
                    foreach ($add_js as $path) {
                        echo '<script src="'.base_url().$path.'" type="text/javascript"  language="JavaScript"></script>';
                    }
                }
                else
                     echo '<script src="'.base_url().$add_js.'" type="text/javascript"  language="JavaScript"></script>';
            }
        ?> 