<?php

return [
    'signing-alg' => 'Lcobucci\JWT\Signer\Hmac\Sha512',
    'signing-key' => '4-)o-agDT=yL$i5VL<muEq2+B=fF{_dC=KBLxXIZ_a|(a3OUlpaom^nn*t<uOe5;',
    'issuer' => 'https://example.com',
    'audience' => 'https://example.com',
    'jti' => function () { return uniqid("", true); }, // NOT CRYPTO SECURE, BUT WE AREN'T VALIDATING JTI ATM
    'default-expiry' => 3600, //in seconds
    'remember-expiry' => 86400
];