<?php

namespace Onadrog\ImageConverterBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * @author Sébastien Gordano <sebastien.gordano@gmail.com>
 */
class ImageConverterDumpConf extends Command
{
    protected static $defaultName = 'onadrog:dump:config';

    public function __construct(private array $config)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Dump the default yaml configuration file for the bundle ');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $array = [
            'image_converter' => $this->config,
        ];
        $yaml = Yaml::dump($array);
        file_put_contents('config/packages/image_converter.yaml', $yaml);

        return Command::SUCCESS;
    }
}
