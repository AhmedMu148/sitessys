<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Domain Not Found - SPS</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .error-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 3rem;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 90%;
        }
        
        .error-code {
            font-size: 6rem;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .error-title {
            font-size: 2rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 1rem;
        }
        
        .error-message {
            font-size: 1.1rem;
            color: #666;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        
        .domain-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1rem;
            margin: 1.5rem 0;
            border-left: 4px solid #667eea;
        }
        
        .domain-name {
            font-family: 'Courier New', monospace;
            font-weight: bold;
            color: #495057;
        }
        
        .btn-home {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            color: white;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        
        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
            color: white;
            text-decoration: none;
        }
        
        .suggestions {
            text-align: left;
            margin-top: 2rem;
            padding: 1.5rem;
            background: #fff3cd;
            border-radius: 10px;
            border-left: 4px solid #ffc107;
        }
        
        .suggestions h5 {
            color: #856404;
            margin-bottom: 1rem;
            font-weight: 600;
        }
        
        .suggestions ul {
            color: #856404;
            margin: 0;
            padding-left: 1.2rem;
        }
        
        .suggestions li {
            margin-bottom: 0.5rem;
        }
        
        .icon {
            font-size: 4rem;
            color: #667eea;
            margin-bottom: 1rem;
        }
        
        @media (max-width: 768px) {
            .error-container {
                padding: 2rem 1.5rem;
            }
            
            .error-code {
                font-size: 4rem;
            }
            
            .error-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="icon">🌐</div>
        <div class="error-code">404</div>
        <h1 class="error-title">Domain Not Found</h1>
        
        <div class="domain-info">
            <p class="mb-1"><strong>Requested Domain:</strong></p>
            <p class="domain-name mb-0">{{ $domain ?? 'Unknown' }}</p>
        </div>
        
        <p class="error-message">
            We couldn't find a website configured for this domain. This could happen if:
        </p>
        
        <div class="suggestions">
            <h5>💡 Possible Solutions:</h5>
            <ul>
                <li>The domain hasn't been set up yet with our system</li>
                <li>There might be a typo in the domain name</li>
                <li>The website might have been temporarily disabled</li>
                <li>DNS settings might not be properly configured</li>
            </ul>
        </div>
        
        <div class="mt-4">
            <a href="/" class="btn-home">
                🏠 Go to Main Site
            </a>
        </div>
        
        <div class="mt-3">
            <small class="text-muted">
                If you believe this is an error, please contact the site administrator.
            </small>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
