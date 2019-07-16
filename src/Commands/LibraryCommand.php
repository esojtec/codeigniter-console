<?php
namespace Esojtec\CodeigniterConsole\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class LibraryCommand extends Command
{
    protected $templates = __DIR__ . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR;
    protected $libraries = APPPATH . 'libraries' . DIRECTORY_SEPARATOR;
    protected $config = APPPATH . 'config' . DIRECTORY_SEPARATOR;

    protected function configure()
    {
        $this
            ->setName('make:library')
            ->setDescription('Make a library class')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'The name of library'
            )
            ->addOption(
                'config', // should be specified like "make:library name --config"
                'c',
                InputOption::VALUE_NONE,
                'if set, you can create a config file inside config directory',
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = strtolower($input->getArgument('name'));

        if($input->getOption('config'))
        {
            $config = "<?php defined('BASEPATH') or exit('No direct script access allowed'); \n \$config = [];";
            
            if(! file_exists($this->config . $name . '.php'))
                file_put_contents($this->config . $name . '.php', $config);
            else
                $output->writeln("$name config file already exists. ommited...");
        }
        
        $replace = [
            '{{ $name }}' => ucfirst($name),
        ];

        if(file_exists($this->libraries . $name. '.php'))
        {
            $output->writeln("$name library already exists");
            return;
        }
        
        $file = file_get_contents($this->templates . 'library.txt');
        $file = strtr($file, $replace);
        $file = preg_replace("/[\n\r]{3,}/", "\n\n", $file);

        try
        {
            file_put_contents($this->libraries . $name . '.php', $file);
        } catch(Exception $e)
        {
            $output->writeln($e->getMessage());
        }

        $output->writeln("<info>$name library was created.</info>");
    }
}