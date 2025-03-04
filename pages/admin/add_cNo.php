<?php
session_start();
include '../../actions/admin_midware.php';
?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card mt-5">
                <div class="card-body">
                    <h5 class="text-center">Road 1 Pharmacy</h5>
                    <form ENCTYPE="multipart/form-data" action="../../actions/admin/admin_add_item.php" method="post">
                        <h4 class="text-center">Add New Item Creams and Ointment</h4>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="item_name">Item Name</label>
                                <input type="text" class="form-control" name="item_name" required placeholder="Enter Item Name">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="unit_qty">Unit of Measurement(Number)</label>
                                <input type="number" class="form-control" name="unit_qty" required placeholder="Enter Item unit" min="1" step=".01">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="unit_type">Unit of Measurement</label>
                                <select class="form-control" name="unit_type" id="">
                                    <option value="g">Grams</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="unit_price">Unit Price</label>
                                <input type="number" class="form-control" inputmode="decimal" name="unit_price" required placeholder="Enter Unit Price" min="0.01" step=".01">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="mark_up">Mark up %</label>
                                <input type="number" class="form-control" name="mark_up" required placeholder="Enter Mark up" min="1" step=".01">
                            </div>
                            <input type="hidden" name="class" value="Creams and ointment">
                            <div class="form-group col-md-12">
                                <label for="vendor_name">Vendor Name</label>
                                <input type="text" class="form-control" name="vendor_name" required placeholder="Enter Vendor Name">
                            </div>
                        </div>
                        <input type="submit" name="submit_cNo" value="Add Item" class="btn btn-primary btn-block text">
                    </form>
                    <p class="text-center"><a href="admin_view_items.php" class="btn btn-danger mt-2 btn-block text ">Cancel</a></p>
                </div>
            </div>
        </div>
    </div>
</div>