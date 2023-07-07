#!/usr/bin/env bash

set -exu
__DIR__=$(
  cd "$(dirname "$0")"
  pwd
)
__PROJECT__=$(
  cd ${__DIR__}/../../../
  pwd
)
cd ${__PROJECT__}

bin/swoole-cli -v
bin/swoole-cli -m
bin/swoole-cli --ri swoole

bin/swoole-cli sapi/scripts/cygwin/cygwin-pack.php
