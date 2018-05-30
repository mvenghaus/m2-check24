<?php

namespace Inkl\Check24\Api;

use Inkl\Check24\Api\Data\OrderInterface;

interface OrderProviderInterface
{
    /** @return \Inkl\Check24\Api\Data\OrderSearchResultsInterface */
    public function getNotProcessedList();

    /** @return OrderInterface|null */
    public function getByFilename($filename);
}