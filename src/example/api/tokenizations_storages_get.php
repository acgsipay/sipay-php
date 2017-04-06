<?php
# -------------------------------------------------
#   PARAMS
# -------------------------------------------------

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
    $auth = new Sipay\Actions\Api\TokenizationsStoragesAuth($profile);

    $extra  = array();

    $idrequest = $auth->call($extra);

# -------------------------------------------------
#   CALL
# -------------------------------------------------
    $action = new Sipay\Actions\Api\TokenizationsStoragesGet($profile);

    $extra = array();

    $response = $action->call($idrequest, $cardindex, $extra);

    if(isset($response['ResultCode']) && $response['ResultCode'] == '0') {
        echo 'Success!';
    }
    else {
        echo 'Not Success!';

    }

    echo '<div><pre>'.print_r($response, true).'</pre></div>';
