<?php

namespace Hyperzod\ShiprocketSdkPhp\Client;

use Exception;
use GuzzleHttp\Client;
use Hyperzod\ShiprocketSdkPhp\Exception\InvalidArgumentException;

class BaseShiprocketClient implements ShiprocketClientInterface
{

   /** @var array<string, mixed> */
   private $config;
   private $accessToken;
   /**
    * Initializes a new instance of the {@link BaseShiprocketClient} class.
    *
    * The constructor takes two arguments.
    * @param string $email the email of the client
    * @param string $password the password of the client
    * @param string $auth_url the base URL for Shiprocket's API
    * @param string $api_base the base URL for Shiprocket's API
    */

   public function __construct($email, $password, $auth_url, $api_base)
   {
      $config = $this->validateConfig(array(
         "email" => $email,
         "password" => $password,
         "auth_url" => $auth_url,
         "api_base" => $api_base,
      ));

      $this->config = $config;
   }

   /**
    * Gets the email used by the client to send requests.
    *
    * @return null|string the email used by the client to send requests
    */
   public function getClientEmail()
   {
      return $this->config['email'];
   }
   /**
    * Gets the password used by the client to send requests.
    *
    * @return null|string the password used by the client to send requests
    */
   public function getClientPassword()
   {
      return $this->config['password'];
   }

   /**
    * Gets the auth_url used by the client to send requests.
    *
    * @return null|string the auth_url used by the client to send requests
    */
   public function getClientAuthUrl()
   {
      return $this->config['auth_url'];
   }

   /**
    * Gets the api_base used by the client to send requests.
    *
    * @return null|string the api_base used by the client to send requests
    */
   public function getClientApiBase()
   {
      return $this->config['api_base'];
   }

   /**
    * Sets the access token used by the client to send requests.
    *
    * @param string $token the access token used by the client to send requests
    */
   public function setAccessToken($token)
   {
      $this->accessToken = $token;
   }

   /**
    * Gets the access token used by the client to send requests.
    *
    * @return null|string the access token used by the client to send requests
    */
   public function getAccessToken()
      {
      if ($this->accessToken) {
         return $this->accessToken;
      }

      // Instantiate a Guzzle client
      $client = new Client();

      $response = $client->post($this->getClientAuthUrl(), [
         'form_params' => [
            'client_email' => $this->getClientEmail(),
            'client_password' => $this->getClientPassword(),
         ]
      ]);

      // Get the response body as a string
      $responseBody = $response->getBody()->getContents();

      // Decode the JSON response
      $result = json_decode($responseBody, true);

      $this->accessToken = $result['access_token'];
      return $this->accessToken;
   } 

   /**
    * Gets the base URL for Shiprocket's API.
    *
    * @return string the base URL for Shiprocket's API
    */
   public function getApiBase()
   {
      return $this->config['api_base'];
   }

   /**
    * Sends a request to Shiprocket's API.
    *
    * @param string $method the HTTP method
    * @param string $path the path of the request
    * @param array $params the parameters of the request
    */

   public function request($method, $path, $params)
   {
      $client = new Client([
         'headers' => [
            'content-type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->getAccessToken()
         ]
      ]);

      $api = $this->getApiBase() . $path;

      $response = $client->request($method, $api, [
         'http_errors' => true,
         'body' => json_encode($params)
      ]);

      return $this->validateResponse($response);
   }

   /**
    * @param array<string, mixed> $config
    *
    * @throws InvalidArgumentException
    */
   private function validateConfig($config)
   {
      // api_key
      if (!isset($config['api_key'])) {
         throw new InvalidArgumentException('api_key field is required');
      }

      if (!is_string($config['api_key'])) {
         throw new InvalidArgumentException('api_key must be a string');
      }

      if ('' === $config['api_key']) {
         throw new InvalidArgumentException('api_key cannot be an empty string');
      }

      if (preg_match('/\s/', $config['api_key'])) {
         throw new InvalidArgumentException('api_key cannot contain whitespace');
      }

      if (!isset($config['api_base'])) {
         throw new InvalidArgumentException('api_base field is required');
      }

      if (!is_string($config['api_base'])) {
         throw new InvalidArgumentException('api_base must be a string');
      }

      if ('' === $config['api_base']) {
         throw new InvalidArgumentException('api_base cannot be an empty string');
      }

      return [
         "api_key" => $config['api_key'],
         "api_base" => $config['api_base'],
      ];
   }

   private function validateResponse($response)
   {
      $status_code = $response->getStatusCode();

      $body = json_decode($response->getBody(), true);

      if ($status_code >= 200 && $status_code < 300) {
         if (isset($body['type']) && $body['type'] === 'success') {
            return $body;
         }
         if (isset($body['errors']) && is_array($body['errors']) && count($body['errors']) > 0) {
            throw new Exception($body['errors'][0]['message'] ?? 'Unknown error');
         }
         throw new Exception("Unknown error or unexpected response structure");
      } else {
         if (isset($body['errors']) && is_array($body['errors']) && count($body['errors']) > 0) {
            throw new Exception($body['errors'][0]['message'] ?? 'Unknown error');
         }
         throw new Exception("Errors node not set in server response");
      }
   }
}
