<?php

namespace Sipay\Actions\Fastpay;

use Sipay\Action;

class SessionAuth extends Action
{
    protected $endpoint = '/api/v1/auth';
    protected $port = 10010;

    public function call($amount, $ticket, array $extra = array())
    {
        $defaults = array(
            'reference' => ''
        );

        $input = array(
            'amount' => $amount,
            'ticket' => $ticket,
            'resource' => 'fastpay/v1/auth'
        );

        $params = array_merge($defaults, $extra, $input);
        $keys = array('apikey', 'authtype', 'lang', 'merchantid', 'currency');

        $params = $this->params($keys, $params);
        $this->log->info('sipay.actions.fastpay.session', 'api.request', 'I-00001', 'Send Request', $params);

        $this->request->json($params)->call();

        if($this->request->error == false) {
            $this->log->info('sipay.actions.fastpay.session', 'api.response', 'I-00001', 'Request OK', $this->request->json);
            return $this->request->get('session');
        }

        $this->log->error('sipay.actions.fastpay.session', 'api.response', 'I-00001', 'Request KO', $this->request->error);
        #TODO control errores
        return false;
    }
}
