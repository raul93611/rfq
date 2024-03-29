<?php
class Email{
  public static function send_email($to, $from, $subject, $message){
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From:" .  $from  . " <elogic@e-logic.us>\r\n";
    $result = mail($to, $subject, $message, $headers) ? 'enviado' : 'no enviado';
    return $result;
  }

  public static function send_email_quote_awarded($to, $quote_id, $address){
    $subject = "Awarded quote";
    $message = '
    <html xmlns="http://www.w3.org/1999/xhtml">
      <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="color-scheme" content="light">
        <meta name="supported-color-schemes" content="light">
        <style>
          @media  only screen and (max-width: 600px) {
          .inner-body {
          width: 100% !important;
          }

          .footer {
          width: 100% !important;
          }
          }

          @media  only screen and (max-width: 500px) {
          .button {
          width: 100% !important;
          }
          }
        </style>
      </head>
      <body style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; -webkit-text-size-adjust: none; background-color: #ffffff; color: #718096; height: 100%; line-height: 1.4; margin: 0; padding: 0; width: 100% !important;" data-new-gr-c-s-check-loaded="14.1024.0" data-gr-ext-installed="">
        <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%; background-color: #edf2f7; margin: 0; padding: 0; width: 100%;">
          <tbody>
            <tr>
              <td align="center" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                <table class="content" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%; margin: 0; padding: 0; width: 100%;">
                  <tbody>
                    <tr>
                      <td class="header" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; padding: 25px 0; text-align: center;">
                        <a target="_blank" rel="noopener noreferrer" href="#" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; color: #3d4852; font-size: 19px; font-weight: bold; text-decoration: none; display: inline-block;">
                          <img src="' . RUTA_IMG . 'eP_logo_home.png" class="logo" alt="Laravel Logo" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; max-width: 100%; border: none; height: 40px; max-height: 40px; width: 60px;">
                        </a>
                      </td>
                    </tr>
                    <!-- Email Body -->
                    <tr>
                      <td class="body" width="100%" cellpadding="0" cellspacing="0" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%; background-color: #edf2f7; border-bottom: 1px solid #edf2f7; border-top: 1px solid #edf2f7; margin: 0; padding: 0; width: 100%;">
                        <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 570px; background-color: #ffffff; border-color: #e8e5ef; border-radius: 2px; border-width: 1px; box-shadow: 0 2px 0 rgba(0, 0, 150, 0.025), 2px 4px 0 rgba(0, 0, 150, 0.015); margin: 0 auto; padding: 0; width: 570px;">
                          <!-- Body content -->
                          <tbody>
                            <tr>
                              <td class="content-cell" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; max-width: 100vw; padding: 32px;">
                                <h1 style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; color: #3d4852; font-size: 18px; font-weight: bold; margin-top: 0; text-align: left;">Hello!</h1>
                                <p style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">Your proposal# ' . $quote_id . ' has been accepted</p>
                                <table class="action" align="center" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%; margin: 30px auto; padding: 0; text-align: center; width: 100%;">
                                  <tbody>
                                    <tr>
                                      <td align="center" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                                        <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                                          <tbody>
                                            <tr>
                                              <td align="center" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                                                <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                                                  <tbody>
                                                    <tr>
                                                      <img src="' . RUTA_IMG . 'congratulation_img.png" class="logo" alt="Laravel Logo" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; max-width: 100%; border: none; height: 300px; max-height: 300px; width: 300px;">
                                                    </tr>
                                                  </tbody>
                                                </table>
                                              </td>
                                            </tr>
                                          </tbody>
                                        </table>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                                <p style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">Thank you for using our application!</p>
                                <p style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">Regards,<br>
                                  E-Logic</p>
                                <table class="subcopy" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; border-top: 1px solid #e8e5ef; margin-top: 25px; padding-top: 25px;">
                                  <tbody>
                                    <tr>
                                      <td style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                                        <p style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; line-height: 1.5em; margin-top: 0; text-align: left; font-size: 14px;">If you’re having trouble seeing this message, report the problem to
                                          : <span class="break-all" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; word-break: break-all;"><a target="_blank" rel="noopener noreferrer" href="mailto:raul93611@gmail.com" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; color: #3869d4;">raul93611@gmail.com</a></span></p>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </td>
                    </tr>
                    <tr>
                      <td style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                        <table class="footer" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 570px; margin: 0 auto; padding: 0; text-align: center; width: 570px;">
                          <tbody>
                            <tr>
                              <td class="content-cell" align="center" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; max-width: 100vw; padding: 32px;">
                                <p style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; line-height: 1.5em; margin-top: 0; color: #b0adc5; font-size: 12px; text-align: center;">© 2021 E-logic. All rights reserved.</p>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </td>
            </tr>
          </tbody>
        </table>
      </body>
    </html>
    ';
    self::send_email($to, 'E-logic', $subject, $message);
  }

