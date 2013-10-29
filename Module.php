<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <daniel@bugadani.hu>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\Processor;

use Miny\Application\BaseApplication;

class Module extends \Miny\Application\Module
{
    public function init(BaseApplication $app)
    {
        $app->add('processor', __NAMESPACE__ . '\ProcessorFactory')
                ->addProperty('transform', __NAMESPACE__ . '\Workers\Transform')
                ->addProperty('branch', __NAMESPACE__ . '\Workers\Branch')
                ->addProperty('filter', __NAMESPACE__ . '\Workers\Filter');
    }

}