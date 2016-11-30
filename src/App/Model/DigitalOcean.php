<?php
/**
 * @author DarMukke <mukke@tbs-dev.co.uk>
 */

namespace App\Model;

use Curl\Curl;


class DigitalOcean
{
    /**
     * @var string
     */
    protected $apikey;
    /**
     *
     */
    CONST DOMMAIN = 'https://api.digitalocean.com/v2';


    /**
     * @var
     */
    protected static $api;


    /**
     * DigitalOcean constructor.
     * @param string $apikey
     */
    public function __construct($apikey)
    {
        $this->apikey = $apikey;


    }

    /**
     * @param $config
     * @return self
     */
    public static function api($config)
    {
        if (static::$api === null) {
            static::$api = new static($config['apikey']);
        }
        return static::$api;
    }


    /**
     * @param $action
     * @param $uri
     * @param $data
     * @return null
     * @throws \ErrorException
     */
    protected function action($action, $uri, $data)
    {
        $curl = new Curl();
        $curl->setHeader('Authorization', 'Bearer ' . $this->apikey);
        $curl->setHeader('Content-Type', 'application/json');
        $curl->{$action}(static::DOMMAIN . $uri, $data);


        if ($curl->error) {
            throw new \ErrorException($curl->error_code . ' : ' . $curl->error_message);
        }
        $response = $curl->response;
        $curl->close();
        return $response;
    }

    /**
     * @param $uri
     * @param array $data
     * @return null
     * @throws \ErrorException
     */
    public function get($uri, array $data = [])
    {
        return $this->action('get', $uri, $data);
    }

    /**
     * @param $uri
     * @param array $data
     * @return null
     * @throws \ErrorException
     */
    public function post($uri, array $data = [])
    {
        return $this->action('post', $uri, $data);
    }

    /**
     * @param $uri
     * @param array $data
     * @return null
     * @throws \ErrorException
     */
    public function put($uri, array $data = [])
    {
        return $this->action('put', $uri, $data);
    }

    /**
     * @param $uri
     * @param array $data
     * @return null
     * @throws \ErrorException
     */
    public function delete($uri, array $data = [])
    {
        return $this->action('delete', $uri, $data = []);
    }


}
