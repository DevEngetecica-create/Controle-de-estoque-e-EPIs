<?php

return [
    'certs' => [
        'publicKey' => storage_path('certs/public.pem'),
        'privateKey' => storage_path('certs/private.pem'),
        'cert' => storage_path('certs/cert.pem'),
        'pfx' => storage_path('certs/certificate.pfx'), // Atualize o caminho aqui
        'passphrase' => env('NFE_CERT_PASSPHRASE', 'sua-senha'),
    ],
    'configJson' => json_encode([
        "atualizacao" => "2024-07-01 20:23:00",
        "tpAmb" => 2, // 1 = Produção, 2 = Homologação
        "razaosocial" => "SUA RAZAO SOCIAL LTDA",
        "cnpj" => "99999999999999",
        "siglaUF" => "PR",
        "schemes" => "PL_009_V4",
        "versao" => "4.00",
        "tokenIBPT" => "AAAAAAA",
        "CSC" => "GPB0JBWLUR6HWFTVEAS6RJ69GPCROFPBBB8G",
        "CSCid" => "000001",
        "proxyConf" => [
            "proxyIp" => "",
            "proxyPort" => "",
            "proxyUser" => "",
            "proxyPass" => ""
        ]
    ])
];

