<?php

namespace Sipay\Actions\Api;

use Sipay\Action;

class TokenizationsStoragesAdd extends Action
{
    protected $endpoint = '/api/v1/tokenizations/storages';
    protected $port = 443;

    public function call($idstorage, $cardindex, $pan, $expiration, array $extra = array())
    {
        $defaults = array(
            'api.notmode'               => '',
            'api.notpage'               => '',
            'tokenizations.checkmode'   => '',
            'tokenizations.ticket'      => '',
            'cardholdername'            => ''
        );

        $input = array(
            'idstorage'         => $idstorage,
            'cardindex'         => $cardindex,
            'pan'               => $pan,
            'expiration'        => $expiration
        );

        $params = array_merge($defaults, $extra, $input);
        $keys = array('apikey', 'authtype', 'lang', 'merchantid', 'merchantname', 'currency');

        $params = $this->params($keys, $params);

        $this->log->info('sipay.actions.api.tokenizations_storages_add', 'api.request', 'I-00001', 'Send Request', $params);

        $this->request->json($params)->call();

        if($this->request->error == false) {
            $this->log->info('sipay.actions.api.tokenizations_storages_add', 'api.response', 'I-00001', 'Request OK', $this->request->json);
            return $this->request->json;
        }

        $this->log->error('sipay.actions.api.tokenizations_storages_add', 'api.response', 'E-00001', 'Request KO', $this->request->error);
        #TODO control errores
        return false;
    }
}
