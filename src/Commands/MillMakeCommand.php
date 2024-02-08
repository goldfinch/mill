<?php

namespace Goldfinch\Mill\Commands;

use Goldfinch\Taz\Console\GeneratorCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;

#[AsCommand(name: 'make:mill')]
class MillMakeCommand extends GeneratorCommand
{
    protected static $defaultName = 'make:mill';

    protected $description = 'Create mill [Mill]';

    protected $path = '[psr4]/Mills';

    protected $type = 'mill';

    protected $stub = './stubs/mill.stub';

    protected $suffix = 'Mill';

    protected function execute($input, $output): int
    {
        if (parent::execute($input, $output) === false) {
            return Command::FAILURE;
        }

        $className = $this->askClassNameQuestion('What [class name] this mill need to be assigned to? (eg: Page, App\Pages\Page)', $input, $output);

        // find config
        $config = $this->findYamlConfigFileByName('app-mill');

        // create new config if not exists
        if (!$config) {

            $command = $this->getApplication()->find('make:config');
            $command->run(new ArrayInput([
                'name' => 'mill',
                '--plain' => true,
                '--after' => 'goldfinch/mill',
                '--nameprefix' => 'app-',
            ]), $output);

            $config = $this->findYamlConfigFileByName('app-mill');
        }

        // update config
        $this->updateYamlConfig(
            $config,
            'Goldfinch\Mill\Mill' . '.millable.' . $this->getNamespaceClass($input),
            $className
        );

        return Command::SUCCESS;
    }
}