  public static function send_email_fulfillment_quote($users, $quote){
    foreach ($users as $key => $user) {
      $subject = 'New Order - Proposal #: ' . $quote-> obtener_id() . ' Contract #: ' . $quote-> obtener_contract_number();
      $message = '
      <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
          <meta name="color-scheme" content="light">
          <meta name="supported-color-schemes" content="light">
          <style>
            @media  only screen and (max-width: 600px) {
            .inner-body {
            width: 100% !important;
            }

            .footer {
            width: 100% !important;
            }
            }

            @media  only screen and (max-width: 500px) {
            .button {
            width: 100% !important;
            }
            }
          </style>
        </head>
        <body style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; -webkit-text-size-adjust: none; background-color: #ffffff; color: #718096; height: 100%; line-height: 1.4; margin: 0; padding: 0; width: 100% !important;" data-new-gr-c-s-check-loaded="14.1024.0" data-gr-ext-installed="">
          <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%; background-color: #edf2f7; margin: 0; padding: 0; width: 100%;">
            <tbody>
              <tr>
                <td align="center" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                  <table class="content" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%; margin: 0; padding: 0; width: 100%;">
                    <tbody>
                      <tr>
                        <td class="header" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; padding: 25px 0; text-align: center;">
                          <a target="_blank" rel="noopener noreferrer" href="#" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; color: #3d4852; font-size: 19px; font-weight: bold; text-decoration: none; display: inline-block;">
                            <img src="' . RUTA_IMG . 'eP_logo_home.png" class="logo" alt="Laravel Logo" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; max-width: 100%; border: none; height: 40px; max-height: 40px; width: 60px;">
                          </a>
                        </td>
                      </tr>
                      <!-- Email Body -->
                      <tr>
                        <td class="body" width="100%" cellpadding="0" cellspacing="0" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%; background-color: #edf2f7; border-bottom: 1px solid #edf2f7; border-top: 1px solid #edf2f7; margin: 0; padding: 0; width: 100%;">
                          <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 570px; background-color: #ffffff; border-color: #e8e5ef; border-radius: 2px; border-width: 1px; box-shadow: 0 2px 0 rgba(0, 0, 150, 0.025), 2px 4px 0 rgba(0, 0, 150, 0.015); margin: 0 auto; padding: 0; width: 570px;">
                            <!-- Body content -->
                            <tbody>
                              <tr>
                                <td class="content-cell" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; max-width: 100vw; padding: 32px;">
                                  <h1 style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; color: #3d4852; font-size: 18px; font-weight: bold; margin-top: 0; text-align: left;">Hello!</h1>
                                  <p style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">A new quote was sent to Fulfillment section proposal# ' . $quote-> obtener_id() . '</p>
                                  <table class="action" align="center" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%; margin: 30px auto; padding: 0; text-align: center; width: 100%;">
                                    <tbody>
                                      <tr>
                                        <td align="center" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                                          <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                                            <tbody>
                                              <tr>
                                                <td align="center" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                                                  <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                                                    <tbody>
                                                      <tr>
                                                        <td style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                                                          <a target="_blank" rel="noopener noreferrer" href="' . EDITAR_COTIZACION . '/' . $quote-> obtener_id() . '" class="button button-primary" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; -webkit-text-size-adjust: none; border-radius: 4px; color: #fff; display: inline-block; overflow: hidden; text-decoration: none; background-color: #2d3748; border-bottom: 8px solid #2d3748; border-left: 18px solid #2d3748; border-right: 18px solid #2d3748; border-top: 8px solid #2d3748;">View Quote</a>
                                                        </td>
                                                      </tr>
                                                    </tbody>
                                                  </table>
                                                </td>
                                              </tr>
                                            </tbody>
                                          </table>
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>
                                  <p style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">Thank you for using our application!</p>
                                  <p style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">Regards,<br>
                                    E-Logic</p>
                                  <table class="subcopy" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; border-top: 1px solid #e8e5ef; margin-top: 25px; padding-top: 25px;">
                                    <tbody>
                                      <tr>
                                        <td style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                                          <p style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; line-height: 1.5em; margin-top: 0; text-align: left; font-size: 14px;">If you’re having trouble seeing this message, report the problem to
                                            : <span class="break-all" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; word-break: break-all;"><a target="_blank" rel="noopener noreferrer" href="mailto:raul93611@gmail.com" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; color: #3869d4;">raul93611@gmail.com</a></span></p>
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </td>
                      </tr>
                      <tr>
                        <td style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                          <table class="footer" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 570px; margin: 0 auto; padding: 0; text-align: center; width: 570px;">
                            <tbody>
                              <tr>
                                <td class="content-cell" align="center" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; max-width: 100vw; padding: 32px;">
                                  <p style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; line-height: 1.5em; margin-top: 0; color: #b0adc5; font-size: 12px; text-align: center;">© 2021 E-logic. All rights reserved.</p>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
            </tbody>
          </table>
        </body>
      </html>
      ';
      self::send_email($user-> obtener_email(), 'E-logic', $subject, $message);
    }
  }

