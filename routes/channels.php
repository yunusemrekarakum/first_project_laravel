<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('public-channel', function () {
    return true;
});
