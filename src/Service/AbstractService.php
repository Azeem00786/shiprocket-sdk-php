<?php

namespace Hyperzod\ShiprocketSdkPhp\Service;

/**
 * Abstract base class for all services.
 */
abstract class AbstractService
{
   /**
    * @var \Hyperzod\ShiprocketSdkPhp\Client\ShiprocketClientInterface
    */
   protected $client;

   /**
    * Initializes a new instance of the {@link AbstractService} class.
    *
    * @param \Hyperzod\ShiprocketSdkPhp\Client\ShiprocketClientInterface $client
    */
   public function __construct($client)
   {
      $this->client = $client;
   }

   /**
    * Gets the client used by this service to send requests.
    *
    * @return \Hyperzod\ShiprocketSdkPhp\Client\ShiprocketClientInterface
    */
   public function getClient()
   {
      return $this->client;
   }

   protected function request($method, $path, $params)
   {
      return $this->getClient()->request($method, $path, $params);
   }
}
