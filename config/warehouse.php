<?php

return [
    // Enable only after warehouse:migrate-legacy and warehouse:verify-migration succeed.
    'use_movements' => env('WAREHOUSE_USE_MOVEMENTS', false),
];
