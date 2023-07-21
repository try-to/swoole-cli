<?php

use SwooleCli\Library;
use SwooleCli\Preprocessor;

return function (Preprocessor $p) {
    $p->addLibrary(
        (new Library('libtdengine'))
            ->withHomePage('https://www.taosdata.com/')
            ->withLicense('https://github.com/taosdata/TDengine/blob/main/LICENSE', Library::LICENSE_SPEC)
            ->withManual('https://docs.taosdata.com/get-started/')
            ->withUrl('https://github.com/taosdata/TDengine/archive/refs/tags/ver-3.0.7.1.tar.gz')
            ->withFile('TDengine-ver-3.0.7.1.tar.gz')
            ->withPrefix('/usr/local/taos/')
            ->withBuildScript(
                <<<EOF
                mkdir -p build
                cd build
                cmake .. \
                -DBUILD_JDBC=false \
                -DTD_BUILD_HTTP=false \
                -DTD_BUILD_LUA=false


              make -j \${LOGICAL_PROCESSORS}
EOF
            )
    );
};
