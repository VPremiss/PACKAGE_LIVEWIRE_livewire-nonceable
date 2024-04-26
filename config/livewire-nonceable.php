<?php

declare(strict_types=1);

return [

    /*
     |--------------------------------------------------------------------------
     | Nonce key attributes character length (int)
     |--------------------------------------------------------------------------
     |
     | Determine the length of Nonce attributes' strings.
     |
     */

    'key_attributes_length' => 40,

    /*
     |--------------------------------------------------------------------------
     | Throw if Nonce attributes are long (bool)
     |--------------------------------------------------------------------------
     |
     | Decide whether the package should throw if it found the nonce key attributes
     | (title and unique-key) used for caching are a longer than the set length
     | in the previous configuration.
     |
     */

    'throw_if_key_attributes_are_long' => false,

];
