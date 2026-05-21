<?php

use App\Providers\AppServiceProvider;
use App\Providers\FortifyServiceProvider;
use CloudinaryLabs\CloudinaryLaravel\CloudinaryServiceProvider;

return [
    AppServiceProvider::class,
    FortifyServiceProvider::class,
    CloudinaryServiceProvider::class,
];
