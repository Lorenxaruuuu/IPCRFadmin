@extends('admin.layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
@yield('content')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IPCRF Admin Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        
        .glass-panel {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .sidebar-gradient {
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
        }
        
        .nav-item {
            transition: all 0.3s ease;
        }
        
        .nav-item:hover, .nav-item.active {
            background: rgba(255, 255, 255, 0.1);
            border-left: 4px solid #3b82f6;
        }
        
        .gradient-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .card-hover {
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }
        
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .priority-high { background: #fee2e2; color: #dc2626; }
        .priority-medium { background: #fef3c7; color: #d97706; }
        .priority-low { background: #dbeafe; color: #2563eb; }
        
        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .step-indicator {
            position: relative;
        }
        
        .step-indicator::after {
            content: '';
            position: absolute;
            top: 50%;
            right: -50%;
            width: 100%;
            height: 2px;
            background: #e5e7eb;
            z-index: 0;
        }
        
        .step-indicator:last-child::after {
            display: none;
        }
        
        .step-active {
            background: #3b82f6;
            color: white;
        }
        
        .step-completed {
            background: #10b981;
            color: white;
        }
    </style>
<div class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="sidebar-gradient w-64 flex-shrink-0 text-white flex flex-col">
            <div class="p-6 border-b border-gray-700">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-chart-line text-xl"></i>
                    </div>
                    <div>
                        <h1 class="font-bold text-lg">IPCRF Admin</h1>
                        <p class="text-xs text-gray-400">Management System</p>
                    </div>
                </div>
            </div>
            
            <nav class="flex-1 py-6">
                <a href="#" onclick="showView('dashboard')" class="nav-item active flex items-center gap-3 px-6 py-3 text-sm" id="nav-dashboard">
                    <i class="fas fa-home w-5"></i>
                    Dashboard Home
                </a>
                <a href="#" onclick="showView('upload')" class="nav-item flex items-center gap-3 px-6 py-3 text-sm" id="nav-upload">
                    <i class="fas fa-upload w-5"></i>
                    Update/Upload IPCRF
                </a>
                <a href="#" onclick="showView('records')" class="nav-item flex items-center gap-3 px-6 py-3 text-sm" id="nav-records">
                    <i class="fas fa-list w-5"></i>
                    List of Uploaded
                </a>
                <a href="#" onclick="showView('notices')" class="nav-item flex items-center gap-3 px-6 py-3 text-sm" id="nav-notices">
                    <i class="fas fa-bell w-5"></i>
                    Manage Notices
                </a>
                <a href="#" onclick="showView('forms')" class="nav-item flex items-center gap-3 px-6 py-3 text-sm" id="nav-forms">
                    <i class="fas fa-file-alt w-5"></i>
                    Manage Forms
                </a>
            </nav>
            
            <div class="p-4 border-t border-gray-700">
                <div class="flex items-center gap-3 px-2">
                    <img src="https://ui-avatars.com/api/?name=Admin+User&background=3b82f6&color=fff" class="w-10 h-10 rounded-full">
                    <div class="flex-1">
                        <p class="text-sm font-medium">Administrator</p>
                        <p class="text-xs text-gray-400">admin@deped.gov.ph</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-auto">
            <!-- Top Header -->
            <header class="glass-panel sticky top-0 z-40 px-8 py-4 flex justify-between items-center border-b">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800" id="page-title">Admin Dashboard Overview</h2>
                    <p class="text-sm text-gray-500">Manage IPCRF records and system announcements</p>
                </div>
                <div class="flex items-center gap-4">
                    <button onclick="showAlert()" class="relative p-2 text-gray-600 hover:text-gray-800">
                        <i class="fas fa-bell text-xl"></i>
                        <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>
                    <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </button>
                </div>
            </header>

            <div class="p-8">
                <!-- DASHBOARD VIEW -->
                <div id="view-dashboard" class="view-section fade-in">
                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="glass-panel rounded-2xl p-6 card-hover border-l-4 border-blue-500">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-gray-500 text-sm mb-1">Total IPCRF Uploaded</p>
                                    <h3 class="text-3xl font-bold text-gray-800">1,248</h3>
                                </div>
                                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-file-alt text-blue-600 text-xl"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="glass-panel rounded-2xl p-6 card-hover border-l-4 border-green-500">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-gray-500 text-sm mb-1">Active Forms</p>
                                    <h3 class="text-3xl font-bold text-gray-800">1</h3>
                                </div>
                                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="glass-panel rounded-2xl p-6 card-hover border-l-4 border-orange-500">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-gray-500 text-sm mb-1">Notices</p>
                                    <h3 class="text-3xl font-bold text-gray-800">12</h3>
                                </div>
                                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-bell text-orange-600 text-xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Recent Submissions -->
                        <div class="lg:col-span-2 glass-panel rounded-2xl p-6">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-lg font-bold text-gray-800">Recent IPCRF Submissions</h3>
                                <button onclick="showView('records')" class="text-blue-600 text-sm hover:underline">View All</button>
                            </div>
                            <p class="text-xs text-gray-500 mb-4">Latest records from regional encoders</p>
                            
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead>
                                        <tr class="text-left text-xs text-gray-500 border-b">
                                            <th class="pb-3 font-medium">Employee</th>
                                            <th class="pb-3 font-medium">Region</th>
                                            <th class="pb-3 font-medium">Date Uploaded</th>
                                            <th class="pb-3 font-medium">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-sm">
                                        <tr class="border-b border-gray-100">
                                            <td class="py-3 font-medium">Juan Dela Cruz</td>
                                            <td class="py-3 text-gray-600">Davao City</td>
                                            <td class="py-3 text-gray-600">February 19, 2026</td>
                                            <td class="py-3"><span class="status-badge bg-green-100 text-green-700">Verified</span></td>
                                        </tr>
                                        <tr class="border-b border-gray-100">
                                            <td class="py-3 font-medium">Maria Santos</td>
                                            <td class="py-3 text-gray-600">Cebu City</td>
                                            <td class="py-3 text-gray-600">February 18, 2026</td>
                                            <td class="py-3"><span class="status-badge bg-yellow-100 text-yellow-700">Pending</span></td>
                                        </tr>
                                        <tr class="border-b border-gray-100">
                                            <td class="py-3 font-medium">Pedro Reyes</td>
                                            <td class="py-3 text-gray-600">Manila</td>
                                            <td class="py-3 text-gray-600">February 17, 2026</td>
                                            <td class="py-3"><span class="status-badge bg-green-100 text-green-700">Verified</span></td>
                                        </tr>
                                        <tr class="border-b border-gray-100">
                                            <td class="py-3 font-medium">Ana Lim</td>
                                            <td class="py-3 text-gray-600">Davao City</td>
                                            <td class="py-3 text-gray-600">February 16, 2026</td>
                                            <td class="py-3"><span class="status-badge bg-blue-100 text-blue-700">Saved</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Latest Announcements -->
                        <div class="glass-panel rounded-2xl p-6">
                            <h3 class="text-lg font-bold text-gray-800 mb-1">Latest Announcements</h3>
                            <p class="text-xs text-gray-500 mb-4">Broadcasted to all users</p>
                            
                            <div class="space-y-4">
                                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                                    <div class="flex justify-between items-start mb-2">
                                        <h4 class="font-semibold text-sm">Deadline Extension</h4>
                                        <span class="status-badge priority-high">High</span>
                                    </div>
                                    <p class="text-xs text-gray-600 mb-2">The deadline for 1st Semester IPCRF has been extended to March 15.</p>
                                    <p class="text-xs text-gray-400">Posted on Feb 19, 2026</p>
                                </div>
                                
                                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                                    <div class="flex justify-between items-start mb-2">
                                        <h4 class="font-semibold text-sm">System Maintenance</h4>
                                        <span class="status-badge priority-medium">Medium</span>
                                    </div>
                                    <p class="text-xs text-gray-600 mb-2">Scheduled maintenance on Feb 25, 2026 at 2:00 AM.</p>
                                    <p class="text-xs text-gray-400">Posted on Feb 18, 2026</p>
                                </div>
                            </div>
                            
                            <button onclick="showView('notices')" class="w-full mt-4 text-blue-600 text-sm font-medium hover:underline">
                                View All Notices
                            </button>
                        </div>
                    </div>
                </div>

                <!-- UPLOAD/UPDATE IPCRF VIEW -->
                <div id="view-upload" class="view-section hidden fade-in">
                    <div class="max-w-4xl mx-auto">
                        <!-- Progress Steps -->
                        <div class="flex justify-between mb-8 px-4">
                            <div class="step-indicator flex-1 text-center" id="step-1">
                                <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center mx-auto mb-2 font-semibold">1</div>
                                <p class="text-sm font-medium">Select Role</p>
                            </div>
                            <div class="step-indicator flex-1 text-center" id="step-2">
                                <div class="w-10 h-10 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center mx-auto mb-2 font-semibold">2</div>
                                <p class="text-sm font-medium">Upload Form</p>
                            </div>
                            <div class="step-indicator flex-1 text-center" id="step-3">
                                <div class="w-10 h-10 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center mx-auto mb-2 font-semibold">3</div>
                                <p class="text-sm font-medium">Confirmation</p>
                            </div>
                        </div>

                        <!-- Step 1: Select Role -->
                        <div id="upload-step-1" class="glass-panel rounded-2xl p-8">
                            <h3 class="text-xl font-bold mb-6">Select Role to Update IPCRF</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <button onclick="selectRole('Teacher')" class="p-6 border-2 border-gray-200 rounded-xl hover:border-blue-500 hover:bg-blue-50 transition text-left group">
                                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4 group-hover:bg-blue-200">
                                        <i class="fas fa-chalkboard-teacher text-blue-600 text-xl"></i>
                                    </div>
                                    <h4 class="font-bold text-lg mb-1">Teacher</h4>
                                    <p class="text-sm text-gray-500">Update IPCRF for teaching staff</p>
                                </button>
                                
                                <button onclick="selectRole('Master Teacher')" class="p-6 border-2 border-gray-200 rounded-xl hover:border-blue-500 hover:bg-blue-50 transition text-left group">
                                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4 group-hover:bg-purple-200">
                                        <i class="fas fa-user-tie text-purple-600 text-xl"></i>
                                    </div>
                                    <h4 class="font-bold text-lg mb-1">Master Teacher</h4>
                                    <p class="text-sm text-gray-500">Update IPCRF for master teachers</p>
                                </button>
                                
                                <button onclick="selectRole('Principal')" class="p-6 border-2 border-gray-200 rounded-xl hover:border-blue-500 hover:bg-blue-50 transition text-left group">
                                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4 group-hover:bg-green-200">
                                        <i class="fas fa-school text-green-600 text-xl"></i>
                                    </div>
                                    <h4 class="font-bold text-lg mb-1">Principal</h4>
                                    <p class="text-sm text-gray-500">Update IPCRF for school heads</p>
                                </button>
                                
                                <button onclick="selectRole('Supervisor')" class="p-6 border-2 border-gray-200 rounded-xl hover:border-blue-500 hover:bg-blue-50 transition text-left group">
                                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mb-4 group-hover:bg-orange-200">
                                        <i class="fas fa-users-cog text-orange-600 text-xl"></i>
                                    </div>
                                    <h4 class="font-bold text-lg mb-1">Supervisor</h4>
                                    <p class="text-sm text-gray-500">Update IPCRF for supervisors</p>
                                </button>
                            </div>
                        </div>

                        <!-- Step 2: Upload Form -->
                        <div id="upload-step-2" class="glass-panel rounded-2xl p-8 hidden">
                            <div class="flex items-center gap-2 mb-6">
                                <button onclick="prevStep()" class="text-gray-500 hover:text-gray-700">
                                    <i class="fas fa-arrow-left"></i>
                                </button>
                                <h3 class="text-xl font-bold">Upload IPCRF Form - <span id="selected-role" class="text-blue-600"></span></h3>
                            </div>
                            
                            <form id="uploadForm" class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Employee Name</label>
                                        <input type="text" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Search employee...">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Employee ID</label>
                                        <input type="text" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., 2024-00123">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Province</label>
                                        <select class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500">
                                            <option>Select Province</option>
                                            <option>Davao del Sur</option>
                                            <option>Cebu</option>
                                            <option>Metro Manila</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Municipality</label>
                                        <select class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500">
                                            <option>Select Municipality</option>
                                            <option>Davao City</option>
                                            <option>Digos City</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">School</label>
                                        <select class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500">
                                            <option>Select School</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Semester</label>
                                        <select class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500">
                                            <option>1st Semester</option>
                                            <option>2nd Semester</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">School Year</label>
                                        <select class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500">
                                            <option>2025-2026</option>
                                            <option>2024-2025</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-blue-500 transition cursor-pointer bg-gray-50">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                                    <p class="text-gray-600 mb-1">Click to upload or drag and drop</p>
                                    <p class="text-sm text-gray-400">PDF, Excel files up to 10MB</p>
                                    <input type="file" class="hidden" accept=".pdf,.xlsx,.xls">
                                </div>

                                <div class="flex gap-4">
                                    <button type="button" onclick="prevStep()" class="flex-1 px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition">Back</button>
                                    <button type="button" onclick="nextStep()" class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Continue</button>
                                </div>
                            </form>
                        </div>

                        <!-- Step 3: Confirmation -->
                        <div id="upload-step-3" class="glass-panel rounded-2xl p-8 hidden text-center">
                            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-check text-green-600 text-3xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold mb-2">IPCRF Successfully Updated!</h3>
                            <p class="text-gray-600 mb-6">The IPCRF form has been uploaded and saved to the system.</p>
                            
                            <div class="bg-gray-50 rounded-lg p-4 max-w-md mx-auto mb-6 text-left">
                                <div class="flex justify-between py-2 border-b border-gray-200">
                                    <span class="text-gray-600">Employee:</span>
                                    <span class="font-medium">Juan Dela Cruz</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-200">
                                    <span class="text-gray-600">Role:</span>
                                    <span class="font-medium" id="confirm-role">Teacher</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-200">
                                    <span class="text-gray-600">Region:</span>
                                    <span class="font-medium">Davao City</span>
                                </div>
                                <div class="flex justify-between py-2">
                                    <span class="text-gray-600">Status:</span>
                                    <span class="status-badge bg-green-100 text-green-700">Saved to Drive</span>
                                </div>
                            </div>
                            
                            <div class="flex gap-4 justify-center">
                                <button onclick="resetUpload()" class="px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition">Upload Another</button>
                                <button onclick="showView('records')" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">View Records</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RECORDS DATABASE VIEW -->
                <div id="view-records" class="view-section hidden fade-in">
                    <div class="glass-panel rounded-2xl p-6">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                            <div>
                                <h3 class="text-xl font-bold text-gray-800">IPCRF Records Database</h3>
                                <p class="text-sm text-gray-500">Manage and download uploaded IPCRF forms</p>
                            </div>
                            <button onclick="downloadReport()" class="bg-blue-900 text-white px-6 py-2 rounded-lg hover:bg-blue-800 transition flex items-center gap-2">
                                <i class="fas fa-download"></i>
                                Download Report
                            </button>
                        </div>

                        <!-- Filters -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase">Province</label>
                                <select id="filter-province" onchange="filterRecords()" class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm">
                                    <option value="">All Provinces</option>
                                    <option value="davao">Davao del Sur</option>
                                    <option value="cebu">Cebu</option>
                                    <option value="manila">Metro Manila</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase">Municipality</label>
                                <select id="filter-municipality" onchange="filterRecords()" class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm">
                                    <option value="">All Municipalities</option>
                                    <option value="davao-city">Davao City</option>
                                    <option value="digos">Digos City</option>
                                    <option value="cebu-city">Cebu City</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase">Semester</label>
                                <select class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm">
                                    <option>All Semesters</option>
                                    <option>1st Semester</option>
                                    <option>2nd Semester</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase">Year</label>
                                <select class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm">
                                    <option>2026</option>
                                    <option>2025</option>
                                    <option>2024</option>
                                </select>
                            </div>
                        </div>

                        <!-- Records Table -->
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="text-left border-b-2 border-gray-200">
                                        <th class="pb-3 font-semibold text-sm text-gray-700">Employee</th>
                                        <th class="pb-3 font-semibold text-sm text-gray-700">Region</th>
                                        <th class="pb-3 font-semibold text-sm text-gray-700">Date Uploaded</th>
                                        <th class="pb-3 font-semibold text-sm text-gray-700">Status</th>
                                        <th class="pb-3 font-semibold text-sm text-gray-700">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="records-table-body" class="text-sm">
                                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                                        <td class="py-4 font-medium">Juan Dela Cruz</td>
                                        <td class="py-4 text-gray-600">Davao City</td>
                                        <td class="py-4 text-gray-600">February 19, 2026</td>
                                        <td class="py-4"><span class="status-badge bg-green-100 text-green-700">Saved to Drive</span></td>
                                        <td class="py-4">
                                            <button class="text-gray-400 hover:text-blue-600 transition">
                                                <i class="fas fa-download text-lg"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                                        <td class="py-4 font-medium">Maria Santos</td>
                                        <td class="py-4 text-gray-600">Cebu City</td>
                                        <td class="py-4 text-gray-600">February 18, 2026</td>
                                        <td class="py-4"><span class="status-badge bg-green-100 text-green-700">Saved to Drive</span></td>
                                        <td class="py-4">
                                            <button class="text-gray-400 hover:text-blue-600 transition">
                                                <i class="fas fa-download text-lg"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                                        <td class="py-4 font-medium">Pedro Reyes</td>
                                        <td class="py-4 text-gray-600">Manila</td>
                                        <td class="py-4 text-gray-600">February 17, 2026</td>
                                        <td class="py-4"><span class="status-badge bg-green-100 text-green-700">Saved to Drive</span></td>
                                        <td class="py-4">
                                            <button class="text-gray-400 hover:text-blue-600 transition">
                                                <i class="fas fa-download text-lg"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="flex justify-between items-center mt-6 pt-4 border-t">
                            <p class="text-sm text-gray-500">Showing 1-10 of 1,248 records</p>
                            <div class="flex gap-2">
                                <button class="px-3 py-1 border rounded hover:bg-gray-50 text-sm">Previous</button>
                                <button class="px-3 py-1 bg-blue-600 text-white rounded text-sm">1</button>
                                <button class="px-3 py-1 border rounded hover:bg-gray-50 text-sm">2</button>
                                <button class="px-3 py-1 border rounded hover:bg-gray-50 text-sm">3</button>
                                <button class="px-3 py-1 border rounded hover:bg-gray-50 text-sm">Next</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- NOTICES/ANNOUNCEMENTS VIEW -->
                <div id="view-notices" class="view-section hidden fade-in">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Create Notice Form -->
                        <div class="glass-panel rounded-2xl p-6">
                            <h3 class="text-xl font-bold mb-1">Create New Notice</h3>
                            <p class="text-sm text-gray-500 mb-6">Post announcements for all users</p>
                            
                            <form id="noticeForm" onsubmit="postNotice(event)" class="space-y-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase">Subject</label>
                                    <input type="text" id="notice-subject" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500" placeholder="e.g., Deadline Extension" required>
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-2 uppercase">Priority Level</label>
                                    <div class="flex gap-3">
                                        <label class="flex-1 cursor-pointer">
                                            <input type="radio" name="priority" value="low" class="hidden peer">
                                            <div class="text-center py-2 border-2 rounded-lg peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 hover:bg-gray-50 transition text-sm font-medium">
                                                LOW
                                            </div>
                                        </label>
                                        <label class="flex-1 cursor-pointer">
                                            <input type="radio" name="priority" value="medium" class="hidden peer">
                                            <div class="text-center py-2 border-2 rounded-lg peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 hover:bg-gray-50 transition text-sm font-medium">
                                                MEDIUM
                                            </div>
                                        </label>
                                        <label class="flex-1 cursor-pointer">
                                            <input type="radio" name="priority" value="high" class="hidden peer" checked>
                                            <div class="text-center py-2 border-2 rounded-lg peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 hover:bg-gray-50 transition text-sm font-medium">
                                                HIGH
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase">Content</label>
                                    <textarea id="notice-content" rows="5" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 resize-none" placeholder="Write your message here..." required></textarea>
                                </div>
                                
                                <button type="submit" class="w-full bg-blue-900 text-white py-3 rounded-lg hover:bg-blue-800 transition font-medium">
                                    Post Announcement
                                </button>
                            </form>
                        </div>

                        <!-- Active Announcements -->
                        <div class="glass-panel rounded-2xl p-6">
                            <h3 class="text-xl font-bold mb-6">Active Announcements</h3>
                            
                            <div id="announcements-list" class="space-y-4">
                                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 relative group">
                                    <div class="flex justify-between items-start mb-2">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-info text-blue-600"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-gray-800">Deadline Extension</h4>
                                                <p class="text-xs text-gray-500">Posted on 25/02/2026</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="status-badge priority-high">High</span>
                                            <button onclick="deleteNotice(this)" class="text-gray-400 hover:text-red-500 opacity-0 group-hover:opacity-100 transition">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600 ml-13 pl-13">The deadline for the 1st Semester IPCRF has been extended to March 15.</p>
                                </div>

                                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 relative group">
                                    <div class="flex justify-between items-start mb-2">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-tools text-purple-600"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-gray-800">System Maintenance</h4>
                                                <p class="text-xs text-gray-500">Posted on 24/02/2026</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="status-badge priority-medium">Medium</span>
                                            <button onclick="deleteNotice(this)" class="text-gray-400 hover:text-red-500 opacity-0 group-hover:opacity-100 transition">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600">Scheduled maintenance on February 25, 2026 at 2:00 AM. System may be unavailable.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- MANAGE FORMS VIEW -->
                <div id="view-forms" class="view-section hidden fade-in">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Upload New Form -->
                        <div class="glass-panel rounded-2xl p-6">
                            <h3 class="text-xl font-bold mb-1">Upload New Form</h3>
                            <p class="text-sm text-gray-500 mb-6">Add documents for encoders to download</p>
                            
                            <form class="space-y-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase">Form Title</label>
                                    <input type="text" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500" placeholder="e.g., IPCRF Template 2025">
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase">Category</label>
                                    <select class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500">
                                        <option>Template</option>
                                        <option>Guidelines</option>
                                        <option>Reference</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase">Description</label>
                                    <textarea rows="3" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 resize-none" placeholder="Briefly describe the form..."></textarea>
                                </div>
                                
                                <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-500 transition cursor-pointer bg-gray-50">
                                    <i class="fas fa-file-upload text-3xl text-gray-400 mb-2"></i>
                                    <p class="text-sm text-gray-600">Click to upload or drag and drop</p>
                                    <p class="text-xs text-gray-400">PDF, DOC, XLS files</p>
                                </div>
                                
                                <button type="button" class="w-full bg-blue-900 text-white py-3 rounded-lg hover:bg-blue-800 transition font-medium">
                                    Publish Form
                                </button>
                            </form>
                        </div>

                        <!-- Published Forms -->
                        <div class="glass-panel rounded-2xl p-6">
                            <h3 class="text-xl font-bold mb-6">Published Forms</h3>
                            
                            <div class="space-y-4">
                                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                                    <div class="flex justify-between items-start mb-3">
                                        <div class="flex items-center gap-3">
                                            <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-file-alt text-gray-600 text-xl"></i>
                                            </div>
                                            <div>
                                                <span class="inline-block px-2 py-1 bg-gray-200 text-gray-700 text-xs rounded mb-1">Template</span>
                                                <h4 class="font-bold text-gray-800">IPCRF Template 2024</h4>
                                                <p class="text-xs text-gray-500">Official template for 2024 encoding</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex justify-between items-center pt-3 border-t border-gray-200">
                                        <span class="text-xs text-gray-500">25/02/2026</span>
                                        <button class="text-blue-600 text-sm font-medium hover:underline flex items-center gap-1">
                                            <i class="fas fa-download"></i> Download
                                        </button>
                                    </div>
                                </div>

                                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                                    <div class="flex justify-between items-start mb-3">
                                        <div class="flex items-center gap-3">
                                            <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-file-pdf text-gray-600 text-xl"></i>
                                            </div>
                                            <div>
                                                <span class="inline-block px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded mb-1">Guidelines</span>
                                                <h4 class="font-bold text-gray-800">Encoding Guidelines</h4>
                                                <p class="text-xs text-gray-500">Step-by-step guide for encoders</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex justify-between items-center pt-3 border-t border-gray-200">
                                        <span class="text-xs text-gray-500">20/02/2026</span>
                                        <button class="text-blue-600 text-sm font-medium hover:underline flex items-center gap-1">
                                            <i class="fas fa-download"></i> Download
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Alert Modal -->
    <div id="alert-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="glass-panel rounded-2xl p-6 max-w-md w-full mx-4 transform scale-95 opacity-0 transition-all duration-300" id="alert-content">
            <div class="text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">System Alert</h3>
                <p class="text-gray-600 mb-6" id="alert-message">Please complete the required fields before proceeding.</p>
                <button onclick="closeAlert()" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition">
                    Acknowledge
                </button>
            </div>
        </div>
    </div>

    <script>
        let currentStep = 1;
        let selectedRole = '';

        function showView(viewName) {
            // Hide all views
            document.querySelectorAll('.view-section').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.nav-item').forEach(el => el.classList.remove('active'));
            
            // Show selected view
            document.getElementById(`view-${viewName}`).classList.remove('hidden');
            document.getElementById(`nav-${viewName}`).classList.add('active');
            
            // Update page title
            const titles = {
                'dashboard': 'Admin Dashboard Overview',
                'upload': 'Update/Upload IPCRF',
                'records': 'IPCRF Records Database',
                'notices': 'Regional Announcements',
                'forms': 'Manage Downloadable Forms'
            };
            document.getElementById('page-title').textContent = titles[viewName];
        }

        function selectRole(role) {
            selectedRole = role;
            document.getElementById('selected-role').textContent = role;
            document.getElementById('confirm-role').textContent = role;
            nextStep();
        }

        function nextStep() {
            if (currentStep < 3) {
                document.getElementById(`upload-step-${currentStep}`).classList.add('hidden');
                currentStep++;
                document.getElementById(`upload-step-${currentStep}`).classList.remove('hidden');
                updateStepIndicator();
            }
        }

        function prevStep() {
            if (currentStep > 1) {
                document.getElementById(`upload-step-${currentStep}`).classList.add('hidden');
                currentStep--;
                document.getElementById(`upload-step-${currentStep}`).classList.remove('hidden');
                updateStepIndicator();
            }
        }

        function updateStepIndicator() {
            for (let i = 1; i <= 3; i++) {
                const stepEl = document.getElementById(`step-${i}`).querySelector('div');
                if (i < currentStep) {
                    stepEl.className = 'w-10 h-10 rounded-full bg-green-500 text-white flex items-center justify-center mx-auto mb-2 font-semibold';
                    stepEl.innerHTML = '<i class="fas fa-check"></i>';
                } else if (i === currentStep) {
                    stepEl.className = 'w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center mx-auto mb-2 font-semibold';
                    stepEl.textContent = i;
                } else {
                    stepEl.className = 'w-10 h-10 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center mx-auto mb-2 font-semibold';
                    stepEl.textContent = i;
                }
            }
        }

        function resetUpload() {
            currentStep = 1;
            document.getElementById('upload-step-3').classList.add('hidden');
            document.getElementById('upload-step-1').classList.remove('hidden');
            updateStepIndicator();
            document.getElementById('uploadForm').reset();
        }

        function postNotice(e) {
            e.preventDefault();
            const subject = document.getElementById('notice-subject').value;
            const content = document.getElementById('notice-content').value;
            const priority = document.querySelector('input[name="priority"]:checked').value;
            
            const priorityClass = {
                'high': 'priority-high',
                'medium': 'priority-medium',
                'low': 'priority-low'
            };
            
            const priorityLabel = {
                'high': 'High',
                'medium': 'Medium',
                'low': 'Low'
            };
            
            const date = new Date().toLocaleDateString('en-GB');
            
            const noticeHTML = `
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 relative group fade-in">
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-info text-blue-600"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800">${subject}</h4>
                                <p class="text-xs text-gray-500">Posted on ${date}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="status-badge ${priorityClass[priority]}">${priorityLabel[priority]}</span>
                            <button onclick="deleteNotice(this)" class="text-gray-400 hover:text-red-500 opacity-0 group-hover:opacity-100 transition">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 ml-13 pl-13">${content}</p>
                </div>
            `;
            
            document.getElementById('announcements-list').insertAdjacentHTML('afterbegin', noticeHTML);
            document.getElementById('noticeForm').reset();
            
            showAlert('Announcement posted successfully!', 'success');
        }

        function deleteNotice(btn) {
            if (confirm('Are you sure you want to delete this announcement?')) {
                btn.closest('.bg-gray-50').remove();
            }
        }

        function filterRecords() {
            // Simulate filtering - in real app, this would filter the table data
            const province = document.getElementById('filter-province').value;
            const municipality = document.getElementById('filter-municipality').value;
            
            if (province && !municipality) {
                showAlert('Please select a municipality to generate report', 'warning');
            }
        }

        function downloadReport() {
            const province = document.getElementById('filter-province').value;
            const municipality = document.getElementById('filter-municipality').value;
            
            if (!province || !municipality) {
                showAlert('Please select both Province and Municipality to download report', 'warning');
                return;
            }
            
            showAlert('Report download started...', 'success');
        }

        function showAlert(message = 'Please complete the required fields before proceeding.', type = 'warning') {
            const modal = document.getElementById('alert-modal');
            const content = document.getElementById('alert-content');
            const msgEl = document.getElementById('alert-message');
            const iconEl = modal.querySelector('i');
            const bgEl = modal.querySelector('.w-16');
            
            msgEl.textContent = message;
            
            if (type === 'success') {
                iconEl.className = 'fas fa-check-circle text-green-600 text-2xl';
                bgEl.className = 'w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4';
            } else {
                iconEl.className = 'fas fa-exclamation-triangle text-red-600 text-2xl';
                bgEl.className = 'w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4';
            }
            
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeAlert() {
            const modal = document.getElementById('alert-modal');
            const content = document.getElementById('alert-content');
            
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 300);
        }

        // Close modal on outside click
        document.getElementById('alert-modal').addEventListener('click', function(e) {
            if (e.target === this) closeAlert();
        });
    </script>
@endsection
