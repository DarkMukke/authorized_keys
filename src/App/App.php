<?php
/**
 * @author DarMukke <mukke@tbs-dev.co.uk>
 */


namespace App;

use App\Command\DropletKeySync;
use App\Command\SingleSync;
use Symfony\Component\Console\Application;

/**
 * Class App
 * @package App
 */
class App
{

    /** @var array  */
    public static $config = [];

    /**
     * @var string
     */
    public static $root = '';


    /**
     * App constructor.
     */
    public function __construct()
    {
        static::$root = __DIR__ . '/../../';
        static::$config = include static::$root . 'config.php';

    }

    /**
     * @return int
     * @throws \LogicException
     * @throws \Exception
     */
    public function run()
    {

        $app = new Application();
        $app->add(new DropletKeySync);
        $app->add(new SingleSync);
        return $app->run();
    }


}
