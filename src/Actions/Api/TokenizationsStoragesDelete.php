<?php

namespace Sipay\Actions\Api;

use Sipay\Action;

class TokenizationsStoragesDelete extends Action
{
    protected $endpoint = '/api/v1/tokenizations/storages';
    protected $port = 443;

    public function call($idstorage, $cardindex, array $extra = array())
    {
        $defaults = array();

        $input = array(
            'idstorage'         => $idstorage,
            'cardindex'         => $cardindex
        );

        $params = array_merge($defaults, $extra, $input);
        $keys = array('apikey', 'authtype', 'lang', 'merchantid', 'merchantname', 'currency');

        $params = $this->params($keys, $params);

        $this->log->info('sipay.actions.api.tokenizations_storages_delete', 'api.request', 'I-00001', 'Send Request', $params);

        $this->request->json($params, 'DELETE')->call();

        if($this->request->error == false) {
            $this->log->info('sipay.actions.api.tokenizations_storages_delete', 'api.response', 'I-00001', 'Request OK', $this->request->json);
            return $this->request->json;
        }

        $this->log->error('sipay.actions.api.tokenizations_storages_delete', 'api.response', 'E-00001', 'Request KO', $this->request->error);
        #TODO control errores
        return false;
    }
}
