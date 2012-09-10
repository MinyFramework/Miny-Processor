<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <daniel@bugadani.hu>
 *
 * For licensing information see the LICENCE file.
 */

namespace Modules\Processor\Workers;

use InvalidArgumentException;
use Modules\Processor\AbstractWorker;

class Filter extends AbstractWorker
{
    private $filter;

    public function __construct($filter)
    {
        if (!is_callable($filter)) {
            throw new InvalidArgumentException('Filter must be callable.');
        }
        $this->filter = $filter;
    }

    private function move()
    {
        $data = $this->data;
        while ($data->valid() && call_user_func_array($this->filter, $data->current(), $data->key())) {
            $data->next();
        }
    }

    public function rewind()
    {
        parent::rewind();
        $this->move();
    }

    protected function next()
    {
        parent::next();
        $this->move();
    }

}