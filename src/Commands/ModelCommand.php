<?php
namespace Esojtec\CodeigniterConsole\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ModelCommand extends Command
{
    protected $templates = __DIR__ . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR;
    protected $models = APPPATH . 'models' . DIRECTORY_SEPARATOR;

    protected function configure()
    {
        $this
            ->setName('make:model')
            ->setDescription('Make a model class')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'The name of model'
            )
            ->addOption(
                'eloquent', // should be specified like "make:model name --eloquent"
                'e',
                InputOption::VALUE_NONE,
                'if set, you can select eloquent model',
            )
            ->addOption(
                'extends', // should be specified like "make:model name --extends=name"
                null,
                InputOption::VALUE_REQUIRED,
                'if set, you can choose a model to extend',
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = ucfirst($input->getArgument('name'));
        $extends = 'CI_Model';

        if($input->getOption('extends'))
        {
            $extends = $input->getOption('extends');
        }
        
        $replace = [
            '{{ $name }}' => $name,
            '{{ $extends }}' => $extends,
        ];

        if( ! $input->getOption('eloquent'))
        {
            $replace['use Illuminate\Database\Eloquent\Model;'] = '';
            $replace['namespace App\Models;'] = '';
        }
        else
        {
            $replace['{{ $extends }}'] = 'Model';
        }

        if(file_exists($this->models . $name. '.php'))
        {
            $output->writeln("$name Already exists");
            return;
        }
        
        $file = file_get_contents($this->templates . 'model.txt');
        $file = strtr($file, $replace);
        $file = preg_replace("/[\n\r]{3,}/", "\n\n", $file);

        try
        {
            file_put_contents($this->models . $name . '.php', $file);
        } catch(Exception $e)
        {
            $output->writeln($e->getMessage());
        }

        $output->writeln("<info>$name model was created.</info>");
    }
}