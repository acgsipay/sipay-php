<?php

namespace Sipay;

class Action
{
    protected $profile;
    public $request;

    public function __construct($profile)
    {
        $this->profile = new Profile($profile);
        $this->log = $this->profile->log();
        $this->request = $this->profile->request($this->endpoint, $this->port);
    }

    protected function params($defaults, $params)
    {
        return array_merge($this->profile->defaults($defaults), $params);
    }
}
