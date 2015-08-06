    <body class='skin-red' 
            <?php
                if(isset($problem) && $problem != ""){
                    echo "problem='".$problem."'";
                }
            ?>
        >
        <?php if(!isset($noNavbar)): ?>
            <header class="header">
                <a href="<?php echo base_url().'cashier'; ?>" class="logo">
                    <img src = '<?php echo base_url(); ?>img/clickLogo.png' height="50">
                </a>
                <!-- Header Navbar: style can be found in header.less -->
                <nav class="navbar navbar-static-top" role="navigation">
                    <div id='nav-problem-txt'></div>
                    <div class="navbar-right">
                        <ul class="nav navbar-nav">
                            <li class="dropdown user user-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="glyphicon glyphicon-user"></i>
                                    <span><i class="caret"></i></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <!-- User image -->
                                    <li class="user-header bg-white">
                                        <img src="<?php echo $user_img; ?>" class="img-circle" alt="User Image" />
                                        <p>
                                            <?php
                                                if(isset($user['full_name']))
                                                    echo $user['full_name'];
                                            ?>
                                            <small>
                                             <?php
                                                if(isset($user['role']))
                                                    echo $user['role'];
                                            ?>
                                            </small>
                                        </p>
                                    </li>
                                    <!-- Menu Body -->
                                    <!-- <li class="user-body">
                                        <div class="col-xs-4 text-center">
                                            <a href="#">Preferences</a>
                                        </div>
                                        <div class="col-xs-4 text-center">
                                            <a href="#">Sales</a>
                                        </div>
                                        <div class="col-xs-4 text-center">
                                            <a href="#">Friends</a>
                                        </div>
                                    </li> -->
                                    <!-- Menu Footer-->
                                    <li class="user-footer">
                                        <!-- <div class="pull-left">
                                            <a href="#" class="btn btn-default btn-flat">Profile</a>
                                        </div> -->
                                        <div class="pull-right">
                                            <a href="<?Php echo base_url()."site/go_logout" ?>" class="btn btn-default btn-flat">Sign out</a>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
        <?php endif; ?>
        <div class="wrapper cashier-wrapper row-offcanvas row-offcanvas-left">
        <?php 
            if(isset($code))
                echo $code; 
        ?>
        </div>
    </body>
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