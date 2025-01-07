@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Informasi Meja -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-white py-3">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">Detail Meja</h5>
                        </div>
                        <div class="col text-end">
                            <a href="{{ route('admin.tables.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td class="fw-bold" width="40%">Nomor Meja</td>
                            <td>Meja #{{ $table->table_number }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Kapasitas</td>
                            <td>{{ $table->capacity }} orang</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Status</td>
                            <td>
                                @if($table->status === 'available')
                                    <span class="badge bg-success">Tersedia</span>
                                @elseif($table->status === 'reserved')
                                    <span class="badge bg-warning text-dark">Direservasi</span>
                                @else
                                    <span class="badge bg-danger">Tidak Tersedia</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Kalender Reservasi -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white py-3">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">Kalender Reservasi</h5>
                        </div>
                        <div class="col-auto">
                            <div class="btn-group">
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="prevMonth">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="currentMonth">
                                    Bulan Ini
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="nextMonth">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Timeline Reservasi -->
<div class="modal fade" id="timelineModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Timeline Reservasi - <span id="selectedDate"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="timeline-container">
                    <!-- Timeline akan diisi melalui JavaScript -->
                    <div id="reservationTimeline"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet'>
<style>
    /* Styling untuk kalender */
    #calendar {
        height: 600px;
    }
    .fc-daygrid-day.has-reservations {
        background-color: rgba(254, 161, 22, 0.1);
    }
    .fc-daygrid-day.has-reservations:hover {
        background-color: rgba(254, 161, 22, 0.2);
        cursor: pointer;
    }
    
    /* Styling untuk timeline */
    .timeline-container {
        position: relative;
        padding: 20px 0;
    }
    .timeline-container::before {
        content: '';
        position: absolute;
        left: 50px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e9ecef;
    }
    .timeline-item {
        position: relative;
        margin-left: 70px;
        background: #f8f9fa;
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -20px;
        top: 50%;
        width: 20px;
        height: 2px;
        background: #e9ecef;
    }
    .timeline-time {
        position: absolute;
        left: -70px;
        top: 50%;
        transform: translateY(-50%);
        font-weight: bold;
        color: #6c757d;
    }
    .timeline-status {
        position: absolute;
        right: 1rem;
        top: 1rem;
    }
</style>
@endpush

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi FullCalendar
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: false,
        firstDay: 1,
        locale: 'id',
        dayMaxEvents: true,
        events: function(info, successCallback, failureCallback) {
            // Ambil data reservasi dari server
            fetch(`/admin/tables/{{ $table->id }}/reservations?start=${info.startStr}&end=${info.endStr}`)
                .then(response => response.json())
                .then(data => {
                    // Tandai hari yang memiliki reservasi
                    const events = data.map(reservation => ({
                        title: `${reservation.user.name} (${reservation.guest_count} orang)`,
                        start: reservation.reservation_date,
                        classNames: ['d-none'], // Sembunyikan event title
                        display: 'background',
                        backgroundColor: 'rgba(254, 161, 22, 0.1)'
                    }));
                    successCallback(events);

                    // Tambahkan class ke hari yang memiliki reservasi
                    data.forEach(reservation => {
                        const date = reservation.reservation_date;
                        const el = document.querySelector(`[data-date="${date}"]`);
                        if (el) {
                            el.classList.add('has-reservations');
                        }
                    });
                });
        },
        dateClick: function(info) {
            // Tampilkan modal timeline saat tanggal diklik
            showTimelineModal(info.dateStr);
        }
    });
    calendar.render();

    // Navigasi kalender
    document.getElementById('prevMonth').addEventListener('click', () => {
        calendar.prev();
    });
    document.getElementById('currentMonth').addEventListener('click', () => {
        calendar.today();
    });
    document.getElementById('nextMonth').addEventListener('click', () => {
        calendar.next();
    });

    // Fungsi untuk menampilkan modal timeline
    function showTimelineModal(date) {
        const modal = new bootstrap.Modal(document.getElementById('timelineModal'));
        document.getElementById('selectedDate').textContent = new Date(date).toLocaleDateString('id-ID', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        // Ambil data reservasi untuk tanggal yang dipilih
        fetch(`/admin/tables/{{ $table->id }}/reservations/${date}`)
            .then(response => response.json())
            .then(data => {
                const timelineContainer = document.getElementById('reservationTimeline');
                timelineContainer.innerHTML = '';

                if (data.length === 0) {
                    timelineContainer.innerHTML = '<div class="text-center text-muted py-4">Tidak ada reservasi pada tanggal ini</div>';
                    return;
                }

                // Urutkan reservasi berdasarkan waktu
                data.sort((a, b) => a.start_time.localeCompare(b.start_time));

                // Buat timeline untuk setiap reservasi
                data.forEach(reservation => {
                    let startTime = '-';
                    let endTime = '-';
                    
                    try {
                        if (reservation.start_time) {
                            // Format waktu dari ISO string ke format jam:menit
                            startTime = reservation.start_time.split('T')[1].substring(0, 5);
                        }
                        
                        if (reservation.end_time) {
                            // Format waktu dari ISO string ke format jam:menit
                            endTime = reservation.end_time.split('T')[1].substring(0, 5);
                        }
                    } catch (error) {
                        console.error('Error parsing time:', error);
                    }

                    const statusBadge = getStatusBadge(reservation.status);

                    const timelineItem = document.createElement('div');
                    timelineItem.className = 'timeline-item';
                    timelineItem.innerHTML = `
                        <div class="timeline-time">${startTime}</div>
                        <div class="timeline-status">${statusBadge}</div>
                        <h6 class="mb-1">${reservation.user.name}</h6>
                        <p class="mb-1">Kode: ${reservation.reservation_code}</p>
                        <p class="mb-1">Waktu: ${startTime} - ${endTime}</p>
                        <p class="mb-0">Jumlah Tamu: ${reservation.guest_count} orang</p>
                        ${reservation.notes ? `<p class="mb-0 text-muted small">Catatan: ${reservation.notes}</p>` : ''}
                    `;
                    timelineContainer.appendChild(timelineItem);
                });
            });

        modal.show();
    }

    // Fungsi untuk mendapatkan badge status
    function getStatusBadge(status) {
        const badges = {
            'pending': '<span class="badge bg-warning text-dark">Menunggu Konfirmasi</span>',
            'approved': '<span class="badge bg-success">Disetujui</span>',
            'completed': '<span class="badge bg-info">Selesai</span>',
            'rejected': '<span class="badge bg-danger">Ditolak</span>',
            'cancelled': '<span class="badge bg-secondary">Dibatalkan</span>'
        };
        return badges[status] || '';
    }
});
</script>
@endpush
@endsection 