<?php
// show error reporting
error_reporting(E_ALL);

// set your default time-zone
date_default_timezone_set('America/Mexico_City');

// variables used for jwt
$key = getenv('JWT_SECRET');

if (!$key) {
    // SECURITY WARNING: This fallback is for development/sandbox environments only.
    // In production, ensure JWT_SECRET is set in the environment.
    $key = "M4rpTr4ck_S3cur3_K3y_987654321_DEV_ONLY";
}

$iss = "http://marptrack.com";
$aud = "http://marptrack.com";
$iat = time();
$nbf = time();
$exp = time() + (60 * 60 * 2); // 2 hours
?>
