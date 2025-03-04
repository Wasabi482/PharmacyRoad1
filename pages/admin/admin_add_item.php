<?php
include '../../database/config.php';
session_start();
include '../../actions/admin_midware.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10">
    </link>
    <link rel="stylesheet" href="../../css/add_items.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <title>Add New Item - Admin</title>
    <link rel="icon" href="../../img/icon copy.png" type="image/x-icon" />
</head>

<body>
    <!-- Push Order Form -->
    <br>
    <center><select name="form" id="formSelector" class=" mb-5">
            <option value="medicine">Medicine</option>
            <option value="alcohol">Alcohol</option>
            <option value="creams_ointment">Creams and Ointment</option>
        </select></center>
    <div id="main">
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
                    xhr.open('GET', 'admin_include_form.php?form=' + selectedForm, true); // Correct path to include_form.php
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
    </div>


</body>
<!-- Load the full jQuery build first -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

<!-- Then load Popper.js and Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script> -->
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</html>