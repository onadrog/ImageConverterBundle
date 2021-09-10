<?php

namespace Onadrog\ImageConverterBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class CommandTest extends KernelTestCase
{
    public function testConfig()
    {
        $kernel = $this->createKernel();
        $app = new Application($kernel);
        $command = $app->find('onadrog:debug:config');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('quality: 80', $output);
    }

    /**
     * test that an entity is created with the given arg.
     */
    public function testAutoGeneration()
    {
        $classPath = dirname(__DIR__, 1).'/Mock/Entity/Entity/Yo.php';
        $repoPath = dirname(__DIR__, 1).'/Mock/Entity/Repository/YoRepository.php';

        $kernel = $this->createKernel();
        $app = new Application($kernel);
        $command = $app->find('onadrog:make:entity');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['name' => 'yo']);
        $this->assertFileExists($classPath);
        $this->assertFileExists($repoPath);
        unlink($classPath);
        unlink($repoPath);
    }
}
