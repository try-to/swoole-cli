@ECHO OFF

echo %PATH%

c:

cd C:\

mkdir TDengine

d:

cd D:\

git clone --recurse -b ver-3.0.5.1 --depth=1 https://github.com/taosdata/TDengine.git

cd "D:\TDengine"

mkdir release

cd release

call "C:\Program Files (x86)\Microsoft Visual Studio\2019\Enterprise\VC\Auxiliary\Build\vcvarsall.bat" x64

cmake .. -G "NMake Makefiles" -DBUILD_JDBC=false -DTD_BUILD_HTTP=false -DTD_BUILD_LUA=false

nmake

nmake install

ls release\build

c:

cd C:\

echo %~dp0

ls "C:\"

ls "C:\TDengine\"

ls "C:\Program Files (x86)\TDengine\"

copy "C:\TDengine\driver\taos.dll" "C:\Windows\System32"
