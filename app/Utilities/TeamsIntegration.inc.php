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

    $bodyItems = [
      [
        "type" => "Container",
        "style" => "emphasis",
        "items" => [
          [
            "type" => "ColumnSet",
            "columns" => [
              [
                "type" => "Column",
                "width" => "auto",
                "items" => [[
                  "type" => "Icon",
                  "name" => "Document",
                  "style" => "filled",
                  "size" => "Large",
                  "color" => "Accent"
                ]]
              ],
              [
                "type" => "Column",
                "width" => "stretch",
                "items" => [
                  [
                    "type" => "TextBlock",
                    "text" => $title,
                    "weight" => "Bolder",
                    "size" => "Large",
                    "color" => "Accent"
                  ],
                  [
                    "type" => "TextBlock",
                    "text" => "E-Logic Portal",
                    "isSubtle" => true,
                    "spacing" => "None"
                  ]
                ]
              ]
            ]
          ]
        ]
      ],
      [
        "type" => "Container",
        "items" => [[
          "type" => "TextBlock",
          "text" => $message,
          "wrap" => true
        ]]
      ]
    ];

    if (!empty($mentions)) {
      $bodyItems[] = [
        "type" => "Container",
        "spacing" => "Medium",
        "items" => [[
          "type" => "ColumnSet",
          "columns" => [
            [
              "type" => "Column",
              "width" => "auto",
              "items" => [[
                "type" => "Icon",
                "name" => "Person",
                "style" => "filled",
                "size" => "Small",
                "color" => "Default"
              ]]
            ],
            [
              "type" => "Column",
              "width" => "stretch",
              "items" => [[
                "type" => "TextBlock",
                "text" => implode(', ', $mentions),
                "wrap" => true,
                "spacing" => "None"
              ]]
            ]
          ]
        ]]
      ];
    }

    $payload = [
      "type" => "message",
      "attachments" => [
        [
          "contentType" => "application/vnd.microsoft.card.adaptive",
          "content" => [
            '$schema' => "http://adaptivecards.io/schemas/adaptive-card.json",
            "type" => "AdaptiveCard",
            "version" => "1.5",
            "body" => $bodyItems,
            "actions" => [
              [
                "type" => "Action.OpenUrl",
                "title" => $button_name,
                "iconUrl" => "icon:Document,filled",
                "url" => $url
              ]
            ],
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
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);
  }

  public static function sendDirectMessage($recipient_email, $message) {
    $payload = [
      "recipient" => $recipient_email,
      "message"   => $message
    ];
    $curl = curl_init('https://cmnetspixaq8r115od7hnp3yu.agent.pa.smyth.ai/api/send_notification');
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 5);
    curl_exec($curl);
    curl_close($curl);
  }

  public static function notifyQuoteCreated($id_rfq, $designated_user, $channel, $email_code, $type_of_bid, $issue_date, $end_date, $created_by) {
    $quote_url = SERVIDOR . 'quote/editar_cotizacion/' . $id_rfq;

    $card = [
      '$schema' => "http://adaptivecards.io/schemas/adaptive-card.json",
      "type"    => "AdaptiveCard",
      "version" => "1.5",
      "body"    => [
        [
          "type"  => "Container",
          "style" => "emphasis",
          "items" => [[
            "type"    => "ColumnSet",
            "columns" => [
              [
                "type"  => "Column",
                "width" => "auto",
                "items" => [[
                  "type"  => "Icon",
                  "name"  => "Document",
                  "style" => "filled",
                  "size"  => "Large",
                  "color" => "Accent"
                ]]
              ],
              [
                "type"  => "Column",
                "width" => "stretch",
                "items" => [
                  [
                    "type"   => "TextBlock",
                    "text"   => "New Quote Assigned",
                    "weight" => "Bolder",
                    "size"   => "Large",
                    "color"  => "Accent"
                  ],
                  [
                    "type"     => "TextBlock",
                    "text"     => "E-Logic Portal",
                    "isSubtle" => true,
                    "spacing"  => "None"
                  ]
                ]
              ]
            ]
          ]]
        ],
        [
          "type"  => "Container",
          "items" => [
            [
              "type"    => "TextBlock",
              "text"    => "A new quote has been assigned to you.",
              "wrap"    => true,
              "spacing" => "Medium"
            ],
            [
              "type"  => "FactSet",
              "facts" => [
                ["title" => "📋 Quote #",     "value" => "#" . $id_rfq],
                ["title" => "📝 Code",         "value" => $email_code],
                ["title" => "📡 Channel",      "value" => $channel],
                ["title" => "🎯 Type of Bid",  "value" => $type_of_bid],
                ["title" => "📅 Issue Date",   "value" => $issue_date],
                ["title" => "⏰ Due Date",      "value" => $end_date],
                ["title" => "👤 Created by",   "value" => $created_by],
              ]
            ]
          ]
        ]
      ],
      "actions" => [
        [
          "type"    => "Action.OpenUrl",
          "title"   => "Open Quote",
          "iconUrl" => "icon:Document,filled",
          "url"     => $quote_url
        ]
      ]
    ];

    $message = [
      "body" => [
        "contentType" => "html",
        "content"     => "<attachment id=\"1\"></attachment>"
      ],
      "attachments" => [
        [
          "id"          => "1",
          "contentType" => "application/vnd.microsoft.card.adaptive",
          "contentUrl"  => null,
          "content"     => json_encode($card)
        ]
      ]
    ];

    self::sendDirectMessage($designated_user->obtener_email(), $message);
  }

  public static function notifyQuoteAward($quote_id, $user, $total_price){
    $users = [$user];
    $amount = '$' . number_format((float)$total_price, 2);
    $message = "📋 Quote #: **{$quote_id}**\n💰 Amount: **{$amount}**\n🏆 Status: **Award**";
    self::sendMessage('Quote Award', $message, $users, EDITAR_COTIZACION . "/{$quote_id}", 'Open Quote', WEBHOOK_AWARD);
  }

  public static function notifyQuoteFulfillment($quote_id, $type_of_contract, $total_price, $users){
    $amount = '$' . number_format((float)$total_price, 2);
    $message = "📋 Quote #: **{$quote_id}**\n💰 Amount: **{$amount}**\n📄 Contract: **{$type_of_contract}**\n🔄 Status: **Fulfillment**";
    self::sendMessage('Quote Fulfillment', $message, $users, EDITAR_COTIZACION . "/{$quote_id}", 'Open Quote', WEBHOOK_FULFILLMENT);
  }
}
