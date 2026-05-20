<?php

use App\Providers\AppServiceProvider;
use App\Providers\FortifyServiceProvider;

return [
    AppServiceProvider::class,
    FortifyServiceProvider::class,
    CloudinaryLabs\CloudinaryLaravel\CloudinaryServiceProvider::class,
];
