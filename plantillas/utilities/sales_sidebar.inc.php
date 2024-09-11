<li class="nav-item <?= ($partes_ruta[2] ?? null) === 'quote' ? 'menu-open' : ''; ?>">
  <a href="#" class="nav-link <?= ($partes_ruta[2] ?? null) === 'quote' ? 'active' : ''; ?>">
    <i class="nav-icon fas fa-tag"></i>
    <p>
      Sales
      <i class="right fa fa-angle-left"></i>
    </p>
  </a>
  <ul class="nav nav-treeview">
    <li class="nav-item">
      <a href="<?= NUEVA_COTIZACION; ?>" class="nav-link <?= ($partes_ruta[3] ?? null) === 'nuevo' ? 'active' : ''; ?>">
        <i class="nav-icon fas fa-plus"></i>
        <p>New Quote</p>
      </a>
    </li>

    <?php
    // Define the main menu structure
    $menu_items = [
      'channel' => [
        'Quotes',
        [
          ['GSA-Buy', GSA_BUY],
          ['FedBid', FEDBID],
          ['E-mails', EMAILS],
          ['Mailbox', MAILBOX],
          ['FindFRP', FINDFRP],
          ['Embassies', EMBASSIES],
          ['SAM', FBO],
          ['Seaport', SEAPORT],
          ['Chemonics', CHEMONICS],
          ['Ebay & Amazon', EBAY_AMAZON],
          ['Stars III', STARSIII],
        ]
      ],
      'completed' => [
        'Completed',
        [
          ['GSA-Buy', GSA_BUY_COMPLETADOS],
          ['Unison', FEDBID_COMPLETADOS],
          ['E-mails', EMAILS_COMPLETADOS],
          ['Mailbox', MAILBOX_COMPLETADOS],
          ['FindFRP', FINDFRP_COMPLETADOS],
          ['Embassies', EMBASSIES_COMPLETADOS],
          ['SAM', FBO_COMPLETADOS],
          ['Seaport', SEAPORT_COMPLETADOS],
          ['Ebay & Amazon', EBAY_AMAZON_COMPLETADOS],
          ['Stars III', STARSIII_COMPLETADOS],
        ]
      ],
      'submitted' => [
        'Submitted',
        [
          ['GSA-Buy', GSA_BUY_SUBMITTED],
          ['Unison', FEDBID_SUBMITTED],
          ['E-mails', EMAILS_SUBMITTED],
          ['Mailbox', MAILBOX_SUBMITTED],
          ['FindFRP', FINDFRP_SUBMITTED],
          ['Embassies', EMBASSIES_SUBMITTED],
          ['SAM', FBO_SUBMITTED],
          ['Seaport', SEAPORT_SUBMITTED],
          ['Ebay & Amazon', EBAY_AMAZON_SUBMITTED],
          ['Stars III', STARSIII_SUBMITTED],
        ]
      ],
      'award' => [
        'Award',
        [
          ['GSA-Buy', GSA_BUY_AWARD],
          ['Unison', FEDBID_AWARD],
          ['E-mails', EMAILS_AWARD],
          ['Mailbox', MAILBOX_AWARD],
          ['FindFRP', FINDFRP_AWARD],
          ['Embassies', EMBASSIES_AWARD],
          ['SAM', FBO_AWARD],
          ['Seaport', SEAPORT_AWARD],
          ['Chemonics', CHEMONICS_AWARD],
          ['Ebay & Amazon', EBAY_AMAZON_AWARD],
          ['Stars III', STARSIII_AWARD],
        ]
      ],
    ];

    // Loop through and render each menu item
    foreach ($menu_items as $key => [$label, $sub_items]) : ?>
      <li class="nav-item <?= ($partes_ruta[3] ?? null) === $key ? 'menu-open' : ''; ?>">
        <a href="#" class="nav-link <?= ($partes_ruta[3] ?? null) === $key ? 'active' : ''; ?>">
          <i class="nav-icon fas fa-tag"></i>
          <p>
            <?= $label ?>
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <?php foreach ($sub_items as [$sub_label, $sub_link]) : ?>
            <li class="nav-item">
              <a href="<?= $sub_link; ?>" class="nav-link <?= ($partes_ruta[4] ?? null) === $sub_label ? 'active' : ''; ?>">
                <i class="far fa-circle nav-icon"></i>
                <p><?= $sub_label ?></p>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </li>
    <?php endforeach; ?>

    <li class="nav-item">
      <a href="<?= NO_BID; ?>" class="nav-link <?= ($partes_ruta[3] ?? null) === 'no_bid' ? 'active' : ''; ?>">
        <i class="nav-icon fas fa-th"></i>
        <p>No Bid</p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?= NO_SUBMITTED; ?>" class="nav-link <?= ($partes_ruta[3] ?? null) === 'no_submitted' ? 'active' : ''; ?>">
        <i class="nav-icon fas fa-th"></i>
        <p>Not Submitted</p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?= CANCELLED; ?>" class="nav-link <?= ($partes_ruta[3] ?? null) === 'cancelled' ? 'active' : ''; ?>">
        <i class="nav-icon fas fa-th"></i>
        <p>Cancelled</p>
      </a>
    </li>
  </ul>
</li>