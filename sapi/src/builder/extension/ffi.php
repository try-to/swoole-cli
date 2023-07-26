<?php

use SwooleCli\Preprocessor;
use SwooleCli\Extension;

return function (Preprocessor $p) {
    $p->addExtension(
        (new Extension('ffi'))
            ->withHomePage('https://www.php.net/ffi')
            ->withOptions('--with-ffi=' . FFI_PREFIX)
            ->withDependentLibraries('libffi')
    );
};