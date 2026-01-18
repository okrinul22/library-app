@extends('admin.layout')

@section('title', 'Dashboard - Admin')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        <div>
            <button class="btn btn-sm btn-secondary shadow-sm" onclick="generateReport('pdf')">
                <i class="fas fa-file-pdf fa-sm text-white-50"></i> Export PDF
            </button>
            <button class="btn btn-sm btn-secondary shadow-sm" onclick="generateReport('excel')">
                <i class="fas fa-file-excel fa-sm text-white-50"></i> Export Excel
            </button>
        </div>
    </div>

    <!-- Content Row (Stats) -->
    <div class="row">

        <!-- Total Users Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card primary border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total User</div>
                            <div class="h5 mb-0 font-weight-bold text-white" id="totalUsers">-</div>
                        </div>
                        <div class="col-auto">
                            <div class="stats-icon primary">
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Books Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card success border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Buku</div>
                            <div class="h5 mb-0 font-weight-bold text-white" id="totalBooks">-</div>
                        </div>
                        <div class="col-auto">
                            <div class="stats-icon success">
                                <i class="fas fa-book fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Free Books Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card info border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Buku
                                Gratis</div>
                            <div class="h5 mb-0 font-weight-bold text-white" id="freeBooks">-</div>
                        </div>
                        <div class="col-auto">
                            <div class="stats-icon" style="background: rgba(79, 70, 229, 0.1); color: var(--primary-color);">
                                <i class="fas fa-gift fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Paid Books Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card warning border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Buku Berbayar</div>
                            <div class="h5 mb-0 font-weight-bold text-white" id="paidBooks">-</div>
                        </div>
                        <div class="col-auto">
                            <div class="stats-icon warning">
                                <i class="fas fa-dollar-sign fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Revenue Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card success border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Revenue</div>
                            <div class="h5 mb-0 font-weight-bold text-white" id="totalRevenue">-</div>
                        </div>
                        <div class="col-auto">
                            <div class="stats-icon success">
                                <i class="fas fa-wallet fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Content Row (Charts) -->
    <div class="row">

        <!-- Revenue Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold">Revenue</h6>
                    <div class="dropdown no-arrow">
                        <select id="revenuePeriod" class="form-select form-select-sm" onchange="loadRevenueChart()" style="width: auto;">
                            <option value="monthly">Bulanan</option>
                            <option value="weekly">Mingguan</option>
                            <option value="yearly">Tahunan</option>
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Book Status Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold">Status Buku</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="bookStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Content Row (Genre Distribution) -->
    <div class="row">

        <!-- Genre Distribution Chart -->
        <div class="col-lg-12 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold">Distribusi Genre</h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar">
                        <canvas id="genreChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
<!-- /.container-fluid -->
@endsection

@push('scripts')
<script>
// Chart.js global config for dark theme
Chart.defaults.color = '#9CA3AF';
Chart.defaults.borderColor = '#374151';

async function loadStats() {
    try {
        const response = await apiRequest('/dashboard/stats');
        const stats = response.data || {};

        document.getElementById('totalUsers').textContent = stats.total_users || 0;
        document.getElementById('totalBooks').textContent = stats.total_books || 0;
        document.getElementById('freeBooks').textContent = stats.free_books || 0;
        document.getElementById('paidBooks').textContent = stats.paid_books || 0;
        document.getElementById('totalRevenue').textContent = formatCurrency(stats.total_revenue || 0);
    } catch (error) {
        console.error('Error loading stats:', error);
        // Show default values when API fails
        document.getElementById('totalUsers').textContent = '0';
        document.getElementById('totalBooks').textContent = '0';
        document.getElementById('freeBooks').textContent = '0';
        document.getElementById('paidBooks').textContent = '0';
        document.getElementById('totalRevenue').textContent = formatCurrency(0);
    }
}

