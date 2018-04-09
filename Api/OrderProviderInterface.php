<?php

namespace Inkl\Check24\Api;

interface OrderProviderInterface
{

    /** @return \Inkl\Check24\Api\Data\OrderSearchResultsInterface */
    public function getNotProcessedList();

}