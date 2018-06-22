<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?php echo PERFIL; ?>" class="brand-link">
        <img src="<?php echo RUTA_IMG; ?>favicon_logito.jpg" alt="E-logic" style="width:35px;height:35px;" class="brand-image img-thumbnail elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light">E-logic</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?php echo RUTA_IMG; ?>user.png" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block"><?php echo $usuario->obtener_nombre_usuario(); ?></a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                <li class="nav-item has-treeview menu-open">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa fa-home"></i>
                        <p>
                            Home
                            <i class="right fa fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo PERFIL; ?>" class="nav-link
                            <?php
                            if ($gestor_actual == '') {
                                echo 'active';
                            }
                            ?>
                               ">
                                <i class="fa fa-th nav-icon"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <?php
                        if ($cargo == 1) {
                            ?>
                            <li class="nav-item">
                                <a href="<?php echo REGISTRO; ?>" class="nav-link
                                <?php
                                if ($gestor_actual == 'registro' || $gestor_actual == 'registro_correcto') {
                                    echo 'active';
                                }
                                ?>
                                   ">
                                    <i class="fa fa-users nav-icon"></i>
                                    <p>User register</p>
                                </a>
                            </li>
                            <?php
                        }
                        ?>

                        <!--<li class="nav-item">
                            <a href="./index3.html" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Dashboard v3</p>
                            </a>
                        </li>-->
                    </ul>
                </li>
                <!---->
                <li class="nav-item has-treeview 
                <?php
                if ($gestor_actual == 'cotizaciones' || $gestor_actual == 'completados' || $gestor_actual == 'submitted') {
                    echo 'menu-open';
                }
                ?>
                    ">
                    <a href="#" class="nav-link 
                    <?php
                    if ($gestor_actual == 'cotizaciones' || $gestor_actual == 'completados' || $gestor_actual == 'submitted') {
                        echo 'active';
                    }
                    ?>
                       ">
                        <i class="nav-icon fa fa-money"></i>
                        <p>
                            RFQ
                            <i class="right fa fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item has-treeview
                        <?php
                        if ($gestor_actual == 'cotizaciones' && $cotizacion != 'editar_cotizacion' && $cotizacion != 'nuevo' && $cotizacion != 'add_item' && $cotizacion != 'add_provider' && $cotizacion != 'edit_item' && $cotizacion != 'edit_provider') {
                            echo 'menu-open';
                        }
                        ?>
                            ">
                            <a href="#" class="nav-link
                            <?php
                            if ($gestor_actual == 'cotizaciones' && $cotizacion != 'editar_cotizacion' && $cotizacion != 'nuevo' && $cotizacion != 'add_item' && $cotizacion != 'add_provider' && $cotizacion != 'edit_item' && $cotizacion != 'edit_provider') {
                                echo 'active';
                            }
                            ?>
                               ">
                                <i class="fa fa-th nav-icon"></i>
                                <p>Quotes</p>
                                <i class="right fa fa-angle-left"></i>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?php echo GSA_BUY; ?>" class="nav-link
                                    <?php
                                    if ($cotizacion == 'gsa_buy') {
                                        echo 'active';
                                    }
                                    ?>
                                       ">
                                        <i class="fa fa-dollar nav-icon"></i>
                                        <p>GSA-Buy</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo FEDBID; ?>" class="nav-link
                                    <?php
                                    if ($cotizacion == 'fedbid') {
                                        echo 'active';
                                    }
                                    ?>
                                       ">
                                        <i class="fa fa-dollar nav-icon"></i>
                                        <p>FedBid</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo EMAILS; ?>" class="nav-link
                                    <?php
                                    if ($cotizacion == 'emails') {
                                        echo 'active';
                                    }
                                    ?>
                                       ">
                                        <i class="fa fa-dollar nav-icon"></i>
                                        <p>E-mails</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo FINDFRP; ?>" class="nav-link
                                    <?php
                                    if ($cotizacion == 'findfrp') {
                                        echo 'active';
                                    }
                                    ?>
                                       ">
                                        <i class="fa fa-dollar nav-icon"></i>
                                        <p>FindFRP</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo EMBASSIES; ?>" class="nav-link
                                    <?php
                                    if ($cotizacion == 'embassies') {
                                        echo 'active';
                                    }
                                    ?>
                                       ">
                                        <i class="fa fa-dollar nav-icon"></i>
                                        <p>Embassies</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo FBO; ?>" class="nav-link
                                    <?php
                                    if ($cotizacion == 'fbo') {
                                        echo 'active';
                                    }
                                    ?>
                                       ">
                                        <i class="fa fa-dollar nav-icon"></i>
                                        <p>FBO</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <?php
                        if ($cargo < 4) {
                            ?>
                            <li class="nav-item has-treeview 
                            <?php
                            if ($gestor_actual == 'completados') {
                                echo 'menu-open';
                            }
                            ?>
                                ">
                                <a href="#" class="nav-link
                                <?php
                                if ($gestor_actual == 'completados') {
                                    echo 'active';
                                }
                                ?>
                                   ">
                                    <i class="fa fa-check-circle-o nav-icon"></i>
                                    <p>Completed</p>
                                    <i class="right fa fa-angle-left"></i>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="<?php echo GSA_BUY_COMPLETADOS; ?>" class="nav-link
                                        <?php
                                        if ($cotizacion == 'gsa_buy_completados') {
                                            echo 'active';
                                        }
                                        ?>
                                           ">
                                            <i class="fa fa-dollar nav-icon"></i>
                                            <p>GSA-Buy</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?php echo FEDBID_COMPLETADOS; ?>" class="nav-link
                                        <?php
                                        if ($cotizacion == 'fedbid_completados') {
                                            echo 'active';
                                        }
                                        ?>
                                           ">
                                            <i class="fa fa-dollar nav-icon"></i>
                                            <p>FedBid</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?php echo EMAILS_COMPLETADOS; ?>" class="nav-link
                                        <?php
                                        if ($cotizacion == 'emails_completados') {
                                            echo 'active';
                                        }
                                        ?>
                                           ">
                                            <i class="fa fa-dollar nav-icon"></i>
                                            <p>E-mails</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?php echo FINDFRP_COMPLETADOS; ?>" class="nav-link
                                        <?php
                                        if ($cotizacion == 'findfrp_completados') {
                                            echo 'active';
                                        }
                                        ?>
                                           ">
                                            <i class="fa fa-dollar nav-icon"></i>
                                            <p>FindFRP</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?php echo EMBASSIES_COMPLETADOS; ?>" class="nav-link
                                        <?php
                                        if ($cotizacion == 'embassies_completados') {
                                            echo 'active';
                                        }
                                        ?>
                                           ">
                                            <i class="fa fa-dollar nav-icon"></i>
                                            <p>Embassies</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?php echo FBO_COMPLETADOS; ?>" class="nav-link
                                        <?php
                                        if ($cotizacion == 'fbo_completados') {
                                            echo 'active';
                                        }
                                        ?>
                                           ">
                                            <i class="fa fa-dollar nav-icon"></i>
                                            <p>FBO</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="nav-item has-treeview 
                            <?php
                            if ($gestor_actual == 'submitted') {
                                echo 'menu-open';
                            }
                            ?>
                                ">
                                <a href="#" class="nav-link
                                <?php
                                if ($gestor_actual == 'submitted') {
                                    echo 'active';
                                }
                                ?>
                                   ">
                                    <i class="fa fa-check-circle-o nav-icon"></i>
                                    <p>Submitted</p>
                                    <i class="right fa fa-angle-left"></i>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="<?php echo GSA_BUY_SUBMITTED; ?>" class="nav-link
                                        <?php
                                        if ($cotizacion == 'gsa_buy_submitted') {
                                            echo 'active';
                                        }
                                        ?>
                                           ">
                                            <i class="fa fa-dollar nav-icon"></i>
                                            <p>GSA-Buy</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?php echo FEDBID_SUBMITTED; ?>" class="nav-link
                                        <?php
                                        if ($cotizacion == 'fedbid_submitted') {
                                            echo 'active';
                                        }
                                        ?>
                                           ">
                                            <i class="fa fa-dollar nav-icon"></i>
                                            <p>FedBid</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?php echo EMAILS_SUBMITTED; ?>" class="nav-link
                                        <?php
                                        if ($cotizacion == 'emails_submitted') {
                                            echo 'active';
                                        }
                                        ?>
                                           ">
                                            <i class="fa fa-dollar nav-icon"></i>
                                            <p>E-mails</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?php echo FINDFRP_SUBMITTED; ?>" class="nav-link
                                        <?php
                                        if ($cotizacion == 'findfrp_submitted') {
                                            echo 'active';
                                        }
                                        ?>
                                           ">
                                            <i class="fa fa-dollar nav-icon"></i>
                                            <p>FindFRP</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?php echo EMBASSIES_SUBMITTED; ?>" class="nav-link
                                        <?php
                                        if ($cotizacion == 'embassies_submitted') {
                                            echo 'active';
                                        }
                                        ?>
                                           ">
                                            <i class="fa fa-dollar nav-icon"></i>
                                            <p>Embassies</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?php echo FBO_SUBMITTED; ?>" class="nav-link
                                        <?php
                                        if ($cotizacion == 'fbo_submitted') {
                                            echo 'active';
                                        }
                                        ?>
                                           ">
                                            <i class="fa fa-dollar nav-icon"></i>
                                            <p>FBO</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <?php
                        }
                        ?>

                        <?php
                        if ($cargo <= 3) {
                            ?>
                            <li class="nav-item">
                                <a href="<?php echo NUEVA_COTIZACION; ?>" class="nav-link
                                <?php
                                if ($cotizacion == 'nuevo') {
                                    echo 'active';
                                }
                                ?>
                                   ">
                                    <i class="fa fa-plus nav-icon"></i>
                                    <p>New quote</p>
                                </a>
                            </li>
                            <?php
                        }
                        ?>
                        <!--<li class="nav-item">
                            <a href="pages/charts/inline.html" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Inline</p>
                            </a>
                        </li>-->
                    </ul>
                </li>
                <!--<li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa fa-tree"></i>
                        <p>
                            UI Elements
                            <i class="fa fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="pages/UI/general.html" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>General</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/UI/icons.html" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Icons</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/UI/buttons.html" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Buttons</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/UI/sliders.html" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Sliders</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa fa-edit"></i>
                        <p>
                            Forms
                            <i class="fa fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="pages/forms/general.html" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>General Elements</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/forms/advanced.html" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Advanced Elements</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/forms/editors.html" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Editors</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa fa-table"></i>
                        <p>
                            Tables
                            <i class="fa fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="pages/tables/simple.html" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Simple Tables</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/tables/data.html" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Data Tables</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-header">EXAMPLES</li>
                <li class="nav-item">
                    <a href="pages/calendar.html" class="nav-link">
                        <i class="nav-icon fa fa-calendar"></i>
                        <p>
                            Calendar
                            <span class="badge badge-info right">2</span>
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa fa-envelope-o"></i>
                        <p>
                            Mailbox
                            <i class="fa fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="pages/mailbox/mailbox.html" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Inbox</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/mailbox/compose.html" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Compose</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/mailbox/read-mail.html" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Read</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa fa-book"></i>
                        <p>
                            Pages
                            <i class="fa fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="pages/examples/invoice.html" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Invoice</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/examples/profile.html" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Profile</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/examples/login.html" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Login</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/examples/register.html" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Register</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/examples/lockscreen.html" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Lockscreen</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa fa-plus-square-o"></i>
                        <p>
                            Extras
                            <i class="fa fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="pages/examples/404.html" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Error 404</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/examples/500.html" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Error 500</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/examples/blank.html" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Blank Page</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="starter.html" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Starter Page</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-header">MISCELLANEOUS</li>
                <li class="nav-item">
                    <a href="https://adminlte.io/docs" class="nav-link">
                        <i class="nav-icon fa fa-file"></i>
                        <p>Documentation</p>
                    </a>
                </li>
                <li class="nav-header">LABELS</li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa fa-circle-o text-danger"></i>
                        <p class="text">Important</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa fa-circle-o text-warning"></i>
                        <p>Warning</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa fa-circle-o text-info"></i>
                        <p>Informational</p>
                    </a>
                </li>-->
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
