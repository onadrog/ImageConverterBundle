<?php

namespace Onadrog\ImageConverterBundle\Command;

use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Doctrine\EntityClassGenerator;
use Symfony\Bundle\MakerBundle\FileManager;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Bundle\MakerBundle\Maker\MakeEntity;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Bundle\MakerBundle\Util\ClassDetails;
use Symfony\Bundle\MakerBundle\Util\ClassSourceManipulator;
use Symfony\Bundle\MakerBundle\Validator;
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

    public function __construct(private EntityClassGenerator $entityClassGenerator, private FileManager $fileManager)
    {
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator): void
    {
        $inputArray = ['name' => 'fileName', 'slug' => 'fileSlug', 'alt' => 'fileAlt', 'dimension' => 'fileDimension'];
        $entityClassDetails = $generator->createClassNameDetails(
            $input->getArgument('name'),
            'Entity\\'
        );
        $repositoryClassDetails = $generator->createClassNameDetails(
            $input->getArgument('name'),
            'Repository\\',
            'Repository'
        );

        $classExists = class_exists($entityClassDetails->getFullName());
        $className = $input->getArgument('name');

        if (!$classExists) {
            if ($input->getOption('interactive')) {
                foreach ($inputArray as $k => $v) {
                    $question = new Question(sprintf('Propertie name for the file <fg=blue>%s</>, defualt:', $k), $v);
                    $question->setValidator([Validator::class, 'validatePropertyName']);
                    $value = $io->askQuestion($question);
                    $inputArray = array_replace($inputArray, [$k => $value]);
                }
            }
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
        } else {
            $classPath = $this->getPathOfClass($entityClassDetails->getFullName());
            $manipulator = $this->createClassManipulator($classPath, $io, true);
            $fileManagerOperations = [];
            $fileManagerOperations[$classPath] = $manipulator;
            if ($input->getOption('interactive')) {
                $io->text(sprintf('The class <info>%s</info> already exist, let\'s add the required fields for the bundle', ucfirst($className)));
                foreach ($inputArray as $k => $v) {
                    $question = new Question(sprintf('Propertie name for the file <fg=blue>%s</>, defualt:', $k), $v);
                    $question->setValidator([Validator::class, 'validatePropertyName']);
                    $value = $io->askQuestion($question);
                    $inputArray = array_replace($inputArray, [$k => $value]);
                    if ('dimension' === $k) {
                        $manipulator->addEntityField($value, ['type' => 'json']);
                    } else {
                        $manipulator->addEntityField($value, ['type' => 'string', 'length' => 150]);
                    }
                }

                /*
                 * Waiting for attribute support
                 * https://github.com/symfony/maker-bundle/pull/920
                 */
                $manipulator->addProperty('file', []);
                $manipulator->addGetter('file', null, true);
                $manipulator->addSetter('file', null, true);
                $manipulator->addEntityField('addEntityField', ['type' => 'json']);
            }

            foreach ($fileManagerOperations as $path => $manipulatorOrMessage) {
                if (\is_string($manipulatorOrMessage)) {
                    $io->comment($manipulatorOrMessage);
                } else {
                    $this->fileManager->dumpFile($path, $manipulatorOrMessage->getSourceCode());
                }
            }
        }

        $this->writeSuccessMessage($io);
        $io->text([
            'Next: Properties are added but for now the maker-bundle doesn\'t support attributes annotations you have to <fg=red>add them mannualy</> see https://github.com/onadrog/ImageConverterBundle/wiki/usage',
                '',
            'Next: Add more fields later manually or by running <info>php bin/console make:entity</info>',
            'Next: When you\'re ready, create a migration with <info>php bin/console make:migration</info>',
            '',
        ]);
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

    private function createClassManipulator(string $path, ConsoleStyle $io, bool $overwrite): ClassSourceManipulator
    {
        $manipulator = new ClassSourceManipulator($this->fileManager->getFileContents($path), $overwrite);
        $manipulator->setIo($io);

        return $manipulator;
    }

    private function getPathOfClass(string $class): string
    {
        $classDetails = new ClassDetails($class);

        return $classDetails->getPath();
    }
}
