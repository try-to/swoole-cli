<?php

use SwooleCli\Preprocessor;
use SwooleCli\Library;
use SwooleCli\Extension;

return function (Preprocessor $p) {

    $p->withExportVariable('PHP_MONGODB_SSL', 'yes');
    $p->withExportVariable('PHP_MONGODB_SSL_CFLAGS', '$(pkg-config --cflags --static libcrypto libssl  openssl)');
    $p->withExportVariable('PHP_MONGODB_SSL_LIBS', '$(pkg-config   --libs   --static libcrypto libssl  openssl)');


    $p->withExportVariable('PHP_MONGODB_ICU', 'yes');
    $p->withExportVariable('PHP_MONGODB_ICU_CFLAGS', '$(pkg-config --cflags --static icu-i18n  icu-io  icu-uc)');
    $p->withExportVariable('PHP_MONGODB_ICU_LIBS', '$(pkg-config   --libs   --static icu-i18n  icu-io  icu-uc)');

    $p->withExportVariable('PHP_MONGODB_ZSTD_CFLAGS', '$(pkg-config --cflags --static libzstd)');
    $p->withExportVariable('PHP_MONGODB_ZSTD_LIBS', '$(pkg-config   --libs   --static libzstd)');

    $p->withExportVariable('PHP_MONGODB_ZLIB_CFLAGS', '$(pkg-config --cflags --static zlib)');
    $p->withExportVariable('PHP_MONGODB_ZLIB_LIBS', '$(pkg-config   --libs   --static zlib)');

    # PHP 8.2 以上 使用clang 编译
    # 需要解决这个问题 https://github.com/mongodb/mongo-php-driver/issues/1445

    $options = ' --enable-mongodb ';
    $options .= ' --with-mongodb-system-libs=no ';
    $options .= ' --with-mongodb-ssl=openssl ';
    $options .= ' --with-mongodb-sasl=no ';
    $options .= ' --with-mongodb-icu=yes ';

    $options .= ' --with-mongodb-snappy=no '; # v1.16 add parameter  https://github.com/mongodb/mongo-php-driver/issues/1427
    $options .= ' --with-mongodb-zlib=yes ';  # v1.16 add parameter
    $options .= ' --with-mongodb-zstd=yes ';  # v1.16 add parameter
    $options .= ' --with-mongodb-sasl=no ';   # v1.16 add parameter

    $ext = new Extension('mongodb');

    $ext->withHomePage('https://www.php.net/mongodb')
        ->withHomePage('https://www.mongodb.com/docs/drivers/php/')
        ->withOptions($options)
        ->withPeclVersion('1.16.1')
        ->withDependentExtensions('openssl');

    $depends = ['icu', 'openssl', 'zlib', 'libzstd', 'bison']; // ,'mongo_c_driver','libbson'

    //$depends[] = 'libsasl';
    //$depends[] = 'snappy';

    call_user_func_array([$ext, 'withDependentLibraries'], $depends);

    $p->addExtension($ext);

    $p->setExtHook('mongodb', function (Preprocessor $p) {

        $workdir = $p->getPhpSrcDir();
        # bug https://github.com/mongodb/mongo-c-driver/blob/6b7caf9da30eeae09c8eb0c539ebacbb31b9e520/src/libbson/src/bson/bson-error.c#L113
        $cmd = <<<EOF
                cd {$workdir}
                # sed -i.backup "497c  "  ext/mongodb/config.m4
                sed -i.backup "113c  "  ext/mongodb/src/libmongoc/src/libbson/src/bson/bson-error.c
                sed -i.backup "113c  "  ext/mongodb/src/libmongoc/src/libbson/src/bson/bson-error.c
EOF;

        return $cmd;
    });
};
