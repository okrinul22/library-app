@extends('admin.layout')

@section('title', 'Transactions - Admin')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Transactions Management</h1>
        <div>
            <button class="btn btn-sm btn-secondary shadow-sm" onclick="exportTransactions('pdf')">
                <i class="fas fa-file-pdf fa-sm text-white-50"></i> Export PDF
            </button>
            <button class="btn btn-sm btn-secondary shadow-sm" onclick="exportTransactions('excel')">
                <i class="fas fa-file-excel fa-sm text-white-50"></i> Export Excel
            </button>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card primary border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Active Transactions</div>
                            <div class="h5 mb-0 font-weight-bold text-white" id="activeTransactions">-</div>
                        </div>
                        <div class="col-auto">
                            <div class="stats-icon primary">
                                <i class="fas fa-book-reader fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card warning border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Payments</div>
                            <div class="h5 mb-0 font-weight-bold text-white" id="pendingPayments">-</div>
                        </div>
                        <div class="col-auto">
                            <div class="stats-icon warning">
                                <i class="fas fa-clock fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card danger border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Overdue</div>
                            <div class="h5 mb-0 font-weight-bold text-white" id="overdueTransactions">-</div>
                        </div>
                        <div class="col-auto">
                            <div class="stats-icon danger">
                                <i class="fas fa-exclamation-triangle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
                                <i class="fas fa-money-bill-wave fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold">Filter & Search</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="searchTransaction" class="form-label text-secondary">Search Transaction ID</label>
                        <input type="text" class="form-control" id="searchTransaction" placeholder="Cari ID transaksi...">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="filterStatus" class="form-label text-secondary">Status</label>
                        <select class="form-select" id="filterStatus">
                            <option value="">Semua Status</option>
                            <option value="active">Active</option>
                            <option value="completed">Completed</option>
                            <option value="overdue">Overdue</option>
                            <option value="pending_payment">Pending Payment</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions Table Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold">Daftar Transaksi</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-sm" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Transaction ID</th>
                            <th>User (Member/Writer)</th>
                            <th>Book</th>
                            <th>Borrow Date</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Payment Proof</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="transactionsTableBody">
                        <tr>
                            <td colspan="8" class="text-center text-secondary">Loading...</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <nav aria-label="Page navigation" class="mt-3">
                <ul class="pagination justify-content-center" id="pagination"></ul>
            </nav>
        </div>
    </div>

</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Detail Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="paymentModalContent"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentTransactionsPage = 1;

async function loadTransactions(page = 1) {
    currentTransactionsPage = page;
    const search = document.getElementById('searchTransaction').value;
    const status = document.getElementById('filterStatus').value;

    let url = `/transactions?page=${page}&per_page=10`;
    if (search) url += `&search=${encodeURIComponent(search)}`;
    if (status) url += `&status=${status}`;

    try {
        const response = await apiRequest(url);
        const transactions = response.data;
        renderTransactionsTable(transactions.data);
        renderPagination(transactions, 'loadTransactions');
        loadStats();
    } catch (error) {
        console.error('Error loading transactions:', error);
        document.getElementById('transactionsTableBody').innerHTML =
            '<tr><td colspan="8" class="text-center text-secondary">Error loading transactions</td></tr>';
    }
}

