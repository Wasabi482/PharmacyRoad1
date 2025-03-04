<?php
session_start();
include '../../actions/admin_midware.php';
// pdo connection
$pdo = new PDO(
                "mysql:host=localhost;dbname=capstone;charset=utf8mb4",
                "root",
                ""
            );


$limit = 3; // Limit of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Get the total number of records
$totalQuery = $pdo->query("SELECT COUNT(*) FROM push_orders");
$totalRows = $totalQuery->fetchColumn();
$totalPages = ceil($totalRows / $limit);

$query = $pdo->prepare("SELECT * FROM push_orders LIMIT :start, :limit");
$query->bindParam(':start', $start, PDO::PARAM_INT);
$query->bindParam(':limit', $limit, PDO::PARAM_INT);
$query->execute();

// Fetch all the rows from the result set
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="orderTableContent">
    <h5>Order Pushed</h5>
    <section class='intro'>
        <div class='gradient-custom-2 h-100'>
            <div class='mask d-flex align-items-center h-100'>
                <div class='container'>
                    <div class='row justify-content-center'>
                        <div class='col-12'>
                            <div class='table-responsive'>
                                <table class='table table-dark table-bordered mb-0'>
                                    <thead>
                                        <tr>
                                            <th scope='col'>ID</th>
                                            <th scope='col'>Item Name</th>
                                            <th scope='col'>Reason</th>
                                            <th scope='col'>Quantity</th>
                                            <th scope='col'>Date</th>
                                            <th scope='col'>Status</th>
                                            <th scope='col'>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($rows as $row) : ?>
                                            <tr>
                                                <td class='id'><?php echo $row['id']; ?></td>
                                                <td><?php echo $row['item_name']; ?></td>
                                                <td><?php echo $row['reason']; ?></td>
                                                <td><?php echo $row['qty']; ?></td>
                                                <td><?php echo $row['date_reported']; ?></td>
                                                <td <?php if ($row['status'] == 'unread') {
                                                        echo "style='color: red;'";
                                                    } ?>><?php echo $row['status']; ?></td>
                                                <td><a href="#" class='btn btn-success mr-2 mark_as_read'>✔️Mark as Read</a>
                                                    <form action="../../actions/admin/delete_message.php" method="post" style="display:inline;">
                                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                        <button type="submit" class='btn btn-danger' name='submit_push'>🗑️Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class='row justify-content-center mt-4'>
                                <nav aria-label='Page navigation example'>
                                    <ul class='pagination'>
                                        <?php if ($page > 1) : ?>
                                            <li class='page-item'><a class='page-link' href='#' data-page='<?php echo $page - 1; ?>'>Previous</a></li>
                                        <?php endif; ?>

                                        <?php
                                        $startPage = max(1, $page - 1);
                                        $endPage = min($startPage + 2, $totalPages);

                                        for ($i = $startPage; $i <= $endPage; $i++) : ?>
                                            <li class='page-item <?php if ($page == $i) echo 'active'; ?>'><a class='page-link' href='#' data-page='<?php echo $i; ?>'><?php echo $i; ?></a></li>
                                        <?php endfor; ?>

                                        <?php if ($page < $totalPages) : ?>
                                            <li class='page-item'><a class='page-link' href='#' data-page='<?php echo $page + 1; ?>'>Next</a></li>
                                        <?php endif; ?>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<div class="modal fade custom-fade" id="markAsReadModal" tabindex="-1" role="dialog" aria-labelledby="markAsReadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg custom-modal-center" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="markAsReadModalLabel">Edit Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="edit_form">


                </div>
            </div>
        </div>
    </div>
</div>