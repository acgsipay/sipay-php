<?php
# -------------------------------------------------
#   PARAMS
# -------------------------------------------------
    $amount         = '0000000010'; # Mandatory
    $ticket         = 'sipay-php-sdk-1234'; # Mandatory
    $dstpage        = ''; # Mandatory

    $reference      = '1234sdkphp'; # Optional

    $checkmode      = 'mode1';

# -------------------------------------------------
#   NOTIFICATION
# -------------------------------------------------
    $notpage        = '';
    $notmode        = 'async'; # [async|sync]

# -------------------------------------------------
#   TOKENIZATION
# -------------------------------------------------
    $cardindex      = 'cardsdk'; # Mandatory

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
    $auth = new Sipay\Actions\Api\DirectPostAuth($profile);

    $extra  = array(
        'api.notpage'   => $notpage,
        'api.notmode'   => $notmode
    );

    $idrequest = $auth->call($amount, $ticket, $dstpage, $extra);


# -------------------------------------------------
#   CALL
# -------------------------------------------------
    $action = new Sipay\Actions\Api\TokenizationsStoragesWebviews($profile);

    $extra = array(
        'notpage'   => $notpage,
        'notmode'   => $notmode
    );

    $response = $action->call($idrequest, $cardindex, $amount, $ticket, $dstpage, $checkmode, $extra);

    if(isset($response['webview.url'])) {
        echo '<div><a href="'.$response['webview.url'].'">URL</a></div>';
    }

    else {
        echo 'Not Success!';

    }

    echo '<div><pre>'.print_r($response, true).'</pre></div>';
