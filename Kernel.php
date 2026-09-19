<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Application;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\ObjectManagerInterface;
use Opengento\Application\ObjectManager\AppBootstrap;
use Opengento\Application\ObjectManager\BootstrapPool;

class Kernel
{
    private BootstrapPool $bootstrapPool;
    private ?AppBootstrap $appBootstrap = null;
    private ?ObjectManagerInterface $omInstance = null;

    public function __construct(array $initParams, private string $applicationType)
    {
        $this->bootstrapPool = new BootstrapPool($initParams);
    }

    public function handle(array $server, array $get): void
    {
        try {
            $this->appBootstrap = $this->bootstrapPool->get($server, $get);
            $this->omInstance = ObjectManager::getInstance();
            ObjectManager::setInstance($this->appBootstrap->getObjectManager());
            $app = $this->appBootstrap->createApplication($this->applicationType);
            if ($app !== null) {
                $this->appBootstrap->run($app);
            }
        } catch (LocalizedException $e) {
            echo $e->getMessage();
            exit(1);
        }
    }

    public function terminate(): void
    {
        $this->appBootstrap?->resetState();
        if ($this->omInstance !== null) {
            ObjectManager::setInstance($this->omInstance);
        }
    }
}
