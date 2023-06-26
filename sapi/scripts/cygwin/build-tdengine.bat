@ECHO OFF

d:

cd D:\

git clone --recurse -b ver-3.0.5.1 --depth=1 https://github.com/taosdata/TDengine.git

cd "D:\TDengine"

mkdir release

cd release

call "C:\Program Files (x86)\Microsoft Visual Studio\2019\Enterprise\VC\Auxiliary\Build\vcvarsall.bat" x64

@REM cmake .. -G "NMake Makefiles" -DBUILD_JDBC=false -DTD_BUILD_HTTP=false -DTD_BUILD_LUA=false
cmake .. -G "NMake Makefiles"

nmake

nmake install

echo %PATH%

copy "C:\TDengine\driver\taos.dll" "C:\Windows\System32"
