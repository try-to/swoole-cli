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
            // ->withUrl('https://github.com/taosdata/TDengine/archive/refs/tags/ver-3.0.7.1.tar.gz')
            // ->withFile('TDengine-ver-3.0.7.1.tar.gz')
            ->withUrl('https://www.taosdata.com/assets-download/3.0/TDengine-client-3.0.7.1-Linux-x64.tar.gz')
            ->withFile('TDengine-client-3.0.7.1.tar.gz')
            // ->withPrefix($tdengine_prefix)
            ->withBuildScript(
                <<<EOF
                ./install_client.sh
EOF
            )
            ->withPkgName('libtdengine')
            ->withBinPath('/usr/local/taos/bin')
        //             ->withBuildScript(
        //                 <<<EOF
        //                 mkdir -p build
        //                 cd build
        //                 PACKAGES=" zlib libssl openssl "
        //                 CPPFLAGS="$(pkg-config  --cflags-only-I --static \$PACKAGES ) " \
        //                 LDFLAGS="$(pkg-config   --libs-only-L   --static \$PACKAGES )  " \
        //                 LIBS="$(pkg-config      --libs-only-l   --static \$PACKAGES )  "
        //                 cmake .. \
        //                 -DBUILD_TOOLS=OFF \
        //                 -DCMAKE_INSTALL_PREFIX={$tdengine_prefix} \
        //                 -DCMAKE_BUILD_TYPE=Release  \
        //                 -DCMAKE_POLICY_DEFAULT_CMP0074=NEW \
        //                 -DBUILD_STATIC_LIBS=ON \
        //                 -DBUILD_SHARED_LIBS=OFF  \
        //                 -DBUILD_TEST=OFF \
        //                 -DBUILD_HTTP=OFF \
        //                 -DJEMALLOC_ENABLED=true \
        //                 -DBUILD_WITH_ICONV=ON \
        //                 -DBUILD_DOCS=OFF \
        //                 -DBUILD_HTTP=false
        //                 make -j \${LOGICAL_PROCESSORS}
        // EOF
        //             )
        //             ->withDependentLibraries(
        //                 'zlib',
        //                 'openssl'
        //             )
    );
};
