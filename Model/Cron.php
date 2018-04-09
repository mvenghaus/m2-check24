<?php

namespace Inkl\Check24\Model;

use Inkl\Check24\Model\Task\ImportOrderTask;
use Inkl\Check24\Model\Task\ProcessOrderTask;
use Magento\Framework\ObjectManagerInterface;

class Cron
{
    /** @var ObjectManagerInterface */
    private $objectManager;

    /**
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function run()
    {
        $this->objectManager->get(ImportOrderTask::class)->run();
        $this->objectManager->get(ProcessOrderTask::class)->run();
    }

}