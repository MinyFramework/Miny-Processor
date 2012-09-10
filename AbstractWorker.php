<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <daniel@bugadani.hu>
 *
 * For licensing information see the LICENCE file.
 */

namespace Modules\Processor;

abstract class AbstractWorker implements iWorker
{
    protected $head;

    public function setHead(Iterator $head)
    {
        $this->head = $head;
        return $this;
    }

    public function getHead()
    {
        return $this->head;
    }

    public function current()
    {
        return $this->head->current();
    }

    public function key()
    {
        return $this->head->key();
    }

    public function next()
    {
        $this->head->next();
    }

    public function rewind()
    {
        $this->head->rewind();
    }

    public function valid()
    {
        return $this->head->valid();
    }

}