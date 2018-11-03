@echo off
echo Generate website

if exist ".\output\" (
    echo   - Clean-up Output Directory
    rmdir /s /q .\output
)

echo   - Prepare Output Directory
mkdir .\output

echo   - Minify script files
.\bin\minify --all --recursive --output .\output\ .\src

echo   - Copying resources
copy .\src\site\res\*.png .\output\site\res\
copy .\src\site\favicon.ico .\output\site\
copy .\src\site\*.php .\output\site\
copy .\src\*.php .\output\
xcopy /s .\src\core .\output\core\

echo Done