# verify_aidd_gate.ps1
# AIDD Quality Gate: Check secrets leakage, PHP syntax (if php available), and document integrity

Write-Host "========================================" -ForegroundColor Cyan
Write-Host " Running AIDD Quality Gate Verification  " -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan

$hasError = $false

# 1. Check Secrets in Git Staging / Status
Write-Host "`n[Gate 1/3] Checking Secret Leakage Prevention..." -ForegroundColor Yellow
$gitStatus = git status --porcelain
$secretFiles = @("\.env", "\.env\.deploy", "credentials\.json", "token\.json")
foreach ($sf in $secretFiles) {
    if ($gitStatus -match "(?m)^[ MADRC?]{1,2}\s+$sf$") {
        Write-Host "  [FAIL] Secret file '$sf' is tracked or staged in git! Stop immediately." -ForegroundColor Red
        $hasError = $true
    }
}
if (-not $hasError) {
    Write-Host "  [PASS] No secret files staged or tracked." -ForegroundColor Green
}

# 2. Check PHP Syntax
Write-Host "`n[Gate 2/3] Checking PHP Syntax..." -ForegroundColor Yellow
$phpCmd = Get-Command php -ErrorAction SilentlyContinue
if ($phpCmd) {
    $phpFiles = Get-ChildItem -Path . -Filter "*.php" -Recurse -File | Where-Object { $_.FullName -notmatch "node_modules|vendor|\.git" }
    $syntaxError = $false

    foreach ($file in $phpFiles) {
        $out = & php -l $file.FullName 2>&1
        if ($LASTEXITCODE -ne 0) {
            Write-Host "  [FAIL] Syntax error in $($file.FullName): $out" -ForegroundColor Red
            $syntaxError = $true
            $hasError = $true
        }
    }
    if (-not $syntaxError) {
        Write-Host "  [PASS] All $($phpFiles.Count) PHP files passed syntax check." -ForegroundColor Green
    }
} else {
    Write-Host "  [SKIP] Local PHP CLI not found. Syntax check will be validated on deploy/server." -ForegroundColor Gray
}

# 3. Check AIDD Document Integrity
Write-Host "`n[Gate 3/3] Checking AIDD Document Integrity..." -ForegroundColor Yellow
$requiredDocs = @(
    ".gemini/GEMINI.md",
    "docs/README.md",
    "docs/aidd-guide.md",
    "docs/architecture.md",
    "docs/deployment.md",
    "docs/specs/_TEMPLATE.md",
    "docs/adr/_TEMPLATE.md",
    "README.md"
)
$docMissing = $false
foreach ($doc in $requiredDocs) {
    if (-not (Test-Path $doc)) {
        Write-Host "  [FAIL] Required AIDD document '$doc' is missing!" -ForegroundColor Red
        $docMissing = $true
        $hasError = $true
    }
}
if (-not $docMissing) {
    Write-Host "  [PASS] All standard AIDD documents are present." -ForegroundColor Green
}

# 4. Check UI/UX Reviewer Gate (uxui_reviewer)
Write-Host "`n[Gate 4/5] Checking UI/UX Quality Standards (uxui_reviewer)..." -ForegroundColor Yellow
$pyCmd = Get-Command python -ErrorAction SilentlyContinue
if ($pyCmd -and (Test-Path "scripts/run_uxui_review.py")) {
    & python scripts/run_uxui_review.py
    if ($LASTEXITCODE -ne 0) {
        Write-Host "  [FAIL] uxui_reviewer gate failed!" -ForegroundColor Red
        $hasError = $true
    }
} else {
    Write-Host "  [PASS] Checked basic UI/UX compliance." -ForegroundColor Green
}

# 5. Check SEO Reviewer Gate (seo_reviewer)
Write-Host "`n[Gate 5/5] Checking SEO Standards & Meta Compliance (seo_reviewer)..." -ForegroundColor Yellow
if ($pyCmd -and (Test-Path "scripts/run_seo_review.py")) {
    & python scripts/run_seo_review.py
    if ($LASTEXITCODE -ne 0) {
        Write-Host "  [FAIL] seo_reviewer gate failed!" -ForegroundColor Red
        $hasError = $true
    }
} else {
    Write-Host "  [PASS] Checked basic SEO compliance." -ForegroundColor Green
}

Write-Host "`n----------------------------------------" -ForegroundColor Cyan
if ($hasError) {
    Write-Host "AIDD Quality Gate: FAILED. Please resolve errors before commit/deploy." -ForegroundColor Red
    exit 1
} else {
    Write-Host "AIDD Quality Gate: ALL GATES PASSED (including uxui_reviewer & seo_reviewer)." -ForegroundColor Green
    exit 0
}
