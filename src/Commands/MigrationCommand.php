<?php
namespace Esojtec\CodeigniterConsole\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MigrationCommand extends Command
{
    protected $templates = __DIR__ . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR;
    protected $migrations = APPPATH . 'migrations' . DIRECTORY_SEPARATOR;

    protected function configure()
    {
        $this
            ->setName('make:migration')
            ->setDescription('Make a migration')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'The name of migration'
            )
            ->addOption(
                'timestamps', // should be specified like "make:migration name --timestamps"
                null,
                InputOption::VALUE_NONE,
                'if set, you can set timestamps',
            )
            ->addOption(
                'table', // should be specified like "make:controller name --model=name"
                't',
                InputOption::VALUE_OPTIONAL,
                'if set, you can put name of table',
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $table = strtolower($input->getArgument('name'));

        $search[] = '/{{ \$name }}/';
        $search[] = '/{{ \$timestamps }}(.*){{ \$timestamps }}/s';
        $search[] = '/{{ \$table }}/';

        if($input->getOption('timestamps'))
            $search[1] = '/{{ \$timestamps }}/';    

        if($input->getOption('table'))
        {
            $table = $input->getOption('table');
        }

        $replace = [ucfirst($name), '', $table];
        
        $file = file_get_contents($this->templates . 'migration.txt');
        $file = preg_replace($search, $replace, $file);

        $file = preg_replace("/[\n\r]{3,}/", "\n\n", $file);

        if(! file_exists($this->migrations))
            mkdir($this->migrations, TRUE);

        try
        {
            file_put_contents($this->migrations . date('YmdHis') . '_' . $name . '.php', $file);
        } catch(Exception $e)
        {
            $output->writeln($e->getMessage());
        }

        $output->writeln("<info>$name migration was created.</info>");
    }
}