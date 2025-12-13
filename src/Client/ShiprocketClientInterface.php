<?php

namespace Hyperzod\ShiprocketSdkPhp\Client;

/**
 * Interface for a Shiprocket client.
 */
interface ShiprocketClientInterface extends BaseShiprocketClientInterface
{
   /**   
    * Sends a request to Shiprocket's API.
    *
    * @param string $method the HTTP method
    * @param string $path the path of the request
    * @param array $params the parameters of the request
    */
   public function request($method, $path, $params);
}
