<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <daniel@bugadani.hu>
 *
 * For licensing information see the LICENCE file.
 */

namespace Modules\Processor;

use ArrayIterator;
use Iterator;
use OutOfBoundsException;
use UnexpectedValueException;

class Processor implements Iterator
{
    private $data;
    private $workers;
    private $task = array();

    public function __construct(array $workers)
    {
        $this->workers = $workers;
    }

    public function append(Processor $other)
    {
        array_walk($other->task, array($this, 'addTask'));
        return $this;
    }

    public function addTask(iWorker $task)
    {
        $task->setData($this->data);
        $this->task[] = $task;
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
        $this->data = $data;

        foreach ($this->tasks as $task) {
            $task->setData($data);
        }

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
        $value = $this->data->current();
        $key = $this->data->key();

        foreach ($this->task as $worker) {
            $value = $worker->run($value, $key);
        }
        return $value;
    }

    public function key()
    {
        return $this->data->key();
    }

    public function next()
    {
        return $this->data->next();
    }

    public function rewind()
    {
        $this->data->rewind();
        foreach ($this->workers as $worker) {
            $worker->reset();
        }
    }

    public function valid()
    {
        return $this->data->valid();
    }

}