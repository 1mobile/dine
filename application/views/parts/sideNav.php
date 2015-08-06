            <aside class="left-side sidebar-offcanvas
            <?php
                if(isset($sideBarHide) && $sideBarHide == true){
                    echo ' collapse-left';
                }
            ?>
            ">                
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <ul class="sidebar-menu">
                        <?php
                            if(isset($sideNav))
                              echo $sideNav;
                        ?>
                    </ul>
                </section>
                <!-- /.sidebar -->
            </aside>