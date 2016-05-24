<?php
# -------------------------------------------------
#   PARAMS
# -------------------------------------------------
    $amount         = '0000000010';
    $ticket         = 'sipay-php-sdk-1234';

    $template       = ''; # Mandatory
    $dstpage        = ''; # Mandatory

    $reference      = '1234sdkphp'; # Optional

    $extradata      = ''; # Optional
    $message        = ''; # Optional

    $interactive    = 'true'; # [true|false]
    $result_message = 'Procesando pago, por favor espere';

    $expiration     = 300; # Optional
    $target         = '_self'; # [_self|_parent]

# -------------------------------------------------
#   NOTIFICATION
# -------------------------------------------------
    $notpage        = '';
    $notmode        = 'async'; # [async|sync]
    $notemail       = '';
    $notemailformat = 'json'; # [nvp|json|line]

# -------------------------------------------------
#   PROFILE
# -------------------------------------------------
    $profile        = 'profile';

# -------------------------------------------------
#   HACK
# -------------------------------------------------
    if (isset($params)) {
        extract($params);
    }

# -------------------------------------------------
#   AUTH
# -------------------------------------------------
    $auth = new Sipay\Actions\Redirect\PaymentsCardsAuth($profile);

    $extra  = array(
        # 'redirects.extradata'           => $extradata,
        # 'redirects.message'             => $message,
        # 'redirects.results_interactive' => $interactive,
        # 'redirects.results_message'     => $result_message,

        # 'reference'                     => $reference,

        # 'redirects.notmode'             => $notmode,
        # 'redirects.notpage'             => $notpage,

        # 'redirects.notemail'            => $notemail,
        # 'redirects.notemailformat'      => $notemailformat,

        # 'redirects.expiration'          => $expiration,

        # 'iframe.target'                 => $target
    );

    $url = $auth->call($amount, $ticket, $template, $dstpage, $extra);

    echo '<div><a href="'.$url.'" target="_blank">URL</a></div>';
