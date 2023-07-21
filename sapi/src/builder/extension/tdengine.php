<?php

use SwooleCli\Library;
use SwooleCli\Preprocessor;
use SwooleCli\Extension;

return function (Preprocessor $p) {

    $options = '--enable-tdengine --with-tdengine-dir=/usr/tdengine';

    $p->addExtension(
        (new Extension('redis'))
            ->withOptions('--enable-redis')
            ->withPeclVersion('5.3.7')
            ->withHomePage('https://github.com/phpredis/phpredis')
            ->withLicense('https://github.com/phpredis/phpredis/blob/develop/COPYING', Extension::LICENSE_PHP)
    );

    $ext = (new Extension('tdengine'))
        ->withOptions($options)
        ->withLicense('https://github.com/Yurunsoft/php-tdengine/blob/master/LICENSE', Extension::LICENSE_SPEC)
        ->withHomePage('https://github.com/Yurunsoft/php-tdengine.git')
        ->withUrl('https://github.com/Yurunsoft/php-tdengine/archive/refs/tags/v1.0.6.tar.gz')
        ->withFile('tdengine-v1.0.6.tar.gz')
        ->withDependentExtensions('swoole')
        ->withDependentLibraries('libtdengine');
    $p->addExtension($ext);
};
