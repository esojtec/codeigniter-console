<?php 

namespace Esojtec\CodeigniterConsole;

use Symfony\Component\Console\Application;

class Console
{
    protected $commands = [];
    protected $application;

    public function __construct($commands = [])
    {
        $this->application = new Application();
        $this->_initialize();

        foreach($commands as $command)
        {
            $this->addCommand($command);
        }
    }

    public function _initialize()
    {
        

        # We load here our default commands

        $this->application->add(new Commands\ControllerCommand());
        $this->application->add(new Commands\ModelCommand());
        $this->application->add(new Commands\LibraryCommand());
        $this->application->add(new Commands\ViewCommand());
        $this->application->add(new Commands\MigrationCommand());
        $this->application->add(new Commands\MigrateCommand());
        $this->application->add(new Commands\RollbackCommand());
        $this->application->add(new Commands\DeployCommand());
        $this->application->add(new Commands\AppCommand());
        
    }

    public function addCommand($command)
    {
        if($command instanceof \Symfony\Component\Console\Command\Command === FALSE)
        {
            echo 'El comando deber ser una instancia de Symfony\Component\Console\Command\Command';
            exit;
        }

        $this->application->add($command);
    }

    public function run()
    {
        $this->application->run();
    }
}