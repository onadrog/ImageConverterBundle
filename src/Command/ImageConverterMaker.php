<?php

namespace Onadrog\ImageConverterBundle\Command;

use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Doctrine\EntityClassGenerator;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Bundle\MakerBundle\Maker\MakeEntity;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Question\Question;

/**
 * @author Sébastien Gordano <sebastien.gordano@gmail.com>
 */
class ImageConverterMaker extends AbstractMaker
{
    private const TEMPLATE = '/Command/Skeleton/Entity.tpl.php';

    public function __construct(private EntityClassGenerator $entityClassGenerator)
    {
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator): void
    {
        $inputArray = ['name' => 'fileName', 'slug' => 'fileSlug', 'alt' => 'fileAlt', 'dimension' => 'fileDimension'];
        if ($input->getOption('interactive')) {
            foreach ($inputArray as $k => $v) {
                $question = new Question(sprintf('Propertie name for the file <fg=blue>%s</>, defualt:', $k), $v);
                $value = $io->askQuestion($question);
                $inputArray = array_replace($inputArray, [$k => $value]);
            }
        }

        $entityClassDetails = $generator->createClassNameDetails(
            $input->getArgument('name'),
            'Entity\\'
        );
        $repositoryClassDetails = $generator->createClassNameDetails(
            $input->getArgument('name'),
            'Repository\\',
            'Repository'
        );

        $className = $input->getArgument('name');
        $this->entityClassGenerator->generateEntityClass($entityClassDetails, false);
        $generator->generateClass($entityClassDetails->getFullName(), dirname(__DIR__).self::TEMPLATE, [
            'class_name' => $className,
            'namespace' => Str::getNamespace($className),
            'repository_full_class_name' => $repositoryClassDetails->getFullName(),
            'repository_class_name' => $repositoryClassDetails->getShortName(),
            'name' => $inputArray['name'],
            'slug' => $inputArray['slug'],
            'alt' => $inputArray['alt'],
            'dimension' => $inputArray['dimension'],
        ]);
        $generator->writeChanges();
    }

    public function configureDependencies(DependencyBuilder $dependencies): void
    {
        $dependencies->addClassDependency(MakeEntity::class, 'symfony/maker-bundle');
    }

    public function configureCommand(Command $command, InputConfiguration $inputConfig): void
    {
        $command->addOption('interactive', '-i', InputOption::VALUE_NONE, 'Change the default properties');
        $command->addArgument('name', InputArgument::OPTIONAL, sprintf('Choose a name for your entity class (e.g. <fg=yellow>%s</>)', Str::asClassName(Str::getRandomTerm())));
    }

    public static function getCommandName(): string
    {
        return 'onadrog:make:entity';
    }

    public function interact(InputInterface $input, ConsoleStyle $io, Command $command): void
    {
        if ($input->getArgument('name')) {
            return;
        }

        $argument = $command->getDefinition()->getArgument('name');
        $question = new Question($argument->getDescription());
        $io->askQuestion($question);
    }

    public static function getCommandDescription(): string
    {
        return 'Create a Doctrine entity with all needed properties';
    }
}
