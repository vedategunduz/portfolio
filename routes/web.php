<?php

$webDir = __DIR__ . '/web';
foreach (glob($webDir . '/*.php') ?: [] as $file) {
    require $file;
}
foreach (glob($webDir . '/*/*.php') ?: [] as $file) {
    require $file;
}
