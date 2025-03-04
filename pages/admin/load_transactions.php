<?php
include '../../database/config.php';
session_start();
include '../../actions/admin_midware.php';

$received_by = $_POST['received_by'] ?? 'all';
$startDate = $_POST['startDate'] ?? '';
$endDate = $_POST['endDate'] ?? '';
$page = $_POST['page'] ?? 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$whereClauses = [];
if ($received_by !== 'all') {
    $whereClauses[] = "received_by = '$received_by'";
}
if (!empty($startDate) && !empty($endDate)) {
    $whereClauses[] = "date_received BETWEEN '$startDate' AND '$endDate'";
}
$whereClause = "";
if (count($whereClauses) > 0) {
    $whereClause = "WHERE " . implode(" AND ", $whereClauses);
}
// Get total number of items
$total_items = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM deliver_received $whereClause"));

// Calculate total number of pages
$total_pages = ceil($total_items / $limit);

$total_query = "SELECT SUM(total) as total_amount FROM deliver_received $whereClause";
$total_result = mysqli_query($conn, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total = $total_row['total_amount'];
$ftotal = number_format($total, 2, '.', '');

// Get data for the graph
$graphQuery = "SELECT DATE(date_received) AS date, SUM(total) AS total_amount FROM deliver_received $whereClause GROUP BY DATE(date_received)";
$graphResult = mysqli_query($conn, $graphQuery);
$graphData = [];
while ($row = mysqli_fetch_assoc($graphResult)) {
    $graphData[] = ['date' => $row['date'], 'total_amount' => $row['total_amount']];
}

$output = '<h2>Total: ₱' . $ftotal . '</h2>';

$data = "SELECT * FROM deliver_received $whereClause ORDER BY post_trans_number DESC LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $data);
$count = mysqli_num_rows($result);

if ($count > 0) {
    $output .= '<table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>System #</th>
                        <th>Transaction #</th>
                        <th>Total</th>
                        <th>Date received</th>
                        <th>Received By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>';
    while ($row = mysqli_fetch_assoc($result)) {
        $output .= '<tr>
                        <td class="system">' . $row['post_trans_number'] . '</td>
                        <td class="id">' . $row['receipt_trans_number'] . '</td>
                        <td class="total">' . $row['total'] . '</td>
                        <td class="date">' . $row['date_received'] . '</td>
                        <td class="received_by">' . $row['received_by'] . '</td>
                        <td>
                            <button class="btn btn-info view_details">🔍View Details</button>
                        </td>
                    </tr>';
    }
    $output .= '</tbody></table>';

    // Pagination
    $paginationQuery = "SELECT COUNT(*) AS total FROM deliver_received $whereClause";
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
    $output = '';
    $output .= '<p class="text-center">No transactions found.</p>';
}

echo json_encode(['table' => $output, 'graphData' => $graphData]);
