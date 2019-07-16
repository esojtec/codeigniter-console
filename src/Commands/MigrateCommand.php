<?php
namespace Esojtec\CodeigniterConsole\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MigrateCommand extends Command
{
    protected $templates = __DIR__ . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR;
    protected $migrations = APPPATH . 'migrations' . DIRECTORY_SEPARATOR;
    protected $core = __DIR__ . DIRECTORY_SEPARATOR . '../Core'. DIRECTORY_SEPARATOR;

    protected function configure()
    {
        $this
            ->setName('migrate')
            ->setDescription('Make migration to latest file')
            ->addArgument(
                'version',
                InputArgument::OPTIONAL,
                'number version'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if($input->getArgument('version'))
            $routing = ['controller' => 'MigrationController', 'function' => 'version'];    
        else
            $routing = ['controller' => 'MigrationController', 'function' => 'migrate'];

        require_once $this->core . 'codeigniter.php';

        // we get estatus for the migration
        $migration = $CI->get_estatus();

        
        if(is_numeric($migration))
            $output->writeln("<info>Se realizo la migracion no. $migration correctamente.</info>");
        elseif(is_string($migration))
            $output->writeln("<comment>$migration</comment>");
        elseif($migration)
            $output->writeln("<info>Se realizo la migracion correctamente.</info>");
        else
            $output->writeln("<comment>Ocurrio un error al intentar actualizar.</comment>");
    }
}