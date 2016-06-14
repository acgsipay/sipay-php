<?php

namespace Sipay\Actions\Api;

use Sipay\Action;

class PreauthorizationsCancel extends Action
{
    protected $endpoint = '/api/v1/preauthorizations';
    protected $port = 443;

    public function call($idrequest, $amount, $ticket, $transactionid, array $extra = array())
    {
        $defaults = array(
            'reference'     => '',
            'customfield1'  => '',
            'customfield2'  => ''
        );

        $input = array(
            'idrequest'         => $idrequest,
            'amount'            => $amount,
            'ticket'            => $ticket,
            'idoriginalrequest' => $transactionid
        );

        $params = array_merge($defaults, $extra, $input);
        $keys = array('apikey', 'authtype', 'lang', 'merchantid', 'merchantname', 'currency');

        $params = $this->params($keys, $params);

        $this->log->info('sipay.actions.api.preauthorizations_confirm', 'api.request', 'I-00001', 'Send Request', $params);

        $this->request->json($params, 'DELETE')->call();

        if($this->request->error == false) {
            $this->log->info('sipay.actions.api.preauthorizations_confirm', 'api.response', 'I-00001', 'Request OK', $this->request->json);
            return $this->request->json;
        }

        $this->log->error('sipay.actions.api.preauthorizations_confirm', 'api.response', 'E-00001', 'Request KO', $this->request->error);
        #TODO control errores
        return false;
    }
}