function renderTransactionsTable(transactions) {
    const tbody = document.getElementById('transactionsTableBody');

    if (transactions.length === 0) {
        tbody.innerHTML = '<tr><td colspan="8" class="text-center text-secondary">Tidak ada transaksi</td></tr>';
        return;
    }

    tbody.innerHTML = transactions.map(trx => {
        // Determine user type (Member/Writer)
        const userType = trx.user?.role || 'Member';
        const userDisplay = trx.user?.name || 'Unknown';

        // Payment proof display - show thumbnail image
        let paymentProof = '';
        if (trx.book?.is_free) {
            paymentProof = '<span class="badge badge-success">FREE</span>';
        } else if (trx.payment?.proof) {
            paymentProof = `
                <a href="#" onclick="viewPayment(${trx.id}); return false;" title="Click to view full proof">
                    <img src="/storage/${trx.payment.proof}" alt="Proof"
                         style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid var(--dark-border);">
                </a>
            `;
        } else if (trx.status === 'pending_payment') {
            paymentProof = '<small class="text-warning">Waiting for proof...</small>';
        } else {
            paymentProof = '<small class="text-secondary">-</small>';
        }

        // Build action buttons based on status
        let actionButtons = '';

        if (trx.status === 'pending_payment') {
            // Pending payment - always show action buttons
            actionButtons = `<div class="btn-group btn-group-sm" role="group">`;

            // Show approve button if payment exists and is pending
            if (trx.payment && trx.payment.status === 'pending') {
                actionButtons += `
                    <button type="button" class="btn btn-success btn-sm" onclick="approvePayment(${trx.payment.id})" title="Approve Payment">
                        <i class="fas fa-check"></i>
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" onclick="rejectPayment(${trx.payment.id})" title="Reject Payment">
                        <i class="fas fa-times"></i>
                    </button>
                `;
            } else if (trx.payment && trx.payment.status === 'approved') {
                actionButtons += `<span class="badge badge-success btn-sm"><i class="fas fa-check"></i> Approved</span>`;
            } else if (trx.payment && trx.payment.status === 'rejected') {
                actionButtons += `<span class="badge badge-danger btn-sm"><i class="fas fa-times"></i> Rejected</span>`;
            }

            actionButtons += `
                <button type="button" class="btn btn-info btn-sm" onclick="viewPayment(${trx.id})" title="View Details">
                    <i class="fas fa-eye"></i>
                </button>
                <button type="button" class="btn btn-secondary btn-sm" onclick="deleteTransaction(${trx.id})" title="Delete">
                    <i class="fas fa-trash"></i>
                </button>
            </div>`;
        } else if (trx.status === 'active') {
            // Active transaction - show return and view buttons
            actionButtons = `
                <button type="button" class="btn btn-warning btn-sm" onclick="markReturned(${trx.id})" title="Mark Returned">
                    <i class="fas fa-undo"></i>
                </button>
                <button type="button" class="btn btn-info btn-sm" onclick="viewPayment(${trx.id})" title="View Details">
                    <i class="fas fa-eye"></i>
                </button>
            `;
        } else if (trx.status === 'completed') {
            // Completed - just view
            actionButtons = `
                <button type="button" class="btn btn-info btn-sm" onclick="viewPayment(${trx.id})" title="View Details">
                    <i class="fas fa-eye"></i>
                </button>
            `;
        } else {
            // Other statuses - delete option
            actionButtons = `
                <button type="button" class="btn btn-secondary btn-sm" onclick="deleteTransaction(${trx.id})" title="Delete">
                    <i class="fas fa-trash"></i>
                </button>
            `;
        }

        return `
            <tr>
                <td>
                    <div class="fw-bold">${trx.transaction_id}</div>
                    <small class="text-secondary">ID: ${trx.id}</small>
                </td>
                <td>
                    <div class="fw-bold">${userDisplay}</div>
                    <small class="badge badge-info" style="font-size: 9px;">${userType.toUpperCase()}</small>
                    <br><small class="text-secondary">${trx.user?.email || ''}</small>
                </td>
                <td>
                    <div class="fw-bold">${trx.book?.title || 'Unknown'}</div>
                    ${trx.book?.price && !trx.book?.is_free ? `<small class="text-primary">${formatCurrency(trx.book.price)}</small>` : ''}
                </td>
                <td>${formatDate(trx.transaction_date)}</td>
                <td>${formatDate(trx.due_date)}</td>
                <td>
                    <span class="badge badge-${getStatusBadgeClass(trx.status)}">
                        ${trx.status.replace('_', ' ').toUpperCase()}
                    </span>
                </td>
                <td>${paymentProof}</td>
                <td>
                    <div class="btn-group btn-group-sm" role="group">
                        ${actionButtons}
                    </div>
                </td>
            </tr>
        `;
    }).join('');
}

