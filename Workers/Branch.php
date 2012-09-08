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

class Branch extends AbstractWorker
{
    private $condition;
    private $true;
    private $false;

    public function __construct($condition, $true, $false = NULL)
    {
        if (!is_callable($condition)) {
            throw new InvalidArgumentException('Condition must be callable');
        }
        if (!is_callable($true)) {
            throw new InvalidArgumentException('True branch must be callable');
        }
        if ($false && !is_callable($false)) {
            throw new InvalidArgumentException('False branch must be callable');
        }
        $this->condition = $condition;
        $this->true = $true;
        $this->false = $false;
    }

    protected function run($value, $key)
    {
        if (call_user_func($this->condition, $value, $key)) {
            return call_user_func($this->true, $value, $key);
        }
        if ($this->false) {
            return call_user_func($this->false, $value, $key);
        }
        return $value;
    }

}