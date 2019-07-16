<?php
namespace Esojtec\CodeigniterConsole\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AppCommand extends Command
{
    protected $templates = __DIR__ . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR;
    protected $commands = APPPATH . 'commands' . DIRECTORY_SEPARATOR;

    protected function configure()
    {
        $this
            ->setName('make:command')
            ->setDescription('Make a command class')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'The name of command'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = ucfirst(strtolower($input->getArgument('name'))).'Command';

        $search = [
            '/{{ \$name }}/',
        ];

        $replace = [
            $name,
        ];

        if(! file_exists($this->commands))
            mkdir($this->commands, TRUE);

        if(file_exists($this->commands . $name. '.php'))
        {
            $output->writeln("$name already exists");
            return;
        }
        
        $file = file_get_contents($this->templates . 'commands.txt');
        $file = preg_replace($search, $replace, $file);
        $file = preg_replace("/[\n\r]{3,}/", "\n\n", $file);

        try
        {
            file_put_contents($this->commands . $name . '.php', $file);
        } catch(Exception $e)
        {
            $output->writeln($e->getMessage());
        }

        $output->writeln("<info>$name command was created.</info>");
    }
}