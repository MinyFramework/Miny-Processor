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

class Transform extends AbstractWorker
{
    private $transform;

    public function __construct($transform)
    {
        if (!is_callable($transform)) {
            throw new InvalidArgumentException('Transform callback must be callable.');
        }
        $this->transform = $transform;
    }

    protected function run($value, $key)
    {
        return call_user_func($this->transfrom, $value, $key);
    }

}