<?php

namespace Inkl\Check24\Logger;

use Inkl\Check24\Helper\Config\GeneralConfig;

class Logger extends \Monolog\Logger
{
    /** @var GeneralConfig */
    private $generalConfig;

    /**
     * @param string $name
     * @param GeneralConfig $generalConfig
     * @param array $handlers
     * @param array $processors
     */
    public function __construct(string $name,
                                GeneralConfig $generalConfig,
                                $handlers = array(),
                                $processors = array())
    {
        parent::__construct($name, $handlers, $processors);

        $this->generalConfig = $generalConfig;
    }

    public function addRecord($level, $message, array $context = array())
    {
        if (!$this->generalConfig->isDebugEnabled())
        {
            return false;
        }

        return parent::addRecord($level, $message, $context);
    }

}