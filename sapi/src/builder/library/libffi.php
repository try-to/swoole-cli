<?php

use SwooleCli\Library;
use SwooleCli\Preprocessor;

return function (Preprocessor $p) {
    $p->addLibrary(
        (new Library('libffi'))
            ->withHomePage('https://sourceware.org/libffi/')
            ->withLicense('http://github.com/libffi/libffi/blob/master/LICENSE', Library::LICENSE_BSD)
            ->withUrl('https://github.com/libffi/libffi/releases/download/v3.4.4/libffi-3.4.4.tar.gz')
            ->withFile('libffi-3.4.4.tar.gz')
            ->withPrefix('/usr/libffi/')
            ->withConfigure(
                '
            ./configure --help ;
            ./configure \
            --prefix=/usr/libffi \
            --enable-shared=no \
            --enable-static=yes 
            '
            )
            ->withPkgName('libffi')
            ->withPkgConfig('/usr/libffi/lib/pkgconfig')
            ->withLdflags('-L/usr/libffi/lib/')
            ->withBinPath('/usr/libffi/bin/')
    );
};