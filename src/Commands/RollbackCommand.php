<?php
namespace Esojtec\CodeigniterConsole\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RollbackCommand extends Command
{
    protected $templates = __DIR__ . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR;
    protected $migrations = APPPATH . 'migrations' . DIRECTORY_SEPARATOR;

    protected function configure()
    {
        $this
            ->setName('migrate:rollback')
            ->setDescription('execute migration rollback')
            ->addOption(
                'steps', // should be specified like "migration:rollback name --steps=2"
                's',
                InputOption::VALUE_OPTIONAL,
                'if set, you can pass --steps=number or -s number',
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        $steps = ($input->getOption('steps'))? $input->getOption('steps') : 1;

        $routing = ['controller' => 'MigrationController', 'function' => 'rollback'];
        require_once BASEPATH . 'core/codeigniter.php';

        // we get estatus for the migration
        $migration = $CI->get_estatus();

        if(is_numeric($migration))
            $output->writeln("<info>Se realizo rollback a la version $migration correctamente.</info>");
        elseif(is_string($migration))
            $output->writeln("<comment>$migration</comment>");
        elseif($migration)
            $output->writeln("<info>Se realizo rollback a la version correctamente.</info>");
        else
            $output->writeln("<comment>Ocurrio un error al intentar actualizar.</comment>");
    }
}