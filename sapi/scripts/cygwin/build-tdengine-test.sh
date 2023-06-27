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
ROOT=${__PROJECT__}

ls "C:\TDengine"

C:\TDengine\taos.exe -V