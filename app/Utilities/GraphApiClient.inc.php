<?php
class GraphApiClient {
  private static $token = null;
  private static $tokenExpiry = 0;

  public static function getToken() {
    if (self::$token && time() < self::$tokenExpiry) {
      return self::$token;
    }

    $url = 'https://login.microsoftonline.com/' . GRAPH_TENANT_ID . '/oauth2/v2.0/token';
    $body = http_build_query([
      'grant_type'    => 'client_credentials',
      'client_id'     => GRAPH_CLIENT_ID,
      'client_secret' => GRAPH_CLIENT_SECRET,
      'scope'         => 'https://graph.microsoft.com/.default',
    ]);

    $ch = curl_init($url);
    curl_setopt_array($ch, [
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_POST           => true,
      CURLOPT_POSTFIELDS     => $body,
      CURLOPT_HTTPHEADER     => ['Content-Type: application/x-www-form-urlencoded'],
      CURLOPT_TIMEOUT        => 15,
    ]);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200) {
      throw new RuntimeException('Graph API auth failed: HTTP ' . $httpCode . ' — ' . $response);
    }

    $data = json_decode($response, true);
    if (empty($data['access_token'])) {
      throw new RuntimeException('Graph API auth failed: no access_token in response');
    }

    self::$token = $data['access_token'];
    self::$tokenExpiry = time() + ($data['expires_in'] ?? 3600) - 60;
    return self::$token;
  }

  public static function request($method, $path, $payload = null) {
    $token = self::getToken();
    $url = 'https://graph.microsoft.com/v1.0' . $path;

    $headers = [
      'Authorization: Bearer ' . $token,
      'Content-Type: application/json',
    ];

    $ch = curl_init($url);
    curl_setopt_array($ch, [
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_CUSTOMREQUEST  => strtoupper($method),
      CURLOPT_HTTPHEADER     => $headers,
      CURLOPT_TIMEOUT        => 30,
    ]);

    if ($payload !== null) {
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    }

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode >= 400) {
      throw new RuntimeException('Graph API error: HTTP ' . $httpCode . ' — ' . $response);
    }

    return $response ? json_decode($response, true) : null;
  }

  public static function get($path) {
    return self::request('GET', $path);
  }

  public static function post($path, $payload) {
    return self::request('POST', $path, $payload);
  }

  public static function put($path, $payload) {
    return self::request('PUT', $path, $payload);
  }

  public static function patch($path, $payload) {
    return self::request('PATCH', $path, $payload);
  }

  public static function delete($path) {
    return self::request('DELETE', $path);
  }
}
