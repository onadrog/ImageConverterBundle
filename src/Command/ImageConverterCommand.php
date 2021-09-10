<?php

namespace Onadrog\ImageConverterBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Sébastien Gordano <sebastien.gordano@gmail.com>
 */
class ImageConverterCommand extends Command
{
    protected static $defaultName = 'onadrog:debug:config';

    public function __construct(private array $config)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Show default configuration');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->config as $k => $v) {
            $output->writeln(sprintf('<fg=green;options=bold>%s</>: %s', $k, var_export($v, true)));
        }

        return Command::SUCCESS;
    }
}
