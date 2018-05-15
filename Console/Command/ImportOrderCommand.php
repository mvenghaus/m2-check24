<?php

namespace Inkl\Check24\Console\Command;

use Inkl\Check24\Model\Task\ImportOrderTask;
use Magento\Framework\App\AreaList;
use Magento\Framework\App\ObjectManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Framework\App\State;

class ImportOrderCommand extends Command
{
    /** @var State */
    private $state;
    /** @var AreaList */
    private $areaList;

    /**
     * @param State $state
     * @param AreaList $areaList
     * @param null $name
     */
    public function __construct(State $state,
                                AreaList $areaList,
                                $name = null)
    {
        parent::__construct($name);
        $this->state = $state;
        $this->areaList = $areaList;
    }

    protected function configure()
    {
        $this->setName('channels:check24:import-order')
            ->setDescription('Import Orders');

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_GLOBAL);
        $this->areaList->getArea(\Magento\Framework\App\Area::AREA_GLOBAL)->load(\Magento\Framework\App\Area::PART_TRANSLATE);

        ObjectManager::getInstance()->get(ImportOrderTask::class)->run();
    }
}
