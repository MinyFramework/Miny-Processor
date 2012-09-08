<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <daniel@bugadani.hu>
 *
 * For licensing information see the LICENCE file.
 */

namespace Modules\Processor;

use Iterator;

interface iWorker
{
    public function setData(Iterator $data);
    public function reset();
    public function work();
}