<?php
# -------------------------------------------------
#   PARAMS
# -------------------------------------------------
    $amount         = '0000000010';
    $ticket         = 'sipay-php-sdk-1234';

    $transactionid  = ''; # Mandatory

    $reference      = '1234sdkphp'; # Optional

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
    $auth = new Sipay\Actions\Redirect\RefundsAuth($profile);

    $extra  = array();

    $idrefund = $auth->call($amount, $ticket, $extra);

# -------------------------------------------------
#   CALL
# -------------------------------------------------
    $action = new Sipay\Actions\Redirect\RefundsById($profile);

    $extra = array(
        'reference' => $reference
    );

    $response = $action->call($idrefund, $amount, $ticket, $transactionid, $extra);

    if($response['ResultCode'] == 0) {
        echo 'Success!';
    }

    else {
        echo 'Not Success!';

    }

    echo '<div><pre>'.print_r($response, true).'</pre></div>';
