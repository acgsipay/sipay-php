<?php

namespace Sipay\Actions\Api;

use Sipay\Action;

class PreauthorizationsCapture extends Action
{
    protected $endpoint = '/api/v1/preauthorizations/capture';
    protected $port = 443;

    public function call($idrequest, $amount, $ticket, $pan, $expiration, $cvv, array $extra = array())
    {
        $defaults = array(
            'reference'     => '',
            'customfield1'  => '',
            'customfield2'  => '',
            'cardholdername'    => ''
        );

        $input = array(
            'idrequest'         => $idrequest,
            'amount'            => $amount,
            'ticket'            => $ticket,
            'pan'               => $pan,
            'expiration'        => $expiration,
            'cvv'               => $cvv
        );

        $params = array_merge($defaults, $extra, $input);
        $keys = array('apikey', 'authtype', 'lang', 'merchantid', 'merchantname', 'currency');

        $params = $this->params($keys, $params);

        $this->log->info('sipay.actions.api.preauthorizations_capture', 'api.request', 'I-00001', 'Send Request', $params);

        $this->request->json($params)->call();

        if($this->request->error == false) {
            $this->log->info('sipay.actions.api.preauthorizations_capture', 'api.response', 'I-00001', 'Request OK', $this->request->json);
            return $this->request->json;
        }

        $this->log->error('sipay.actions.api.preauthorizations_capture', 'api.response', 'E-00001', 'Request KO', $this->request->error);
        #TODO control errores
        return false;
    }
}
