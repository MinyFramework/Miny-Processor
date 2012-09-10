<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <daniel@bugadani.hu>
 *
 * For licensing information see the LICENCE file.
 */

namespace Modules\Processor;

use ArrayIterator;
use OutOfBoundsException;
use ReflectionClass;
use UnexpectedValueException;
use Workers\HeadWorker;

class Processor extends AbstractWorker
{
    private $workers;
    private $tail;

    public function __construct(array $workers)
    {
        $this->workers = $workers;
        $this->head = new HeadWorker();
        $this->tail = $this->head;
    }

    public function addTask(iWorker $task)
    {
        $task->setHead($this->tail);
        $this->tail = $task;
        return $this;
    }

    public function __invoke($data)
    {
        return $this->setData($data);
    }

    public function setData($data)
    {
        if (!$data) {
            $data = array();
        }
        if (is_array($data)) {
            $data = new ArrayIterator($data);
        }
        $this->head = $data;
        return $this;
    }

    public function process($data = NULL)
    {
        if ($data) {
            $this->setData($data);
        }
        $return = array();
        foreach ($this as $key => $value) {
            $return[$key] = $value;
        }
        return $return;
    }

    public function __call($name, $parameters)
    {
        if (!isset($this->workers[$name])) {
            throw new OutOfBoundsException('Worker ' . $name . ' not exists');
        }

        $worker = $this->workers[$name];

        if (is_callable($worker)) {
            $worker = call_user_func_array($worker, $parameters);
        } elseif (is_string($worker) && class_exists($worker)) {

            switch (count($parameters)) {
                case 0:
                    $worker = new $worker;
                    break;
                case 1:
                    $worker = new $worker(current($parameters));
                    break;
                default:
                    $ref = new ReflectionClass($worker);
                    $worker = $ref->newInstanceArgs($parameters);
            }
        } else {
            throw new UnexpectedValueException('Worker ' . $name . ' has an invalid initializator set.');
        }

        if (!$worker instanceof iWorker) {
            throw new UnexpectedValueException('Worker ' . $name . ' must implement iWorker.');
        }

        return $this->addTask($worker);
    }


    public function current()
    {
        return $this->tail->current();
    }

    public function key()
    {
        return $this->tail->key();
    }

    public function next()
    {
        $this->tail->next();
    }

    public function rewind()
    {
        $this->tail->rewind();
    }

    public function valid()
    {
        return $this->tail->valid();
    }

}