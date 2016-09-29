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

# -------------------------------------------------
#   TOKENIZATION
# -------------------------------------------------
    $cardindex      = 'cardsdk';

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
    $auth = new Sipay\Actions\Api\TokenizationsRefundsAuth($profile);

    $extra  = array(
        'api.notpage'   => $notpage,
        'api.notmode'   => $notmode
    );

    $idrequest = $auth->call($amount, $ticket, $extra);

# -------------------------------------------------
#   CALL
# -------------------------------------------------
    $action = new Sipay\Actions\Api\TokenizationsRefunds($profile);

    $extra = array(
        'reference' => $reference
    );

    $response = $action->call($idrequest, $cardindex, $amount, $ticket, $extra);

    if($response['ResultCode'] == 0) {
        echo var_dump($response);
        echo 'Success!';
    }

    else if ($response['ResultCode'] == '8') {
        echo '<div><a href="'.$response['url'].'">URL</a></div>';
    }

    else {
        echo 'Not Success!';

    }

    echo '<div><pre>'.print_r($action->request->fields, true).'</pre></div>';

    echo '<div><pre>'.print_r($response, true).'</pre></div>';
