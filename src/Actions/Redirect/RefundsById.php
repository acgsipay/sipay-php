<?php

namespace Sipay\Actions\Redirect;

use Sipay\Action;

class RefundsById extends Action
{
    protected $endpoint = '/api/v1/refundsbyid';
    protected $port = 443;

    public function call($idrefund, $amount, $ticket, $transactionid, array $extra = array())
    {
        $defaults = array(
            'reference'     => ''
        );

        $input = array(
            'idrequest'         => $idrefund,
            'amount'            => $amount,
            'ticket'            => $ticket,
            'idoriginalrequest' => $transactionid
        );

        $params = array_merge($defaults, $extra, $input);
        $keys = array('apikey', 'authtype', 'lang', 'merchantid', 'merchantname', 'currency');

        $params = $this->params($keys, $params);

        $this->log->info('sipay.actions.api.refunds', 'api.request', 'I-00001', 'Send Request', $params);

        $this->request->json($params)->call();

        if($this->request->error == false) {
            $this->log->info('sipay.actions.api.refunds', 'api.response', 'I-00001', 'Request OK', $this->request->json);
            return $this->request->json;
        }

        $this->log->error('sipay.actions.api.refunds', 'api.response', 'E-00001', 'Request KO', $this->request->error);
        #TODO control errores
        return false;
    }
}
