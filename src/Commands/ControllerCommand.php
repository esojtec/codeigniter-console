<?php
namespace Esojtec\CodeigniterConsole\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ControllerCommand extends Command
{
    protected $templates = __DIR__ . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR;
    protected $controllers = APPPATH . 'controllers' . DIRECTORY_SEPARATOR;

    protected function configure()
    {
        $this
            ->setName('make:controller')
            ->setDescription('Make a controller class')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'The name of controller'
            )
            ->addOption(
                'extends', // should be specified like "make:controller name --extends=ci or --extends=my"
                'e',
                InputOption::VALUE_OPTIONAL,
                'if set, you can pass --extends=my or --extends="CLASS_YOU_EXTENDS"',
            )
            ->addOption(
                'model', // should be specified like "make:controller name --model=name"
                'm',
                InputOption::VALUE_OPTIONAL,
                'if set, you can pass model to use',
            )
            ->addOption(
                'resources', // should be specified like "make:controller name --resources"
                'r',
                InputOption::VALUE_NONE,
                'if set, you can create a CRUD controller',
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = ucfirst($input->getArgument('name')).'Controller';
        $extends = 'CI_Controller';
        $model = '';

        if($input->getOption('extends') == 'my')
            $extends = 'MY_Controller';
        elseif(! empty($input->getOption('extends')))
            $extends = ucfirst($input->getOption('extends'));

        if($input->getOption('model'))
            $model = "use App\Models\\" . ucfirst($input->getOption('model')) . ';';

        if($input->getOption('resources'))
            $resources = '/{{ \$resources }}/';
        else
            $resources = '/{{ \$resources }}(.*){{ \$resources }}/s';

        $search = [
            '/{{ \$name }}/',
            '/{{ \$extends }}/',
            '/{{ \$model }}/',
            $resources,
        ];

        $replace = [
            $name,
            $extends,
            $model,
            '',
        ];

        if(file_exists($this->controllers . $name. '.php'))
        {
            $output->writeln("$name Already exists");
            return;
        }
        
        $file = file_get_contents($this->templates . 'controller.txt');
        $file = preg_replace($search, $replace, $file);
        $file = preg_replace("/[\n\r]{3,}/", "\n\n", $file);

        try
        {
            file_put_contents($this->controllers . $name . '.php', $file);
        } catch(Exception $e)
        {
            $output->writeln($e->getMessage());
        }

        $output->writeln("<info>$name controller was created.</info>");
    }
}