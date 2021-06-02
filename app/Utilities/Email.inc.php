<?php
class Email{
  public static function send_email($to, $from, $subject, $message){
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From:" .  $from  . " <elogic@e-logic.us>\r\n";
    mail($to, $subject, $message, $headers);
  }

  public static function send_email_quote_awarded($to, $quote_id, $address){
    $subject = "Awarded quote";
    $message = '
    <html>
    <body style="margin:0;border-radius: 10px; padding:10px; width:600px;" bgcolor="white">
    <table align="center" border="0" cellpadding="0" cellspacing="0" style="box-shadow: 5px 10px 8px #888888;width:400px;padding:10px;border-radius: 10px; border-collapse: separate;" bgcolor="#FFFFFF">
      <tr>
        <td align="center" style="padding: 10px;">
          <img src="https://www.elogicportal.com/congratulation_img.png" alt="Logo" style="width:300px;border:0;"/>
        </td>
      </tr>
      <tr>
        <td align="center" style="color: #333538; padding: 10px; font-size: 25px;">
          <span>Your proposal# ' . $quote_id . ' has been accepted by:<br>' . $address . '</span>
        </td>
      </tr>
      <tr>
        <td align="center" style="padding: 10px;">
          <img src="https://www.elogicportal.com/rfp/img/eP_logo_home.png" alt="Logo" style="width:50px;border:0;"/>
        </td>
      </tr>
    </table>
    </body>
    </html>
    ';
    self::send_email($to, 'E-logic', $subject, $message);
  }
}
?>
