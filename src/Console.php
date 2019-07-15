<?php 

namespace Esojtec\CodeigniterConsole;

use Symfony\Component\Console\Application;

class Console
{
    protected $commands = [];
    protected $application;

    public function __construct($commands)
    {
        $this->_initialize();
    }

    public function _initialize()
    {
        $application = new Application();

        # We add here our codeigniter default commands

        $application->add(new Esojtec\CodeigniterConsole\Commands\ControllerCommand());
        $application->add(new Esojtec\CodeigniterConsole\Commands\ModelCommand());
        $application->add(new Esojtec\CodeigniterConsole\Commands\LibraryCommand());
        $application->add(new Esojtec\CodeigniterConsole\Commands\ViewCommand());
        $application->add(new Esojtec\CodeigniterConsole\Commands\MigrationCommand());
        $application->add(new Esojtec\CodeigniterConsole\Commands\MigrateCommand());
        $application->add(new Esojtec\CodeigniterConsole\Commands\RollbackCommand());
        $application->run();
    }

    public function addCommand($command)
    {
        if($command instanceof Application === FALSE)
            throw('Debe ser una instancia de Symfony\Component\Console\Application');

        $this->application->add($command);
    }
}