function getStatusBadgeClass(status) {
    const classes = {
        'active': 'success',
        'completed': 'info',
        'overdue': 'danger',
        'pending_payment': 'warning',
        'cancelled': 'danger'
    };
    return classes[status] || 'info';
}

async function loadStats() {
    try {
        const response = await apiRequest('/dashboard/stats');
        const stats = response.data;

        document.getElementById('activeTransactions').textContent = stats.active_transactions || 0;
        document.getElementById('pendingPayments').textContent = stats.pending_payments || 0;
        document.getElementById('overdueTransactions').textContent = stats.overdue_transactions || 0;
        document.getElementById('totalRevenue').textContent = formatCurrency(stats.total_revenue || 0);
    } catch (error) {
        console.error('Error loading stats:', error);
    }
}

async function approvePayment(id) {
    showConfirm(
        'Approve Pembayaran',
        'Apakah Anda yakin ingin menyetujui pembayaran ini?',
        async () => {
            try {
                await apiRequest(`/payments/${id}/approve`, { method: 'POST' });
                showAlert('Pembayaran disetujui', 'success');
                loadTransactions(currentTransactionsPage);
            } catch (error) {
                console.error('Error approving payment:', error);
                showAlert('Gagal menyetujui pembayaran', 'error');
            }
        }
    );
}

async function rejectPayment(id) {
    const reason = prompt('Alasan penolakan:');
    if (reason) {
        try {
            await apiRequest(`/payments/${id}/reject`, {
                method: 'POST',
                body: JSON.stringify({ rejection_reason: reason })
            });
            showAlert('Pembayaran ditolak', 'success');
            loadTransactions(currentTransactionsPage);
        } catch (error) {
            console.error('Error rejecting payment:', error);
            showAlert('Gagal menolak pembayaran', 'error');
        }
    }
}

async function markReturned(id) {
    showConfirm(
        'Mark as Returned',
        'Apakah Anda yakin buku sudah dikembalikan?',
        async () => {
            try {
                await apiRequest(`/transactions/${id}/return`, { method: 'POST' });
                showAlert('Buku berhasil dikembalikan', 'success');
                loadTransactions(currentTransactionsPage);
            } catch (error) {
                console.error('Error marking returned:', error);
                showAlert('Gagal mengembalikan buku', 'error');
            }
        }
    );
}

function deleteTransaction(id) {
    showConfirm(
        'Hapus Transaksi',
        'Apakah Anda yakin ingin menghapus transaksi ini? Tindakan ini tidak dapat dibatalkan.',
        async () => {
            try {
                await apiRequest(`/transactions/${id}`, { method: 'DELETE' });
                showAlert('Transaksi berhasil dihapus', 'success');
                loadTransactions(currentTransactionsPage);
            } catch (error) {
                console.error('Error deleting transaction:', error);
                showAlert('Gagal menghapus transaksi', 'error');
            }
        }
    );
}

