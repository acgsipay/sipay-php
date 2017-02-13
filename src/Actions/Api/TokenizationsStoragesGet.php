<?php

namespace Sipay\Actions\Api;

use Sipay\Action;

class TokenizationsStoragesGet extends Action
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

        $headers = array(
            'X-Sipay-API-v1-idstorage'      => $params['idstorage'],
            'X-Sipay-API-v1-merchantid'     => $params['merchantid'],
            'X-Sipay-API-v1-merchantname'   => $params['merchantname'],
            'X-Sipay-API-v1-authtype'       => $params['authtype'],
            'X-Sipay-API-v1-lang'           => $params['lang']
        );

        $this->request->setUrl($this->profile->url($this->endpoint.'/'.$cardindex));

        $this->request->addHeaders($headers);

        $this->log->info('sipay.actions.api.tokenizations_storages_get', 'api.request', 'I-00001', 'Send Request', $params);

        $this->request->call();

        if($this->request->error == false) {
            $this->log->info('sipay.actions.api.tokenizations_storages_get', 'api.response', 'I-00001', 'Request OK', $this->request->json);
            return $this->request->json;
        }

        $this->log->error('sipay.actions.api.tokenizations_storages_get', 'api.response', 'E-00001', 'Request KO', $this->request->error);
        #TODO control errores
        return false;
    }
}
