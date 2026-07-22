<?php

$certDir = __DIR__ . '/ Certificates';
if (!is_dir($certDir)) {
    mkdir($certDir, 0755, true);
}

echo "Generating certificates in: $certDir\n";

// 1. Generate Root CA private key
$cmd1 = "\"D:\\Apps\\xampp\\apache\\bin\\openssl.exe\" genrsa -out \"$certDir/ca.key\" 2048";
echo "Executing: $cmd1\n";
shell_exec($cmd1);

// 2. Create self-signed Root CA cert
$cmd2 = "\"D:\\Apps\\xampp\\apache\\bin\\openssl.exe\" req -x509 -new -nodes -key \"$certDir/ca.key\" -sha256 -days 3650 -out \"$certDir/ca.crt\" -subj \"/CN=Education Root/O=Education Inc/C=US\" -config \"D:\\Apps\\xampp\\apache\\conf\\openssl.cnf\"";
echo "Executing: $cmd2\n";
shell_exec($cmd2);

// 3. Generate Website private key
$cmd3 = "\"D:\\Apps\\xampp\\apache\\bin\\openssl.exe\" genrsa -out \"$certDir/secure-study.key\" 2048";
echo "Executing: $cmd3\n";
shell_exec($cmd3);

// 4. Create Website CSR
$cmd4 = "\"D:\\Apps\\xampp\\apache\\bin\\openssl.exe\" req -new -key \"$certDir/secure-study.key\" -out \"$certDir/secure-study.csr\" -subj \"/CN=www.secure-study.com/O=Education Website/C=US\" -config \"D:\\Apps\\xampp\\apache\\conf\\openssl.cnf\"";
echo "Executing: $cmd4\n";
shell_exec($cmd4);

// 5. Create Extension file
$extContent = <<<EOT
authorityKeyIdentifier=keyid,issuer
basicConstraints=CA:FALSE
keyUsage = digitalSignature, nonRepudiation, keyEncipherment, dataEncipherment
subjectAltName = @alt_names
[alt_names]
DNS.1 = www.secure-study.com
DNS.2 = secure-study.com
EOT;

file_put_contents("$certDir/secure-study.ext", $extContent);
echo "Created extension file: secure-study.ext\n";

// 6. Sign the website certificate (valid for 1 year = 365 days)
$cmd5 = "\"D:\\Apps\\xampp\\apache\\bin\\openssl.exe\" x509 -req -in \"$certDir/secure-study.csr\" -CA \"$certDir/ca.crt\" -CAkey \"$certDir/ca.key\" -CAcreateserial -out \"$certDir/secure-study.crt\" -days 365 -sha256 -extfile \"$certDir/secure-study.ext\"";
echo "Executing: $cmd5\n";
shell_exec($cmd5);

echo "Verification:\n";
if (file_exists("$certDir/ca.crt") && file_exists("$certDir/secure-study.crt")) {
    echo "SUCCESS: Certificates generated successfully!\n";
} else {
    echo "ERROR: Certificate generation failed.\n";
}
