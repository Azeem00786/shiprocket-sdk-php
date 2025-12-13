<?php

namespace Hyperzod\ShiprocketSdkPhp\Service;

use Hyperzod\ShiprocketSdkPhp\Enums\HttpMethodEnum;

class OrderService extends AbstractService
{
   /**
    * Create a job on Shiprocket
    *
    * @param array $params
    *
    * @throws \Hyperzod\ShiprocketSdkPhp\Exception\ApiErrorException if the request fails
    *
    */
   public function create(array $params)
   {
      return $this->request(HttpMethodEnum::POST, 'v1/external/orders/create/adhoc', $params);
   }
}
