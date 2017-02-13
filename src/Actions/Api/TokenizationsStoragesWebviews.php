<?php

namespace Sipay\Actions\Api;

use Sipay\Action;

class TokenizationsStoragesWebviews extends Action
{
    protected $endpoint = '/api/v1/tokenizations/storageswebviews';
    protected $port = 443;

    public function call($idstorage, $cardindex, $amount, $ticket, $dstpage, $checkmode, array $extra = array())
    {
        $defaults = array(
            'notpage'   => '',
            'notmode'   => 'async'
        );

        $input = array(
            'idstorage' => $idstorage,
            'amount'    => $amount,
            'ticket'    => $ticket,
            'cardindex' => $cardindex,
            'dstpage'   => $dstpage,
            'checkmode' => $checkmode
        );

        $params = array_merge($defaults, $extra, $input);
        $keys = array('apikey', 'authtype', 'lang', 'merchantid', 'merchantname', 'currency');

        $params = $this->params($keys, $params);

        $this->log->info('sipay.actions.api.tokenizations_storage_webviews', 'api.request', 'I-00001', 'Send Request', $params);

        $this->request->json($params)->call();

        if($this->request->error == false) {
            $this->log->info('sipay.actions.api.tokenizations_storage_webviews', 'api.response', 'I-00001', 'Request OK', $this->request->json);
            return $this->request->json;
        }

        $this->log->error('sipay.actions.api.tokenizations_storage_webviews', 'api.response', 'E-00001', 'Request KO', $this->request->error);
        #TODO control errores
        return false;
    }
}
