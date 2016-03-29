<?php

namespace Sipay\Actions;

use Sipay\Action;

class RedirectAuth extends Action
{
    protected $endpoint = '/api/v1/auth';
    protected $port = 10010;

    public function call($amount, $ticket, $template, array $extra = array())
    {
        $defaults = array(
            'reference'                     => '',
            'redirects.dstpage'             => '',
            'redirects.extradata'           => '',
            'redirects.message'             => '',
            'redirects.notmode'             => 'async',
            'redirects.notpage'             => '',
            'redirects.results_interactive' => 'false'
        );

        $input = array(
            'amount' => $amount, 
            'ticket' => $ticket, 
            'redirects.templateid' => $template, 
            'resource' => 'iframe/v1/redirects/payments/cards'
        );

        $params = array_merge($defaults, $extra, $input);
        $keys = array('apikey', 'authtype', 'lang', 'merchantid', 'currency');

        $params = $this->params($keys, $params);

        $this->log->info('sipay.actions.redirect_auth', 'api.request', 'I-00001', 'Send Request', $params);

        $this->request->json($params)->call();
        echo '<pre>'.print_r($this->request, true).'</pre>';
        if($this->request->error == false) {
            $this->log->info('sipay.actions.redirect_auth', 'api.response', 'I-00001', 'Request OK', $this->request->json);
            return $this->request->get('redirects.url');
        }

        $this->log->error('sipay.actions.redirect_auth', 'api.response', 'I-00001', 'Request KO', $this->request->error);
        #TODO control errores
        return false;
    }
}
