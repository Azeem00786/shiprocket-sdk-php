<?php

namespace Hyperzod\ShiprocketSdkPhp\Client;

use Hyperzod\ShiprocketSdkPhp\Service\CoreServiceFactory;

class ShiprocketClient extends BaseShiprocketClient
{
    /**
     * @var CoreServiceFactory
     */
    private $coreServiceFactory;

    public function __get($name)
    {
        if (null === $this->coreServiceFactory) {
            $this->coreServiceFactory = new CoreServiceFactory($this);
        }

        return $this->coreServiceFactory->__get($name);
    }
}
