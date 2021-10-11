<?php

namespace Onadrog\ImageConverterBundle\Tests\Panther;

use Facebook\WebDriver\WebDriverBy;
use Symfony\Component\Panther\Client;
use Symfony\Component\Panther\PantherTestCase;

/**
 * @author Sébastien Gordano <sebastien.gordano@gmail.com>
 */
class ModalTest extends PantherTestCase
{
    private Client $client;

    private function getPanther()
    {
        $client = static::createPantherClient();

        $client->request('GET', '/form/Product/media');

        return $client;
    }

    public function testModalExist()
    {
        $this->getPanther()->waitFor('image-converter-modal', 2);
        $this->assertSelectorExists('image-converter-modal');
    }

    public function testSelectExistingImage()
    {
        $client = static::createPantherClient();

        $crawler = $client->request('GET', '/form/Product/media');

        $client->waitFor('image-converter-modal', 2);
        $client->getWebDriver()->findElement(WebDriverBy::id('imc-openModal'))->click();
        $client->waitForVisibility('#imc-modal-fields', 2);
        $this->assertSelectorIsVisible('#imc-modal-fields');
        $client->getWebDriver()->findElement(WebDriverBy::cssSelector("input[value='1']"))->click();
        $client->getWebDriver()->findElement(WebDriverBy::className('imc-modal-save'))->click();
        $client->waitForInvisibility('#imc-modal-fields', 2);
        $form = $crawler->selectButton('Save')->form(['mock[name]' => 'test']);
        $client->wait(2);
        $client->submit($form);
    }
}
