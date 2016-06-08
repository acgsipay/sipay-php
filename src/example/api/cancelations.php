<?php
# -------------------------------------------------
#   PARAMS
# -------------------------------------------------
    $amount         = '0000000010';
    $ticket         = 'sipay-php-sdk-1234';
    $transactionid  = ''; # Mandatory

# -------------------------------------------------
#   NOTIFICATION
# -------------------------------------------------
    $notpage        = '';
    $notmode        = 'async'; # [async|sync]


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
    $auth = new Sipay\Actions\Api\CancelationsAuth($profile);

    $extra  = array(
        'api.notpage'   => $notpage,
        'api.notmode'   => $notmode
    );

    $idcancelation = $auth->call($amount, $ticket, $extra);

# -------------------------------------------------
#   CALL
# -------------------------------------------------
    $action = new Sipay\Actions\Api\Cancelations($profile);

    $response = $action->call($idcancelation, $transactionid);

    if(isset($response['ResultCode']) && $response['ResultCode'] == 0) {
        echo 'Success!';
    }
    else {
        echo 'Not Success!';

    }