  public static function send_email_invoice_quote($users, $quote){
    foreach ($users as $key => $user) {
      $subject = 'New Order - Proposal #: ' . $quote-> obtener_id() . ' Contract #: ' . $quote-> obtener_contract_number();
      $message = '
      <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
          <meta name="color-scheme" content="light">
          <meta name="supported-color-schemes" content="light">
          <style>
            @media  only screen and (max-width: 600px) {
            .inner-body {
            width: 100% !important;
            }

            .footer {
            width: 100% !important;
            }
            }

            @media  only screen and (max-width: 500px) {
            .button {
            width: 100% !important;
            }
            }
          </style>
        </head>
        <body style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; -webkit-text-size-adjust: none; background-color: #ffffff; color: #718096; height: 100%; line-height: 1.4; margin: 0; padding: 0; width: 100% !important;" data-new-gr-c-s-check-loaded="14.1024.0" data-gr-ext-installed="">
          <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%; background-color: #edf2f7; margin: 0; padding: 0; width: 100%;">
            <tbody>
              <tr>
                <td align="center" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                  <table class="content" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%; margin: 0; padding: 0; width: 100%;">
                    <tbody>
                      <tr>
                        <td class="header" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; padding: 25px 0; text-align: center;">
                          <a target="_blank" rel="noopener noreferrer" href="#" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; color: #3d4852; font-size: 19px; font-weight: bold; text-decoration: none; display: inline-block;">
                            <img src="' . RUTA_IMG . 'eP_logo_home.png" class="logo" alt="Laravel Logo" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; max-width: 100%; border: none; height: 40px; max-height: 40px; width: 60px;">
                          </a>
                        </td>
                      </tr>
                      <!-- Email Body -->
                      <tr>
                        <td class="body" width="100%" cellpadding="0" cellspacing="0" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%; background-color: #edf2f7; border-bottom: 1px solid #edf2f7; border-top: 1px solid #edf2f7; margin: 0; padding: 0; width: 100%;">
                          <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 570px; background-color: #ffffff; border-color: #e8e5ef; border-radius: 2px; border-width: 1px; box-shadow: 0 2px 0 rgba(0, 0, 150, 0.025), 2px 4px 0 rgba(0, 0, 150, 0.015); margin: 0 auto; padding: 0; width: 570px;">
                            <!-- Body content -->
                            <tbody>
                              <tr>
                                <td class="content-cell" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; max-width: 100vw; padding: 32px;">
                                  <h1 style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; color: #3d4852; font-size: 18px; font-weight: bold; margin-top: 0; text-align: left;">Hello!</h1>
                                  <p style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">A new proposal was sent to Invoice section proposal# ' . $quote-> obtener_id() . '</p>
                                  <table class="action" align="center" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%; margin: 30px auto; padding: 0; text-align: center; width: 100%;">
                                    <tbody>
                                      <tr>
                                        <td align="center" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                                          <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                                            <tbody>
                                              <tr>
                                                <td align="center" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                                                  <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                                                    <tbody>
                                                      <tr>
                                                        <td style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                                                          <a target="_blank" rel="noopener noreferrer" href="' . EDITAR_COTIZACION . '/' . $quote-> obtener_id() . '" class="button button-primary" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; -webkit-text-size-adjust: none; border-radius: 4px; color: #fff; display: inline-block; overflow: hidden; text-decoration: none; background-color: #2d3748; border-bottom: 8px solid #2d3748; border-left: 18px solid #2d3748; border-right: 18px solid #2d3748; border-top: 8px solid #2d3748;">View Quote</a>
                                                        </td>
                                                      </tr>
                                                    </tbody>
                                                  </table>
                                                </td>
                                              </tr>
                                            </tbody>
                                          </table>
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>
                                  <p style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">Thank you for using our application!</p>
                                  <p style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">Regards,<br>
                                    E-Logic</p>
                                  <table class="subcopy" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; border-top: 1px solid #e8e5ef; margin-top: 25px; padding-top: 25px;">
                                    <tbody>
                                      <tr>
                                        <td style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                                          <p style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; line-height: 1.5em; margin-top: 0; text-align: left; font-size: 14px;">If you’re having trouble seeing this message, report the problem to
                                            : <span class="break-all" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; word-break: break-all;"><a target="_blank" rel="noopener noreferrer" href="mailto:raul93611@gmail.com" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; color: #3869d4;">raul93611@gmail.com</a></span></p>
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </td>
                      </tr>
                      <tr>
                        <td style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                          <table class="footer" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 570px; margin: 0 auto; padding: 0; text-align: center; width: 570px;">
                            <tbody>
                              <tr>
                                <td class="content-cell" align="center" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; max-width: 100vw; padding: 32px;">
                                  <p style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; line-height: 1.5em; margin-top: 0; color: #b0adc5; font-size: 12px; text-align: center;">© 2021 E-logic. All rights reserved.</p>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
            </tbody>
          </table>
        </body>
      </html>
      ';
      self::send_email($user-> obtener_email(), 'E-logic', $subject, $message);
    }
  }

