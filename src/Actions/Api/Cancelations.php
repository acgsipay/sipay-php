<?php

namespace Sipay\Actions\Api;

use Sipay\Action;

class Cancelations extends Action
{
    protected $endpoint = '/api/v1/cancelations';
    protected $port = 443;

    public function call($idcancelation, $transactionid, array $extra = array())
    {
        $defaults = array(
        );

        $input = array(
            'idcancelation' => $idcancelation,
            'transactionid' => $transactionid
        );

        $params = array_merge($defaults, $extra, $input);
        $keys = array('apikey', 'authtype', 'lang', 'merchantid', 'merchantname', 'currency');

        $params = $this->params($keys, $params);

        $this->log->info('sipay.actions.api.cancelations', 'api.request', 'I-00001', 'Send Request', $params);

        $this->request->json($params)->call();

        if($this->request->error == false) {
            $this->log->info('sipay.actions.api.cancelations', 'api.response', 'I-00001', 'Request OK', $this->request->json);
            return $this->request->json;
        }

        $this->log->error('sipay.actions.api.cancelations', 'api.response', 'E-00001', 'Request KO', $this->request->error);
        #TODO control errores
        return false;
    }
}
