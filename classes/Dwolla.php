<?php defined('SYSPATH') or die('No direct script access.');

class Dwolla
{
    private $api_key = NULL;
    private $api_secret = NULL;
    private $api_token = NULL;
    private $api_pin = NULL;

    public function __construct()
    {
        $config = Kohana::$config->load('dwolla');

        $this->api_key = $config['api_key'] ? $config['api_key'] : FALSE;
        $this->api_secret = $config['api_secret'] ? $config['api_secret'] : FALSE;
        $this->api_token = $config['api_token'] ? $config['api_token'] : FALSE;
        $this->api_pin = $config['api_pin'] ? $config['api_pin'] : FALSE;
    }

    public static function factory()
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
        if ( ! $this->api_secret )
            throw new Kohana_Exception('no api_secret set (check dwolla config)');

        $amount = number_format($amount, 2);
        $signature = hash_hmac("sha1", "{$checkout_id}&{$amount}", $this->api_secret);

        return $signature == $proposed_sig;
    }
}