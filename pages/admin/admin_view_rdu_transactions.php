<?php
include '../../database/config.php';
session_start();
include '../../actions/admin_midware.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'ham.php'; ?>
</head>

<body>
    <div class="height-100 bg-light">
        <h2>
            <center><img src='../../img/IMG_5789__1_-removebg-preview.png' class='logo-image-navbar h1' alt='logo'>View Delivery Transactions</center>
        </h2>
        <div class="d-flex flex-row-reverse mt-2">
            <button id='exportExcelBtn' class='btn btn-success mb-2'>Export to Excel</button>
        </div>
        <!-- Filter Dropdown -->
        <center>
            <div class="container mb-5">
                <div class="row">
                    <div class="col-6">
                        <label for="received_by">Received By:</label>
                        <select id="received_by" class="mb-2 form-select my-select" style="width:100%;">
                            <option value='all'>All</option>
                            <?php
                            $data = "SELECT username FROM accounts WHERE role_as = '3'";
                            $result = mysqli_query($conn, $data);
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $username = $row['username'];
                                    echo "<option value='$username'>$username</option>";
                                }
                            } else {
                                echo "<option value=''>No data found!</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-6">
                        <div class="card-body mid income" id="content">
                            <p class="class-title text-center fs-3">
                                <?php
                                if (isset($_POST['submit'])) {
                                    echo "The sales from " . $_POST['startDate'] . " to " . $_POST['endDate'] . ":";
                                }
                                ?>
                            </p>
                            <form id="dateRangeForm" method="post">
                                <label for="startDate">Start Date</label>
                                <?php
                                $sql = "SELECT MIN(date_received) AS earliest_date FROM deliver_received";
                                $result = mysqli_query($conn, $sql);
                                $row = mysqli_fetch_assoc($result);
                                $earliest_date = $row['earliest_date'];
                                $today = date('Y-m-d');
                                ?>
                                <input type="date" id="startDate" name="startDate" placeholder="Start Date (YYYY/MM/DD)" required min="<?php echo $earliest_date; ?>" max="<?php echo $today; ?>">
                                <label for="endDate">End Date</label>
                                <input type="date" id="endDate" name="endDate" placeholder="End Date (YYYY/MM/DD)" required min="<?php echo $earliest_date; ?>" max="<?php echo $today; ?>">
                                <input type="submit" name="submit" class="btn btn-primary"></input>
                            </form>
                        </div>
                    </div>
                    <div class="col-12">
                        <div id="graphContainer" style="width: 100%; height: 400px;">
                            <canvas id="transactionsGraph"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </center>

        <div id="main">
            <div id="formContent">
                <!-- Content will be loaded here via AJAX -->
            </div>
        </div>
    </div>

    <!-- Modal for Viewing Details -->
    <div class="modal fade custom-fade" id="viewDetailsModal" tabindex="-1" role="dialog" aria-labelledby="viewDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg custom-modal-center" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewDetailsModalLabel">View Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="view_form"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Load the full jQuery build first -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>
    <!-- Then load Popper.js and Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment"></script>
    <!-- AJAX Script -->
    <script>
        $(document).ready(function() {
            function loadTransactions(received_by, startDate, endDate, page) {
                $.ajax({
                    url: 'load_transactions.php',
                    type: 'POST',
                    data: {
                        received_by: received_by,
                        startDate: startDate,
                        endDate: endDate,
                        page: page
                    },
                    success: function(response) {
                        var data = JSON.parse(response);
                        $('#formContent').html(data.table);
                        updateGraph(data.graphData);
                    }
                });
            }

            $('#received_by').change(function() {
                var received_by = $(this).val();
                var startDate = $('#startDate').val();
                var endDate = $('#endDate').val();
                loadTransactions(received_by, startDate, endDate, 1);
            });

            $('#dateRangeForm').submit(function(e) {
                e.preventDefault();
                var received_by = $('#received_by').val();
                var startDate = $('#startDate').val();
                var endDate = $('#endDate').val();
                loadTransactions(received_by, startDate, endDate, 1);
            });

            $('body').on('click', '.pagination a', function(e) {
                e.preventDefault();
                var received_by = $('#received_by').val();
                var startDate = $('#startDate').val();
                var endDate = $('#endDate').val();
                var page = $(this).attr('data-page');
                loadTransactions(received_by, startDate, endDate, page);
            });

            // Initial load
            loadTransactions('all', '', '', 1);
            // Chart.js setup
            var ctx = document.getElementById('transactionsGraph').getContext('2d');
            var transactionsChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Total Amount',
                        data: [],
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 2,
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                unit: 'day'
                            },
                            title: {
                                display: true,
                                text: 'Date'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Total Amount (₱)'
                            }
                        }
                    }
                }
            });

            function updateGraph(graphData) {
                var dates = graphData.map(item => item.date);
                var amounts = graphData.map(item => item.total_amount);

                transactionsChart.data.labels = dates;
                transactionsChart.data.datasets[0].data = amounts;
                transactionsChart.update();
            }


            // View Details
            $('body').on('click', '.view_details', function(e) {
                e.preventDefault();
                var id = $(this).closest('tr').find('td.system').text();
                var trans = $(this).closest('tr').find('td.id').text();
                var total = $(this).closest('tr').find('td.total').text();
                var date_received = $(this).closest('tr').find('td.date').text();
                var received_by = $(this).closest('tr').find('td.received_by').text();

                $.ajax({
                    method: "POST",
                    url: "../../actions/admin/admin_view_rdu_details.php",
                    data: {
                        'click_view_details': true,
                        'id': id,
                        'trans': trans,
                        'total': total,
                        'date_received': date_received,
                        'received_by': received_by,
                    },
                    success: function(response) {
                        $('.view_form').html(response);
                        $('#viewDetailsModal').modal('show');
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // Export to Excel button click event
            $('#exportExcelBtn').click(function() {
                // Get the table headers
                var tableHeaders = [];
                $('table thead th').each(function(index) {
                    // Exclude the "Actions" column (assuming it is the last column)
                    if (index !== $('table thead th').length - 1) {
                        tableHeaders.push($(this).text());
                    }
                });

                // Get the table rows data
                var tableData = [];
                $('table tbody tr').each(function() {
                    var rowData = {};
                    $(this).find('td').each(function(index) {
                        // Exclude the "Actions" column (assuming it is the last column)
                        if (index !== $('table thead th').length - 1) {
                            rowData[tableHeaders[index]] = $(this).text();
                        }
                    });
                    tableData.push(rowData);
                });

                // Generate the Excel file
                var worksheet = XLSX.utils.json_to_sheet(tableData, {
                    header: tableHeaders
                });
                var workbook = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(workbook, worksheet, 'Transactions');
                var excelBuffer = XLSX.write(workbook, {
                    bookType: 'xlsx',
                    type: 'array'
                });

                // Create a Blob from the Excel buffer and initiate download
                var blob = new Blob([excelBuffer], {
                    type: 'application/octet-stream'
                });
                var downloadLink = document.createElement('a');
                downloadLink.href = URL.createObjectURL(blob);
                downloadLink.download = 'transactions.xlsx';
                downloadLink.click();
            });
        });
    </script>
</body>

</html>