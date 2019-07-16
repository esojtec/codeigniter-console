<?php
namespace Esojtec\CodeigniterConsole\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ViewCommand extends Command
{
    protected $templates = __DIR__ . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR;
    protected $views = APPPATH . 'views' . DIRECTORY_SEPARATOR;

    protected function configure()
    {
        $this
            ->setName('make:view')
            ->setDescription('Make a view inside views directory')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'The name view'
            )
            ->addOption(
                'blade', // should be specified like "make:view name --blade"
                'b',
                InputOption::VALUE_NONE,
                'if set, you can make a blade view',
            )
            ->addOption(
                'layout', // should be specified like "make:view name --layout=layouts.master"
                'l',
                InputOption::VALUE_OPTIONAL,
                'if set, you can choose the blade layout',
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = strtolower($input->getArgument('name'));
        $layouts = 'layouts.master';
        $directories = explode('.', stripslashes(trim($name)));
        $blade = '/{{ \$blade }}(.*){{ \$blade }}|{{ \$html }}/s';

        if(count($directories) > 1)
        {
            // We extract the last element
            $name = array_pop($directories);
            $directories = implode(DIRECTORY_SEPARATOR, $directories);
            $directories .= DIRECTORY_SEPARATOR;

            //We create mkdir directories
            try
            {
                if(! file_exists($this->views . $directories))
                {
                    if(mkdir($this->views . $directories, 0644, TRUE))
                    {
                        $output->writeln("<info>$directories were created correctly.</info>");
                    }
                }
            }
            catch(Exeption $e)
            {
                $output->writeln("<comment>{$e->getMessage()}</comment>");
            }
        }
        else
        {
            $directories = '';
        }

        // add extension blade to view
        if($input->getOption('blade'))
        {
            $name = $name . '.blade';
            $blade = '/{{ \$blade }}|{{ \$html }}(.*){{ \$html }}/s';
        }

        if($input->getOption('layout'))
        {
            $layouts = $input->getOption('layout');
        }

        if(file_exists($this->views . $directories . $name . '.php'))
        {
            $output->writeln("<comment>$name view already exists</comment>");
            return;
        }
        
        $search = [
            '/{{ \$layouts }}/',
            $blade,

        ];

        $replace = [
            $layouts,
            ''
        ];

        $file = file_get_contents($this->templates . 'view.txt');
        //$file = strtr($file, $replace);
        $file = preg_replace($search, $replace, $file);
        $file = preg_replace("/[\n\r]{3,}/", "\n\n", $file);

        try
        {
            file_put_contents($this->views . $directories . $name . '.php', $file);
        } catch(Exception $e)
        {
            $output->writeln("<comment>{$e->getMessage()}</comment>");
        }

        $output->writeln("<info>file $name view was created.</info>");
    }
}