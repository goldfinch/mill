<?php

namespace Goldfinch\Mill\Commands;

use Goldfinch\Taz\Console\GeneratorCommand;
use Symfony\Component\Console\Command\Command;

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
        parent::execute($input, $output);

        return Command::SUCCESS;
    }
}
