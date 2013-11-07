<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Dwolla - Kohana module for the Dwolla API
 *
 * @package Dwolla
 * @author  Clay McIlrath
 */
class Dwolla
{
    protected $_config;

    private $api_key = NULL;
    private $api_secret = NULL;
    private $api_token = NULL;
    private $api_pin = NULL;

    /**
     * Dwolla Class constructor
     * 
     * @param   array    Dwolla config
     * @return  Dwolla
     */
    public function __construct(array $config = [])
    {
        $this->_config = $config + (array) Kohana::$config->load('dwolla');

        require_once Kohana::find_file('vendor/lib', 'dwolla');

        $this->api_key = $this->_config['api_key'] ? $this->_config['api_key'] : FALSE;
        $this->api_secret = $this->_config['api_secret'] ? $this->_config['api_secret'] : FALSE;
        $this->api_token = $this->_config['api_token'] ? $this->_config['api_token'] : FALSE;
        $this->api_pin = $this->_config['api_pin'] ? $this->_config['api_pin'] : FALSE;
    }

    /**
     * Create and return new CSV class
     *
     * @param   array    Dwolla config
     * @return  Dwolla
     */
    public static function factory(array $config = [])
    {
        return new Dwolla();
    }

    /**
     * Verify Gateway Signature
     *
     * A quick little function to get up and running with the dwolla checkout button,
     * this function will verfiy that the signature matches up with the hash here.
     * It's easily spoofable by a developer, but not the average Joe.
     */
    public function verify_signature($proposed_sig, $checkout_id, $amount)
    {
        if ( ! $this->api_secret)
            throw new Kohana_Exception('no api_secret set (check dwolla config)');

        $amount = number_format($amount, 2);
        $signature = hash_hmac("sha1", "{$checkout_id}&{$amount}", $this->api_secret);

        return $signature == $proposed_sig;
    }
}