  public static function send_email_task_created($to, $task){
    $subject = "New Task";
    $message = '
    <html xmlns="http://www.w3.org/1999/xhtml">
      <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="color-scheme" content="light">
        <meta name="supported-color-schemes" content="light">
        <style>
          @media  only screen and (max-width: 600px) {
          .inner-body {
          width: 100% !important;
          }

          .footer {
          width: 100% !important;
          }
          }

          @media  only screen and (max-width: 500px) {
          .button {
          width: 100% !important;
          }
          }
        </style>
      </head>
      <body style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; -webkit-text-size-adjust: none; background-color: #ffffff; color: #718096; height: 100%; line-height: 1.4; margin: 0; padding: 0; width: 100% !important;" data-new-gr-c-s-check-loaded="14.1024.0" data-gr-ext-installed="">
        <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%; background-color: #edf2f7; margin: 0; padding: 0; width: 100%;">
          <tbody>
            <tr>
              <td align="center" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                <table class="content" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%; margin: 0; padding: 0; width: 100%;">
                  <tbody>
                    <tr>
                      <td class="header" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; padding: 25px 0; text-align: center;">
                        <a target="_blank" rel="noopener noreferrer" href="#" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; color: #3d4852; font-size: 19px; font-weight: bold; text-decoration: none; display: inline-block;">
                          <img src="' . RUTA_IMG . 'eP_logo_home.png" class="logo" alt="Laravel Logo" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; max-width: 100%; border: none; height: 40px; max-height: 40px; width: 60px;">
                        </a>
                      </td>
                    </tr>
                    <!-- Email Body -->
                    <tr>
                      <td class="body" width="100%" cellpadding="0" cellspacing="0" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%; background-color: #edf2f7; border-bottom: 1px solid #edf2f7; border-top: 1px solid #edf2f7; margin: 0; padding: 0; width: 100%;">
                        <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 570px; background-color: #ffffff; border-color: #e8e5ef; border-radius: 2px; border-width: 1px; box-shadow: 0 2px 0 rgba(0, 0, 150, 0.025), 2px 4px 0 rgba(0, 0, 150, 0.015); margin: 0 auto; padding: 0; width: 570px;">
                          <!-- Body content -->
                          <tbody>
                            <tr>
                              <td class="content-cell" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; max-width: 100vw; padding: 32px;">
                                <h1 style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; color: #3d4852; font-size: 18px; font-weight: bold; margin-top: 0; text-align: left;">Hello!</h1>
                                <p style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">A task was assigned to you.</p>
                                <table class="action" align="center" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%; margin: 30px auto; padding: 0; text-align: center; width: 100%;">
                                  <tbody>
                                    <tr>
                                      <td align="center" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                                        <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                                          <tbody>
                                            <tr>
                                              <td style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                                                <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                                                  <tbody>
                                                    <tr>
                                                      <b>Created By:</b> ' .  $task-> get_id_user_name() . '<br>
                                                      <b>Title:</b> ' .  $task-> get_title() . '<br><br><br>
                                                    </tr>
                                                  </tbody>
                                                </table>
                                              </td>
                                            </tr>
                                            <tr>
                                              <td align="center" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                                                <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                                                  <tbody>
                                                    <tr>
                                                      <td style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                                                        <a target="_blank" rel="noopener noreferrer" href="https://www.elogicportal.com/rfq/perfil/#edit_task_modal#id=' . $task-> get_id() . '" class="button button-primary" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; -webkit-text-size-adjust: none; border-radius: 4px; color: #fff; display: inline-block; overflow: hidden; text-decoration: none; background-color: #2d3748; border-bottom: 8px solid #2d3748; border-left: 18px solid #2d3748; border-right: 18px solid #2d3748; border-top: 8px solid #2d3748;">View Task</a>
                                                      </td>
                                                    </tr>
                                                  </tbody>
                                                </table>
                                              </td>
                                            </tr>
                                          </tbody>
                                        </table>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                                <p style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">Thank you for using our application!</p>
                                <p style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">Regards,<br>
                                  E-Logic</p>
                                <table class="subcopy" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; border-top: 1px solid #e8e5ef; margin-top: 25px; padding-top: 25px;">
                                  <tbody>
                                    <tr>
                                      <td style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                                        <p style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; line-height: 1.5em; margin-top: 0; text-align: left; font-size: 14px;">If you’re having trouble seeing this message, report the problem to
                                          : <span class="break-all" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; word-break: break-all;"><a target="_blank" rel="noopener noreferrer" href="mailto:raul93611@gmail.com" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; color: #3869d4;">raul93611@gmail.com</a></span></p>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </td>
                    </tr>
                    <tr>
                      <td style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                        <table class="footer" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 570px; margin: 0 auto; padding: 0; text-align: center; width: 570px;">
                          <tbody>
                            <tr>
                              <td class="content-cell" align="center" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; max-width: 100vw; padding: 32px;">
                                <p style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; line-height: 1.5em; margin-top: 0; color: #b0adc5; font-size: 12px; text-align: center;">© 2021 E-logic. All rights reserved.</p>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </td>
            </tr>
          </tbody>
        </table>
      </body>
    </html>
    ';
    self::send_email($to, 'E-logic', $subject, $message);
  }

