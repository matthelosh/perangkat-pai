<?php

use App\Models\Semester;
use App\Models\Tapel;


if (! function_exists('tapel')) {
    function tapel()
    {
        return Tapel::whereStatus('active')->first();
    }
}
if (! function_exists('semester')) {
    function semester()
    {
        return Semester::whereStatus('active')->first();
    }
}
