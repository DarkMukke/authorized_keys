<?php

/**
 * @author DarMukke <mukke@tbs-dev.co.uk>
 */


namespace App\Command;

use App\App;
use App\Model\Droplet;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use SSHClient\ClientConfiguration\ClientConfiguration;
use SSHClient\ClientBuilder\ClientBuilder;


/**
 * Class DropletKeySync
 * @package App\Command
 */
class DropletKeySync extends Command
{
    /**
     * @throws \InvalidArgumentException
     */
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('sync:keys')
            // the short description shown while running "php bin/console list"
            ->setDescription('Sync the keys to all droplets.')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Sync rsa keys to the juno user of all droplets')//required init string
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Syncing Keys',
            '===============',
            '',
        ]);
        $droplets = Droplet::getAll();

        for ($i = 0, $iMax = count($droplets); $i < $iMax; $i++) {


            $ip = $droplets[$i]->getIp();
            if (in_array($ip, App::$config['exclude'], true) || in_array($droplets[$i]->name, App::$config['exclude'],
                    true)
            ) {
                continue;
            }

            $output->writeln([
                $droplets[$i]->name . ' : ' . $ip,
                '',
            ]);


            $config = new ClientConfiguration($ip, App::$config['default_user']);
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
                'Synced ' . $droplets[$i]->name,
                '',
            ]);


        }
    }
}
