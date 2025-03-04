<!DOCTYPE html>
<html>

<head>
    <style>
        #med_close {
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

<body>
    <a onclick="closeMedList()"><i class="fas fa-times" id="med_close"></i></a>
    <script>
        function closeMedList() {
            var chatbox = document.getElementById("med_list");
            chatbox.style.display = chatbox.style.display === "none" ? "block" : "none";
        }
    </script>

    <div class="container-fluid">
        <div class="row" style=" height:80px">
            <div class="col-3 col-lg-4  container-fluid ">
                <form id="searchForm" class="mt-1 mt-md-2 mt-lg-3 container-xs row"method='GET' action=''>
                    <input type='text' class="col-10 col-lg-7 col-md-7" id="search" name='search' placeholder="Search Medicine">
                    <input type='submit' class="btn btn-success col-10 col-md-7 col-lg-5 col-xl-3" name='item_search' value='Search'>
                </form>
            </div>
            <div class="col-9 col-lg-8">
                <p class="mx-0 mx-sm-5 fs-1 ">Medicine Lists</p>
            </div> 
        </div>
    </div>
    <!--
    <div class="d-flex align-items-start">
        <form id="searchForm" class="text-start mt-3" method='GET' action=''>
            <input type='text' name='search' placeholder="Search Medicine">
            <input type='submit' class="btn btn-success" name='item_search' value='Search'>
        </form>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <p class="text-center fs-1 mx-1">Medicine Lists</p>
    </div>
    -->
    <?php
    include 'database/config.php';

    $limit = 7;
    $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $offset = ($currentPage - 1) * $limit;
    $search = isset($_GET['search']) ? $_GET['search'] : '';

    $terms = explode(" ", $search);
    $data = "SELECT * FROM items WHERE classification = 'medicine'";

    if (!empty($terms)) {
        $data .= " AND (";
        $i = 0;
        foreach ($terms as $each) {
            $i++;
            if ($i == 1) {
                $data .= "item_name LIKE '%$each%'";
            } else {
                $data .= " OR item_name LIKE '%$each%'";
            }
        }
        $data .= ")";
    }

    $query = mysqli_query($conn, $data);
    $num = mysqli_num_rows($query);
    $totalRows = $num;
    $totalPages = ceil($totalRows / $limit);

    $sql = $data . " ORDER BY item_name ASC LIMIT $limit OFFSET $offset";
    $result = mysqli_query($conn, $sql);

    if ($num > 0) {
        echo "$num result(s) found for <b>$search</b>!";
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
            echo "<tr>";
            echo "<td data-label='Code'>" . $row['code'] . "</td>";
            echo "<td data-label='Item Name'>" . $row['item_name'] . "</td>";
            echo "<td data-label='Type'>" . $row['type'] . "</td>";
            echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";

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
        echo "<input type='number' id='gotoPage' class='form-control' min='1' max='$totalPages' placeholder='Page'>";
        echo "</div>";
        echo "<div class='col-md-1'>";
        echo "<button class='btn btn-danger' id='goButton' data-total-pages='$totalPages'>Go</button>";
        echo "</div>";

        echo "</ul>";
        echo "</nav>";
        echo "</div>";
    } else {
        echo "0 result found for <b>$search</b>!";
    }
    ?>

</body>

</html>