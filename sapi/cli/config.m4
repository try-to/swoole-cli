PHP_ARG_ENABLE([cli],,
  [AS_HELP_STRING([--disable-cli],
    [Disable building CLI version of PHP (this forces --without-pear)])],
  [yes],
  [no])

AC_CHECK_FUNCS(setproctitle)

AC_CHECK_HEADERS([sys/pstat.h])

AC_CACHE_CHECK([for PS_STRINGS], [cli_cv_var_PS_STRINGS],
[AC_LINK_IFELSE([AC_LANG_PROGRAM([[#include <machine/vmparam.h>
#include <sys/exec.h>
]],
[[PS_STRINGS->ps_nargvstr = 1;
PS_STRINGS->ps_argvstr = "foo";]])],
[cli_cv_var_PS_STRINGS=yes],
[cli_cv_var_PS_STRINGS=no])])
if test "$cli_cv_var_PS_STRINGS" = yes ; then
  AC_DEFINE([HAVE_PS_STRINGS], [], [Define to 1 if the PS_STRINGS thing exists.])
fi

AC_DEFUN([PHP_SELECT_CLI_SAPI],[
    PHP_BINARIES="$PHP_BINARIES $1"
	PHP_INSTALLED_SAPIS="$PHP_INSTALLED_SAPIS $1"

	PHP_BUILD_PROGRAM
	install_binaries="install-binaries"
	install_binary_targets="$install_binary_targets install-$1"
	PHP_SUBST(PHP_[]translit($1,a-z0-9-,A-Z0-9_)[]_OBJS)
	ifelse($3,,,[PHP_ADD_SOURCES_X([main/$1],[$3],[$4],PHP_[]translit($1,a-z0-9-,A-Z0-9_)[]_OBJS)])
])

AC_MSG_CHECKING(for CLI build)
if test "$PHP_CLI" != "no"; then
  PHP_ADD_MAKEFILE_FRAGMENT($abs_srcdir/main/cli/Makefile.frag)

  dnl Set filename.
  SAPI_CLI_PATH=bin/swoole-cli

  dnl Select SAPI.
  PHP_ADD_BUILD_DIR([main/cli])
  PHP_SELECT_CLI_SAPI(cli, program, php_cli.c ps_title.c php_cli_process_title.c, -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1, '$(SAPI_CLI_PATH)')

  case $host_alias in
  *aix*)
    if test "$php_sapi_module" = "shared"; then
      BUILD_CLI="echo '\#! .' > php.sym && echo >>php.sym && nm -BCpg \`echo \$(PHP_GLOBAL_OBJS) \$(PHP_BINARY_OBJS) \$(PHP_CLI_OBJS) | sed 's/\([A-Za-z0-9_]*\)\.lo/.libs\/\1.o/g'\` | \$(AWK) '{ if (((\$\$2 == \"T\") || (\$\$2 == \"D\") || (\$\$2 == \"B\")) && (substr(\$\$3,1,1) != \".\")) { print \$\$3 } }' | sort -u >> php.sym && \$(LIBTOOL) --mode=link \$(CXX) -export-dynamic \$(CFLAGS_CLEAN) \$(EXTRA_CFLAGS) \$(EXTRA_LDFLAGS_PROGRAM) \$(LDFLAGS) -Wl,-brtl -Wl,-bE:php.sym \$(PHP_RPATHS) \$(PHP_GLOBAL_OBJS) \$(PHP_BINARY_OBJS) \$(PHP_CLI_OBJS) \$(EXTRA_LIBS) \$(ZEND_EXTRA_LIBS) -o \$(SAPI_CLI_PATH)"
    else
      BUILD_CLI="echo '\#! .' > php.sym && echo >>php.sym && nm -BCpg \`echo \$(PHP_GLOBAL_OBJS) \$(PHP_BINARY_OBJS) \$(PHP_CLI_OBJS) | sed 's/\([A-Za-z0-9_]*\)\.lo/\1.o/g'\` | \$(AWK) '{ if (((\$\$2 == \"T\") || (\$\$2 == \"D\") || (\$\$2 == \"B\")) && (substr(\$\$3,1,1) != \".\")) { print \$\$3 } }' | sort -u >> php.sym && \$(LIBTOOL) --mode=link \$(CXX) -export-dynamic \$(CFLAGS_CLEAN) \$(EXTRA_CFLAGS) \$(EXTRA_LDFLAGS_PROGRAM) \$(LDFLAGS) -Wl,-brtl -Wl,-bE:php.sym \$(PHP_RPATHS) \$(PHP_GLOBAL_OBJS) \$(PHP_BINARY_OBJS) \$(PHP_CLI_OBJS) \$(EXTRA_LIBS) \$(ZEND_EXTRA_LIBS) -o \$(SAPI_CLI_PATH)"
    fi
    ;;
  *darwin*)
    BUILD_CLI="\$(CXX) \$(CFLAGS_CLEAN) \$(EXTRA_CFLAGS) \$(EXTRA_LDFLAGS_PROGRAM) \$(LDFLAGS) \$(NATIVE_RPATHS) \$(PHP_GLOBAL_OBJS:.lo=.o) \$(PHP_BINARY_OBJS:.lo=.o) \$(PHP_CLI_OBJS:.lo=.o) \$(PHP_FRAMEWORKS) \$(EXTRA_LIBS) \$(ZEND_EXTRA_LIBS) -o \$(SAPI_CLI_PATH)"
    ;;
  *)
    BUILD_CLI="\$(LIBTOOL) --mode=link \$(CXX) -export-dynamic \$(CFLAGS_CLEAN) \$(EXTRA_CFLAGS) \$(EXTRA_LDFLAGS_PROGRAM) \$(LDFLAGS) \$(PHP_RPATHS) \$(PHP_GLOBAL_OBJS:.lo=.o) \$(PHP_BINARY_OBJS:.lo=.o) \$(PHP_CLI_OBJS:.lo=.o) \$(EXTRA_LIBS) \$(ZEND_EXTRA_LIBS) -o \$(SAPI_CLI_PATH)"
    ;;
  esac

  dnl Set executable for tests.
  PHP_EXECUTABLE="\$(top_builddir)/\$(SAPI_CLI_PATH)"
  PHP_SUBST(PHP_EXECUTABLE)

  dnl Expose to Makefile.
  PHP_SUBST(SAPI_CLI_PATH)
  PHP_SUBST(BUILD_CLI)

  PHP_OUTPUT(main/cli/php.1)

  PHP_INSTALL_HEADERS([main/cli/cli.h])
fi
AC_MSG_RESULT($PHP_CLI)