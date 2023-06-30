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

  AC_DEFINE(HAVE_TDENGINE, 1, [ Have tdengine support ])

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

  PHP_ADD_LIBRARY_WITH_PATH(taos, $TDENGINE_LIBDIR, TDENGINE_SHARED_LIBADD)
  PHP_SUBST(TDENGINE_SHARED_LIBADD)

  PHP_ADD_INCLUDE($TDENGINE_INCLUDE)

  PHP_ADD_INCLUDE([$ext_builddir/include])
  PHP_ADD_INCLUDE([$ext_srcdir/include])

  AC_DEFINE(HAVE_SWOOLE, 1, [use swoole])
  PHP_ADD_INCLUDE([$phpincludedir/ext/swoole])
  PHP_ADD_INCLUDE([$phpincludedir/ext/swoole/include])
  PHP_ADD_EXTENSION_DEP(tdengine, swoole)

  PHP_NEW_EXTENSION(tdengine, tdengine.cc src/ext_taos.cc src/ext_taos_connection.cc src/ext_taos_resource.cc src/ext_taos_statement.cc, $ext_shared,,, cxx)

  PHP_REQUIRE_CXX()

  CXXFLAGS="$CXXFLAGS -Wall -Wno-unused-function -Wno-deprecated -Wno-deprecated-declarations -Wwrite-strings"

  if test "$TD_OS" = "CYGWIN" || test "$TD_OS" = "MINGW"; then
    CXXFLAGS="$CXXFLAGS -std=gnu++11"
  else
    CXXFLAGS="$CXXFLAGS -std=c++11"
  fi

fi