async function viewPayment(id) {
    try {
        const response = await apiRequest(`/transactions/${id}`);
        const transaction = response.data;

        const modal = document.getElementById('paymentModal');
        const content = document.getElementById('paymentModalContent');

        content.innerHTML = `
            <div class="mb-3">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-secondary">Transaction ID</span>
                    <span class="fw-bold">${transaction.transaction_id}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-secondary">User</span>
                    <span class="fw-bold">${transaction.user?.name}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-secondary">Book</span>
                    <span class="fw-bold">${transaction.book?.title}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-secondary">Amount</span>
                    <span class="fw-bold fs-5" style="color: var(--secondary-color);">${formatCurrency(transaction.book?.price || 0)}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-secondary">Borrow Date</span>
                    <span class="fw-bold">${formatDate(transaction.borrow_date)}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-secondary">Due Date</span>
                    <span class="fw-bold">${formatDate(transaction.due_date)}</span>
                </div>
            </div>
            ${transaction.payment?.proof ? `
                <div>
                    <h6 class="mb-3">Bukti Pembayaran</h6>
                    <div class="text-center mb-3">
                        <img src="/storage/${transaction.payment.proof}" alt="Payment Proof"
                             class="img-fluid rounded border"
                             style="max-height: 400px; cursor: pointer;"
                             onclick="window.open('/storage/${transaction.payment.proof}', '_blank')">
                    </div>
                    <p class="text-secondary small mb-3">
                        <i class="fas fa-info-circle"></i> Click image to open in new tab for detailed view
                    </p>
                    <div class="d-flex gap-2 justify-content-center">
                        <a href="/storage/${transaction.payment.proof}" download class="btn btn-sm btn-info">
                            <i class="fas fa-download"></i> Download Proof
                        </a>
                        ${transaction.payment.status === 'pending' ? `
                            <button class="btn btn-sm btn-success" onclick="approvePayment(${transaction.payment.id})">
                                <i class="fas fa-check"></i> Approve Payment
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="rejectPayment(${transaction.payment.id})">
                                <i class="fas fa-times"></i> Reject Payment
                            </button>
                        ` : ''}
                    </div>
                </div>
            ` : '<p class="text-secondary">Belum ada bukti pembayaran</p>'}
        `;

        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();
    } catch (error) {
        console.error('Error viewing payment:', error);
        showAlert('Gagal memuat detail pembayaran', 'error');
    }
}

