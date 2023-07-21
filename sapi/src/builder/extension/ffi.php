<?php

use SwooleCli\Library;
use SwooleCli\Preprocessor;
use SwooleCli\Extension;

return function (Preprocessor $p) {
    $p->addExtension(
        (new Extension('ffi'))
            ->withHomePage('https://www.php.net/ffi')
            ->withOptions('--with-ffi')
            ->withDependentLibraries('libffi')
    );
};
