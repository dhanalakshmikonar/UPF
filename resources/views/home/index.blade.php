@extends('layouts.erp')

@section('content')

    <style>
        /* Modern Container */
        .dashboard-container {
            max-width: 1300px;
            margin: 0 auto;
            padding: 30px 20px;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        .dashboard-header {
            margin-bottom: 2.5rem;
            color: #111827;
            font-weight: 800;
            font-size: 2.25rem;
            letter-spacing: -0.025em;
        }

        body.dark-mode .dashboard-header {
            color: #f9fafb;
        }

        .alert-custom {
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border: none;
            font-weight: 500;
        }

        .category-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 24px;
        }

        .category-card {
            background-color: #ffffff;
            border-radius: 16px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            display: flex;
            flex-direction: column;
        }

        body.dark-mode .category-card {
            background-color: #1f2937;
            border-color: #374151;
        }

        .category-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            border-color: #d1d5db;
        }

        body.dark-mode .category-card:hover {
            border-color: #4b5563;
        }

        .card-header-icon {
            padding: 24px 24px 16px;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .icon-wrapper {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .icon-blue { background: #eff6ff; color: #3b82f6; }
        .icon-pink { background: #fdf2f8; color: #ec4899; }
        .icon-green { background: #ecfdf5; color: #10b981; }
        .icon-orange { background: #fff7ed; color: #f97316; }
        .icon-purple { background: #f5f3ff; color: #8b5cf6; }
        .icon-cyan { background: #ecfeff; color: #06b6d4; }
        .icon-yellow { background: #fefce8; color: #eab308; }
        .icon-fuchsia { background: #fdf4ff; color: #d946ef; }

        body.dark-mode .icon-blue { background: rgba(59, 130, 246, 0.2); }
        body.dark-mode .icon-pink { background: rgba(236, 72, 153, 0.2); }
        body.dark-mode .icon-green { background: rgba(16, 185, 129, 0.2); }
        body.dark-mode .icon-orange { background: rgba(249, 115, 22, 0.2); }
        body.dark-mode .icon-purple { background: rgba(139, 92, 246, 0.2); }
        body.dark-mode .icon-cyan { background: rgba(6, 182, 212, 0.2); }
        body.dark-mode .icon-yellow { background: rgba(234, 179, 8, 0.2); }
        body.dark-mode .icon-fuchsia { background: rgba(217, 70, 239, 0.2); }

        .card-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #111827;
            margin: 0;
        }

        body.dark-mode .card-title {
            color: #f9fafb;
        }

        .card-body {
            padding: 0 24px 24px;
            flex-grow: 1;
        }

        .card-description {
            color: #6b7280;
            font-size: 0.875rem;
            margin-bottom: 0;
            line-height: 1.5;
        }

        body.dark-mode .card-description {
            color: #9ca3af;
        }

        .card-footer {
            padding: 16px 24px;
            background-color: #f9fafb;
            border-top: 1px solid #e5e7eb;
            display: flex;
            gap: 8px;
            align-items: center;
        }

        body.dark-mode .card-footer {
            background-color: #374151;
            border-top-color: #4b5563;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 8px 12px;
            font-size: 0.875rem;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.2s;
            text-decoration: none;
            border: 1px solid transparent;
            cursor: pointer;
            flex: 1;
        }

        .btn-view {
            background-color: #eff6ff;
            color: #1d4ed8;
            border-color: transparent;
        }

        .btn-view:hover {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .btn-upload {
            background-color: #ffffff;
            color: #374151;
            border-color: #d1d5db;
        }

        .btn-upload:hover {
            background-color: #f3f4f6;
            color: #111827;
        }

        .btn-delete {
            background-color: #fef2f2;
            color: #b91c1c;
            border-color: transparent;
            flex: 0 0 auto;
            padding: 8px 12px;
        }

        .btn-delete:hover {
            background-color: #fee2e2;
            color: #991b1b;
        }

        body.dark-mode .btn-view {
            background-color: rgba(59, 130, 246, 0.2);
            color: #bfdbfe;
        }

        body.dark-mode .btn-view:hover {
            background-color: rgba(59, 130, 246, 0.3);
        }

        body.dark-mode .btn-upload {
            background-color: #4b5563;
            color: #f9fafb;
            border-color: #6b7280;
        }

        body.dark-mode .btn-upload:hover {
            background-color: #6b7280;
            border-color: #9ca3af;
        }

        body.dark-mode .btn-delete {
            background-color: rgba(239, 68, 68, 0.2);
            color: #fca5a5;
        }

        body.dark-mode .btn-delete:hover {
            background-color: rgba(239, 68, 68, 0.3);
        }

        .upload-input {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        .upload-form {
            margin: 0;
            display: flex;
            flex: 1;
        }

        .clear-form {
            margin: 0;
            display: flex;
            flex: 0 0 auto;
        }

        .upload-note {
            display: block;
            margin-top: 10px;
            color: #6b7280;
            font-size: 0.75rem;
        }

        body.dark-mode .upload-note {
            color: #9ca3af;
        }
    </style>

    <div class="dashboard-container">
        <h3 class="dashboard-header">Data Management Center</h3>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show alert-custom mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show alert-custom mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show alert-custom mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ $errors->first('document') ?? $errors->first() }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="category-grid">

            @php
                $categories = [
                    ['id' => 'boys-hostel', 'title' => 'Boys Hostel', 'icon' => 'bi-building', 'color' => 'blue', 'route' => 'home.boys'],
                    ['id' => 'girls-hostel', 'title' => 'Girls Hostel', 'icon' => 'bi-building-fill', 'color' => 'pink', 'route' => 'home.girls'],
                    ['id' => 'oam', 'title' => 'Old Age Men', 'icon' => 'bi-people', 'color' => 'green', 'route' => 'home.oam'],
                    ['id' => 'oaw', 'title' => 'Old Age Women', 'icon' => 'bi-people-fill', 'color' => 'orange', 'route' => 'home.oaw'],
                    ['id' => 'dam', 'title' => 'Disabled Men', 'icon' => 'bi-person-wheelchair', 'color' => 'purple', 'route' => 'home.dam'],
                    ['id' => 'daw', 'title' => 'Disabled Women', 'icon' => 'bi-person-wheelchair', 'color' => 'cyan', 'route' => 'home.daw'],
                    ['id' => 'mr-mi-m', 'title' => 'MR & MI Men', 'icon' => 'bi-person-badge', 'color' => 'yellow', 'route' => 'home.mrim'],
                    ['id' => 'mr-mi-w', 'title' => 'MR & MI Women', 'icon' => 'bi-person-vcard', 'color' => 'fuchsia', 'route' => 'home.mriw']
                ];
            @endphp

            @foreach($categories as $category)
            <div class="category-card">
                <div class="card-header-icon">
                    <div class="icon-wrapper icon-{{ $category['color'] }}">
                        <i class="bi {{ $category['icon'] }}"></i>
                    </div>
                    <h4 class="card-title">{{ $category['title'] }}</h4>
                </div>

                <div class="card-body">
                    <p class="card-description">Manage residents and administrative data for the {{ $category['title'] }} division.</p>
                    <span class="upload-note">Upload a `.csv` or `.xlsx` file with the first row as headings.</span>
                </div>

                <div class="card-footer">
                    <a href="{{ route($category['route']) }}" class="btn-action btn-view">
                        <i class="bi bi-table"></i> Data
                    </a>

                    <form action="{{ route('home.document.store', $category['id']) }}" method="POST" enctype="multipart/form-data" class="upload-form">
                        @csrf
                        <button type="button" class="btn-action btn-upload m-0 w-100" onclick="openUpload(this)">
                            <i class="bi bi-cloud-arrow-up"></i> Upload
                        </button>
                        <input
                            type="file"
                            name="document"
                            class="upload-input"
                            accept=".xlsx,.csv,.txt"
                            onchange="submitUpload(this)"
                        >
                    </form>

                    <form action="{{ route('home.document.clear', $category['id']) }}" method="POST" class="clear-form" onsubmit="return confirm('Are you sure you want to delete all uploaded data for {{ $category['title'] }}?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-action btn-delete m-0" title="Clear all data">
                            <i class="bi bi-trash3"></i>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach

        </div>
    </div>

    <script>
        function openUpload(button) {
            const form = button.closest('form');

            if (!form) {
                return;
            }

            const input = form.querySelector('input[type="file"][name="document"]');

            if (!input) {
                return;
            }

            input.click();
        }

        function submitUpload(input) {
            if (!input.files || !input.files.length) {
                return;
            }

            const form = input.form;
            const btn = form.querySelector('.btn-upload');

            if (btn) {
                btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Uploading';
                btn.style.pointerEvents = 'none';
            }

            form.submit();
        }
    </script>

@endsection
