<?php

use SwooleCli\Library;
use SwooleCli\Preprocessor;

return function (Preprocessor $p) {
    $tdengine_prefix = TDENGINE_PREFIX;
    $p->addLibrary(
        (new Library('libtdengine'))
            ->withHomePage('https://www.taosdata.com/')
            ->withLicense('https://github.com/taosdata/TDengine/blob/main/LICENSE', Library::LICENSE_SPEC)
            ->withManual('https://docs.taosdata.com/get-started/')
            ->withUrl('https://github.com/taosdata/TDengine/archive/refs/tags/ver-3.0.7.1.tar.gz')
            ->withFile('TDengine-ver-3.0.7.1.tar.gz')
            ->withPrefix($tdengine_prefix)
            ->withBuildScript(
                <<<EOF
                mkdir -p build
                cd build
                cmake .. \
                -DBUILD_TOOLS=OFF \
                -DCMAKE_INSTALL_PREFIX={$tdengine_prefix} \
                -DCMAKE_BUILD_TYPE=Release  \
                -DCMAKE_POLICY_DEFAULT_CMP0074=NEW \
                -DBUILD_STATIC_LIBS=ON \
                -DBUILD_SHARED_LIBS=OFF  \
                -DBUILD_TEST=OFF \
                -DBUILD_HTTP=OFF \
                -DJEMALLOC_ENABLED=true \
                -DBUILD_WITH_ICONV=ON \
                -DBUILD_DOCS=OFF


              make -j \${LOGICAL_PROCESSORS}
EOF
            )
    );
};
