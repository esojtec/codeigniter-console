<?php 

namespace Esojtec\CodeigniterConsole;

use Symfony\Component\Console\Application;

class Console
{
    protected $commands = [];
    protected $application;

    public function __construct($commands = [])
    {
        $this->_initialize();

        foreach($commands as $command)
        {
            $this->addCommand($command);
        }
    }

    public function _initialize()
    {
        $application = new Application();

        # We add here our codeigniter default commands

        $application->add(new Commands\ControllerCommand());
        $application->add(new Commands\ModelCommand());
        $application->add(new Commands\LibraryCommand());
        $application->add(new Commands\ViewCommand());
        $application->add(new Commands\MigrationCommand());
        $application->add(new Commands\MigrateCommand());
        $application->add(new Commands\RollbackCommand());
        $application->add(new Commands\DeployCommand());
        $application->run();
    }

    public function addCommand($command)
    {
        if($command instanceof Application === FALSE)
            throw('Debe ser una instancia de Symfony\Component\Console\Application');

        $this->application->add($command);
    }
}