<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <a href="<?php echo PERFIL; ?>" class="brand-link">
    <img src="<?php echo RUTA_IMG; ?>eP_perfil.png" alt="E-logic" style="width:35px;height:35px;" class="brand-image img-thumbnail elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">E-logic</span>
  </a>
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
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item has-treeview menu-open">
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
          <li class="nav-item has-treeview menu-open">
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
        <!---->
        <li class="nav-item has-treeview
        <?php
        if ($gestor_actual == 'cotizaciones' || $gestor_actual == 'completados' || $gestor_actual == 'submitted' || $gestor_actual == 'award') {
          echo 'menu-open';
        }
        ?>
            ">
          <a href="#" class="nav-link
          <?php
          if ($gestor_actual == 'cotizaciones' || $gestor_actual == 'completados' || $gestor_actual == 'submitted' || $gestor_actual == 'award') {
            echo 'active';
          }
          ?>
             ">
            <i class="nav-icon fas fa-tag"></i>
            <p>
              RFQ Team
              <i class="right fa fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item has-treeview
            <?php
            if ($gestor_actual == 'cotizaciones' && $cotizacion != 'add_project_risk' && $cotizacion != 'add_project_milestone' && $cotizacion != 'add_out_of_scope' && $cotizacion != 'add_high_level_requirement' && $cotizacion != 'cuestionario' && $cotizacion != 'editar_cotizacion' && $cotizacion != 'nuevo' && $cotizacion != 'add_item' && $cotizacion != 'add_provider' && $cotizacion != 'edit_item' && $cotizacion != 'edit_provider') {
              echo 'menu-open';
            }
            ?>
                ">
              <a href="#" class="nav-link
              <?php
              if ($gestor_actual == 'cotizaciones' && $cotizacion != 'add_project_risk' && $cotizacion != 'add_project_milestone' && $cotizacion != 'add_out_of_scope' && $cotizacion != 'add_high_level_requirement' && $cotizacion != 'cuestionario' && $cotizacion != 'editar_cotizacion' && $cotizacion != 'nuevo' && $cotizacion != 'add_item' && $cotizacion != 'add_provider' && $cotizacion != 'edit_item' && $cotizacion != 'edit_provider') {
                echo 'active';
              }
              ?>
                 ">
                  <!--<i class="fa fa-th nav-icon"></i>-->
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
                    <p>E-mails</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?php echo MAILBOX; ?>" class="nav-link
                  <?php
                  if ($cotizacion == 'mailbox') {
                    echo 'active';
                  }
                  ?>
                     ">
                    <p>Mailbox</p>
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
                    <p>FBO</p>
                  </a>
                </li>
              </ul>
            </li>

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
                <!--<i class="fa fa-check-circle-o nav-icon"></i>-->
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
                    <p>E-mails</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?php echo MAILBOX_COMPLETADOS; ?>" class="nav-link
                  <?php
                  if ($cotizacion == 'mailbox_completados') {
                    echo 'active';
                  }
                  ?>
                     ">
                    <p>Mailbox</p>
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
                      <p>E-mails</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="<?php echo MAILBOX_SUBMITTED; ?>" class="nav-link
                    <?php
                    if ($cotizacion == 'mailbox_submitted') {
                        echo 'active';
                    }
                    ?>
                       ">
                      <p>Mailbox</p>
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
                      <p>FBO</p>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="nav-item has-treeview
              <?php
              if ($gestor_actual == 'award') {
                echo 'menu-open';
              }
              ?>
                  ">
                <a href="#" class="nav-link
                <?php
                if ($gestor_actual == 'award') {
                  echo 'active';
                }
                ?>
                   ">
                  <p>Award</p>
                  <i class="right fa fa-angle-left"></i>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="<?php echo GSA_BUY_AWARD; ?>" class="nav-link
                    <?php
                    if ($cotizacion == 'gsa_buy_award') {
                      echo 'active';
                    }
                    ?>
                       ">
                      <p>GSA-Buy</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="<?php echo FEDBID_AWARD; ?>" class="nav-link
                    <?php
                    if ($cotizacion == 'fedbid_award') {
                      echo 'active';
                    }
                    ?>
                       ">
                      <p>FedBid</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="<?php echo EMAILS_AWARD; ?>" class="nav-link
                    <?php
                    if ($cotizacion == 'emails_award') {
                        echo 'active';
                    }
                    ?>
                       ">
                      <p>E-mails</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="<?php echo MAILBOX_AWARD; ?>" class="nav-link
                    <?php
                    if ($cotizacion == 'mailbox_award') {
                        echo 'active';
                    }
                    ?>
                       ">
                      <p>Mailbox</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="<?php echo FINDFRP_AWARD; ?>" class="nav-link
                    <?php
                    if ($cotizacion == 'findfrp_award') {
                        echo 'active';
                    }
                    ?>
                       ">
                      <p>FindFRP</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="<?php echo EMBASSIES_AWARD; ?>" class="nav-link
                    <?php
                    if ($cotizacion == 'embassies_award') {
                        echo 'active';
                    }
                    ?>
                       ">
                      <p>Embassies</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="<?php echo FBO_AWARD; ?>" class="nav-link
                    <?php
                    if ($cotizacion == 'fbo_award') {
                        echo 'active';
                    }
                    ?>
                       ">
                      <p>FBO</p>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="nav-item">
                <a href="<?php echo RFP_QUOTES; ?>" class="nav-link
                <?php
                if ($cotizacion == 'rfp_quotes') {
                  echo 'active';
                }
                ?>
                   ">
                  <p>RFP Quotes</p>
                </a>
              </li>
              <?php
              if($cargo < 4){
                ?>
                <li class="nav-item">
                  <a href="<?php echo NO_BID; ?>" class="nav-link
                  <?php
                  if ($cotizacion == 'no_bid') {
                    echo 'active';
                  }
                  ?>
                     ">
                    <p>No Bid</p>
                  </a>
                </li>
                <?php
              }
              ?>
              <li class="nav-item">
                <a href="<?php echo NO_SUBMITTED; ?>" class="nav-link
                <?php
                if ($cotizacion == 'no_submitted') {
                  echo 'active';
                }
                ?>
                   ">
                  <p>No submitted</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo CANCELLED; ?>" class="nav-link
                <?php
                if ($cotizacion == 'cancelled') {
                  echo 'active';
                }
                ?>
                   ">
                  <p>Cancelled</p>
                </a>
              </li>
            <?php
            if ($cargo < 5) {
              ?>
              <li class="nav-item">
                <a href="<?php echo NUEVA_COTIZACION; ?>" id="new_quote" class="nav-link
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
          </ul>
        </li>
        <?php
        if($cargo < 5){
          ?>
          <li class="nav-item has-treeview menu-open">
            <a href="<?php echo SEARCH_QUOTES; ?>" class="nav-link
            <?php
            if ($gestor_actual == 'search_quotes') {
              echo 'active';
            }
            ?>
               ">
              <i class="fa fa-search nav-icon"></i>
              <p>Search</p>
            </a>
          </li>
          <?php
        }
        ?>
        <li class="nav-item has-treeview menu-open">
          <a href="<?php echo EMPLOYEE_DOCS_PAGE; ?>" class="nav-link
          <?php
          if ($gestor_actual == 'employee_docs_page') {
            echo 'active';
          }
          ?>
             ">
            <i class="fas fa-file nav-icon"></i>
            <p>Employee docs</p>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</aside>
