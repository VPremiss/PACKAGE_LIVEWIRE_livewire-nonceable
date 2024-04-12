<?php

return [

    /*
     |--------------------------------------------------------------------------
     | Throw if Nonce attributes are long limit (bool)
     |--------------------------------------------------------------------------
     |
     | Decide whether the package would throw and remind you if it found the
     | nonce attributes (title and unique-key) used for caching are a bit
     | long or not.
     |
     */

    'throw_if_long' => false,

    /*
     |--------------------------------------------------------------------------
     | Nonce attributes character length (int)
     |--------------------------------------------------------------------------
     |
     | Determine the length of Nonce attributes' strings.
     |
     */

    'attributes_length' => 40,

];
