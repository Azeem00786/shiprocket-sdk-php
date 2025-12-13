<?php

namespace Hyperzod\ShiprocketSdkPhp\Client;

/**
 * Interface for a Shiprocket client.
 */
interface BaseShiprocketClientInterface
{
   /**
    * Gets the API key used by the client to send requests.
    *
    * @return null|string the API key used by the client to send requests
    */
   public function getClientEmail();
   /**
    * Gets the API key used by the client to send requests.
    *
    * @return null|string the API key used by the client to send requests
    */
   public function getClientPassword();

   /**
    * Gets the auth_url used by the client to send requests.
    *
    * @return null|string the auth_url used by the client to send requests
    */
   public function getClientAuthUrl();

   /**
    * Gets the base URL for Shiprocket's API.
    *
    * @return string the base URL for Shiprocket's API
    */
   public function getApiBase();
}
