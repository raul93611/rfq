<?php

/**
 * DigestEmailTemplate
 *
 * Renders the Daily RFQ Digest HTML email body from the Claude Design "Daily RFQ Digest
 * Email" handoff: branded header, four fixed sections (Created/Submitted/Awarded/Due
 * Today) each either a quote list or a quiet empty-state line, and a footer. Table-based
 * layout with inlined styles throughout, as required for consistent rendering across email
 * clients — no external stylesheet, no flexbox/grid.
 *
 * Column ownership per row: Quote #, Name (both link to the quote via EDITAR_COTIZACION),
 * Channel/Client (client on top, channel/vendor site below), Designated User.
 */
class DigestEmailTemplate {

  private const SECTIONS = [
    'created'   => ['title' => 'Created',   'bar' => '#2db4e8', 'icon_bg' => '#e6f5fc', 'icon_color' => '#1aa2dc', 'icon_glyph' => '+',       'period' => 'yesterday', 'empty' => 'No quotes created yesterday.'],
    'submitted' => ['title' => 'Submitted', 'bar' => '#4f6ef0', 'icon_bg' => '#eef1fd', 'icon_color' => '#4f6ef0', 'icon_glyph' => '&rarr;',  'period' => 'yesterday', 'empty' => 'No quotes submitted yesterday.'],
    'awarded'   => ['title' => 'Awarded',   'bar' => '#16a34a', 'icon_bg' => '#e8f6ec', 'icon_color' => '#15803d', 'icon_glyph' => '&#10003;', 'period' => 'yesterday', 'empty' => 'No quotes awarded yesterday.'],
    'due'       => ['title' => 'Due Today', 'bar' => '#d97706', 'icon_bg' => '#fdf2dc', 'icon_color' => '#d97706', 'icon_glyph' => '!',       'period' => 'today',     'empty' => 'No quotes due today.'],
  ];

