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
    $dstpage        = ''; # Conditional (Mandatory for CES)

# -------------------------------------------------
#   CARD
# -------------------------------------------------
    $pan            = '4242424242424242';
    $cvv            = '123';
    $expiration     = '1217';

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
    $auth = new Sipay\Actions\Api\PaymentsAuth($profile);

    $extra  = array(
        'api.dstpage'   => $dstpage, # CES
        'api.notpage'   => $notpage,
        'api.notmode'   => $notmode
    );

    $idrequest = $auth->call($amount, $ticket, $extra);

# -------------------------------------------------
#   CALL
# -------------------------------------------------
    $action = new Sipay\Actions\Api\Payments($profile);

    $extra = array(
        'reference' => $reference
    );

    $response = $action->call($idrequest, $amount, $ticket, $pan, $cvv, $expiration, $extra);

    if($response !== false) {
        if($response['ResultCode'] == 0) {
            echo 'Success!';
        }

        # CES
        else if ($response['ResultCode'] == '8') {
            echo '<div><a href="'.$response['url'].'">URL</a></div>';
        }
    }
    else {
        echo 'Not Success!';

    }
