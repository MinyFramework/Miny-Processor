<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <daniel@bugadani.hu>
 *
 * For licensing information see the LICENCE file.
 */

namespace Modules\Processor;

use Iterator;

abstract class AbstractWorker implements iWorker
{
    protected $data;

    public function setData(Iterator $data)
    {
        $this->data = $data;
    }

    public function reset()
    {

    }

    public function work()
    {
        $value = $this->data->current();
        $key = $this->data->key();

        return $this->run($value, $key);
    }

    abstract protected function run($value, $key);

}