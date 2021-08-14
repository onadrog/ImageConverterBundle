<?php

namespace Onadrog\ImageConverterBundle\Tests\Unit;

use Onadrog\ImageConverterBundle\EventSubscriber\ImageConverterSubscriber;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormEvents;

class SubscriberTest extends TestCase
{
    public function testEventSubscribed(): void
    {
        $this->assertArrayHasKey(FormEvents::PRE_SUBMIT, ImageConverterSubscriber::getSubscribedEvents());
    }
}
