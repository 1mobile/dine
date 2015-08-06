pushd C:\xampp\htdocs\dine\sm\
for /f "tokens=*" %%a in ('dir /b /od') do set newest=%%a
type "%newest%"
pause >nul