<?php

use SwooleCli\Library;
use SwooleCli\Preprocessor;
use SwooleCli\Extension;

return function (Preprocessor $p) {

    $options = '--with-tdengine --with-tdengine-dir=' . TDENGINE_PREFIX;

    $ext = (new Extension('tdengine'))
        ->withOptions($options)
        ->withLicense('https://github.com/Yurunsoft/php-tdengine/blob/master/LICENSE', Extension::LICENSE_SPEC)
        ->withHomePage('https://github.com/Yurunsoft/php-tdengine')
        ->withPeclVersion('1.0.6')
        ->withDependentExtensions('swoole')
        ->withDependentLibraries('libtdengine');
    $p->addExtension($ext);
};