async function loadRevenueChart() {
    try {
        const period = document.getElementById('revenuePeriod').value;
        const response = await apiRequest(`/dashboard/revenue?period=${period}`);
        const data = response.data;

        const ctx = document.getElementById('revenueChart').getContext('2d');

        // Destroy existing chart if it exists
        if (window.revenueChartInstance) {
            window.revenueChartInstance.destroy();
        }

        window.revenueChartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.map(d => d.date || d.week || d.month || d.year),
                datasets: [{
                    label: 'Revenue',
                    data: data.map(d => d.revenue),
                    borderColor: '#4F46E5',
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#4F46E5',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: '#4F46E5'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1F2937',
                        titleColor: '#F9FAFB',
                        bodyColor: '#F9FAFB',
                        borderColor: '#374151',
                        borderWidth: 1
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(55, 65, 81, 0.5)'
                        },
                        ticks: {
                            color: '#9CA3AF',
                            callback: value => formatCurrency(value)
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#9CA3AF'
                        }
                    }
                }
            }
        });
    } catch (error) {
        console.error('Error loading revenue chart:', error);
        // Show empty chart when API fails
        const ctx = document.getElementById('revenueChart').getContext('2d');
        if (window.revenueChartInstance) {
            window.revenueChartInstance.destroy();
        }
        window.revenueChartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['No Data'],
                datasets: [{
                    label: 'Revenue',
                    data: [0],
                    borderColor: '#4F46E5',
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(55, 65, 81, 0.5)' }, ticks: { color: '#9CA3AF' } },
                    x: { grid: { display: false }, ticks: { color: '#9CA3AF' } }
                }
            }
        });
    }
}

async function loadBookStatusChart() {
    try {
        const response = await apiRequest('/dashboard/book-status');
        const stockStatus = response.data?.stock_status || { in_stock: 0, low_stock: 0, out_of_stock: 0 };

        const ctx = document.getElementById('bookStatusChart').getContext('2d');

        // Destroy existing chart if it exists
        if (window.bookStatusChartInstance) {
            window.bookStatusChartInstance.destroy();
        }

        window.bookStatusChartInstance = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['In Stock', 'Low Stock', 'Out of Stock'],
                datasets: [{
                    data: [
                        stockStatus.in_stock || 0,
                        stockStatus.low_stock || 0,
                        stockStatus.out_of_stock || 0
                    ],
                    backgroundColor: ['#10B981', '#F59E0B', '#EF4444'],
                    borderWidth: 2,
                    borderColor: '#1F2937'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#9CA3AF',
                            padding: 20,
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#1F2937',
                        titleColor: '#F9FAFB',
                        bodyColor: '#F9FAFB',
                        borderColor: '#374151',
                        borderWidth: 1
                    }
                }
            }
        });
    } catch (error) {
        console.error('Error loading book status chart:', error);
        // Show empty chart when API fails
        const ctx = document.getElementById('bookStatusChart').getContext('2d');
        if (window.bookStatusChartInstance) {
            window.bookStatusChartInstance.destroy();
        }
        window.bookStatusChartInstance = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['In Stock', 'Low Stock', 'Out of Stock'],
                datasets: [{
                    data: [0, 0, 0],
                    backgroundColor: ['#10B981', '#F59E0B', '#EF4444'],
                    borderWidth: 2,
                    borderColor: '#1F2937'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: '#9CA3AF', padding: 20, font: { size: 12 } }
                    },
                    tooltip: {
                        backgroundColor: '#1F2937',
                        titleColor: '#F9FAFB',
                        bodyColor: '#F9FAFB',
                        borderColor: '#374151',
                        borderWidth: 1
                    }
                }
            }
        });
    }
}

async function loadGenreChart() {
    try {
        const response = await apiRequest('/dashboard/book-status');
        const genres = response.data?.by_genre || [];

        const ctx = document.getElementById('genreChart').getContext('2d');

        // Destroy existing chart if it exists
        if (window.genreChartInstance) {
            window.genreChartInstance.destroy();
        }

        window.genreChartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: genres.length > 0 ? genres.map(g => g.genre || 'Unknown') : ['No Data'],
                datasets: [{
                    label: 'Jumlah Buku',
                    data: genres.length > 0 ? genres.map(g => g.count) : [0],
                    backgroundColor: '#4F46E5',
                    borderColor: '#4338CA',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1F2937',
                        titleColor: '#F9FAFB',
                        bodyColor: '#F9FAFB',
                        borderColor: '#374151',
                        borderWidth: 1
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(55, 65, 81, 0.5)'
                        },
                        ticks: {
                            color: '#9CA3AF',
                            precision: 0
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#9CA3AF'
                        }
                    }
                }
            }
        });
    } catch (error) {
        console.error('Error loading genre chart:', error);
        // Show empty chart when API fails
        const ctx = document.getElementById('genreChart').getContext('2d');
        if (window.genreChartInstance) {
            window.genreChartInstance.destroy();
        }
        window.genreChartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['No Data'],
                datasets: [{
                    label: 'Jumlah Buku',
                    data: [0],
                    backgroundColor: '#4F46E5',
                    borderColor: '#4338CA',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1F2937',
                        titleColor: '#F9FAFB',
                        bodyColor: '#F9FAFB',
                        borderColor: '#374151',
                        borderWidth: 1
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(55, 65, 81, 0.5)'
                        },
                        ticks: {
                            color: '#9CA3AF',
                            precision: 0
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#9CA3AF'
                        }
                    }
                }
            }
        });
    }
}

