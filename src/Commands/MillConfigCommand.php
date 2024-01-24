<?php

namespace Goldfinch\Mill\Commands;

use Goldfinch\Taz\Console\GeneratorCommand;
use Symfony\Component\Console\Command\Command;

#[AsCommand(name: 'vendor:mill:config')]
class MillConfigCommand extends GeneratorCommand
{
    protected static $defaultName = 'vendor:mill:config';

    protected $description = 'Create Mill YML config';

    protected $path = 'app/_config';

    protected $type = 'config';

    protected $stub = './stubs/config.stub';

    protected $extension = '.yml';

    protected function execute($input, $output): int
    {
        parent::execute($input, $output);

        return Command::SUCCESS;
    }
}
