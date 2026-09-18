<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-2 text-gray-800">Daftar Verifikasi Proses Produksi</h1>
    </div>

    <?php if($this->session->flashdata('success_msg')): ?>
        <div class="alert alert-success text-center">
            <i class="fas fa-check"></i>
            <?= $this->session->flashdata('success_msg') ?>
        </div>
        <br>
    <?php endif ?>

    <?php if($this->session->flashdata('error_msg')): ?>
        <div class="alert alert-danger text-center">
            <i class="fas fa-check"></i>
            <?= $this->session->flashdata('error_msg') ?>
        </div>
        <br>
    <?php endif ?> 

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <!-- <form action="<?= base_url('proses/cetak') ?>" method="post" id="form_cetak_pdf"> -->
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                               <!--  <th width="30px" class="text-center">
                                    <i class="fas fa-print fa-lg"></i>
                                </th> -->
                                <th width="20px" class="text-center">No</th>
                                <th>Date</th>
                                <th>Shift</th>
                                <th>Types of Product</th>
                                <th>Last Updated</th>
                                <th>Last Verified</th>
                                <th>SPV</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            foreach($proses as $val) {
                                $datetime = new datetime($val->date);
                                $datetime = $datetime->format('d-m-Y');
                                ?>
                                <tr>
                                    <!-- <td class="text-center"><input type="checkbox" name="checkbox[]" value="<?= $val->uuid ?>" class="select_row"></td> -->
                                    <td class="text-center"><?= $no; ?></td>
                                    <td><?= $datetime; ?></td> 
                                    <td><?= $val->shift; ?></td>
                                    <td><?= $val->nama_produk; ?></td>
                                    <td><?= date('H:i - d m Y', strtotime($val->modified_at)); ?></td>
                                    <td><?= date('H:i - d m Y', strtotime($val->tgl_update)); ?></td>
                                    <td class="text-center">
                                        <?php
                                        if ($val->status_spv == 0) {
                                            echo '<span style="color: #99a3a4; font-weight: bold;">Created</span>';
                                        } elseif ($val->status_spv == 1) {
                                            echo '<span style="color: #28b463; font-weight: bold;">Verified</span>';
                                        } elseif ($val->status_spv == 2) {
                                            echo '<span style="color: red; font-weight: bold;">Revision</span>';
                                        }
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= base_url('proses/status/'.$val->uuid);?>" class="btn btn-warning btn-icon-split">
                                            <span class="text">Verifikasi</span>
                                        </a>
                                    </td>
                                </tr>
                                <?php 
                                $no++;
                            }
                            ?>
                        </tbody>
                    </table>
                   <!--  <input type="hidden" name="checkbox[]" id="selected_items">
                </form> -->
            </div>

            <br>
            <hr>
            <div class="form-group">
                <label>Pilih Data yang akan dicetak:</label>
                <form action="<?= base_url('proses/cetak') ?>" method="post" id="form_cetak_pdf">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="tanggal">Tanggal</label>
                            <input type="date" name="tanggal" id="tanggal" class="form-control" required>
                        </div>
                        <div class="col-md-6 align-self-end">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-print fa-sm text-white-50"></i> Cetak PDF
                            </button>
                            <button type="button" class="btn btn-primary" id="btn_export_excel">
                                <i class="fas fa-file-excel fa-sm text-white-50"></i> Export Excel
                            </button>
                        </div> 
                    </div>
                </form>
            </div>

            <script>
    // Tombol Export Excel
                document.getElementById('btn_export_excel').addEventListener('click', function() {
        // Ambil data tanggal & shift
                    const tanggal = document.getElementById('tanggal').value;
                    const shift = document.getElementById('shift').value;

                    if (!tanggal || !shift) {
                        alert('Tanggal dan Shift harus dipilih terlebih dahulu!');
                        return;
                    }

        // Redirect ke export_excel controller dengan data POST
                    const form = document.createElement('form');
                    form.method = 'post';
                    form.action = '<?= base_url("proses/export_excel") ?>';

                    const inputTanggal = document.createElement('input');
                    inputTanggal.type = 'hidden';
                    inputTanggal.name = 'tanggal';
                    inputTanggal.value = tanggal;

                    const inputShift = document.createElement('input');
                    inputShift.type = 'hidden';
                    inputShift.name = 'shift';
                    inputShift.value = shift;

                    form.appendChild(inputTanggal);
                    form.appendChild(inputShift);

                    document.body.appendChild(form);
                    form.submit();
                });
            </script>

        </div>
    </div>

</div>
</div>

<script>
    document.getElementById('select_all').addEventListener('change', function() {
        var checkboxes = document.querySelectorAll('.select_row');
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = this.checked;
        });
    });

    document.getElementById('form_cetak_pdf').addEventListener('submit', function(event) {
        var selectedCheckboxes = document.querySelectorAll('.select_row:checked');
        var selectedItems = [];

        selectedCheckboxes.forEach(function(checkbox) {
            selectedItems.push(checkbox.value); 
        });
        if (selectedItems.length === 0) {
            event.preventDefault(); 
            alert("Silakan pilih data yang ingin dicetak.");
            return;
        }
        document.getElementById('selected_items').value = selectedItems.join(',');
    });
</script>

<style> 
    th {
        background-color: #f8f9fc;
    }
</style>