  public static function send_email_task_done($to, $task){
    $subject = "Task Done";
    $message = '
    <html xmlns="http://www.w3.org/1999/xhtml">
      <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="color-scheme" content="light">
        <meta name="supported-color-schemes" content="light">
        <style>
          @media  only screen and (max-width: 600px) {
          .inner-body {
          width: 100% !important;
          }

          .footer {
          width: 100% !important;
          }
          }

          @media  only screen and (max-width: 500px) {
          .button {
          width: 100% !important;
          }
          }
        </style>
      </head>
      <body style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; -webkit-text-size-adjust: none; background-color: #ffffff; color: #718096; height: 100%; line-height: 1.4; margin: 0; padding: 0; width: 100% !important;" data-new-gr-c-s-check-loaded="14.1024.0" data-gr-ext-installed="">
        <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%; background-color: #edf2f7; margin: 0; padding: 0; width: 100%;">
          <tbody>
            <tr>
              <td align="center" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                <table class="content" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%; margin: 0; padding: 0; width: 100%;">
                  <tbody>
                    <tr>
                      <td class="header" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; padding: 25px 0; text-align: center;">
                        <a target="_blank" rel="noopener noreferrer" href="#" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; color: #3d4852; font-size: 19px; font-weight: bold; text-decoration: none; display: inline-block;">
                          <img src="' . RUTA_IMG . 'eP_logo_home.png" class="logo" alt="Laravel Logo" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; max-width: 100%; border: none; height: 40px; max-height: 40px; width: 60px;">
                        </a>
                      </td>
                    </tr>
                    <!-- Email Body -->
                    <tr>
                      <td class="body" width="100%" cellpadding="0" cellspacing="0" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%; background-color: #edf2f7; border-bottom: 1px solid #edf2f7; border-top: 1px solid #edf2f7; margin: 0; padding: 0; width: 100%;">
                        <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 570px; background-color: #ffffff; border-color: #e8e5ef; border-radius: 2px; border-width: 1px; box-shadow: 0 2px 0 rgba(0, 0, 150, 0.025), 2px 4px 0 rgba(0, 0, 150, 0.015); margin: 0 auto; padding: 0; width: 570px;">
                          <!-- Body content -->
                          <tbody>
                            <tr>
                              <td class="content-cell" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; max-width: 100vw; padding: 32px;">
                                <h1 style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; color: #3d4852; font-size: 18px; font-weight: bold; margin-top: 0; text-align: left;">Hello!</h1>
                                <p style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">The task you created was done.</p>
                                <table class="action" align="center" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%; margin: 30px auto; padding: 0; text-align: center; width: 100%;">
                                  <tbody>
                                    <tr>
                                      <td align="center" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                                        <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                                          <tbody>
                                            <tr>
                                              <td style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                                                <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                                                  <tbody>
                                                    <tr>
                                                      <b>Assigned to:</b> ' .  $task-> get_assigned_user_name() . '<br>
                                                      <b>Title:</b> ' .  $task-> get_title() . '<br><br><br>
                                                    </tr>
                                                  </tbody>
                                                </table>
                                              </td>
                                            </tr>
                                            <tr>
                                              <td align="center" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                                                <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                                                  <tbody>
                                                    <tr>
                                                      <td style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                                                        <a target="_blank" rel="noopener noreferrer" href="https://www.elogicportal.com/rfq/perfil/#edit_task_modal#id=' . $task-> get_id() . '" class="button button-primary" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; -webkit-text-size-adjust: none; border-radius: 4px; color: #fff; display: inline-block; overflow: hidden; text-decoration: none; background-color: #2d3748; border-bottom: 8px solid #2d3748; border-left: 18px solid #2d3748; border-right: 18px solid #2d3748; border-top: 8px solid #2d3748;">View Task</a>
                                                      </td>
                                                    </tr>
                                                  </tbody>
                                                </table>
                                              </td>
                                            </tr>
                                          </tbody>
                                        </table>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                                <p style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">Thank you for using our application!</p>
                                <p style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">Regards,<br>
                                  E-Logic</p>
                                <table class="subcopy" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; border-top: 1px solid #e8e5ef; margin-top: 25px; padding-top: 25px;">
                                  <tbody>
                                    <tr>
                                      <td style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                                        <p style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; line-height: 1.5em; margin-top: 0; text-align: left; font-size: 14px;">If you’re having trouble seeing this message, report the problem to
                                          : <span class="break-all" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; word-break: break-all;"><a target="_blank" rel="noopener noreferrer" href="mailto:raul93611@gmail.com" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; color: #3869d4;">raul93611@gmail.com</a></span></p>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </td>
                    </tr>
                    <tr>
                      <td style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                        <table class="footer" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 570px; margin: 0 auto; padding: 0; text-align: center; width: 570px;">
                          <tbody>
                            <tr>
                              <td class="content-cell" align="center" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; max-width: 100vw; padding: 32px;">
                                <p style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; line-height: 1.5em; margin-top: 0; color: #b0adc5; font-size: 12px; text-align: center;">© 2021 E-logic. All rights reserved.</p>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </td>
            </tr>
          </tbody>
        </table>
      </body>
    </html>
    ';
    self::send_email($to, 'E-logic', $subject, $message);
  }

