<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <a href="<?php echo PROFILE; ?>" class="brand-link">
    <img src="<?php echo IMG_PATH; ?>eP_perfil.png" alt="E-logic" style="width:35px;height:35px;" class="brand-image img-thumbnail elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">E-logic</span>
  </a>
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="<?php echo IMG_PATH; ?>user.png" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block"><?php echo $_SESSION['username']; ?></a>
      </div>
    </div>
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item has-treeview menu-open">
          <a href="<?php echo PROFILE; ?>" class="nav-link
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
        if ($_SESSION['role'] == 1) {
          ?>
          <li class="nav-item has-treeview menu-open">
            <a href="<?php echo REGISTER_USER; ?>" class="nav-link
            <?php
            if ($gestor_actual == 'register_user' || $gestor_actual == 'registro_correcto') {
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
        if ($gestor_actual == 'quotes' || $gestor_actual == 'complete' || $gestor_actual == 'submitted' || $gestor_actual == 'award' || $gestor_actual == 'fulfillment_quotes' || $gestor_actual == 'no_bid' || $gestor_actual == 'no_submitted' || $gestor_actual == 'cancelled') {
          echo 'menu-open';
        }
        ?>
            ">
          <a href="#" class="nav-link
          <?php
          if ($gestor_actual == 'quotes' || $gestor_actual == 'complete' || $gestor_actual == 'submitted' || $gestor_actual == 'award' || $gestor_actual == 'fulfillment_quotes' || $gestor_actual == 'no_bid' || $gestor_actual == 'no_submitted' || $gestor_actual == 'cancelled') {
            echo 'active';
          }
          ?>
             ">
            <i class="nav-icon fas fa-tag"></i>
            <p>
              RFQ & RFP Team
              <i class="right fa fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item has-treeview
            <?php
            if ($gestor_actual == 'quotes' && $quote != 'edit_quote' && $quote != 'new' && $quote != 'add_item' && $quote != 'add_provider' && $quote != 'edit_item' && $quote != 'edit_provider') {
              echo 'menu-open';
            }
            ?>
                ">
              <a href="#" class="nav-link
              <?php
              if ($gestor_actual == 'quotes' && $quote != 'edit_quote' && $quote != 'new' && $quote != 'add_item' && $quote != 'add_provider' && $quote != 'edit_item' && $quote != 'edit_provider') {
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
                  if ($quote == 'gsa_buy') {
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
                  if ($quote == 'fedbid') {
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
                  if ($quote == 'emails') {
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
                  if ($quote == 'mailbox') {
                    echo 'active';
                  }
                  ?>
                     ">
                    <p>Mailbox</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?php echo FINDRFP; ?>" class="nav-link
                  <?php
                  if ($quote == 'findrfp') {
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
                  if ($quote == 'embassies') {
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
                  if ($quote == 'fbo') {
                    echo 'active';
                  }
                  ?>
                     ">
                    <p>FBO</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?php echo CHEMONICS; ?>" class="nav-link
                  <?php
                  if ($quote == 'chemonics') {
                    echo 'active';
                  }
                  ?>
                     ">
                    <p>Chemonics</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?php echo EBAY_AMAZON; ?>" class="nav-link
                  <?php
                  if ($quote == 'ebay_amazon') {
                    echo 'active';
                  }
                  ?>
                     ">
                    <p>Ebay & Amazon</p>
                  </a>
                </li>
              </ul>
            </li>

            <li class="nav-item has-treeview
            <?php
            if ($gestor_actual == 'complete') {
              echo 'menu-open';
            }
            ?>
                ">
              <a href="#" class="nav-link
              <?php
              if ($gestor_actual == 'complete') {
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
                  <a href="<?php echo GSA_BUY_COMPLETE; ?>" class="nav-link
                  <?php
                  if ($quote == 'gsa_buy_completados') {
                    echo 'active';
                  }
                  ?>
                     ">
                    <p>GSA-Buy</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?php echo FEDBID_COMPLETE; ?>" class="nav-link
                  <?php
                  if ($quote == 'fedbid_completados') {
                    echo 'active';
                  }
                  ?>
                     ">
                    <p>FedBid</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?php echo EMAILS_COMPLETE; ?>" class="nav-link
                  <?php
                  if ($quote == 'emails_completados') {
                    echo 'active';
                  }
                  ?>
                     ">
                    <p>E-mails</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?php echo MAILBOX_COMPLETE; ?>" class="nav-link
                  <?php
                  if ($quote == 'mailbox_completados') {
                    echo 'active';
                  }
                  ?>
                     ">
                    <p>Mailbox</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?php echo FINDRFP_COMPLETE; ?>" class="nav-link
                  <?php
                  if ($quote == 'findfrp_completados') {
                    echo 'active';
                  }
                  ?>
                     ">
                    <p>FindFRP</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?php echo EMBASSIES_COMPLETE; ?>" class="nav-link
                  <?php
                  if ($quote == 'embassies_completados') {
                    echo 'active';
                  }
                  ?>
                     ">
                    <p>Embassies</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?php echo FBO_COMPLETE; ?>" class="nav-link
                  <?php
                  if ($quote == 'fbo_completados') {
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
                    if ($quote == 'gsa_buy_submitted') {
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
                    if ($quote == 'fedbid_submitted') {
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
                    if ($quote == 'emails_submitted') {
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
                    if ($quote == 'mailbox_submitted') {
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
                    if ($quote == 'findfrp_submitted') {
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
                    if ($quote == 'embassies_submitted') {
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
                    if ($quote == 'fbo_submitted') {
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
                    if ($quote == 'gsa_buy_award') {
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
                    if ($quote == 'fedbid_award') {
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
                    if ($quote == 'emails_award') {
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
                    if ($quote == 'mailbox_award') {
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
                    if ($quote == 'findfrp_award') {
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
                    if ($quote == 'embassies_award') {
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
                    if ($quote == 'fbo_award') {
                        echo 'active';
                    }
                    ?>
                       ">
                      <p>FBO</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="<?php echo CHEMONICS_AWARD; ?>" class="nav-link
                    <?php
                    if ($quote == 'chemonics_award') {
                        echo 'active';
                    }
                    ?>
                       ">
                      <p>Chemonics</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="<?php echo EBAY_AMAZON_AWARD; ?>" class="nav-link
                    <?php
                    if ($quote == 'ebay_amazon_award') {
                        echo 'active';
                    }
                    ?>
                       ">
                      <p>Ebay & Amazon</p>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="nav-item">

                <a href="<?php echo FULFILLMENT_QUOTES; ?>" class="nav-link
                <?php
                if ($gestor_actual == 'fulfillment_quotes') {
                  echo 'active';
                }
                ?>
                   ">
                  <p>Fulfillment Quotes</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo NO_BID; ?>" class="nav-link
                <?php
                if ($gestor_actual == 'no_bid') {
                  echo 'active';
                }
                ?>
                   ">
                  <p>No Bid</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo NO_SUBMITTED; ?>" class="nav-link
                <?php
                if ($gestor_actual == 'no_submitted') {
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
                if ($gestor_actual == 'cancelled') {
                  echo 'active';
                }
                ?>
                   ">
                  <p>Cancelled</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo NEW_QUOTE; ?>" id="new_quote" class="nav-link
                <?php
                if ($quote == 'new') {
                    echo 'active';
                }
                ?>
                   ">
                  <i class="fa fa-plus nav-icon"></i>
                  <p>New quote</p>
                </a>
              </li>
          </ul>
        </li>
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
        <li class="nav-item has-treeview menu-open">
          <a href="<?php echo EXCEL_REPORTS; ?>" class="nav-link
          <?php
          if ($gestor_actual == 'excel_reports') {
            echo 'active';
          }
          ?>
             ">
            <i class="fas fa-file-excel nav-icon"></i>
            <p>Excel reports</p>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</aside>