  /**
   * @param string $date_label e.g. "Monday, July 20, 2026"
   * @param array  $created    rows from DigestRepository::getCreatedOn
   * @param array  $submitted  rows from DigestRepository::getSubmittedOn
   * @param array  $awarded    rows from DigestRepository::getAwardedOn
   * @param array  $due        rows from DigestRepository::getDueOn
   */
  public static function render($date_label, array $created, array $submitted, array $awarded, array $due) {
    $rows_by_section = ['created' => $created, 'submitted' => $submitted, 'awarded' => $awarded, 'due' => $due];

    $preheader = sprintf(
      'Yesterday: %d created, %d submitted, %d awarded &middot; Today: %d due — your team&#8217;s RFQ activity from Elogic',
      count($created), count($submitted), count($awarded), count($due)
    );
    $preheader_pad = str_repeat('&nbsp;&zwnj;', 10);

    $sections_html = '';
    $keys = array_keys(self::SECTIONS);
    foreach ($keys as $i => $key) {
      $sections_html .= self::renderSection(self::SECTIONS[$key], $rows_by_section[$key]);
      if ($i < count($keys) - 1) {
        $sections_html .= '<tr><td style="height:20px;line-height:20px;font-size:1px;">&nbsp;</td></tr>' . "\n";
      }
    }

    $safe_date = htmlspecialchars($date_label, ENT_QUOTES, 'UTF-8');

    return '<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="color-scheme" content="light dark">
<meta name="supported-color-schemes" content="light dark">
<title>Daily RFQ Digest — Elogic</title>
<style>
body,table,td,a{-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%}
table,td{mso-table-lspace:0pt;mso-table-rspace:0pt}
body{margin:0;padding:0;width:100%!important;background:#f1f3f7}
a{color:#1aa2dc;text-decoration:none}
a:hover{color:#2db4e8}
.link-quote:hover{color:#0e6b94!important}
.link-name:hover{text-decoration:underline!important}
@media (max-width:620px){
.container{width:100%!important}
.pad-side{padding-left:18px!important;padding-right:18px!important}
}
</style>
</head>
<body style="margin:0;padding:0;background:#f1f3f7;">
<div style="display:none;max-height:0;overflow:hidden;opacity:0;mso-hide:all;font-size:1px;line-height:1px;color:#f1f3f7;">' . $preheader . $preheader_pad . '</div>
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f1f3f7;">
<tr><td align="center" style="padding:32px 16px;">
<!--[if mso]><table role="presentation" width="600" align="center" cellpadding="0" cellspacing="0" border="0"><tr><td><![endif]-->
<table role="presentation" class="container" width="600" cellpadding="0" cellspacing="0" border="0" style="width:600px;max-width:600px;background:#ffffff;border:1px solid #e3e7ee;border-radius:12px;box-shadow:0 1px 3px rgba(15,22,35,0.08);">

<tr><td class="pad-side" bgcolor="#14202f" style="background:#14202f;padding:24px 32px 22px;border-radius:12px 12px 0 0;">
<table role="presentation" cellpadding="0" cellspacing="0" border="0"><tr>
<td width="30" style="width:30px;"><img src="' . RUTA_IMG . 'eP_perfil.png" width="30" height="30" alt="Elogic" style="display:block;width:30px;height:30px;border-radius:7px;"></td>
<td style="padding-left:10px;" valign="middle"><span style="font-family:Arial,Helvetica,sans-serif;font-size:12px;font-weight:700;letter-spacing:0.08em;color:#aeb9c9;text-transform:uppercase;">Elogic</span></td>
</tr></table>
<div style="height:18px;line-height:18px;font-size:1px;">&nbsp;</div>
<div style="font-family:Arial,Helvetica,sans-serif;font-size:23px;line-height:1.3;font-weight:700;color:#ffffff;mso-line-height-rule:exactly;">Daily RFQ Digest</div>
<div style="height:4px;line-height:4px;font-size:1px;">&nbsp;</div>
<div style="font-family:Arial,Helvetica,sans-serif;font-size:13px;color:#aeb9c9;mso-line-height-rule:exactly;">' . $safe_date . '</div>
</td></tr>

<tr><td class="pad-side" style="padding:20px 32px 2px;">
<div style="font-family:Arial,Helvetica,sans-serif;font-size:12.5px;color:#5a6a7e;line-height:1.5;">Created, Submitted, and Awarded reflect <strong>yesterday\'s</strong> activity — Due Today looks ahead to what\'s due <strong>today</strong>.</div>
</td></tr>

<tr><td class="pad-side" style="padding:16px 32px 0;"><table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="height:1px;line-height:1px;font-size:1px;">&nbsp;</td></tr></table></td></tr>
' . $sections_html . '
<tr><td style="height:26px;line-height:26px;font-size:1px;">&nbsp;</td></tr>

<tr><td class="pad-side" style="padding:20px 32px 26px;border-top:1px solid #eef1f6;border-radius:0 0 12px 12px;">
<div style="font-family:Arial,Helvetica,sans-serif;font-size:11.5px;line-height:1.6;color:#7d8ba0;">This is an automated daily summary from Elogic, sent each morning to Admin-role users. No action is needed.</div>
</td></tr>

</table>
<!--[if mso]></td></tr></table><![endif]-->
</td></tr>
</table>
</body>
</html>
';
  }

  private static function renderSection(array $meta, array $rows) {
    $header = '
<tr><td class="pad-side" style="padding:0 32px;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border:1px solid #e3e7ee;border-radius:10px;">
<tr><td style="height:3px;line-height:3px;font-size:1px;background:' . $meta['bar'] . ';border-radius:9px 9px 0 0;">&nbsp;</td></tr>
<tr><td style="padding:12px 16px 10px;">
<table role="presentation" cellpadding="0" cellspacing="0" border="0"><tr>
<td width="22" style="width:22px;"><table role="presentation" width="22" height="22" cellpadding="0" cellspacing="0" border="0"><tr><td align="center" valign="middle" bgcolor="' . $meta['icon_bg'] . '" style="background:' . $meta['icon_bg'] . ';border-radius:6px;width:22px;height:22px;color:' . $meta['icon_color'] . ';font-family:Arial,Helvetica,sans-serif;font-size:12px;font-weight:700;">' . $meta['icon_glyph'] . '</td></tr></table></td>
<td style="padding-left:8px;" valign="middle"><span style="font-family:Arial,Helvetica,sans-serif;font-size:14.5px;font-weight:700;color:#0f1623;">' . htmlspecialchars($meta['title'], ENT_QUOTES, 'UTF-8') . '</span><span style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#7d8ba0;padding-left:6px;">&middot; ' . count($rows) . ' ' . $meta['period'] . '</span></td>
</tr></table>
</td></tr>';

    if (empty($rows)) {
      $body = '
<tr><td style="padding:2px 16px 18px;">
<div style="font-family:Arial,Helvetica,sans-serif;font-size:12.5px;color:#aab4c2;padding:10px 0 2px;border-top:1px dashed #eef1f6;">' . htmlspecialchars($meta['empty'], ENT_QUOTES, 'UTF-8') . '</div>
</td></tr>';
    } else {
      $body = '
<tr><td style="padding:0 16px;"><table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-top:1px solid #eef1f6;border-bottom:1px solid #eef1f6;background:#fafbfd;"><tr>
<td width="14%" style="padding:7px 4px 7px 0;font-family:Arial,Helvetica,sans-serif;font-size:10.5px;font-weight:700;letter-spacing:0.05em;color:#5a6a7e;text-transform:uppercase;">Quote #</td>
<td width="32%" style="padding:7px 4px;font-family:Arial,Helvetica,sans-serif;font-size:10.5px;font-weight:700;letter-spacing:0.05em;color:#5a6a7e;text-transform:uppercase;">Name</td>
<td width="30%" style="padding:7px 4px;font-family:Arial,Helvetica,sans-serif;font-size:10.5px;font-weight:700;letter-spacing:0.05em;color:#5a6a7e;text-transform:uppercase;">Channel / Client</td>
<td width="24%" style="padding:7px 0 7px 4px;font-family:Arial,Helvetica,sans-serif;font-size:10.5px;font-weight:700;letter-spacing:0.05em;color:#5a6a7e;text-transform:uppercase;">Designated User</td>
</tr></table></td></tr>
<tr><td style="padding:0 16px;"><table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">';
      $last = count($rows) - 1;
      foreach ($rows as $i => $row) {
        $border = $i < $last ? 'border-bottom:1px solid #eef1f6;' : '';
        $body .= self::renderRow($row, $border);
      }
      $body .= '</table></td></tr>
<tr><td style="height:6px;line-height:6px;font-size:1px;">&nbsp;</td></tr>';
    }

    return $header . $body . '
</table>
</td></tr>
';
  }

  private static function renderRow(array $row, $border) {
    $url = htmlspecialchars(EDITAR_COTIZACION . '/' . $row['id'], ENT_QUOTES, 'UTF-8');
    $quote_num = htmlspecialchars('#' . $row['id'], ENT_QUOTES, 'UTF-8');
    $name = htmlspecialchars($row['name'] ?? '', ENT_QUOTES, 'UTF-8');
    $client = htmlspecialchars($row['client'] ?? '—', ENT_QUOTES, 'UTF-8');
    $channel = htmlspecialchars(self::channelLabel($row['canal'] ?? ''), ENT_QUOTES, 'UTF-8');
    $designated = htmlspecialchars(trim(($row['nombres'] ?? '') . ' ' . ($row['apellidos'] ?? '')) ?: '—', ENT_QUOTES, 'UTF-8');

    return '
<tr>
<td width="14%" style="padding:10px 4px 10px 0;' . $border . 'font-family:Arial,Helvetica,sans-serif;font-size:13px;vertical-align:top;"><a href="' . $url . '" class="link-quote" style="color:#1aa2dc;font-weight:700;text-decoration:none;">' . $quote_num . '</a></td>
<td width="32%" style="padding:10px 4px;' . $border . 'font-family:Arial,Helvetica,sans-serif;font-size:13px;vertical-align:top;"><a href="' . $url . '" class="link-name" style="color:#0f1623;text-decoration:none;font-weight:600;">' . $name . '</a></td>
<td width="30%" style="padding:10px 4px;' . $border . 'font-family:Arial,Helvetica,sans-serif;font-size:12.5px;vertical-align:top;"><div style="color:#2c3849;font-weight:600;">' . $client . '</div><div style="color:#7d8ba0;font-size:11px;margin-top:2px;">' . $channel . '</div></td>
<td width="24%" style="padding:10px 0 10px 4px;' . $border . 'font-family:Arial,Helvetica,sans-serif;font-size:12.5px;color:#2c3849;vertical-align:top;">' . $designated . '</td>
</tr>';
  }

  private static function channelLabel($canal) {
    switch ($canal) {
      case 'FedBid': return 'Unison';
      case 'FBO':    return 'SAM';
      default:       return $canal;
    }
  }
}
