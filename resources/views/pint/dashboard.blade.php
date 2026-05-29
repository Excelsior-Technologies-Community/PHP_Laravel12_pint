<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laravel Pint Dashboard - Code Formatter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .code-block {
            background: #1e1e1e;
            color: #d4d4d4;
            padding: 15px;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            overflow-x: auto;
        }
        .status-badge {
            font-size: 0.9rem;
            padding: 8px 15px;
        }
        .animate-spin {
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .file-item {
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .file-item:hover {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">
            <span class="navbar-brand">
                <i class="bi bi-brush"></i> Laravel Pint Dashboard
            </span>
            <span class="text-white">
                <i class="bi bi-code-slash"></i> PSR-12 Standard
            </span>
        </div>
    </nav>

    <div class="container">
        <!-- Stats Cards -->
        <div class="row mb-4" id="statsSection">
            <div class="col-md-3 mb-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-file-code"></i> PHP Files</h5>
                        <h2 id="totalFiles">-</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-layout-text-window"></i> Lines of Code</h5>
                        <h2 id="totalLines">-</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-hdd-stack"></i> Total Size</h5>
                        <h2 id="totalSize">-</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-warning text-dark" id="qualityCard">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-shield-check"></i> Code Quality</h5>
                        <h2 id="qualityStatus">-</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title mb-3"><i class="bi bi-tools"></i> Actions</h5>
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <button class="btn btn-danger w-100" onclick="checkCode()" id="checkBtn">
                            <i class="bi bi-search"></i> Check Code Quality
                        </button>
                    </div>
                    <div class="col-md-4 mb-2">
                        <button class="btn btn-success w-100" onclick="fixCode()" id="fixBtn">
                            <i class="bi bi-magic"></i> Auto-Fix All Code
                        </button>
                    </div>
                    <div class="col-md-4 mb-2">
                        <button class="btn btn-info w-100" onclick="createTestFile()" id="testFileBtn">
                            <i class="bi bi-file-plus"></i> Create Test File
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Code Samples -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-danger text-white">
                        <i class="bi bi-emoji-frown"></i> Before Pint (Bad Code)
                    </div>
                    <div class="card-body">
                        <div class="code-block">
                            <pre id="badCodeSample">{{ $badCodeSample }}</pre>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-success text-white">
                        <i class="bi bi-emoji-smile"></i> After Pint (Clean Code)
                    </div>
                    <div class="card-body">
                        <div class="code-block">
                            <pre id="cleanCodeSample">{{ $cleanCodeSample }}</pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results Section -->
        <div class="card" id="resultsCard" style="display: none;">
            <div class="card-header">
                <i class="bi bi-terminal"></i> Results
                <button class="btn btn-sm btn-secondary float-end" onclick="clearResults()">Clear</button>
            </div>
            <div class="card-body">
                <div id="results"></div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Load stats on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadStats();
            setInterval(loadStats, 30000); // Refresh stats every 30 seconds
        });

        function loadStats() {
            fetch('/pint/stats')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('totalFiles').textContent = data.total_php_files;
                    document.getElementById('totalLines').textContent = data.total_lines_of_code.toLocaleString();
                    document.getElementById('totalSize').textContent = data.total_size_kb + ' KB';
                    
                    const qualityStatus = document.getElementById('qualityStatus');
                    if (data.code_quality_status === 'clean') {
                        qualityStatus.innerHTML = '<span class="badge bg-success">✅ Clean</span>';
                    } else {
                        qualityStatus.innerHTML = '<span class="badge bg-danger">⚠️ Needs Formatting</span>';
                    }
                })
                .catch(error => console.error('Error loading stats:', error));
        }

        function checkCode() {
            const checkBtn = document.getElementById('checkBtn');
            const originalHtml = checkBtn.innerHTML;
            checkBtn.innerHTML = '<i class="bi bi-hourglass-split animate-spin"></i> Checking...';
            checkBtn.disabled = true;

            fetch('/pint/check', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                showResults(data.message, data.output, data.success ? 'success' : 'danger');
                if (data.files_with_issues && data.files_with_issues.length > 0) {
                    showFileList(data.files_with_issues);
                }
            })
            .catch(error => {
                showResults('Error checking code', error.toString(), 'danger');
            })
            .finally(() => {
                checkBtn.innerHTML = originalHtml;
                checkBtn.disabled = false;
                loadStats();
            });
        }

        function fixCode() {
            const fixBtn = document.getElementById('fixBtn');
            const originalHtml = fixBtn.innerHTML;
            fixBtn.innerHTML = '<i class="bi bi-hourglass-split animate-spin"></i> Fixing...';
            fixBtn.disabled = true;

            fetch('/pint/fix', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                showResults(data.message, data.output, data.success ? 'success' : 'warning');
                if (data.success) {
                    setTimeout(() => loadStats(), 1000);
                }
            })
            .catch(error => {
                showResults('Error fixing code', error.toString(), 'danger');
            })
            .finally(() => {
                fixBtn.innerHTML = originalHtml;
                fixBtn.disabled = false;
            });
        }

        function createTestFile() {
            const testBtn = document.getElementById('testFileBtn');
            const originalHtml = testBtn.innerHTML;
            testBtn.innerHTML = '<i class="bi bi-hourglass-split animate-spin"></i> Creating...';
            testBtn.disabled = true;

            fetch('/pint/create-test-file', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                showResults('Test File Created', 
                    `File created at: ${data.file}\n\nRun "Fix Code" to format it!`, 
                    'info');
                loadStats();
            })
            .catch(error => {
                showResults('Error creating test file', error.toString(), 'danger');
            })
            .finally(() => {
                testBtn.innerHTML = originalHtml;
                testBtn.disabled = false;
            });
        }

        function showResults(title, content, type) {
            const resultsCard = document.getElementById('resultsCard');
            const resultsDiv = document.getElementById('results');
            
            const alertClass = `alert alert-${type}`;
            const icon = type === 'success' ? 'bi-check-circle' : (type === 'danger' ? 'bi-x-circle' : 'bi-info-circle');
            
            resultsDiv.innerHTML = `
                <div class="${alertClass}">
                    <h5><i class="${icon}"></i> ${title}</h5>
                    <pre class="mb-0 mt-2" style="white-space: pre-wrap; word-wrap: break-word;">${escapeHtml(content)}</pre>
                </div>
            `;
            
            resultsCard.style.display = 'block';
            resultsCard.scrollIntoView({ behavior: 'smooth' });
        }

        function showFileList(files) {
            const resultsDiv = document.getElementById('results');
            let fileListHtml = '<div class="mt-3"><strong>Files with issues:</strong><ul class="list-group mt-2">';
            
            files.forEach(file => {
                const statusClass = file.status === 'fixed' ? 'list-group-item-success' : 'list-group-item-danger';
                fileListHtml += `<li class="list-group-item ${statusClass}">${file.file}</li>`;
            });
            
            fileListHtml += '</ul></div>';
            resultsDiv.innerHTML += fileListHtml;
        }

        function clearResults() {
            document.getElementById('resultsCard').style.display = 'none';
            document.getElementById('results').innerHTML = '';
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    </script>
</body>
</html>