async function generateReport(type) {
    try {
        const startDate = new Date(new Date().setMonth(new Date().getMonth() - 1)).toISOString().split('T')[0];
        const endDate = new Date().toISOString().split('T')[0];

        if (type === 'pdf') {
            // Generate PDF with charts
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('p', 'mm', 'a4');
            const pageWidth = doc.internal.pageSize.getWidth();
            const pageHeight = doc.internal.pageSize.getHeight();
            let yPosition = 20;

            // Title
            doc.setFontSize(20);
            doc.setTextColor(79, 70, 229);
            doc.text('Library Management Report', pageWidth / 2, yPosition, { align: 'center' });
            yPosition += 15;

            // Report info
            doc.setFontSize(10);
            doc.setTextColor(100, 100, 100);
            doc.text(`Generated: ${new Date().toLocaleString('id-ID')}`, pageWidth / 2, yPosition, { align: 'center' });
            yPosition += 6;
            doc.text(`Period: ${startDate} to ${endDate}`, pageWidth / 2, yPosition, { align: 'center' });
            yPosition += 15;

            // Statistics
            doc.setFontSize(14);
            doc.setTextColor(0, 0, 0);
            doc.text('Statistics Summary', 15, yPosition);
            yPosition += 10;

            doc.setFontSize(11);
            doc.setTextColor(60, 60, 60);

            const stats = [
                ['Total Users', document.getElementById('totalUsers').textContent],
                ['Total Books', document.getElementById('totalBooks').textContent],
                ['Free Books', document.getElementById('freeBooks').textContent],
                ['Paid Books', document.getElementById('paidBooks').textContent],
                ['Total Revenue', document.getElementById('totalRevenue').textContent]
            ];

            stats.forEach(([label, value]) => {
                doc.text(`${label}:`, 15, yPosition);
                doc.text(value, 60, yPosition);
                yPosition += 7;
            });

            yPosition += 10;

            // Add Revenue Chart
            if (window.revenueChartInstance) {
                if (yPosition > 150) {
                    doc.addPage();
                    yPosition = 20;
                }

                doc.setFontSize(14);
                doc.setTextColor(0, 0, 0);
                doc.text('Revenue Chart', pageWidth / 2, yPosition, { align: 'center' });
                yPosition += 10;

                const revenueChartImg = window.revenueChartInstance.toBase64Image();
                doc.addImage(revenueChartImg, 'PNG', 15, yPosition, pageWidth - 30, 80);
                yPosition += 90;
            }

            // Add Book Status Chart
            if (window.bookStatusChartInstance) {
                if (yPosition > pageHeight - 100) {
                    doc.addPage();
                    yPosition = 20;
                }

                doc.setFontSize(14);
                doc.setTextColor(0, 0, 0);
                doc.text('Book Status Distribution', pageWidth / 2, yPosition, { align: 'center' });
                yPosition += 10;

                const statusChartImg = window.bookStatusChartInstance.toBase64Image();
                doc.addImage(statusChartImg, 'PNG', 50, yPosition, pageWidth - 100, 80);
                yPosition += 90;
            }

            // Add Genre Distribution Chart
            if (window.genreChartInstance) {
                if (yPosition > pageHeight - 100) {
                    doc.addPage();
                    yPosition = 20;
                }

                doc.setFontSize(14);
                doc.setTextColor(0, 0, 0);
                doc.text('Genre Distribution', pageWidth / 2, yPosition, { align: 'center' });
                yPosition += 10;

                const genreChartImg = window.genreChartInstance.toBase64Image();
                doc.addImage(genreChartImg, 'PNG', 15, yPosition, pageWidth - 30, 80);
                yPosition += 90;
            }

            // Add Genre Data Table
            if (yPosition > pageHeight - 80) {
                doc.addPage();
                yPosition = 20;
            }

            doc.setFontSize(14);
            doc.setTextColor(0, 0, 0);
            doc.text('Books by Genre', 15, yPosition);
            yPosition += 10;

            doc.setFontSize(10);
            doc.setTextColor(60, 60, 60);

            if (window.genreChartInstance) {
                const genres = window.genreChartInstance.data.labels;
                const counts = window.genreChartInstance.data.datasets[0].data;

                genres.forEach((genre, index) => {
                    doc.text(`${genre}:`, 15, yPosition);
                    doc.text(`${counts[index]} books`, 60, yPosition);
                    yPosition += 7;
                });
            }

            // Footer
            const pageCount = doc.internal.getNumberOfPages();
            for (let i = 1; i <= pageCount; i++) {
                doc.setPage(i);
                doc.setFontSize(9);
                doc.setTextColor(150, 150, 150);
                doc.text(`Page ${i} of ${pageCount}`, pageWidth / 2, pageHeight - 10, { align: 'center' });
                doc.text('Library Management System', pageWidth / 2, pageHeight - 5, { align: 'center' });
            }

            // Save PDF
            doc.save(`library_report_pdf_${new Date().toISOString().split('T')[0]}.pdf`);
            showAlert('Laporan PDF berhasil didownload', 'success');

        } else {
            // Generate proper Excel file with chart images and formatting
            const wb = XLSX.utils.book_new();

            // Report Info Sheet
            const reportInfo = [
                ['LIBRARY MANAGEMENT REPORT'],
                [''],
                ['Generated:', new Date().toLocaleString('id-ID')],
                ['Period:', `${startDate} to ${endDate}`],
                [''],
                ['STATISTICS SUMMARY'],
                [''],
                ['Metric', 'Value'],
                ['Total Users', document.getElementById('totalUsers').textContent],
                ['Total Books', document.getElementById('totalBooks').textContent],
                ['Free Books', document.getElementById('freeBooks').textContent],
                ['Paid Books', document.getElementById('paidBooks').textContent],
                ['Total Revenue', document.getElementById('totalRevenue').textContent]
            ];
            const wsInfo = XLSX.utils.aoa_to_sheet(reportInfo);
            wsInfo['!cols'] = [{ wch: 25 }, { wch: 30 }];
            XLSX.utils.book_append_sheet(wb, wsInfo, 'Report Summary');

            // Revenue Chart Sheet with Image
            if (window.revenueChartInstance) {
                const revenueData = [
                    ['REVENUE CHART WITH DIAGRAM'],
                    [''],
                    ['Period', 'Revenue (IDR)', 'Note']
                ];

                window.revenueChartInstance.data.labels.forEach((label, index) => {
                    const value = window.revenueChartInstance.data.datasets[0].data[index];
                    revenueData.push([label, value, 'Monthly Revenue Data']);
                });

                const wsRevenue = XLSX.utils.aoa_to_sheet(revenueData);
                wsRevenue['!cols'] = [{ wch: 20 }, { wch: 25 }, { wch: 30 }];

                // Add revenue chart image
                addChartImageToSheet(wsRevenue, window.revenueChartInstance, 20, 20, 12, 'Revenue Chart');
            }

            // Book Status Sheet with Image
            if (window.bookStatusChartInstance) {
                const statusData = [
                    ['BOOK STATUS WITH DIAGRAM'],
                    [''],
                    ['Status', 'Count', 'Description']
                ];

                window.bookStatusChartInstance.data.labels.forEach((label, index) => {
                    const value = window.bookStatusChartInstance.data.datasets[0].data[index];
                    const labels = {
                        'In Stock': 'Buku Tersedia (>5 stok)',
                        'Low Stock': 'Buku Sedikit (1-5 stok)',
                        'Out of Stock': 'Buku Habis'
                    };
                    statusData.push([labels[label] || label, value, 'Stock Status']);
                });

                const wsStatus = XLSX.utils.aoa_to_sheet(statusData);
                wsStatus['!cols'] = [{ wch: 25 }, { wch: 20 }, { wch: 30 }];

                // Add book status chart image
                addChartImageToSheet(wsStatus, window.bookStatusChartInstance, 25, 20, 12, 'Book Status');
            }

            // Genre Distribution Sheet with Image
            if (window.genreChartInstance) {
                const genreData = [
                    ['GENRE DISTRIBUTION WITH DIAGRAM'],
                    [''],
                    ['Genre', 'Total Books', 'Percentage']
                ];

                const totalCount = window.genreChartInstance.data.datasets[0].data.reduce((a, b) => a + b, 0);

                window.genreChartInstance.data.labels.forEach((label, index) => {
                    const count = window.genreChartInstance.data.datasets[0].data[index];
                    const percentage = totalCount > 0 ? ((count / totalCount) * 100).toFixed(2) + '%' : '0%';
                    genreData.push([label, count, percentage]);
                });

                const wsGenre = XLSX.utils.aoa_to_sheet(genreData);
                wsGenre['!cols'] = [{ wch: 25 }, { wch: 20 }, { wch: 20 }];

                // Add genre chart image
                addChartImageToSheet(wsGenre, window.genreChartInstance, 20, 20, 12, 'Genre Distribution');
            }

            // Detailed Statistics Sheet
            const detailData = [
                ['DETAILED STATISTICS'],
                [''],
                ['Category', 'Sub-Category', 'Value', 'Remarks']
            ];

            const totalUsers = parseInt(document.getElementById('totalUsers').textContent) || 0;
            const totalBooks = parseInt(document.getElementById('totalBooks').textContent) || 0;
            const freeBooks = parseInt(document.getElementById('freeBooks').textContent) || 0;
            const paidBooks = parseInt(document.getElementById('paidBooks').textContent) || 0;
            const totalRevenue = document.getElementById('totalRevenue').textContent || 'Rp 0';

            detailData.push(
                ['Users', 'Total Registered', totalUsers, 'Active user accounts'],
                ['Books', 'Total Books', totalBooks, 'All books in library'],
                ['Books', 'Free Books', freeBooks, 'Books available for free'],
                ['Books', 'Paid Books', paidBooks, 'Premium books'],
                ['Revenue', 'Total Revenue', totalRevenue, 'Total income from book sales']
            );

            const wsDetail = XLSX.utils.aoa_to_sheet(detailData);
            wsDetail['!cols'] = [{ wch: 20 }, { wch: 25 }, { wch: 20 }, { wch: 40 }];
            XLSX.utils.book_append_sheet(wb, wsDetail, 'Detailed Stats');

            // Generate Excel file
            XLSX.writeFile(wb, `library_report_excel_${new Date().toISOString().split('T')[0]}.xlsx`);
            showAlert('Laporan Excel dengan diagram berhasil didownload', 'success');
        }
    } catch (error) {
        console.error('Error generating report:', error);
        showAlert('Gagal generate laporan: ' + error.message, 'error');
    }
}