function renderPagination(data, callbackName) {
    const pagination = document.getElementById('pagination');
    const { links } = data;

    let html = '';

    if (links) {
        links.forEach(link => {
            if (link.url) {
                const page = new URL(link.url).searchParams.get('page');
                const active = link.active ? 'active' : '';
                const label = link.label.includes('Previous') ? 'Previous' :
                             link.label.includes('Next') ? 'Next' : page;

                html += `
                    <li class="page-item ${active}">
                        <a class="page-link" href="#" data-page="${page}"
                           onclick="${callbackName}(${page}); return false;">${label}</a>
                    </li>
                `;
            }
        });
    }

    pagination.innerHTML = html;
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Store all transactions data for export
let allTransactionsData = [];

async function exportTransactions(type) {
    try {
        // Fetch all transactions without pagination for export
        const search = document.getElementById('searchTransaction').value;
        const status = document.getElementById('filterStatus').value;

        let url = `/transactions?per_page=1000`;
        if (search) url += `&search=${encodeURIComponent(search)}`;
        if (status) url += `&status=${status}`;

        const response = await apiRequest(url);
        allTransactionsData = response.data.data || [];

        if (allTransactionsData.length === 0) {
            showAlert('Tidak ada data transaksi untuk diekspor', 'error');
            return;
        }

        if (type === 'pdf') {
            exportToPDF();
        } else {
            exportToExcel();
        }
    } catch (error) {
        console.error('Error exporting transactions:', error);
        showAlert('Gagal mengekspor data transaksi', 'error');
    }
}

function exportToPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('p', 'mm', 'a4');
    const pageWidth = doc.internal.pageSize.getWidth();
    const pageHeight = doc.internal.pageSize.getHeight();
    let yPosition = 20;

    // Title
    doc.setFontSize(20);
    doc.setTextColor(79, 70, 229);
    doc.text('Laporan Transaksi', pageWidth / 2, yPosition, { align: 'center' });
    yPosition += 15;

    // Report info
    doc.setFontSize(10);
    doc.setTextColor(100, 100, 100);
    doc.text(`Generated: ${new Date().toLocaleString('id-ID')}`, pageWidth / 2, yPosition, { align: 'center' });
    yPosition += 6;
    doc.text(`Total Transactions: ${allTransactionsData.length}`, pageWidth / 2, yPosition, { align: 'center' });
    yPosition += 10;

    // Stats summary
    doc.setFontSize(14);
    doc.setTextColor(0, 0, 0);
    doc.text('Ringkasan Status', 15, yPosition);
    yPosition += 10;

    const stats = {
        active: allTransactionsData.filter(t => t.status === 'active').length,
        completed: allTransactionsData.filter(t => t.status === 'completed').length,
        overdue: allTransactionsData.filter(t => t.status === 'overdue').length,
        pending_payment: allTransactionsData.filter(t => t.status === 'pending_payment').length,
        cancelled: allTransactionsData.filter(t => t.status === 'cancelled').length
    };

    doc.setFontSize(11);
    doc.setTextColor(60, 60, 60);

    const statusLabels = {
        active: 'Active',
        completed: 'Completed',
        overdue: 'Overdue',
        pending_payment: 'Pending Payment',
        cancelled: 'Cancelled'
    };

    Object.entries(stats).forEach(([key, value]) => {
        doc.text(`${statusLabels[key]}:`, 15, yPosition);
        doc.text(`${value}`, 60, yPosition);
        yPosition += 7;
    });

    yPosition += 10;

    // Transactions table header
    if (yPosition > pageHeight - 80) {
        doc.addPage();
        yPosition = 20;
    }

    doc.setFontSize(12);
    doc.setTextColor(0, 0, 0);
    doc.text('Daftar Transaksi', 15, yPosition);
    yPosition += 10;

    // Table headers
    doc.setFontSize(9);
    doc.setFillColor(79, 70, 229);
    doc.setTextColor(255, 255, 255);
    doc.rect(15, yPosition, pageWidth - 30, 8, 'F');
    doc.text('ID', 16, yPosition + 5);
    doc.text('User', 30, yPosition + 5);
    doc.text('Book', 70, yPosition + 5);
    doc.text('Date', 120, yPosition + 5);
    doc.text('Status', 150, yPosition + 5);
    doc.text('Amount', 175, yPosition + 5);
    yPosition += 8;

    // Table rows
    doc.setTextColor(60, 60, 60);
    doc.setFillColor(245, 245, 245);

    allTransactionsData.forEach((trx, index) => {
        if (yPosition > pageHeight - 20) {
            doc.addPage();
            yPosition = 20;

            // Repeat header on new page
            doc.setFontSize(9);
            doc.setFillColor(79, 70, 229);
            doc.setTextColor(255, 255, 255);
            doc.rect(15, yPosition, pageWidth - 30, 8, 'F');
            doc.text('ID', 16, yPosition + 5);
            doc.text('User', 30, yPosition + 5);
            doc.text('Book', 70, yPosition + 5);
            doc.text('Date', 120, yPosition + 5);
            doc.text('Status', 150, yPosition + 5);
            doc.text('Amount', 175, yPosition + 5);
            yPosition += 8;
            doc.setTextColor(60, 60, 60);
        }

        if (index % 2 === 0) {
            doc.rect(15, yPosition, pageWidth - 30, 7, 'F');
        }

        doc.setFontSize(8);
        doc.text(String(trx.transaction_id?.substring(0, 10) || trx.id), 16, yPosition + 5);
        doc.text(String((trx.user?.name || 'Unknown').substring(0, 15)), 30, yPosition + 5);
        doc.text(String((trx.book?.title || 'Unknown').substring(0, 20)), 70, yPosition + 5);
        doc.text(String(formatDate(trx.borrow_date)), 120, yPosition + 5);
        doc.text(String(trx.status.replace('_', ' ')), 150, yPosition + 5);
        // Simple currency format for PDF
        const price = trx.book?.price || 0;
        doc.text('Rp ' + String(price), 175, yPosition + 5);
        yPosition += 7;
    });

    // Footer
    const pageCount = doc.internal.getNumberOfPages();
    for (let i = 1; i <= pageCount; i++) {
        doc.setPage(i);
        doc.setFontSize(9);
        doc.setTextColor(150, 150, 150);
        doc.text(`Page ${i} of ${pageCount}`, pageWidth / 2, pageHeight - 10, { align: 'center' });
        doc.text('Library Management System', pageWidth / 2, pageHeight - 5, { align: 'center' });
    }

    doc.save(`transactions_report_${new Date().toISOString().split('T')[0]}.pdf`);
    showAlert('Laporan PDF berhasil didownload', 'success');
}

