<?php
# -------------------------------------------------
#   PARAMS
# -------------------------------------------------
    $amount         = '0000000010';
    $ticket         = 'sipay-php-sdk-1234';

    $reference      = '1234sdkphp'; # Optional

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
    $auth = new Sipay\Actions\Api\TokenizationsPaymentsAuth($profile);

    $extra  = array(
        'api.dstpage'   => $dstpage, # CES
        'api.notpage'   => $notpage,
        'api.notmode'   => $notmode
    );

    $idrequest = $auth->call($amount, $ticket, $extra);


# -------------------------------------------------
#   CALL
# -------------------------------------------------
    $action = new Sipay\Actions\Api\TokenizationsPayments($profile);

    $extra = array(
        'reference' => $reference
    );

    $response = $action->call($idrequest, $cardindex, $amount, $ticket, $extra);

    if($response['ResultCode'] == 0) {
        echo 'Success!';
    }

    else if ($response['ResultCode'] == '8') {
        echo '<div><a href="'.$response['url'].'">URL</a></div>';
    }

    else {
        echo 'Not Success!';

    }

    echo '<div><pre>'.print_r($response, true).'</pre></div>';
