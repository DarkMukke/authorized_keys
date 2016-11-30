<?php
/**
 * @author DarMukke <mukke@tbs-dev.co.uk>
 */

namespace App\Model;


use App\App;

/**
 * Class Droplet
 * @package App\Model
 */
final class Droplet
{

    /**
     * @var array|mixed
     */
    private $_data = [];

    /**
     * Droplet constructor.
     * @param mixed $_data
     */
    public function __construct($_data)
    {

        $_data = json_decode(json_encode($_data), true);
        $this->_data = $_data;
    }

    /**
     * @param $data
     * @return static
     */
    static public function load($data)
    {
        return new static($data);
    }

    /**
     * @param $name
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->_data)) {
            return $this->_data[$name];
        }

        throw new \InvalidArgumentException(__CLASS__ . ' does not have a member called ' . $name);
    }

    /**
     * @param $name
     * @param $value
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function __set($name, $value)
    {
        if (array_key_exists($name, $this->_data)) {
            $this->_data[$name] = $value;
            return true;
        }

        throw new \InvalidArgumentException(__CLASS__ . ' does not have a member called ' . $name);
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return array_key_exists($name, $this->_data);
    }

    /**
     * @return mixed
     */
    public function getIp()
    {
        return $this->_data['networks']['v4'][0]['ip_address'];
    }

    /**
     * @return Droplet[]
     * @throws \ErrorException
     */
    public static function getAll()
    {
        $json = DigitalOcean::api(App::$config)->get('/droplets?page=1&per_page=500');
        $droplets = json_decode($json, true);
        $droplets = $droplets['droplets'];
        $all = [];
        foreach ($droplets as $droplet) {
            $all[] = Droplet::load($droplet);
        }
        return $all;
    }

}
