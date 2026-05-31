@extends('layouts.admin')

<link rel="stylesheet" href="{{ asset('css/admin-style.css') }}">

@section('admin_content')
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center">
            <i class="fa-solid fa-circle-check fs-5 me-2 text-success"></i>
            <span class="fw-bold text-success">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center">
            <i class="fa-solid fa-triangle-exclamation fs-5 me-2 text-danger"></i>
            <span class="fw-bold text-danger">{{ session('error') }}</span>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1" style="font-family: 'Kantumruy Pro', sans-serif;">គ្រប់គ្រងទិន្នន័យគ្រាប់ពូជ</h4>
            <p class="text-muted small mb-0">បន្ថែម កែប្រែ ឬលុបទិន្នន័យគ្រាប់ពូជកសិកម្មក្នុងប្រព័ន្ធ</p>
        </div>
        <a href="{{ url('/admin/seeds/create') }}" class="btn btn-success fw-bold rounded-pill px-4 shadow-sm">
             <i class="fa-solid fa-plus me-2"></i>បន្ថែមគ្រាប់ពូជថ្មី
        </a>
    </div>

    <div class="card admin-main-card border-0 shadow-sm mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle admin-table admin-dashboard-table mb-0" style="font-size: 14px;">
                    <thead>
                        <tr>
                            <th class="ps-4" width="10%">រូបភាព</th>
                            <th>ឈ្មោះគ្រាប់ពូជ (ខ្មែរ/EN)</th>
                            <th class="text-center" width="15%">លេខបាច់ និង QR ស្លាក</th>
                            <th>ប្រភេទ (Category)</th>
                            <th>តម្លៃ (Price)</th>
                            <th>ស្តុក (Stock)</th>
                            <th class="text-center" width="15%">សកម្មភាព</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($seeds as $seed)
                        <tr>
                            <td class="ps-4">
                                <img src="{{ $seed->image ? asset('uploads/seeds/' . $seed->image) : 'https://via.placeholder.com/50' }}" class="seed-img-thumbnail border" alt="Seed" style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px;">
                            </td>

                            <td>
                                <span class="fw-bold d-block text-dark mb-0">{{ $seed->name_kh }}</span>
                                <small class="text-muted" style="font-size: 12px;">{{ $seed->name_en }}</small>
                            </td>

                            <td class="text-center">
                                @if($seed->batch_number)
                                    @php
                                        $traceUrl = url('/trace?batch_number=' . $seed->batch_number);
                                        // 🟢 កែសម្រួល៖ បំប្លែង SVG ទៅជា Base64 string ដើម្បីកុំឱ្យជាន់គ្នាក្នុង JavaScript ទោះបីកូដវែងប៉ុណ្ណាក៏ដើរគ្រឹបៗ
                                        $qrSvg = QrCode::size(140)->margin(1)->color(25, 135, 84)->generate($traceUrl);
                                        $base64Qr = base64_encode($qrSvg);
                                    @endphp
                                    <div class="d-flex flex-column align-items-center gap-1">
                                        <div class="p-1 bg-white border rounded d-inline-block shadow-sm"
                                             title="ចុចដើម្បីព្រីនស្លាក QR"
                                             style="cursor: pointer;"
                                             onclick="printQrLabel('{{ $seed->batch_number }}', '{{ $base64Qr }}', '{{ addslashes($seed->name_kh) }}')">
                                            {!! QrCode::size(45)->margin(1)->color(25, 135, 84)->generate($traceUrl) !!}
                                        </div>
                                        <span class="badge bg-light text-dark border font-monospace px-2 py-0.5 mt-1" style="font-size: 11px;">
                                            {{ $seed->batch_number }}
                                        </span>
                                    </div>
                                @else
                                    <span class="text-muted small italic text-secondary">មិនទាន់មានលេខបាច់</span>
                                @endif
                            </td>

                            <td class="text-secondary fw-bold small">{{ $seed->category?->name_kh ?? 'មិនមាន' }}</td>

                            <td class="text-danger fw-bold fs-6 font-monospace">${{ number_format($seed->price, 2) }}</td>

                            <td>
                                @if($seed->stock > 0)
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-20 py-1.5 px-3 rounded-pill">{{ $seed->stock }} កញ្ចប់</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-20 py-1.5 px-3 rounded-pill">អស់ពីស្តុក</span>
                                @endif
                            </td>

                            <td>
                                <div class="d-flex justify-content-center align-items-center gap-2">
                                    <a href="{{ url('/admin/traceability/show/' . ($seed->traceability?->id ?? $seed->id)) }}" class="admin-action-btn admin-action-btn-trace text-success shadow-sm" title="មើលព័ត៌មានកសិកម្មលម្អិត">
                                        <i class="fa-solid fa-file-shield"></i>
                                    </a>

                                    <a href="{{ url('/admin/seeds/edit/' . $seed->id) }}" class="admin-action-btn admin-action-btn-edit text-primary shadow-sm" title="កែប្រែ">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>

                                    <form action="{{ url('/admin/seeds/delete/' . $seed->id) }}" method="POST" id="delete-seed-form-{{ $seed->id }}" class="m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="admin-action-btn admin-action-btn-delete shadow-sm btn-delete-seed" data-id="{{ $seed->id }}" title="លុបគ្រាប់ពូជ">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <div class="p-3 bg-light rounded-circle d-inline-flex mb-2 text-muted">
                                    <i class="fa-solid fa-folder-open display-6"></i>
                                </div>
                                <h6 class="fw-bold text-secondary">មិនទាន់មានទិន្នន័យគ្រាប់ពូជនៅឡើយទេ</h6>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.btn-delete-seed').forEach(button => {
                button.addEventListener('click', function() {
                    const seedId = this.getAttribute('data-id');

                    Swal.fire({
                        title: 'តើអ្នកពិតជាចង់លុបមែនទេ?',
                        text: "ទិន្នន័យគ្រាប់ពូជនេះនឹងត្រូវបាត់បង់ ហើយមិនអាចទាញត្រឡប់មកវិញបានឡើយ!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: '<i class="fa-solid fa-trash me-1"></i> យល់ព្រមលុប',
                        cancelButtonText: 'បោះបង់'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById(`delete-seed-form-${seedId}`).submit();
                        }
                    });
                });
            });
        });

        /**
         * 🖨️ មុខងារពិសេស៖ ទាញយកទិន្នន័យ Base64 មកបកប្រែជា SVG រត់ព្រីនបានយ៉ាងលឿន Offline 100%
         */
        function printQrLabel(batchNumber, base64Qr, seedName) {
            // បកប្រែពី Base64 ត្រឡប់មកជាកូដ SVG វិញធម្មតា
            const qrSvgString = atob(base64Qr);
            const printWindow = window.open('', '_blank', 'width=450,height=450');
            printWindow.document.write(`
                <html>
                <head>
                    <title>Print QR Label - ${batchNumber}</title>
                    <style>
                        @import url('https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;700&display=swap');
                        body { font-family: 'Kantumruy Pro', sans-serif; text-align: center; padding: 20px; color: #333; background: #f9f9f9; }
                        .label-box { border: 2px dashed #198754; padding: 20px; border-radius: 12px; display: inline-block; background: #fff; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
                        .title { font-weight: bold; font-size: 18px; margin-bottom: 5px; color: #198754; }
                        .batch { font-family: monospace; font-size: 14px; color: #333; margin-top: 8px; font-weight: bold; background: #f0fbf4; padding: 4px 10px; border-radius: 4px; display: inline-block; border: 1px solid #d1f2dc; }
                        .info { font-size: 11px; color: #777; margin-top: 10px; line-height: 1.4; }
                        .qr-container { margin: 15px 0; }
                        .qr-container svg { width: 140px; height: 140px; }
                    </style>
                </head>
                <body>
                    <div class="label-box">
                        <div class="title">Grow2Growth Agri-Tech</div>
                        <div style="font-size: 14px; font-weight: 700; color: #495057; margin-bottom: 5px;">គ្រាប់ពូជ៖ ${seedName}</div>

                        <div class="qr-container">${qrSvgString}</div>

                        <div class="batch">លេខបាច់៖ ${batchNumber}</div>
                        <div class="info">ស្កេនដើម្បីពិនិត្យមើលប្រភពដើម<br>ការថែទាំ និងបច្ចេកទេសដាំដុះវិទ្យាសាស្ត្រ</div>
                    </div>
                    <script>
                        window.onload = function() {
                            setTimeout(function() {
                                window.print();
                                window.close();
                            }, 300);
                        }
                    <\/script>
                </body>
                </html>
            `);
            printWindow.document.close();
        }
    </script>
@endsection
