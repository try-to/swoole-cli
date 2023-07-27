<?php

use SwooleCli\Library;
use SwooleCli\Preprocessor;

return function (Preprocessor $p) {
    $libffi_prefix = LIBFFI_PREFIX;
    $p->addLibrary(
        (new Library('libffi'))
            ->withHomePage('https://sourceware.org/libffi/')
            ->withLicense('http://github.com/libffi/libffi/blob/master/LICENSE', Library::LICENSE_BSD)
            ->withUrl('https://github.com/libffi/libffi/releases/download/v3.4.4/libffi-3.4.4.tar.gz')
            ->withFile('libffi-3.4.4.tar.gz')
            ->withPrefix($libffi_prefix)
            ->withConfigure(
                <<<EOF
                ./autogen.sh
                ./configure --help
                ./configure \
                --prefix={$libffi_prefix} \
                --enable-static=yes \
                --enable-shared=no
EOF
            )
            ->withPkgName('libffi')
            ->withPkgConfig($libffi_prefix . '/lib/pkgconfig')
            ->withLdflags('-L' . $libffi_prefix . '/lib/')
            ->withBinPath($libffi_prefix . '/bin/')
    );
    // $p->withVariable('CPPFLAGS', '$CPPFLAGS -I' . $libffi_prefix . '/include');
    // $p->withVariable('LDFLAGS', '$LDFLAGS -L' . $libffi_prefix . '/lib');
    // $p->withVariable('LIBS', '$LIBS -lffi');
};
