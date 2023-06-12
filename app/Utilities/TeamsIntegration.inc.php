<?php
class TeamsIntegration {
  public static function sendMessage($message) {
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
                "text" => "Sample Adaptive Card with User Mention"
              ],
              [
                "type" => "TextBlock",
                "text" => "Hi <at>Raul Velasco</at>, <at>Adele Azure AD</at>"
              ]
            ],
            '$schema' => "http://adaptivecards.io/schemas/adaptive-card.json",
            "version" => "1.0",
            "msteams" => [
              "entities" => [
                [
                  "type" => "mention",
                  "text" => "<at>Raul Velasco</at>",
                  "mentioned" => [
                    "id" => "raul93611_gmail.com@Elogic1.onmicrosoft.com",
                    "name" => "Raul Velasco"
                  ]
                ]
              ]
            ]
          ]
        ]
      ]
    ];

    $curl = curl_init(WEBHOOK);
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
}
