<?php

use Onadrog\ImageConverterBundle\Tests\ImageConverterTestingKernel;

require_once dirname(__DIR__, 2).'/vendor/autoload_runtime.php';

return function (array $context) {
    return new ImageConverterTestingKernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
