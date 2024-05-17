<?php include('../dbcon.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
} ?>

<?php
    if ($_SESSION['type'] == "Barangay Health Worker") {
        ?>

<head>
<style>
#readonly-field {
  background-color: transparent;
  border: none;
}
.form-control:focus {
    border-color: #007bff; 
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); 
    outline: none; 
}

</style>

  <script>
function validateInput(inputElement) {
    let inputValue = inputElement.value;
    let lettersOnly = inputValue.replace(/[^a-zA-Z\s.]/g, '');

    if (inputValue !== lettersOnly) {
        let selectionStart = inputElement.selectionStart;
        let selectionEnd = inputElement.selectionEnd;

        inputElement.value = lettersOnly;

        // Restore cursor position
        inputElement.setSelectionRange(selectionStart, selectionEnd);
    }
}
  </script>
</head>

<div class="modal fade" id="add-client" style="font-family: Helvetica;">
    <form method="post" action="add.php">
        <div class="modal-dialog modal-default">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold">Register Client</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="container-fluid">
                        <div class="row">

                            <div>
                                <input name="id" type="hidden">
                            </div>

                        <div class="col-4">
                                    <div class="form-group">
                                <label>Name: <code class="text-danger">*</code></label>
                                <input name="fname" type="text" class="form-control form-control-sm"
                                    placeholder="First Name" oninput="validateInput(this)"required>
                            </div>
                            </div>
                            <div class="col-4">
                                    <div class="form-group">
                                <label><br></label>
                                <input name="minitial" type="text" class="form-control form-control-sm"
                                    placeholder="Middle Initial" oninput="validateInput(this)">
                            </div>
                            </div>
                            <div class="col-4">
                                    <div class="form-group">
                                <label><br></label>
                                <input name="lname" type="text" class="form-control form-control-sm"
                                    placeholder="Last Name" oninput="validateInput(this)" required>
                            </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Date of Birth: <code class="text-danger">*</code></label>
                                    <input name="birth_date" class="form-control form-control-sm" type="date" placeholder="Date of Birth" required max="<?php echo date('Y-m-d'); ?>">
                                </div>
                            </div>

                            <div class="col-md-6">
                                    <label>Sex: <code class="text-danger">*</code><br></label>
                                    <div class="form-group">
                                        <div class="icheck-primary">
                                            <input type="radio" name="sex" value="M" id="radioPrimary1">
                                            <label for="radioPrimary1">
                                                <span class="text" style="font-weight: normal;">
                                                    Male
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label><br></label>
                                    <div class="form-group">
                                        <div class="icheck-primary">
                                            <input type="radio" name="sex" value="F" id="radioPrimary2">
                                            <label for="radioPrimary2">
                                                <span class="text" style="font-weight: normal;">
                                                    Female
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                        <div class="col-md-12">
                                <div class="form-group">
                                    <label>Name of Mother:</label>
                                    <input name="mother_name" class="form-control form-control-sm" 
                                    type="text" placeholder="First Name, Middle Initial, Last Name" 
                                    oninput="validateInput(this)">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Complete Address: <code class="text-danger">*</code><br></label>
                                    <select name="purok" class="form-control form-control-sm" required>
                                        <option selected disabled value="">Select Purok</option>
                                        <option value="Purok 93">Purok 93</option>
                                        <option value="Purok 94">Purok 94</option>
                                        <option value="Purok 95">Purok 95</option>
                                        <option value="Purok 96">Purok 96</option>
                                        <option value="Purok 97">Purok 97</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><br></label>
                                    <input name="address" class="form-control form-control-sm" type="text"
                                        value="Maharlika East, Tagaytay City" readonly>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" name="submit" class="btn btn-primary toastrDefaultSuccess">Add new client</button>
                </div>

            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </form>
    <!-- /.form -->
</div>
<!-- /.modal -->


    <?php } elseif ($_SESSION['type'] == "Nurse") {
      header("Location: ../../index.php"); } ?>

