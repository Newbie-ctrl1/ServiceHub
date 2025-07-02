# Railway GitHub Deployment Setup Script
# Script untuk setup deployment Railway via GitHub

Write-Host "🚀 Setting up Railway deployment via GitHub..." -ForegroundColor Green

# Check if railway CLI is installed
try {
    railway --version | Out-Null
    Write-Host "✅ Railway CLI found" -ForegroundColor Green
} catch {
    Write-Host "❌ Railway CLI not found. Installing..." -ForegroundColor Yellow
    npm install -g @railway/cli
}

# Check if logged in to Railway
Write-Host "🔐 Checking Railway authentication..." -ForegroundColor Blue
try {
    railway whoami | Out-Null
    Write-Host "✅ Already logged in to Railway" -ForegroundColor Green
} catch {
    Write-Host "🔑 Please login to Railway..." -ForegroundColor Yellow
    railway login
}

# Create new Railway project
Write-Host "📦 Creating new Railway project..." -ForegroundColor Blue
railway init

# Add MySQL database
Write-Host "🗄️ Adding MySQL database..." -ForegroundColor Blue
railway add --database mysql

# Connect to GitHub repository
Write-Host "🔗 Connecting to GitHub repository..." -ForegroundColor Blue
railway connect

# Set environment variables
Write-Host "⚙️ Setting environment variables..." -ForegroundColor Blue

if (Test-Path ".env") {
    Write-Host "📋 Found .env file. Reading configuration..." -ForegroundColor Green
    
    # Read .env file
    $envContent = Get-Content ".env"
    
    # Extract values
    $appKey = ($envContent | Where-Object { $_ -match "^APP_KEY=" }) -replace "APP_KEY=", ""
    $pusherAppId = ($envContent | Where-Object { $_ -match "^PUSHER_APP_ID=" }) -replace "PUSHER_APP_ID=", ""
    $pusherAppKey = ($envContent | Where-Object { $_ -match "^PUSHER_APP_KEY=" }) -replace "PUSHER_APP_KEY=", ""
    $pusherAppSecret = ($envContent | Where-Object { $_ -match "^PUSHER_APP_SECRET=" }) -replace "PUSHER_APP_SECRET=", ""
    $pusherAppCluster = ($envContent | Where-Object { $_ -match "^PUSHER_APP_CLUSTER=" }) -replace "PUSHER_APP_CLUSTER=", ""
    $groqApiKey = ($envContent | Where-Object { $_ -match "^GROQ_API_KEY=" }) -replace "GROQ_API_KEY=", ""
    
    # Set Laravel variables
    Write-Host "Setting Laravel configuration..." -ForegroundColor Yellow
    railway variables set APP_NAME="SO Chat App"
    railway variables set APP_ENV=production
    railway variables set APP_DEBUG=false
    railway variables set LOG_LEVEL=error
    railway variables set APP_KEY=$appKey
    
    # Set session and cache
    railway variables set SESSION_DRIVER=database
    railway variables set CACHE_STORE=database
    railway variables set QUEUE_CONNECTION=database
    railway variables set BROADCAST_CONNECTION=pusher
    railway variables set BROADCAST_DRIVER=pusher
    
    # Set Pusher variables
    Write-Host "Setting Pusher configuration..." -ForegroundColor Yellow
    railway variables set PUSHER_APP_ID=$pusherAppId
    railway variables set PUSHER_APP_KEY=$pusherAppKey
    railway variables set PUSHER_APP_SECRET=$pusherAppSecret
    railway variables set PUSHER_APP_CLUSTER=$pusherAppCluster
    railway variables set VITE_PUSHER_APP_KEY=$pusherAppKey
    railway variables set VITE_PUSHER_APP_CLUSTER=$pusherAppCluster
    
    # Set Groq API
    if ($groqApiKey) {
        Write-Host "Setting Groq API configuration..." -ForegroundColor Yellow
        railway variables set GROQ_API_KEY=$groqApiKey
    }
    
    Write-Host "✅ Environment variables set successfully!" -ForegroundColor Green
} else {
    Write-Host "⚠️ .env file not found. Please set variables manually." -ForegroundColor Red
}

# Enable auto-deploy from GitHub
Write-Host "🔄 Enabling auto-deploy from GitHub..." -ForegroundColor Blue
railway service --enable-auto-deploy

Write-Host ""
Write-Host "✅ Railway GitHub deployment setup completed!" -ForegroundColor Green
Write-Host "📊 Check deployment status: railway status" -ForegroundColor Cyan
Write-Host "📋 View logs: railway logs" -ForegroundColor Cyan
Write-Host "🌐 Open app: railway open" -ForegroundColor Cyan
Write-Host ""
Write-Host "🔗 Push to GitHub to trigger automatic deployment!" -ForegroundColor Yellow
Write-Host "📖 GitHub Actions workflow created at .github/workflows/railway-deploy.yml" -ForegroundColor Blue

# Wait for user input
Write-Host "Press any key to continue..." -ForegroundColor Gray
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")