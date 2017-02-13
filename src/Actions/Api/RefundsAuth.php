<?php

namespace Sipay\Actions\Api;

use Sipay\Action;

class RefundsAuth extends Action
{
    protected $endpoint = '/api/v1/auth';
    protected $port = 10010;

    public function call($amount, $ticket, array $extra = array())
    {
        $default = array(
            'api.notpage'   => '',
            'api.notmode'   => ''
        );

        $input = array(
            'amount'    => $amount,
            'ticket'    => $ticket,
            'resource'  => 'refunds'
        );

        $params = array_merge($default, $extra, $input);
        $keys = array('apikey', 'authtype', 'lang', 'merchantid', 'merchantname', 'currency');

        $params = $this->params($keys, $params);

        $this->log->info('sipay.actions.api.refunds_auth', 'api.request', 'I-00001', 'Send Request', $params);

        $this->request->json($params)->call();

        if($this->request->error == false) {
            $this->log->info('sipay.actions.api.refunds_auth', 'api.response', 'I-00001', 'Request OK', $this->request->json);
            return $this->request->get('idrefund');
        }

        $this->log->error('sipay.actions.api.refunds_auth', 'api.response', 'E-00001', 'Request KO', $this->request->error);
        #TODO control errores
        return false;
    }
}
