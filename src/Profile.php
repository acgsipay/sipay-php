<?php

namespace Sipay;

use ErrorException;

class Profile
{
    protected $params;
    protected $cert;
    protected $log;

    protected $profile;

    protected $domain;

    public function __construct($profile)
    {

        if(defined('SIPAY_SDK_PROFILE_PATH')) {
            $folder = SIPAY_SDK_PROFILE_PATH;
        }
        else {
            $folder = realpath(__DIR__) . DIRECTORY_SEPARATOR . 'profiles' . DIRECTORY_SEPARATOR;
        }

        if(!is_dir($folder)) {
            throw new ErrorException("Profile Directory Not Found: [$folder]", 1);
        }

        if(!is_file($folder . 'defaults.ini')) {
            throw new ErrorException("Config File Not Found", 1);
        }

        if(!is_file($folder . 'profiles.ini')) {
            throw new ErrorException("Profiles File Not Found", 1);
        }

        $this->defaults = parse_ini_file($folder . 'defaults.ini', true);
        $this->profiles = parse_ini_file($folder . 'profiles.ini', true);

        if(array_key_exists($profile, $this->profiles)) {
            $this->profile = $profile;

            $this->setup($this->profiles[$profile], $this->defaults);
        }
        else {
            throw new ErrorException('Profile "'.$profile.'" Not Found', 1);
        }
    }

    protected function setup($profile, $defaults)
    {
        if(!array_key_exists('log', $profile)) {
            $profile['log'] = array();
        }

        $this->params = array_merge($defaults['params'], $profile['params']);
        $this->cert = array_merge($defaults['cert'], $profile['cert']);

        $this->domain = $profile['environment'];

        $this->log = new Logger(array_merge($defaults['log'], $profile['log']));
    }

    protected function url($endpoint, $port = '443')
    {
        return "{$this->domain}:$port$endpoint";
    }

    public function defaults(array $keys)
    {
        $params = array();

        foreach ($keys as $key) {
            $params[$key] = $this->params[$key];
        }

        return $params;
    }

    public function request($endpoint, $port = '443')
    {
        $request = new Request($this->url($endpoint, $port), $this->log);

        $request->setVerify(sipay_sdk_profile_path($this->cert['path'], $this->cert['ca']));
        $request->setCert(sipay_sdk_profile_path($this->cert['path'], $this->cert['public']));
        $request->setKey(sipay_sdk_profile_path($this->cert['path'], $this->cert['private']));

        $request->offVeriryPeer()->offVerifyHost();

        return $request;
    }

    public function log()
    {
        return $this->log;
    }
}
