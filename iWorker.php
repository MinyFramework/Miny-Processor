<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <daniel@bugadani.hu>
 *
 * For licensing information see the LICENCE file.
 */

namespace Modules\Processor;

use Iterator;

interface iWorker extends Iterator
{
    public function setHead(Iterator $head);
    public function getHead();
}