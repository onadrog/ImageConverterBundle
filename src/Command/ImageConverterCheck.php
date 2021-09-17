<?php

namespace Onadrog\ImageConverterBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImageConverterCheck extends Command
{
    protected static $defaultName = 'onadrog:check:reqs';
    // https://www.fileformat.info/info/unicode/block/miscellaneous_symbols_and_pictographs/list.htm symbols/characters

    private const CHECK_MARK = "\u{1F5F8}";
    private const CROSS = "\u{1F5F4}";

    protected function configure(): void
    {
        $this->setDescription('Check if the bundle requirements and the platform are met');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (phpversion() < 8) {
            $output->writeln(sprintf('<fg=blue>PHP8.0:</> <fg=red>%s ', phpversion()).self::CROSS.'</>');
            $output->writeln('This bundle use the new <info>Php 8 Attributes</info>, please consider upgrading to php8 to use this bundle.');
        } else {
            $output->write(sprintf('<fg=blue>PHP8.0:</> <fg=green>%s ', phpversion()).self::CHECK_MARK.'</>');
        }
        if (extension_loaded('gd')) {
            $output->writeln('');
            $output->writeln('<fg=blue>GD:</> <fg=green>GD extension installed '.self::CHECK_MARK.'</>');
        } else {
            $output->writeln('');
            $output->writeln('<fg=blue>GD:</> <fg=red>GD extension not found '.self::CROSS.'</>');
            $output->writeln('This bundle use the <info>Gd extension </info>to convert images, please visit <comment>https://www.php.net/manual/en/book.image.php</comment> for more informattions');
        }

        return Command::SUCCESS;
    }
}
