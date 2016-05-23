<?php

namespace Sipay;

use ErrorException;

class Request implements \Serializable
{
    protected $handler;
    protected $log;

    protected $defaults = array(
        array('flag' => \CURLOPT_TIMEOUT, 'value' => 30),
        array('flag' => \CURLOPT_MAXREDIRS, 'value' => 3),
        array('flag' => \CURLOPT_FOLLOWLOCATION, 'value' => 1),
        array('flag' => \CURLOPT_RETURNTRANSFER, 'value' => 1),
        array('flag' => \CURLOPT_HEADER, 'value' => 0),
        array('flag' => \CURLINFO_HEADER_OUT, 'value' => 0),
        array('flag' => \CURLOPT_USERAGENT, 'value' => 'Sipay PHP SDK RC1.0')
    );

    protected $headers = array();
    protected $options = array();

    public $body = array();
    public $info = array();
    public $json = array();

    public $error = array();

    public function __construct($url, Logger $log)
    {
        $this->log = $log;
        $this->setOption(\CURLOPT_URL, $url);
    }

    public function serialize()
    {
        $minify = function($option) {
            return array($option['flag'], $option['value']);
        };

        $data = array(
            'd' => array_map($minify, $this->defaults),
            'h' => $this->headers,
            'o' => array_map($minify, $this->options),
            'b' => $this->body,
            'i' => $this->info,
            'e' => $this->error
        );

        return serialize($data);
    }

    public function unserialize($string)
    {
        $data = unserialize($string);

        $maxify = function($option) {
            return array('flag' => $option[0], 'value' => $option[1]);
        };

        $this->defaults = array_map($maxify, $data['d']);
        $this->headers  = $data['h'];
        $this->options  = array_map($maxify, $data['o']);
        $this->body     = $data['b'];
        $this->info     = $data['i'];
        $this->error    = $data['e'];

        if($this->is_json($this->info)) {
            $this->json = json_decode($this->body, true);
        }
    }

    public function is_json($info)
    {
        return array_key_exists('content_type', $info) && strpos($info['content_type'], 'application/json') >= 0;
    }

    public function call()
    {
        $this->handler = curl_init();

        $options = array(
            array('flag' => \CURLOPT_HTTPHEADER, 'value' => $this->headers)
        );

        foreach (array_merge($this->defaults, $options, $this->options) as $option) {
            curl_setopt($this->handler, $option['flag'], $option['value']);
        }

        $this->body = curl_exec($this->handler);
        $this->info = curl_getinfo($this->handler);
        $this->error = false;

        # TODO control de errores
        if(curl_errno($this->handler)) {
            $this->error = array('code' => curl_errno($this->handler), 'description' => curl_error($this->handler));
            $this->log->error('sipay.request', 'curl.response', 'E0001', 'Curl Error', $this->error);
        }

        else if(empty($this->body)) {
            $this->error = array('code' => 'CERR0001', 'description' => 'Body empty');
            $this->log->error('sipay.request', 'curl.response', 'E0002', 'Body empty', $this->info);
        }

        else if($this->info['http_code'] != '200') {
            $this->error = array('code' => 'CERR0002', 'description' => "Response not successful. [C:{$this->info['http_code']}]");
            $this->log->error('sipay.request', 'curl.response', 'E0003', 'Response not successful', $this->info);
        }

        else if($this->is_json($this->info)) {
            $this->json = @json_decode($this->body, true);
            if(is_null($this->json) || json_last_error() !== JSON_ERROR_NONE) {
                $this->error = array('code' => json_last_error(), 'description' => json_last_error_msg());
                $this->log->error('sipay.request', 'json.decode', 'E0003', 'Curl Error', $this->error);
            }
        }

        curl_close($this->handler);

        return $this;
    }

    public function get($key, $default = null)
    {
        return array_key_exists($key, $this->json) ? $this->json[$key] : $default;
    }

    public function post(array $fields = array())
    {
        $this->setOption(\CURLOPT_POST, 1);
        $this->setOption(\CURLOPT_POSTFIELDS, $fields);

        return $this;
    }

    public function json(array $fields = array())
    {
        $this->setHeader('Content-Type: application/json');

        $this->setOption(\CURLOPT_POST, 1);
        $this->setOption(\CURLOPT_POSTFIELDS, json_encode($fields));

        return $this;
    }

    public function setOption($option, $value)
    {
        $this->options[] = array('flag' => $option, 'value' => $value);

        return $this;
    }

    public function getOption($option)
    {
        return $this->hasOption($option) ? $this->options[$option] : null;
    }

    public function hasOption($option)
    {
        return array_key_exists($option, $this->options);
    }

    public function setOptions(array $options)
    {
        foreach ($options as $option => $value) {
            $this->setOption($option, $value);
        }

        return $this;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function setHeader($header)
    {
        $this->headers[] = $header;

        return $this;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function setVerify($file)
    {
        #TODO: corregir error
        if(!is_file($file)) {
            throw new ErrorException("File not exist: [$file]", 1);
        }

        $this->setOption(\CURLOPT_CAINFO, $file);

        return $this;
    }

    public function setCert($file, $type = 'PEM')
    {
        #TODO: corregir error
        if(!is_file($file)) {
            throw new ErrorException("File not exist: [$file]", 1);
        }

        $this->setOption(\CURLOPT_SSLCERTTYPE, $type);
        $this->setOption(\CURLOPT_SSLCERT, $file);

        return $this;
    }

    public function setKey($file, $type = 'PEM')
    {
        #TODO: corregir error
        if(!is_file($file)) {
            throw new ErrorException("File not exist: [$file]", 1);
        }

        $this->setOption(\CURLOPT_SSLKEYTYPE, $type);
        $this->setOption(\CURLOPT_SSLKEY, $file);

        return $this;
    }

    public function onVerifyPeer()
    {
        $this->setOption(\CURLOPT_SSL_VERIFYPEER, 1);

        return $this;
    }

    public function offVeriryPeer()
    {
        $this->setOption(\CURLOPT_SSL_VERIFYPEER, 0);

        return $this;
    }

    public function onVerifyHost()
    {
        $this->setOption(\CURLOPT_SSL_VERIFYHOST, 2);

        return $this;
    }

    public function offVerifyHost()
    {
        $this->setOption(\CURLOPT_SSL_VERIFYHOST, 0);

        return $this;
    }
}