function exportToExcel() {
    const wb = XLSX.utils.book_new();

    // Report Info Sheet
    const reportInfo = [
        ['LAPORAN TRANSAKSI'],
        [''],
        ['Generated:', new Date().toLocaleString('id-ID')],
        ['Total Transactions:', allTransactionsData.length],
        [''],
        ['RINGKASAN STATUS'],
        [''],
        ['Status', 'Jumlah']
    ];

    const stats = {
        active: allTransactionsData.filter(t => t.status === 'active').length,
        completed: allTransactionsData.filter(t => t.status === 'completed').length,
        overdue: allTransactionsData.filter(t => t.status === 'overdue').length,
        pending_payment: allTransactionsData.filter(t => t.status === 'pending_payment').length,
        cancelled: allTransactionsData.filter(t => t.status === 'cancelled').length
    };

    const statusLabels = {
        active: 'Active',
        completed: 'Completed',
        overdue: 'Overdue',
        pending_payment: 'Pending Payment',
        cancelled: 'Cancelled'
    };

    Object.entries(stats).forEach(([key, value]) => {
        reportInfo.push([statusLabels[key], value]);
    });

    const wsInfo = XLSX.utils.aoa_to_sheet(reportInfo);
    wsInfo['!cols'] = [{ wch: 25 }, { wch: 20 }];
    XLSX.utils.book_append_sheet(wb, wsInfo, 'Ringkasan');

    // Transactions Data Sheet
    const transactionsData = [
        ['DAFTAR TRANSAKSI'],
        [''],
        ['Transaction ID', 'User', 'User Email', 'Book', 'Author', 'Borrow Date', 'Due Date', 'Status', 'Payment Status', 'Amount']
    ];

    allTransactionsData.forEach(trx => {
        transactionsData.push([
            trx.transaction_id || trx.id,
            trx.user?.name || 'Unknown',
            trx.user?.email || '',
            trx.book?.title || 'Unknown',
            trx.book?.author || '',
            formatDate(trx.borrow_date),
            formatDate(trx.due_date),
            trx.status.replace('_', ' ').toUpperCase(),
            trx.payment?.status?.toUpperCase() || (trx.book?.is_free ? 'GRATIS' : '-'),
            formatCurrency(trx.book?.price || 0)
        ]);
    });

    const wsTransactions = XLSX.utils.aoa_to_sheet(transactionsData);
    wsTransactions['!cols'] = [
        { wch: 20 }, { wch: 25 }, { wch: 30 }, { wch: 30 }, { wch: 20 },
        { wch: 15 }, { wch: 15 }, { wch: 20 }, { wch: 15 }, { wch: 15 }
    ];
    XLSX.utils.book_append_sheet(wb, wsTransactions, 'Transaksi');

    // Generate Excel file
    XLSX.writeFile(wb, `transactions_report_${new Date().toISOString().split('T')[0]}.xlsx`);
    showAlert('Laporan Excel berhasil didownload', 'success');
}

document.addEventListener('DOMContentLoaded', () => {
    loadTransactions();

    document.getElementById('searchTransaction').addEventListener('input', debounce(() => loadTransactions(1), 500));
    document.getElementById('filterStatus').addEventListener('change', () => loadTransactions(1));
});
</script>
@endpush
