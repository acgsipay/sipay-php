<?php

namespace Sipay\Actions;

use Sipay\Action;

class TokenizationsPreauthorizationsAuth extends Action
{
    protected $endpoint = '/api/v1/auth';
    protected $port = 10010;

    public function call($amount, $ticket, array $extra = array())
    {
        $defaults = array(
            'api.notpage'   => '',
            'api.notmode'   => 'async',
            'api.dstpage'   => ''
        );

        $input = array(
            'amount'        => $amount,
            'ticket'        => $ticket,
            'resource'      => 'tokenizations/preauthorizations'
        );

        $params = array_merge($defaults, $extra, $input);
        $keys = array('apikey', 'authtype', 'lang', 'merchantid', 'merchantname', 'currency');

        $params = $this->params($keys, $params);

        $this->log->info('sipay.actions.api.tokenizations_preauthorizations_auth', 'api.request', 'I-00001', 'Send Request', $params);

        $this->request->json($params)->call();

        if($this->request->error == false) {
            $this->log->info('sipay.actions.api.tokenizations_preauthorizations_auth', 'api.response', 'I-00001', 'Request OK', $this->request->json);
            return $this->request->get('idrequest');
        }

        $this->log->error('sipay.actions.api.tokenizations_preauthorizations_auth', 'api.response', 'E-00001', 'Request KO', $this->request->error);
        #TODO control errores
        return false;
    }
}
