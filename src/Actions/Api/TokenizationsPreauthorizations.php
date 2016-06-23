<?php

namespace Sipay\Actions\Api;

use Sipay\Action;

class TokenizationsPreauthorizations extends Action
{
    protected $endpoint = '/api/v1/tokenizations/preauthorizations';
    protected $port = 443;

    public function call($idrequest, $cardindex, $amount, $ticket, array $extra = array())
    {
        $defaults = array(
            'reference'     => ''
        );

        $input = array(
            'idrequest'     => $idrequest,
            'amount'        => $amount,
            'ticket'        => $ticket,
            'cardindex'     => $cardindex
        );

        $params = array_merge($defaults, $extra, $input);
        $keys = array('apikey', 'authtype', 'lang', 'merchantid', 'merchantname', 'currency');

        $params = $this->params($keys, $params);

        $this->log->info('sipay.actions.api.tokenizations_preauthorizations', 'api.request', 'I-00001', 'Send Request', $params);

        $this->request->json($params)->call();

        if($this->request->error == false) {
            $this->log->info('sipay.actions.api.tokenizations_preauthorizations', 'api.response', 'I-00001', 'Request OK', $this->request->json);
            return $this->request->json;
        }

        $this->log->error('sipay.actions.api.tokenizations_preauthorizations', 'api.response', 'E-00001', 'Request KO', $this->request->error);
        #TODO control errores
        return false;
    }
}
