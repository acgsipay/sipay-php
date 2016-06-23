<?php

namespace Sipay\Actions\Redirect;

use Sipay\Action;

class PaymentsMethodsAuth extends Action
{
    protected $endpoint = '/api/v1/auth';
    protected $port = 10010;

    public function call($amount, $ticket, $template, $dstpage, array $extra = array())
    {
        $defaults = array(
            'reference'                     => '',
            'redirects.extradata'           => '',
            'redirects.message'             => '',
            'redirects.notmode'             => 'async',
            'redirects.notpage'             => '',
            'redirects.results_interactive' => 'false'
        );

        $input = array(
            'amount'                => $amount,
            'ticket'                => $ticket,
            'redirects.templateid'  => $template,
            'redirects.dstpage'     => $dstpage,
            'resource'              => 'iframe/v1/redirects/payments/methods'
        );

        $params = array_merge($defaults, $extra, $input);
        $keys = array('apikey', 'authtype', 'lang', 'merchantid', 'merchantname', 'currency');

        $params = $this->params($keys, $params);


        $this->log->info('sipay.actions.redirect.payments_methods_auth', 'api.request', 'I-00001', 'Send Request', $params);
        $this->request->json($params)->call();

        if($this->request->error == false) {
            $this->log->info('sipay.actions.redirect.payments_methods_auth', 'api.response', 'I-00001', 'Request OK', $this->request->json);
            return $this->request->get('redirects.url');
        }

        $this->log->error('sipay.actions.redirect.payments_methods_auth', 'api.response', 'I-00001', 'Request KO', $this->request->error);
        #TODO control errores
        return false;
    }
}
