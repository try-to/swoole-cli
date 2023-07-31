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

                if grep -Eqi "CentOS" /etc/issue || grep -Eq "CentOS" /etc/*-release; then
                    yum install -y libffi-devel
                elif grep -Eqi "Alpine" /etc/issue || grep -Eq "Alpine" /etc/*-release; then
                    apk add libffi-dev
                elif grep -Eqi "Ubuntu" /etc/issue || grep -Eq "Ubuntu" /etc/*-release; then
                    apt-get install -y libffi-dev
                elif grep -Eqi "Debian" /etc/issue || grep -Eq "Debian" /etc/*-release; then
                    apt-get install -y libffi-dev
                fi

                if [ ! -d {$libffi_prefix}/lib ]; then
                    mkdir -p {$libffi_prefix}/lib
                fi
                if [ ! -d {$libffi_prefix}/include ]; then
                    mkdir -p {$libffi_prefix}/include
                fi
                ./configure --help
                ./configure \
                --prefix={$libffi_prefix} \
                --disable-docs \
                --enable-static=yes \
                --enable-shared=no
EOF
            )
            ->withPkgName('libffi')
            ->withBinPath($libffi_prefix . '/bin/')
    );
    // $p->withVariable('CPPFLAGS', '$CPPFLAGS -I' . $libffi_prefix . '/include');
    // $p->withVariable('LDFLAGS', '$LDFLAGS -L' . $libffi_prefix . '/lib');
    // $p->withVariable('LIBS', '$LIBS -lffi');
};