// Helper function to add chart image to Excel sheet
function addChartImageToSheet(ws, chartInstance, x, y, scale, chartName) {
    try {
        const chartImg = chartInstance.toBase64Image();
        const base64Data = chartImg.split(',')[1];
        const binaryString = atob(base64Data);
        const arrayBuffer = new ArrayBuffer(binaryString.length);
        const uint8Array = new Uint8Array(arrayBuffer);
        for (let i = 0; i < binaryString.length; i++) {
            uint8Array[i] = binaryString.charCodeAt(i);
        }

        const imageBlob = new Blob([uint8Array], { type: 'image/png' });
        const imageUrl = URL.createObjectURL(imageBlob);

        const img = new Image();
        img.onload = function() {
            URL.revokeObjectURL(imageUrl);

            // Add image to Excel
            const imgWidth = this.width * scale;
            const imgHeight = this.height * scale;

            XLSX.utils.sheet_add_image(ws, {
                url: imageUrl,
                type: 'png',
                width: imgWidth,
                height: imgHeight
            }, {
                tl: { x: x, y: y },
                ext: { width: imgWidth, height: imgHeight }
            });
        };
        img.src = imageUrl;
    } catch (e) {
        console.log(`Could not add ${chartName} image to Excel:`, e);
    }
}

// Format currency helper
function formatCurrency(value) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(value);
}

// Load all data on page load
document.addEventListener('DOMContentLoaded', function() {
    loadStats();
    loadRevenueChart();
    loadBookStatusChart();
    loadGenreChart();
});
</script>
@endpush
