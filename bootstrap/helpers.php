<?php

// Auto-load helper files
if (file_exists(__DIR__ . '/../app/Helpers')) {
    foreach (glob(__DIR__ . '/../app/Helpers/*.php') as $file) {
        require_once $file;
    }
}
