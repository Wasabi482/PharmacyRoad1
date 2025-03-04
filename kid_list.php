<!DOCTYPE html>
<html>

<head>
    <style>
        #kid_close {
            position: absolute;
            top: 10px;
            right: 15px;
            color: #dc3545;
            font-size: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            color: #212529;
        }

        td {
            color: #dc3545;
        }

        .page-item.active .page-link {
            background-color: #dc3545;
            color: #fff;
            border-color: #dc3545;
        }

        .page-item:not(.active) .page-link {
            background-color: #fff;
            color: #dc3545;
            border-color: #dc3545;
        }

        .page-link {
            border: 1px solid #dc3545;
            margin: 0 2px;
        }

        @media screen and (max-width: 600px) {
            table {
                border: 0;
            }

            table thead {
                display: none;
            }

            table tr {
                margin-bottom: 10px;
                display: block;
                border-bottom: 2px solid #ddd;
            }

            table td {
                display: block;
                text-align: right;
                font-size: 13px;
                border-bottom: 1px dotted #ccc;
            }

            table td:before {
                content: attr(data-label);
                float: left;
                font-weight: bold;
                text-transform: uppercase;
            }
        }
    </style>
</head>
<a onclick="closeKidList()"><i class="fas fa-times" id="kid_close"></i></a>
<script>
    function closeKidList() {
        var chatbox = document.getElementById("kid_list");
        chatbox.style.display = chatbox.style.display === "none" ? "block" : "none";
    }
</script>

<body>
    <p class="text-center fs-1">Milks Lists</p>
    <?php
    include 'database/config.php';

    // Get the total number of rows
    $countSql = "SELECT COUNT(*) as total FROM items WHERE classification = 'milk'"; //replace with what_for
    $countResult = mysqli_query($conn, $countSql);
    $countRow = mysqli_fetch_assoc($countResult);
    $totalRows = $countRow['total'];

    // Calculate the total number of pages
    $limit = 7; // Number of rows per page
    $totalPages = ceil($totalRows / $limit);

    // Get the current page number
    if (isset($_GET['page'])) {
        $currentPage = $_GET['page'];
    } else {
        $currentPage = 1;
    }

    // Calculate the offset for the SQL query
    $offset = ($currentPage - 1) * $limit;

    // Fetch the rows for the current page
    $sql = "SELECT code, item_name, type FROM items WHERE classification = 'milk' ORDER BY item_name ASC LIMIT $limit OFFSET $offset"; //replace what_for
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo "<table>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>Code</th>";
        echo "<th>Item Name</th>";
        echo "<th>Type</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        while ($row = mysqli_fetch_assoc($result)) {
            $code = $row['code'];
            $item_name = $row['item_name'];
            $type = $row['type'];
            echo "<tr>";
            echo "<td data-label='Code'>" . $code . "</td>";
            echo "<td data-label='Item Name'>" . $item_name . "</td>";
            echo "<td data-label='Type'>" . $type . "</td>";
            echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";

        // Pagination buttons
        echo "<div class='row justify-content-center mt-4'>";
        echo "<nav aria-label='Page navigation example'>";
        echo "<ul class='pagination'>";
        if ($currentPage > 1) {
            echo "<li class='page-item'><a class='page-link' href='#' data-page='" . ($currentPage - 1) . "'>Previous</a></li>";
        }

        $startPage = max(1, $currentPage - 1);
        $endPage = min($startPage + 2, $totalPages);

        for ($i = $startPage; $i <= $endPage; $i++) {
            echo "<li class='page-item " . ($currentPage == $i ? 'active' : '') . "'><a class='page-link' href='#' data-page='$i'>$i</a></li>";
        }

        if ($currentPage < $totalPages) {
            echo "<li class='page-item'><a class='page-link' href='#' data-page='" . ($currentPage + 1) . "'>Next</a></li>";
        }
        echo "<div class='col-md-2'>";
        echo "<input type='number' id='gotoPageK' class='form-control' min='1' max='$totalPages' placeholder='Page'>";
        echo "</div>";
        echo "<div class='col-md-1'>";
        echo "<button class='btn btn-danger' id='goButtonK'>Go</button>";
        echo "</div>";

        echo "</ul>";
        echo "</nav>";
        echo "</div>";

        echo "<div class='row justify-content-center mt-2'>";

        echo "</div>";

        echo "<script>
document.getElementById('goButtonK').addEventListener('click', function() {
    var page = document.getElementById('gotoPageK').value;
    if (page >= 1 && page <= $totalPages) {
        loadKidList(page);
    } else {
        alert('Please enter a valid page number between 1 and $totalPages');
    }
});

document.querySelectorAll('.page-link').forEach(function(element) {
    element.addEventListener('click', function(event) {
        event.preventDefault();
        var page = this.getAttribute('data-page');
        loadKidList(page);
    });
});
</script>";
    } else {
        echo "0 results";
    }
    ?>
</body>

</html>