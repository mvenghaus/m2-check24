<?php

namespace Inkl\Check24\Ui\Component\Listing\Column\Order\Error;

use Magento\Framework\Data\OptionSourceInterface;

class Options implements OptionSourceInterface
{
    /**
     * @var array
     */
    protected $options;

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        if ($this->options === null) {
            $this->options = [
                ['value' => 0, 'label' => __('No')],
                ['value' => 1, 'label' => __('Yes')]
            ];
        }
        return $this->options;
    }
}
