<?php

use SwooleCli\Preprocessor;
use SwooleCli\Extension;

return function (Preprocessor $p) {
    $p->addExtension(
        (new Extension('ffi'))
            ->withHomePage('https://www.php.net/ffi')
            ->withLicense('http://github.com/libffi/libffi/blob/master/LICENSE', Extension::LICENSE_MIT)
            ->withOptions('--with-ffi')
            // ->withOptions('--with-ffi=' . LIBFFI_PREFIX)
            // ->withDependentLibraries('libffi')
    );
};
