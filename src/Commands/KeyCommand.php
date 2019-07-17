<?php
namespace Esojtec\CodeigniterConsole\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class KeyCommand extends Command
{
    protected $templates = __DIR__ . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR;
    protected $config = APPPATH . 'config' . DIRECTORY_SEPARATOR;

    protected function configure()
    {
        $this
            ->setName('key:generate')
            ->setDescription('Put a key on config file')
            ->addArgument(
                'key',
                InputArgument::OPTIONAL,
                'You can put a key here, if not exists creates a random one'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $key = $input->getArgument('key');

        if($key == null)
        {
            $key = PASSWORD_DEFAULT($key);
            
        }

        $search = '$config[\'encryption_key\'] = \'\';';
        $replace = "\$config['encryption_key'] = '$key';";

        $file = file_get_contents(APPPATH . 'config/config.php');
        $file = str_replace($search, $replace, $file);

        try
        {
            $success = file_put_contents($this->config . 'config.php', $file);
        } catch(Exception $e)
        {
            $output->writeln($e->getMessage());
        }

        if($success)
            $output->writeln("<info>key $key generated.</info>");
        else
        {
            $output->writeln('<comment>Something went wrong check permissions</comment>');
            $output->writeln('<comment>or key is not set up.</comment>');
        }
    }
}