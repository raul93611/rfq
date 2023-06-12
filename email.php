<?php

// // Replace with your own values
// $tenantId = "0506708f-afde-4517-9f3e-3ec000518b07";
// $clientId = "be25953e-1014-4804-bbed-bf877e7259dc";
// $clientSecret = "xOc8Q~GJ3jOfmn-pnOgC~pBrM0mbjqljxkrSVaEs";
// $scope = "https://graph.microsoft.com/.default"; // Use appropriate scope for Microsoft Teams access

// // Request an access token
// $tokenEndpoint = "https://login.microsoftonline.com/{$tenantId}/oauth2/v2.0/token";
// $data = array(
//   "grant_type" => "client_credentials",
//   "client_id" => $clientId,
//   "client_secret" => $clientSecret,
//   "scope" => $scope,
// );
// $options = array(
//   "http" => array(
//     "header" => "Content-type: application/x-www-form-urlencoded\r\n",
//     "method" => "POST",
//     "content" => http_build_query($data),
//   ),
// );
// $context = stream_context_create($options);
// $response = file_get_contents($tokenEndpoint, false, $context);

// if ($response === false) {
//   echo "Error getting access token: " . print_r(error_get_last(), true);
//   exit;
// }

// $accessTokenData = json_decode($response, true);
// $accessToken = $accessTokenData["access_token"];

// echo $accessToken;

// Replace with your own values
$teamsWebhookUrl = "https://elogic1.webhook.office.com/webhookb2/5cbf724b-aeca-4765-96c7-bd13aa783588@0506708f-afde-4517-9f3e-3ec000518b07/IncomingWebhook/1cde5420666242c2b5bf074b25d3cf44/65d78568-e892-41ba-8d63-f02ac5914e70";
$mentionedUserId = "8:orgid:ab93466f-e2df-4157-b5ca-bb5a1a4170ad";

// Message payload
// $messagePayload = array(
//   "text" => "Hello, <at>{$mentionedUserId}</at> this is a message from PHP121233333333312!",
//   // "to" => array("raul93611_gmail.com@Elogic1.onmicrosoft.com", "user2@contoso.com"),
//   // Additional options can be added here
// );

$messagePayload = array(
  "@type" => "MessageCard",
  "title" => "New Task Created",
  "text" => "A new task has been created.",
  "sections" => array(
      array(
          "title" => "Task Details",
          "facts" => array(
              array("Name", "John Doe"),
              array("Due Date", "2023-05-01"),
              array("Status", "In Progress"),
          ),
      ),
  ),
);

// Send message using the Microsoft Teams API
$curl = curl_init($teamsWebhookUrl);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, '{
  "type": "message",
  "attachments": [
      {
      "contentType": "application/vnd.microsoft.card.adaptive",
      "content": {
          "type": "AdaptiveCard",
          "body": [
              {
                  "type": "TextBlock",
                  "size": "Medium",
                  "weight": "Bolder",
                  "text": "Sample Adaptive Card with User Mention"
              },
              {
                  "type": "TextBlock",
                  "text": "Hi <at>Raul Velasco</at>, <at>Adele Azure AD</at>"
              }
          ],
          "$schema": "http://adaptivecards.io/schemas/adaptive-card.json",
          "version": "1.0",
          "msteams": {
              "entities": [
                  {
                      "type": "mention",
                      "text": "<at>Raul Velasco</at>",
                      "mentioned": {
                        "id": "raul93611_gmail.com@Elogic1.onmicrosoft.com",
                        "name": "Raul Velasco"
                      }
                    },
                    {
                      "type": "mention",
                      "text": "<at>Adele Azure AD</at>",
                      "mentioned": {
                        "id": "87d349ed-44d7-43e1-9a83-5f2406dee5bd",
                        "name": "Adele Vance"
                      }
                    }
              ]
          }
      }
  }]
}');
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
  // "Authorization: Bearer ".$accessToken,
  "Content-Type: application/json",
));
$response = curl_exec($curl);
curl_close($curl);

// Check response for errors
if ($response === false) {
  echo "Error sending message: " . curl_error($curl);
} else {
  echo "Message sent successfully!";
}

// Use the access token to send messages or perform other actions in Microsoft Teams
// ...