  public static function send_email_task_comment($users, $task){
    foreach ($users as $key => $user) {
      $subject = "New comment in Task";
      $message = '
      <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
          <meta name="color-scheme" content="light">
          <meta name="supported-color-schemes" content="light">
          <style>
            @media  only screen and (max-width: 600px) {
            .inner-body {
            width: 100% !important;
            }

            .footer {
            width: 100% !important;
            }
            }

            @media  only screen and (max-width: 500px) {
            .button {
            width: 100% !important;
            }
            }
          </style>
        </head>
        <body style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; -webkit-text-size-adjust: none; background-color: #ffffff; color: #718096; height: 100%; line-height: 1.4; margin: 0; padding: 0; width: 100% !important;" data-new-gr-c-s-check-loaded="14.1024.0" data-gr-ext-installed="">
          <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%; background-color: #edf2f7; margin: 0; padding: 0; width: 100%;">
            <tbody>
              <tr>
                <td align="center" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                  <table class="content" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%; margin: 0; padding: 0; width: 100%;">
                    <tbody>
                      <tr>
                        <td class="header" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; padding: 25px 0; text-align: center;">
                          <a target="_blank" rel="noopener noreferrer" href="#" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; color: #3d4852; font-size: 19px; font-weight: bold; text-decoration: none; display: inline-block;">
                            <img src="' . RUTA_IMG . 'eP_logo_home.png" class="logo" alt="Laravel Logo" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; max-width: 100%; border: none; height: 40px; max-height: 40px; width: 60px;">
                          </a>
                        </td>
                      </tr>
                      <!-- Email Body -->
                      <tr>
                        <td class="body" width="100%" cellpadding="0" cellspacing="0" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%; background-color: #edf2f7; border-bottom: 1px solid #edf2f7; border-top: 1px solid #edf2f7; margin: 0; padding: 0; width: 100%;">
                          <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 570px; background-color: #ffffff; border-color: #e8e5ef; border-radius: 2px; border-width: 1px; box-shadow: 0 2px 0 rgba(0, 0, 150, 0.025), 2px 4px 0 rgba(0, 0, 150, 0.015); margin: 0 auto; padding: 0; width: 570px;">
                            <!-- Body content -->
                            <tbody>
                              <tr>
                                <td class="content-cell" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; max-width: 100vw; padding: 32px;">
                                  <h1 style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; color: #3d4852; font-size: 18px; font-weight: bold; margin-top: 0; text-align: left;">Hello!</h1>
                                  <p style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">A new comment was left in the following task:</p>
                                  <table class="action" align="center" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%; margin: 30px auto; padding: 0; text-align: center; width: 100%;">
                                    <tbody>
                                      <tr>
                                        <td align="center" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                                          <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                                            <tbody>
                                              <tr>
                                                <td style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                                                  <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                                                    <tbody>
                                                      <tr>
                                                        <b>Assigned to:</b> ' .  $task-> get_assigned_user_name() . '<br>
                                                        <b>Title:</b> ' .  $task-> get_title() . '<br><br><br>
                                                      </tr>
                                                    </tbody>
                                                  </table>
                                                </td>
                                              </tr>
                                              <tr>
                                                <td align="center" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                                                  <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                                                    <tbody>
                                                      <tr>
                                                        <td style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                                                          <a target="_blank" rel="noopener noreferrer" href="https://www.elogicportal.com/rfq/perfil/#edit_task_modal#id=' . $task-> get_id() . '" class="button button-primary" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; -webkit-text-size-adjust: none; border-radius: 4px; color: #fff; display: inline-block; overflow: hidden; text-decoration: none; background-color: #2d3748; border-bottom: 8px solid #2d3748; border-left: 18px solid #2d3748; border-right: 18px solid #2d3748; border-top: 8px solid #2d3748;">View Task</a>
                                                        </td>
                                                      </tr>
                                                    </tbody>
                                                  </table>
                                                </td>
                                              </tr>
                                            </tbody>
                                          </table>
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>
                                  <p style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">Thank you for using our application!</p>
                                  <p style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">Regards,<br>
                                    E-Logic</p>
                                  <table class="subcopy" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; border-top: 1px solid #e8e5ef; margin-top: 25px; padding-top: 25px;">
                                    <tbody>
                                      <tr>
                                        <td style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                                          <p style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; line-height: 1.5em; margin-top: 0; text-align: left; font-size: 14px;">If you’re having trouble seeing this message, report the problem to
                                            : <span class="break-all" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; word-break: break-all;"><a target="_blank" rel="noopener noreferrer" href="mailto:raul93611@gmail.com" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; color: #3869d4;">raul93611@gmail.com</a></span></p>
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </td>
                      </tr>
                      <tr>
                        <td style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                          <table class="footer" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 570px; margin: 0 auto; padding: 0; text-align: center; width: 570px;">
                            <tbody>
                              <tr>
                                <td class="content-cell" align="center" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; max-width: 100vw; padding: 32px;">
                                  <p style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; line-height: 1.5em; margin-top: 0; color: #b0adc5; font-size: 12px; text-align: center;">© 2021 E-logic. All rights reserved.</p>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
            </tbody>
          </table>
        </body>
      </html>
      ';
      self::send_email($user-> obtener_email(), 'E-logic', $subject, $message);
    }
  }

