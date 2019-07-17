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
            $key = $this->random_key_string();
            $key = password_hash($key, PASSWORD_DEFAULT);
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

    protected function random_key_string() 
    {
        $source = bin2hex(openssl_random_pseudo_bytes(128));
        $string = '';
        $c = 0;
        while(strlen($string) < 32) { 
            $dec = gmp_strval(gmp_init(substr($source, $c*2, 2), 16),10);
            if($dec > 33 && $dec < 127 && $dec !== 39)
                $string.=chr($dec);
            $c++;
        }
        return $string;
    }
}