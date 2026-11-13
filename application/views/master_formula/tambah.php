
<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-2 text-gray-800">Tambah Master Formula</h1>

        <a href="<?= base_url('master_formula'); ?>"
            class="btn btn-secondary btn-sm shadow-sm">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <?php if ($this->session->flashdata('error_msg')): ?>
        <div class="alert alert-danger text-center">
            <?= $this->session->flashdata('error_msg'); ?>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('master_formula/simpan'); ?>"
        method="POST"
        id="formMasterFormula">

        <input type="hidden"
            name="<?= $this->security->get_csrf_token_name(); ?>"
            value="<?= $this->security->get_csrf_hash(); ?>">

        <!-- Informasi Formula -->
        <div class="card shadow mb-4">

            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-file-alt mr-1"></i>
                    Informasi Formulasi
                </h6>
            </div>

            <div class="card-body">

                <div class="form-group">
                    <label>Nama Formulasi <span class="text-danger">*</span></label>
                    <input type="text"
                        name="nama_formulasi"
                        class="form-control"
                        placeholder="Masukkan nama formulasi"
                        required>
                </div>

                <div class="form-group">
                    <label>Nama Produk <span class="text-danger">*</span></label>

                    <select name="produk_uuid"
                        class="form-control"
                        id="produk_uuid"
                        required>

                        <option value="">-- Pilih Nama Produk --</option>

                        <?php foreach ($produk as $row): ?>
                            <option value="<?= html_escape($row->uuid); ?>">
                                <?= html_escape($row->nama_produk); ?>
                            </option>
                        <?php endforeach; ?>

                    </select>

                    <?php if (empty($produk)): ?>
                        <small class="text-danger">
                            Semua produk sudah memiliki Master Formula.
                        </small>
                    <?php endif; ?>
                </div>

                <div class="form-row">

                    <div class="form-group col-md-4">
                        <label>No. Dokumen</label>
                        <input type="text"
                            name="no_dokumen"
                            class="form-control"
                            placeholder="Contoh: FRM/PROD/001">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Revisi</label>
                        <input type="text"
                            name="revisi"
                            class="form-control"
                            placeholder="Contoh: 00">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Formula</label>
                        <input type="text"
                            name="formula"
                            class="form-control"
                            placeholder="Masukkan formula">
                    </div>

                </div>

            </div>
        </div>

        <!-- Formulasi Bahan 
        <div class="card shadow mb-4">

            <div class="card-header py-3 d-flex justify-content-between align-items-center">

                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-flask mr-1"></i>
                    Formulasi Bahan
                </h6>

                <button type="button"
                    class="btn btn-success btn-sm"
                    id="btnTambahBahan">
                    <i class="fas fa-plus mr-1"></i>
                    Tambah Bahan
                </button>

            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered"
                        id="tableBahan">

                        <thead class="text-center">
                            <tr>
                                <th width="5%">No.</th>
                                <th>Nama Bahan</th>
                                <th width="25%">Komposisi</th>
                                <th width="20%">Satuan</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>

                        <tbody id="bodyBahan">

                            <tr>
                                <td class="text-center nomor-bahan">1</td>

                                <td>
                                    <input type="text"
                                        name="nama_bahan[]"
                                        class="form-control"
                                        placeholder="Nama bahan"
                                        required>
                                </td>

                                <td>
                                    <input type="text"
                                        name="komposisi[]"
                                        class="form-control"
                                        placeholder="Contoh: 100">
                                </td>

                                <td>
                                    <input type="text"
                                        name="satuan[]"
                                        class="form-control"
                                        placeholder="Contoh: kg">
                                </td>

                                <td class="text-center">
                                    <button type="button"
                                        class="btn btn-danger btn-sm btnHapusBahan"
                                        disabled>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>

                        </tbody>

                    </table>

                </div>

                <small class="text-muted">
                    Tambahkan bahan sesuai urutan formulasi produk.
                </small>

            </div>
        </div> -->

        <!-- Tombol Simpan -->
        <div class="text-right mb-4">

            <a href="<?= base_url('master_formula'); ?>"
                class="btn btn-secondary">
                Batal
            </a>

            <button type="submit"
                class="btn btn-primary"
                id="btnSimpan">
                <i class="fas fa-save mr-1"></i>
                Simpan Formula
            </button>

        </div>

    </form>

</div>
<!--
<script>
$(document).ready(function() {

    // Tambah baris bahan
    $('#btnTambahBahan').on('click', function() {

        const row = `
            <tr>
                <td class="text-center nomor-bahan"></td>

                <td>
                    <input type="text"
                        name="nama_bahan[]"
                        class="form-control"
                        placeholder="Nama bahan"
                        required>
                </td>

                <td>
                    <input type="text"
                        name="komposisi[]"
                        class="form-control"
                        placeholder="Contoh: 100">
                </td>

                <td>
                    <input type="text"
                        name="satuan[]"
                        class="form-control"
                        placeholder="Contoh: kg">
                </td>

                <td class="text-center">
                    <button type="button"
                        class="btn btn-danger btn-sm btnHapusBahan">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;

        $('#bodyBahan').append(row);

        updateNomorBahan();
    });

    // Hapus baris bahan
    $(document).on('click', '.btnHapusBahan', function() {

        if ($('#bodyBahan tr').length <= 1) {
            return;
        }

        $(this).closest('tr').remove();

        updateNomorBahan();
    });

    // Update nomor urut
    function updateNomorBahan() {

        $('#bodyBahan tr').each(function(index) {

            $(this).find('.nomor-bahan').text(index + 1);

            $(this).find('.btnHapusBahan')
                .prop('disabled', $('#bodyBahan tr').length <= 1);
        });
    }

    // Validasi sebelum simpan
    $('#formMasterFormula').on('submit', function(e) {

        if (!$('#produk_uuid').val()) {
            e.preventDefault();

            Swal.fire(
                'Perhatian',
                'Silakan pilih nama produk terlebih dahulu.',
                'warning'
            );

            return false;
        }

        if ($('#bodyBahan tr').length < 1) {
            e.preventDefault();

            Swal.fire(
                'Perhatian',
                'Minimal harus ada satu bahan formulasi.',
                'warning'
            );

            return false;
        }

        $('#btnSimpan').prop('disabled', true);
        $('#btnSimpan').html(
            '<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...'
        );
    });

});
</script>
					-->
