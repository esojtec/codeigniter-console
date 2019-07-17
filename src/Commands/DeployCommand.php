<?php
namespace Esojtec\CodeigniterConsole\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class DeployCommand extends Command
{
    protected $templates = __DIR__ . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR;
    protected $controller = APPPATH . 'controllers' . DIRECTORY_SEPARATOR;
    protected $config = APPPATH . 'config' . DIRECTORY_SEPARATOR;

    protected function configure()
    {
        $this
            ->setName('deploy:migration')
            ->setDescription('Make a deploy of console codeigniter')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $search = '$config[\'permitted_uri_chars\'] = \'a-z 0-9~%.:_\-\';';

        $replace = '$config["permitted_uri_chars"] = defined("STDIN")? "a-z 0-9~%.:_\-=" : "a-z 0-9~%.:_\-";';
        
        if(file_exists($this->controller . 'MigrationController.php'))
        {
            $output->writeln("<comment>MigrationController.php already exists</comment>");
            $helper = $this->getHelper('question');
            $question = new ConfirmationQuestion('Do you want to replace it?', false);

            if (! $helper->ask($input, $output, $question)) {
                return;
            }
        }
        
        $file = file_get_contents(APPPATH . 'config/config.php');
        $file = str_replace($search, $replace, $file);

        $controller = file_get_contents($this->templates . 'MigrationController.txt');

        try
        {
            file_put_contents($this->config . 'config.php', $file);
            file_put_contents($this->controller . 'MigrationController.php', $controller);
        } catch(Exception $e)
        {
            $output->writeln($e->getMessage());
        }

        $output->writeln("<info>Successful deploy.</info>");
    }
}