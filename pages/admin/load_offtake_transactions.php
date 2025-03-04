<?php
include '../../database/config.php';
session_start();
include '../../actions/admin_midware.php';

$transact_by = $_POST['transact_by'] ?? 'all';
$payment_mode = $_POST['payment_mode'] ?? 'all';
$startDate = $_POST['startDate'] ?? '';
$endDate = $_POST['endDate'] ?? '';
$page = $_POST['page'] ?? 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$whereClauses = [];
if ($transact_by !== 'all') {
    $whereClauses[] = "transact_by = '$transact_by'";
}
if ($payment_mode !== 'all') {
    $whereClauses[] = "payment_mode = '$payment_mode'";
}
if (!empty($startDate) && !empty($endDate)) {
    $whereClauses[] = "date_transacted BETWEEN '$startDate' AND '$endDate'";
}

$whereClause = "";
if (count($whereClauses) > 0) {
    $whereClause = "WHERE " . implode(" AND ", $whereClauses);
}

// Query for total transactions
$totalQuery = "SELECT SUM(amount) AS total_transactions FROM transactions $whereClause";
$totalResult = mysqli_query($conn, $totalQuery);
$totalRow = mysqli_fetch_assoc($totalResult);
$totalTransactions = $totalRow['total_transactions'];
$ftotal = number_format($totalTransactions, 2, '.', '');

// Adjust the cash and gcash queries
$cash_query = "SELECT SUM(amount) as cash_amount FROM transactions ";
$gcash_query = "SELECT SUM(amount) as gcash_amount FROM transactions ";
if (!empty($whereClause)) {
    $cash_query .= $whereClause . " AND payment_mode = 'Cash'";
    $gcash_query .= $whereClause . " AND payment_mode = 'Gcash'";
} else {
    $cash_query .= "WHERE payment_mode = 'Cash'";
    $gcash_query .= "WHERE payment_mode = 'Gcash'";
}

$cash_result = mysqli_query($conn, $cash_query);
$cash_row = mysqli_fetch_assoc($cash_result);
$cash = $cash_row['cash_amount'] ?? 0;
$fcash = number_format($cash, 2, '.', '');

$gcash_result = mysqli_query($conn, $gcash_query);
$gcash_row = mysqli_fetch_assoc($gcash_result);
$gcash = $gcash_row['gcash_amount'] ?? 0;
$fgcash = number_format($gcash, 2, '.', '');

// Get data for the graph
$graphQuery = "SELECT DATE(date_transacted) AS date, SUM(amount) AS total_amount FROM transactions $whereClause GROUP BY DATE(date_transacted)";
$graphResult = mysqli_query($conn, $graphQuery);
$graphData = [];
while ($row = mysqli_fetch_assoc($graphResult)) {
    $graphData[] = ['date' => $row['date'], 'total_amount' => $row['total_amount']];
}

$data = "SELECT * FROM transactions $whereClause ORDER BY id DESC LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $data);
$count = mysqli_num_rows($result);

$output = '<div class="summary">
                <h2>Total Transactions: ₱ ' . $ftotal . ' &nbsp&nbsp&nbsp Cash: ₱' . $fcash . ' &nbsp&nbsp&nbsp GCash: ₱' . $fgcash . '</h2>
            </div>';

if ($count > 0) {
    $output .= '<table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Amount</th>
                        <th>Amount Tendered</th>
                        <th>Date Transacted</th>
                        <th>Time Transacted</th>
                        <th>Payment Mode</th>
                        <th>Transacted By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>';
    while ($row = mysqli_fetch_assoc($result)) {
        $output .= '<tr>
                        <td class="id">' . $row['id'] . '</td>
                        <td class="amount">' . $row['amount'] . '</td>
                        <td class="amount_tendered">' . $row['tender_amount'] . '</td>
                        <td class="date">' . $row['date_transacted'] . '</td>
                        <td class="time">' . $row['time_transacted'] . '</td>
                        <td class="payment_mode">' . $row['payment_mode'] . '</td>
                        <td class="transact_by">' . $row['transact_by'] . '</td>
                        <td>
                            <button class="btn btn-info view_details">🔍View Details</button>
                        </td>
                    </tr>';
    }
    $output .= '</tbody></table>';

    // Pagination
    $paginationQuery = "SELECT COUNT(*) AS total FROM transactions $whereClause";
    $paginationResult = mysqli_query($conn, $paginationQuery);
    $paginationRow = mysqli_fetch_assoc($paginationResult);
    $totalItems = $paginationRow['total'];
    $totalPages = ceil($totalItems / $limit);

    $paginationOutput = '<nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">';

    if ($page > 1) {
        $paginationOutput .= "<li class='page-item'><a class='page-link' href='#' data-page='" . ($page - 1) . "'>Previous</a></li>";
    }

    $startPage = max(1, $page - 1);
    $endPage = min($startPage + 2, $totalPages);

    for ($i = $startPage; $i <= $endPage; $i++) {
        $paginationOutput .= "<li class='page-item " . ($page == $i ? 'active' : '') . "'><a class='page-link' href='#' data-page='$i'>$i</a></li>";
    }

    if ($page < $totalPages) {
        $paginationOutput .= "<li class='page-item'><a class='page-link' href='#' data-page='" . ($page + 1) . "'>Next</a></li>";
    }

    $paginationOutput .= "</ul></nav>";

    $output .= $paginationOutput;
} else {
    $output .= '<p class="text-center">No transactions found.</p>';
}

echo json_encode(['table' => $output, 'graphData' => $graphData]);

