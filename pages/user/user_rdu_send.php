<?php
include '../../database/config.php';
include '../../actions/session_check.php';
include '../../actions/user_midware.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'user_ham.php'; ?>
</head>
<h2>
    <center><img src='../../img/IMG_5789__1_-removebg-preview.png' class='logo-image-navbar h1' alt='logo'>Push Order/Report Items</center>
</h2>

<body id="main">

    <!-- Push Order Form -->
    <select name="form" id="formSelector">
        <option value="order">Push Order</option>
        <option value="report">Report Item</option>
    </select>

    <!-- Dynamic Form Inclusion -->
    <div id="formContent">
        <!-- The included form will be displayed here -->
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var formSelector = document.getElementById('formSelector');
            var formContentDiv = document.getElementById('formContent');

            // Clear the current content
            formContentDiv.innerHTML = '';

            // Function to handle the change event
            function handleFormChange() {
                var selectedForm = formSelector.value;

                // Use AJAX to request the server to include the appropriate form
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'include_form.php?form=' + selectedForm, true); // Correct path to include_form.php
                xhr.onload = function() {
                    if (this.status == 200) {
                        formContentDiv.innerHTML = this.responseText;
                    } else {
                        formContentDiv.innerHTML = 'Error loading form.';
                    }
                };
                xhr.onerror = function() {
                    formContentDiv.innerHTML = 'Error loading form.';
                };
                xhr.send();
            }

            // Trigger the initial form change
            handleFormChange();

            // Listen for the change event on the form selector
            formSelector.addEventListener('change', handleFormChange);
        });
    </script>


    <!-- Existing scripts -->
    <!-- Optional JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src="user_ham.js"></script>
</body>

</html>