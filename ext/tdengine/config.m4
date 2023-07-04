PHP_ARG_ENABLE([tdengine],
  [whether to enable tdengine support],
  [AS_HELP_STRING([--enable-tdengine],
    [Enable tdengine support])],
  [no])

if test "$PHP_TDENGINE" != "no"; then

  PHP_ARG_WITH([tdengine_dir],
    [dir of tdengine],
    [AS_HELP_STRING([[--with-tdengine-dir[=DIR]]],
      [Include TDengine support (requires TDengine >= 2.0.0)])], [no], [no])

  if test "$PHP_TDENGINE_DIR" != "no"; then
    TDENGINE_INCLUDE="${PHP_TDENGINE_DIR}/include"
    TDENGINE_LIBDIR="${PHP_TDENGINE_DIR}/driver"
  else
    TDENGINE_INCLUDE="/usr/local/taos/include"
    TDENGINE_LIBDIR="/usr/local/taos/driver"
  fi

  PHP_CHECK_LIBRARY(taos, taos_init,
  [
  ], [
    AC_MSG_ERROR(tdengine module requires libtaos >= 2.0.0)
  ], [
    -L$TDENGINE_LIBDIR
  ])

  AC_CHECK_TYPES([TAOS_BIND], [
    AC_DEFINE(HAVE_TAOS_BIND, 1, [ Have TAOS_BIND ])
  ], [], [#include <taos.h>])

  AS_CASE([$host_os],
    [darwin*], [TD_OS="MAC"],
    [cygwin*], [TD_OS="CYGWIN"],
    [mingw*], [TD_OS="MINGW"],
    [linux*], [TD_OS="LINUX"],
    [*bsd*], [TD_OS="BSD"],
    []
  )

  tdengine_source_file=" \
    tdengine.cc \
    ext_taos.cc \
    ext_taos_connection.cc \
    ext_taos_resource.cc \
    ext_taos_statement.cc";

  dnl AC_DEFINE(HAVE_SWOOLE, 1, [use swoole])
  dnl PHP_ADD_INCLUDE([$phpincludedir/ext/swoole])
  dnl PHP_ADD_INCLUDE([$phpincludedir/ext/swoole/include])
  dnl PHP_ADD_EXTENSION_DEP(tdengine, swoole)

  PHP_ADD_INCLUDE($TDENGINE_INCLUDE)
  PHP_ADD_LIBRARY_WITH_PATH(taos, $TDENGINE_LIBDIR, TDENGINE_SHARED_LIBADD)

  dnl CXXFLAGS="$CXXFLAGS -Wall -Wno-unused-function -Wno-deprecated -Wno-deprecated-declarations -Wwrite-strings"

  dnl if test "$TD_OS" = "CYGWIN" || test "$TD_OS" = "MINGW"; then
  dnl   CXXFLAGS="$CXXFLAGS -std=gnu++11"
  dnl else
  dnl   CXXFLAGS="$CXXFLAGS -std=c++11"
  dnl fi

  PHP_REQUIRE_CXX()

  PHP_ADD_LIBRARY(stdc++, 1, TDENGINE_SHARED_LIBADD)

  dnl PHP_NEW_EXTENSION(tdengine, $tdengine_source_file, $ext_shared,, -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1, cxx)

  PHP_SUBST(TDENGINE_SHARED_LIBADD)

  PHP_NEW_EXTENSION(tdengine, $tdengine_source_file, $ext_shared)

  AC_DEFINE(HAVE_TDENGINE, 1, [ Have tdengine support ])

fi
