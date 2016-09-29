<?php

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
    $auth = new Sipay\Actions\Api\TokenizationsStoragesAuth($profile);

    $idstorage = $auth->call();

# -------------------------------------------------
#   CALL
# -------------------------------------------------
    $action = new Sipay\Actions\Api\TokenizationsStoragesGet($profile);

    $response = $action->call($idstorage, $cardindex);

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
