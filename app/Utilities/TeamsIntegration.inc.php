<?php
class TeamsIntegration {
  public static function sendMessage($title, $message, $users, $url, $button_name, $webhook) {
    $mentions = [];
    $entities = [];
    foreach ($users as $key => $user) {
      $mentions[] = "<at>{$user->obtener_nombre_usuario()}</at>";
      $entities[] = [
        "type" => "mention",
        "text" => "<at>{$user->obtener_nombre_usuario()}</at>",
        "mentioned" => [
          "id" => $user->obtener_email(),
          "name" => $user->obtener_nombre_usuario()
        ]
      ];
    }

    $payload = [
      "type" => "message",
      "attachments" => [
        [
          "contentType" => "application/vnd.microsoft.card.adaptive",
          "content" => [
            "type" => "AdaptiveCard",
            "body" => [
              [
                "type" => "TextBlock",
                "size" => "Medium",
                "weight" => "Bolder",
                "text" => $title
              ],
              [
                "type" => "TextBlock",
                "text" => $message
              ],
              [
                "type" => "TextBlock",
                "text" => implode(',', $mentions),
                "wrap" => true
              ]
            ],
            'actions' => [
              [
                "type" => "Action.OpenUrl",
                "title" => $button_name,
                "url" => $url
              ]
            ],
            '$schema' => "http://adaptivecards.io/schemas/adaptive-card.json",
            "version" => "1.0",
            "msteams" => [
              "entities" => $entities
            ]
          ]
        ]
      ]
    ];
    $curl = curl_init($webhook);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
      "Content-Type: application/json",
    ));
    $response = curl_exec($curl);
    curl_close($curl);

    if ($response === false) {
      echo "Error sending message: " . curl_error($curl);
    } else {
      echo "Message sent successfully!";
    }
  }

  public static function notifyQuoteAward($quote_id, $user){
    $users = [$user];
    self::sendMessage('Status Award', 'Quote ID: ' . $quote_id, $users, EDITAR_COTIZACION . "/{$quote_id}", 'Open quote', WEBHOOK_AWARD);
  }

  public static function notifyQuoteFulfillment($quote_id, $users){
    self::sendMessage('Status Fulfillment', 'Quote ID: ' . $quote_id, $users, EDITAR_COTIZACION . "/{$quote_id}", 'Open quote', WEBHOOK_FULFILLMENT);
  }
}
