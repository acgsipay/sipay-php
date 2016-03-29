<?php

namespace Sipay\Actions;

use Sipay\Action;

class PaymentsAuth extends Action
{
    protected $endpoint = '/api/v1/auth';
    protected $port = 10010;

    public function call($amount, $ticket, array $extra = array())
    {
        $params = array_merge($extra, array('amount' => $amount, 'ticket' => $ticket, 'resource' => 'payments'));
        $keys = array('apikey', 'authtype', 'lang', 'merchantid', 'currency');

        $params = $this->params($keys, $params);

        $this->log->info('sipay.actions.payments_auth', 'api.request', 'I-00001', 'Send Request', $params);

        $this->request->json($params)->call();

        if($this->request->error == false) {
            $this->log->info('sipay.actions.payments_auth', 'api.response', 'I-00001', 'Request OK', $this->request->json);
            return $this->request->get('idrequest');
        }

        $this->log->error('sipay.actions.payments_auth', 'api.response', 'E-00001', 'Request KO', $this->request->error);
        #TODO control errores
        return false;
    }
}
