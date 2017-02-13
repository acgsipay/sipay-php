<?php
# -------------------------------------------------
#   PARAMS
# -------------------------------------------------
    $amount         = '0000000010'; # Mandatory
    $ticket         = 'sipay-php-sdk-1234'; # Mandatory

    $reference      = '1234sdkphp'; # Optional

# -------------------------------------------------
#   NOTIFICATION
# -------------------------------------------------
    $notpage        = '';
    $notmode        = 'async'; # [async|sync]
    $notemail       = '';
    $notemailformat = 'json'; # [nvp|json|line]

# -------------------------------------------------
#   PREAUTHORIZATIONS
# -------------------------------------------------
    $transactionid  = ''; # Mandatory

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
    $auth = new Sipay\Actions\Api\PreauthorizationsAuth($profile);

    $extra  = array(
        'api.notpage'   => $notpage,
        'api.notmode'   => $notmode,

        # 'notemail'            => $notemail,
        # 'notemailformat'      => $notemailformat,

    );

    $idrequest = $auth->call($amount, $ticket, $extra);


# -------------------------------------------------
#   CALL
# -------------------------------------------------
    $action = new Sipay\Actions\Api\PreauthorizationsConfirm($profile);

    $extra = array(
        'reference' => $reference
    );

    $response = $action->call($idrequest, $amount, $ticket, $transactionid, $extra);

    if($response['ResultCode'] == 0) {
        echo 'Success!';
    }

    else {
        echo 'Not Success!';

    }

    echo '<div><pre>'.print_r($response, true).'</pre></div>';
