<?php

$apiDir = __DIR__ . '/api';
foreach (glob($apiDir . '/*.php') ?: [] as $file) {
    require $file;
}
foreach (glob($apiDir . '/*/*.php') ?: [] as $file) {
    require $file;
}
