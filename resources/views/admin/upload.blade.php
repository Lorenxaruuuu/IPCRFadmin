@extends('admin.layouts.admin')

@section('title', 'Upload IPCRF')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload IPCRF</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --secondary: #8b5cf6;
            --accent: #a855f7;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #06b6d4;
            --dark: #1e1b4b;
            --darker: #0f0a1e;
            --card-bg: rgba(30, 27, 75, 0.6);
            --glass: rgba(255, 255, 255, 0.05);
            --border: rgba(139, 92, 246, 0.2);
            --text: #e0e7ff;
            --text-muted: #94a3b8;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, var(--darker) 0%, var(--dark) 100%);
            min-height: 100vh;
            color: var(--text);
            overflow-x: hidden;
        }

        .bg-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }

        .bg-animation::before {
            content: '';
            position: absolute;
            width: 150%;
            height: 150%;
            background: radial-gradient(circle at 20% 80%, rgba(99, 102, 241, 0.15) 0%, transparent 50%),
                        radial-gradient(circle at 80% 20%, rgba(168, 85, 247, 0.15) 0%, transparent 50%);
            animation: float 20s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            50% { transform: translate(-30px, -30px) rotate(5deg); }
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 20px;
            background: var(--glass);
            backdrop-filter: blur(10px);
            border: 1px solid var(--border);
            border-radius: 16px;
        }

        .back-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            color: var(--text);
        }

        .page-title h1 {
            font-size: 24px;
            font-weight: 700;
            background: linear-gradient(135deg, #fff, #c7d2fe);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .page-title p {
            color: var(--text-muted);
            font-size: 14px;
            margin-top: 4px;
        }

        .form-card {
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .form-section {
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title::before {
            content: '';
            width: 4px;
            height: 20px;
            background: linear-gradient(to bottom, var(--primary), var(--secondary));
            border-radius: 2px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-label {
            font-size: 13px;
            font-weight: 500;
            color: var(--text-muted);
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-input, .form-select {
            padding: 14px 18px;
            background: var(--glass);
            border: 1px solid var(--border);
            border-radius: 12px;
            color: var(--text);
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-input:focus, .form-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .form-input::placeholder {
            color: var(--text-muted);
        }

        .form-select option {
            background: var(--dark);
            color: var(--text);
        }

        .role-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }

        .role-option {
            position: relative;
        }

        .role-option input[type="radio"] {
            position: absolute;
            opacity: 0;
        }

        .role-label {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 20px;
            background: var(--glass);
            border: 2px solid var(--border);
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .role-option input[type="radio"]:checked + .role-label {
            border-color: var(--primary);
            background: rgba(99, 102, 241, 0.15);
        }

        .role-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            transition: all 0.3s ease;
        }

        .role-icon.teacher { background: rgba(59, 130, 246, 0.2); color: #60a5fa; }
        .role-icon.master { background: rgba(139, 92, 246, 0.2); color: #a78bfa; }
        .role-icon.principal { background: rgba(16, 185, 129, 0.2); color: #34d399; }
        .role-icon.supervisor { background: rgba(245, 158, 11, 0.2); color: #fbbf24; }

        .role-option input[type="radio"]:checked + .role-label .role-icon {
            transform: scale(1.1);
        }

        .role-info h4 {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 2px;
        }

        .role-info p {
            font-size: 12px;
            color: var(--text-muted);
        }

        .file-upload {
            border: 2px dashed var(--border);
            border-radius: 16px;
            padding: 50px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
        }

        .file-upload:hover {
            border-color: var(--primary);
            background: rgba(99, 102, 241, 0.05);
        }

        .file-upload input[type="file"] {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .file-upload-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 28px;
            box-shadow: 0 8px 30px rgba(99, 102, 241, 0.3);
        }

        .file-upload h3 {
            font-size: 18px;
            margin-bottom: 8px;
        }

        .file-upload p {
            color: var(--text-muted);
            font-size: 14px;
        }

        .file-types {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 15px;
        }

        .file-type {
            padding: 6px 12px;
            background: var(--glass);
            border-radius: 20px;
            font-size: 12px;
            color: var(--text-muted);
        }

        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            padding-top: 30px;
            border-top: 1px solid var(--border);
        }

        .btn {
            flex: 1;
            padding: 16px 30px;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
        }

        .btn-secondary {
            background: var(--glass);
            color: var(--text);
            border: 1px solid var(--border);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            box-shadow: 0 4px 20px rgba(99, 102, 241, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(99, 102, 241, 0.5);
        }

        .alert {
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #f87171;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #34d399;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .role-grid {
                grid-template-columns: 1fr;
            }
            
            .form-actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="bg-animation"></div>
    
    <div class="container">
        <header class="header">
            <a href="{{ route('admin.dashboard') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i>
                Back to Dashboard
            </a>
            <div class="page-title" style="text-align: right;">
                <h1>Upload IPCRF</h1>
                <p>Upload or update IPCRF forms</p>
            </div>
        </header>

        @if($errors->any())
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <div>
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
        @endif

        @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
        @endif

        <form action="{{ route('admin.upload.store') }}" method="POST" enctype="multipart/form-data" class="form-card">
            @csrf
            
            <div class="form-section">
                <h3 class="section-title">Select Role</h3>
                <div class="role-grid">
                    <div class="role-option">
                        <input type="radio" name="role" id="role-teacher" value="Teacher" checked>
                        <label for="role-teacher" class="role-label">
                            <div class="role-icon teacher">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                            <div class="role-info">
                                <h4>Teacher</h4>
                                <p>Teaching staff</p>
                            </div>
                        </label>
                    </div>
                    
                    <div class="role-option">
                        <input type="radio" name="role" id="role-master" value="Master Teacher">
                        <label for="role-master" class="role-label">
                            <div class="role-icon master">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div class="role-info">
                                <h4>Master Teacher</h4>
                                <p>Master teachers</p>
                            </div>
                        </label>
                    </div>
                    
                    <div class="role-option">
                        <input type="radio" name="role" id="role-principal" value="Principal">
                        <label for="role-principal" class="role-label">
                            <div class="role-icon principal">
                                <i class="fas fa-school"></i>
                            </div>
                            <div class="role-info">
                                <h4>Principal</h4>
                                <p>School heads</p>
                            </div>
                        </label>
                    </div>
                    
                    <div class="role-option">
                        <input type="radio" name="role" id="role-supervisor" value="Supervisor">
                        <label for="role-supervisor" class="role-label">
                            <div class="role-icon supervisor">
                                <i class="fas fa-users-cog"></i>
                            </div>
                            <div class="role-info">
                                <h4>Supervisor</h4>
                                <p>Supervisors</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3 class="section-title">Employee Information</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Employee Name</label>
                        <input type="text" name="employee_name" class="form-input" placeholder="Search employee..." required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Employee ID</label>
                        <input type="text" name="employee_id" class="form-input" placeholder="e.g., 2024-00123" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Province</label>
                        <select name="province_id" class="form-select" required onchange="loadMunicipalities(this.value)">
                            <option value="">Select Province</option>
                            @foreach($provinces as $province)
                                <option value="{{ $province->id }}">{{ $province->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Municipality</label>
                        <select name="municipality_id" class="form-select" required id="municipality-select">
                            <option value="">Select Municipality</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">School</label>
                        <select name="school_id" class="form-select" required>
                            <option value="">Select School</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Semester</label>
                        <select name="semester" class="form-select" required>
                            <option value="1st">1st Semester</option>
                            <option value="2nd">2nd Semester</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">School Year</label>
                        <select name="school_year" class="form-select" required>
                            <option value="2025-2026">2025-2026</option>
                            <option value="2024-2025">2024-2025</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3 class="section-title">Upload File</h3>
                <div class="file-upload">
                    <input type="file" name="file" accept=".pdf,.xlsx,.xls" required onchange="showFileName(this)">
                    <div class="file-upload-icon">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                    <h3 id="upload-text">Click to upload or drag and drop</h3>
                    <p>Maximum file size 10MB</p>
                    <div class="file-types">
                        <span class="file-type">PDF</span>
                        <span class="file-type">Excel</span>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-upload"></i>
                    Upload IPCRF
                </button>
            </div>
        </form>
    </div>

    <script>
// Add to upload.blade.php script section
function loadMunicipalities(provinceId) {
    if (!provinceId) return;
    
    fetch(`/admin/api/provinces/${provinceId}/municipalities`)
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('municipality-select');
            select.innerHTML = '<option value="">Select Municipality</option>';
            data.forEach(mun => {
                select.innerHTML += `<option value="${mun.id}">${mun.name}</option>`;
            });
            // Clear schools when province changes
            document.querySelector('select[name="school_id"]').innerHTML = '<option value="">Select School</option>';
        });
}

// Add this new function
document.getElementById('municipality-select').addEventListener('change', function() {
    const municipalityId = this.value;
    if (!municipalityId) return;
    
    fetch(`/admin/api/municipalities/${municipalityId}/schools`)
        .then(response => response.json())
        .then(data => {
            const select = document.querySelector('select[name="school_id"]');
            select.innerHTML = '<option value="">Select School</option>';
            data.forEach(school => {
                select.innerHTML += `<option value="${school.id}">${school.name}</option>`;
            });
        });
});

        function showFileName(input) {
            if (input.files && input.files[0]) {
                document.getElementById('upload-text').textContent = input.files[0].name;
            }
        }
    </script>
</body>
</html>
@endsection