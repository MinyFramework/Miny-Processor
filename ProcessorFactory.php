<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <daniel@bugadani.hu>
 *
 * For licensing information see the LICENCE file.
 */

namespace Modules\Processor;

class ProcessorFactory
{
    private $workers = array();

    public function __set($name, $value)
    {
        $this->workers[$name] = $value;
    }

    public function process($data = NULL, $processor_class = NULL)
    {
        $processor_class = $processor_class ? : __NAMESPACE__ . '\Processor';
        $processor = new $processor_class($this->workers);
        if ($data) {
            $processor->setData($data);
        }
        return $processor;
    }

}