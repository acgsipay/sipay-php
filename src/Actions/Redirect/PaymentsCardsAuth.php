<?php

namespace Sipay\Actions\Redirect;

use Sipay\Action;

class PaymentsCardsAuth extends Action
{
    protected $endpoint = '/api/v1/auth';
    protected $port = 10010;

    public function call($amount, $ticket, $templateid, $dstpage, array $extra = array())
    {
        $default = array(
            'redirects.extradata'           => '',
            //'redirects.message'             => '',
            'redirects.results_interactive' => 'true',
            'redirects.results_message'     => 'Procesando pago, por favor espere',
            //'redirects.notpage'             => '',
            'redirects.notmode'             => 'async', //async|sync
            //'redirects.notemail'            => '',
            'redirects.notemailformat'      => 'json', //nvp|json|line
            'redirects.expiration'          => '300',
            //'reference'                     => '',
            'iframe.target'                 => '_self',
            //'tokenizations.checkmode'       => '',
            //'tokenizations.cardindex'       => ''
        );

        $input = array(
            'amount'                => $amount,
            'ticket'                => $ticket,
            'redirects.templateid'  => $templateid,
            'redirects.dstpage'     => $dstpage,
            'resource'              => 'iframe/v1/redirects/payments/cards'
        );

        $params = array_merge($default, $extra, $input);
        $keys = array('apikey', 'authtype', 'lang', 'merchantid', 'currency');

        $params = $this->params($keys, $params);

        $this->log->info('sipay.actions.redirect.payments_cards_auth', 'api.request', 'I-00001', 'Send Request', $params);

        $this->request->json($params)->call();

        if($this->request->error == false) {
            $this->log->info('sipay.actions.redirect.payments_cards_auth', 'api.response', 'I-00001', 'Request OK', $this->request->json);
            return $this->request->get('redirects.url');
        }

        $this->log->error('sipay.actions.redirect.payments_cards_auth', 'api.response', 'E-00001', 'Request KO', $this->request->error);
        #TODO control errores
        return false;
    }
}
