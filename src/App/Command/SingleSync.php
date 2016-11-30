<?php

/**
 * @author DarMukke <mukke@tbs-dev.co.uk>
 */


namespace App\Command;

use App\App;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use SSHClient\ClientConfiguration\ClientConfiguration;
use SSHClient\ClientBuilder\ClientBuilder;


/**
 * Class SingleSync
 * @package App\Command
 */
class SingleSync extends Command
{
    /**
     * @throws \InvalidArgumentException
     */
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('sync:single')
            // the short description shown while running "php bin/console list"
            ->setDescription('Sync the keys to a specific droplet.')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('./bin/console sync:single --ip=127.0.0.1 [--user=' . App::$config['default_user'] . '] ')
            //required ip
            ->addOption('ip', 'i', InputOption::VALUE_REQUIRED, 'IP to sync to, eg "127.0.0.1".', null)
            //optional user
            ->addOption('user', 'u', InputOption::VALUE_OPTIONAL,
                'User to sync to on the request ip. [default: ' . App::$config['default_user'] . ']',
                App::$config['default_user']);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $ip = $input->getOption('ip');
        $user = $input->getOption('user');

        if ($ip === null) {
            $output->writeln(['Ip is required', $this->getHelp(), '']);
            exit(1);
        }


        $output->writeln([
            'Syncing Keys',
            '===============',
            '',
        ]);

        $output->writeln([
            $ip,
            '',
        ]);


        $config = new ClientConfiguration($ip, $user);
        $config->setOptions([
            'IdentityFile' => '~/.ssh/id_rsa',
            'IdentitiesOnly' => 'yes',
        ]);
        $builder = new ClientBuilder($config);
        $config->setSCPOptions(array('r'));
        $scp_client = $builder->buildSecureCopyClient();
        $scp_client->copy(
            App::$root . 'authorized_keys',
            $scp_client->getRemotePath('~/.ssh/authorized_keys')
        );
        $output->writeln([
            'Synced ' . $user . '@' . $ip,
            '',
        ]);
    }
}