  public static function send_email_restart_password($to, $secret_url){
    $subject = "Restart your password";
    $message = '
    <html xmlns="http://www.w3.org/1999/xhtml">
      <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="color-scheme" content="light">
        <meta name="supported-color-schemes" content="light">
        <style>
          @media  only screen and (max-width: 600px) {
          .inner-body {
          width: 100% !important;
          }

          .footer {
          width: 100% !important;
          }
          }

          @media  only screen and (max-width: 500px) {
          .button {
          width: 100% !important;
          }
          }
        </style>
      </head>
      <body style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; -webkit-text-size-adjust: none; background-color: #ffffff; color: #718096; height: 100%; line-height: 1.4; margin: 0; padding: 0; width: 100% !important;" data-new-gr-c-s-check-loaded="14.1024.0" data-gr-ext-installed="">
        <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%; background-color: #edf2f7; margin: 0; padding: 0; width: 100%;">
          <tbody>
            <tr>
              <td align="center" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                <table class="content" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%; margin: 0; padding: 0; width: 100%;">
                  <tbody>
                    <tr>
                      <td class="header" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; padding: 25px 0; text-align: center;">
                        <a target="_blank" rel="noopener noreferrer" href="#" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; color: #3d4852; font-size: 19px; font-weight: bold; text-decoration: none; display: inline-block;">
                          <img src="' . RUTA_IMG . 'eP_logo_home.png" class="logo" alt="Laravel Logo" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; max-width: 100%; border: none; height: 40px; max-height: 40px; width: 60px;">
                        </a>
                      </td>
                    </tr>
                    <!-- Email Body -->
                    <tr>
                      <td class="body" width="100%" cellpadding="0" cellspacing="0" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%; background-color: #edf2f7; border-bottom: 1px solid #edf2f7; border-top: 1px solid #edf2f7; margin: 0; padding: 0; width: 100%;">
                        <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 570px; background-color: #ffffff; border-color: #e8e5ef; border-radius: 2px; border-width: 1px; box-shadow: 0 2px 0 rgba(0, 0, 150, 0.025), 2px 4px 0 rgba(0, 0, 150, 0.015); margin: 0 auto; padding: 0; width: 570px;">
                          <!-- Body content -->
                          <tbody>
                            <tr>
                              <td class="content-cell" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; max-width: 100vw; padding: 32px;">
                                <h1 style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; color: #3d4852; font-size: 18px; font-weight: bold; margin-top: 0; text-align: left;">Hello!</h1>
                                <p style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">Click on the following button to restart your password.</p>
                                <table class="action" align="center" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%; margin: 30px auto; padding: 0; text-align: center; width: 100%;">
                                  <tbody>
                                    <tr>
                                      <td align="center" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                                        <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                                          <tbody>
                                            <tr>
                                              <td align="center" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                                                <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                                                  <tbody>
                                                    <tr>
                                                      <td style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                                                        <a target="_blank" rel="noopener noreferrer" href="https://www.elogicportal.com/rfq/restart_password/' . $secret_url . '" class="button button-primary" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; -webkit-text-size-adjust: none; border-radius: 4px; color: #fff; display: inline-block; overflow: hidden; text-decoration: none; background-color: #2d3748; border-bottom: 8px solid #2d3748; border-left: 18px solid #2d3748; border-right: 18px solid #2d3748; border-top: 8px solid #2d3748;">Restart your Password</a>
                                                      </td>
                                                    </tr>
                                                  </tbody>
                                                </table>
                                              </td>
                                            </tr>
                                          </tbody>
                                        </table>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                                <p style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">Thank you for using our application!</p>
                                <p style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">Regards,<br>
                                  E-Logic</p>
                                <table class="subcopy" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; border-top: 1px solid #e8e5ef; margin-top: 25px; padding-top: 25px;">
                                  <tbody>
                                    <tr>
                                      <td style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                                        <p style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; line-height: 1.5em; margin-top: 0; text-align: left; font-size: 14px;">If you’re having trouble seeing this message, report the problem to
                                          : <span class="break-all" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; word-break: break-all;"><a target="_blank" rel="noopener noreferrer" href="mailto:raul93611@gmail.com" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; color: #3869d4;">raul93611@gmail.com</a></span></p>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </td>
                    </tr>
                    <tr>
                      <td style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative;">
                        <table class="footer" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 570px; margin: 0 auto; padding: 0; text-align: center; width: 570px;">
                          <tbody>
                            <tr>
                              <td class="content-cell" align="center" style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; max-width: 100vw; padding: 32px;">
                                <p style="box-sizing: border-box; font-family: -apple-system, Roboto, Helvetica, Arial, sans-serif; position: relative; line-height: 1.5em; margin-top: 0; color: #b0adc5; font-size: 12px; text-align: center;">© 2021 E-logic. All rights reserved.</p>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </td>
            </tr>
          </tbody>
        </table>
      </body>
    </html>
    ';
    self::send_email($to, 'E-logic', $subject, $message);
  }
}
?>
