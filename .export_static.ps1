$phpExe = 'C:\xampp\php\php.exe'
if (-not (Test-Path $phpExe)) { Write-Host 'PHP not found at C:\xampp\php\php.exe'; exit 2 }
$proc = Start-Process -FilePath $phpExe -ArgumentList '-S','localhost:8000','-t','.' -WindowStyle Hidden -PassThru
Start-Sleep -Seconds 1
$export='export'
if (Test-Path $export) { Remove-Item -Recurse -Force $export }
New-Item -ItemType Directory -Path $export | Out-Null
$pages = @('index','about','project','certification','contact')
foreach ($p in $pages) {
    $url = "http://localhost:8000/pages/$p.php"
    $out = Join-Path $export ("$p.html")
    try {
        Invoke-WebRequest -Uri $url -UseBasicParsing -OutFile $out -ErrorAction Stop
        Write-Host "Fetched $url"
    } catch {
        Write-Host "Failed to fetch $url"
        Stop-Process -Id $proc.Id
        exit 3
    }
}
Write-Host 'Copying assets and images'
Copy-Item -Recurse -Force assets, image -Destination $export
Get-ChildItem -Path $export -Filter *.html -Recurse | ForEach-Object {
    (Get-Content $_.FullName -Raw) -replace '/portfolio/','' | Set-Content $_.FullName
}
Start-Sleep -Milliseconds 300
Stop-Process -Id $proc.Id
Write-Host 'Export complete'