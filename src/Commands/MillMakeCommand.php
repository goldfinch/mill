<?php

namespace Goldfinch\Mill\Commands;

use Symfony\Component\Finder\Finder;
use Goldfinch\Taz\Console\GeneratorCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;

#[AsCommand(name: 'make:mill')]
class MillMakeCommand extends GeneratorCommand
{
    protected static $defaultName = 'make:mill';

    protected $description = 'Create mill [Mill]';

    protected $path = '[psr4]/Mills';

    protected $type = 'mill';

    protected $stub = './stubs/mill.stub';

    protected $prefix = 'Mill';

    protected function execute($input, $output): int
    {
        $millName = $input->getArgument('name');
        $target = $input->getArgument('target');

        $millName = 'App\Mills\\' . $millName . $this->prefix; // TODO

        if (!$this->setMillInConfig($millName, $target)) {
            // create config

            $command = $this->getApplication()->find('vendor:mill:config');

            $arguments = [
                'name' => 'mill',
            ];

            $greetInput = new ArrayInput($arguments);
            $returnCode = $command->run($greetInput, $output);

            $this->setMillInConfig($millName, $target);
        }

        parent::execute($input, $output);

        return Command::SUCCESS;
    }

    private function setMillInConfig($millName, $target)
    {
        $rewritten = false;

        $finder = new Finder();
        $files = $finder->in(BASE_PATH . '/app/_config')->files()->contains('Goldfinch\Mill\Mill:');

        foreach ($files as $file) {

            // stop after first replacement
            if ($rewritten) {
                break;
            }

            if (strpos($file->getContents(), 'millable') !== false) {

                $ucfirst = ucfirst($millName);

                $newContent = $this->addToLine(
                    $file->getPathname(),
                    'millable:','    '.$millName.': '.$target,
                );

                file_put_contents($file->getPathname(), $newContent);

                $rewritten = true;
            }
        }

        return $rewritten;
    }

    protected function configure(): void
    {
        $this
            ->setDescription($this->description)
            ->setHelp($this->help);

        $this->addArgument(
            'name',
            InputArgument::REQUIRED,
            'The target class of the ' . strtolower($this->type)
       );

       $this->addArgument(
            'target',
            InputArgument::REQUIRED,
            'What is the target of ' . strtolower($this->type) . '? Use full namespace path to the class'
       );
    }

    protected function promptForMissingArgumentsUsing()
    {
        return [
            'name' => 'What should the ' . strtolower($this->type) . ' be named?',
            'target' => 'What target should the ' . strtolower($this->type) . ' be reffered to?',
        ];
    }
}
