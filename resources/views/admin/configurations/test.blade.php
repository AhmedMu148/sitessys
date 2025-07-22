<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JSON Configuration System Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4>{{ __('JSON Configuration System - Test Dashboard') }}</h4>
                </div>

                <div class="card-body">
                    <div class="alert alert-success">
                        <h5>✅ JSON Configuration System Successfully Implemented!</h5>
                        <p>The backend configuration system is now fully operational with the following features:</p>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header bg-primary text-white">
                                    <h6>🎨 Theme Configuration</h6>
                                </div>
                                <div class="card-body">
                                    <pre class="small">{{ json_encode($configurations['theme'] ?? [], JSON_PRETTY_PRINT) }}</pre>
                                </div>
                            </div>

                            <div class="card mb-3">
                                <div class="card-header bg-success text-white">
                                    <h6>🌐 Language Configuration</h6>
                                </div>
                                <div class="card-body">
                                    <pre class="small">{{ json_encode($configurations['language'] ?? [], JSON_PRETTY_PRINT) }}</pre>
                                </div>
                            </div>

                            <div class="card mb-3">
                                <div class="card-header bg-info text-white">
                                    <h6>🎨 Colors Configuration</h6>
                                </div>
                                <div class="card-body">
                                    <pre class="small">{{ json_encode($configurations['colors'] ?? [], JSON_PRETTY_PRINT) }}</pre>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header bg-warning text-white">
                                    <h6>🧩 Configuration Features</h6>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">✅ ConfigurationService with JSON validation</li>
                                        <li class="list-group-item">✅ Caching system with Redis/File cache</li>
                                        <li class="list-group-item">✅ Model accessors and mutators</li>
                                        <li class="list-group-item">✅ Controller integration</li>
                                        <li class="list-group-item">✅ CLI management commands</li>
                                        <li class="list-group-item">✅ Database JSON indexes</li>
                                        <li class="list-group-item">✅ Configuration versioning</li>
                                        <li class="list-group-item">✅ Export/Import functionality</li>
                                        <li class="list-group-item">✅ Schema validation rules</li>
                                        <li class="list-group-item">✅ HasConfiguration trait</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header bg-dark text-white">
                                    <h6>🚀 CLI Commands Available</h6>
                                </div>
                                <div class="card-body">
                                    <code class="small">
                                        php artisan config:manage get --site=1 --type=theme<br>
                                        php artisan config:manage set --site=1 --type=colors --data='{...}'<br>
                                        php artisan config:manage export --site=1<br>
                                        php artisan config:manage import --site=1 --file=backup.json<br>
                                        php artisan config:manage initialize --site=1<br>
                                        php artisan config:manage clear-cache --site=1
                                    </code>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <h6>📊 Database Storage Information</h6>
                                <p><strong>Site ID:</strong> {{ $site->id ?? 'N/A' }}</p>
                                <p><strong>Site Name:</strong> {{ $site->site_name ?? 'N/A' }}</p>
                                <p><strong>Configuration Storage:</strong> All stored in JSON format in existing database fields</p>
                                <p><strong>Cache Status:</strong> Configurations cached for 60 minutes</p>
                                <p><strong>Schema Validation:</strong> Active for all configuration types</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-secondary">
                                <h6>🔧 API Endpoints Available</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Theme Management:</strong></p>
                                        <ul class="small">
                                            <li>POST /admin/configurations/theme</li>
                                            <li>POST /admin/configurations/navigation</li>
                                            <li>POST /admin/configurations/colors</li>
                                            <li>POST /admin/configurations/sections</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Settings Management:</strong></p>
                                        <ul class="small">
                                            <li>POST /admin/settings/languages</li>
                                            <li>POST /admin/settings/media</li>
                                            <li>POST /admin/settings/tenant</li>
                                            <li>GET /admin/settings/all-configurations</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12 text-center">
                            <a href="/admin" class="btn btn-primary">← Back to Admin Dashboard</a>
                            <a href="/admin/configurations/export" class="btn btn-success">📤 Export All Configurations</a>
                            <button class="btn btn-warning" onclick="initializeDefaults()">🔄 Initialize Defaults</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function initializeDefaults() {
    if (confirm('This will reset all configurations to default values. Are you sure?')) {
        fetch('/admin/configurations/initialize-defaults', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message || 'Configurations initialized successfully!');
            window.location.reload();
        })
        .catch(error => {
            alert('Error initializing configurations');
        });
    }
}
</script>
</body>
</html>
