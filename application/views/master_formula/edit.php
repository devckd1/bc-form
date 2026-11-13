
<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-2 text-gray-800">Edit Master Formula</h1>

        <a href="<?= base_url('master_formula'); ?>"
            class="btn btn-secondary btn-sm shadow-sm">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <form action="<?= base_url('master_formula/update/' . rawurlencode($formula->uuid)); ?>"
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
                        value="<?= html_escape($formula->nama_formulasi); ?>"
                        required>
                </div>

                <div class="form-group">
                    <label>Nama Produk <span class="text-danger">*</span></label>

                    <select name="produk_uuid"
                        id="produk_uuid"
                        class="form-control"
                        required>

                        <option value="">-- Pilih Nama Produk --</option>

                        <?php foreach ($produk as $row): ?>
                            <option value="<?= html_escape($row->uuid); ?>"
                                <?= $row->uuid === $formula->produk_uuid ? 'selected' : ''; ?>>
                                <?= html_escape($row->nama_produk); ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="form-row">

                    <div class="form-group col-md-4">
                        <label>No. Dokumen</label>

                        <input type="text"
                            name="no_dokumen"
                            class="form-control"
                            value="<?= html_escape($formula->no_dokumen ?? ''); ?>">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Revisi</label>

                        <input type="text"
                            name="revisi"
                            class="form-control"
                            value="<?= html_escape($formula->revisi ?? ''); ?>">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Formula</label>

                        <input type="text"
                            name="formula"
                            class="form-control"
                            value="<?= html_escape($formula->formula ?? ''); ?>">
                    </div>

                </div>

            </div>
        </div>

        <!-- Formulasi Bahan -->
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

                            <?php if (!empty($detail)): ?>

                                <?php foreach ($detail as $index => $row): ?>

                                    <tr>
                                        <td class="text-center nomor-bahan">
                                            <?= $index + 1; ?>
                                        </td>

                                        <td>
                                            <input type="text"
                                                name="nama_bahan[]"
                                                class="form-control"
                                                value="<?= html_escape($row->nama_bahan); ?>"
                                                required>
                                        </td>

                                        <td>
                                            <input type="text"
                                                name="komposisi[]"
                                                class="form-control"
                                                value="<?= html_escape($row->komposisi ?? ''); ?>">
                                        </td>

                                        <td>
                                            <input type="text"
                                                name="satuan[]"
                                                class="form-control"
                                                value="<?= html_escape($row->satuan ?? ''); ?>">
                                        </td>

                                        <td class="text-center">
                                            <button type="button"
                                                class="btn btn-danger btn-sm btnHapusBahan">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>

                                <?php endforeach; ?>

                            <?php else: ?>

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
                                            placeholder="Komposisi">
                                    </td>

                                    <td>
                                        <input type="text"
                                            name="satuan[]"
                                            class="form-control"
                                            placeholder="Satuan">
                                    </td>

                                    <td class="text-center">
                                        <button type="button"
                                            class="btn btn-danger btn-sm btnHapusBahan"
                                            disabled>
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>

                            <?php endif; ?>

                        </tbody>

                    </table>

                </div>

                <small class="text-muted">
                    Tambahkan atau hapus bahan sesuai kebutuhan formulasi.
                </small>

            </div>
        </div>

        <!-- Tombol -->
        <div class="text-right mb-4">

            <a href="<?= base_url('master_formula'); ?>"
                class="btn btn-secondary">
                Batal
            </a>

            <button type="submit"
                class="btn btn-primary"
                id="btnSimpan">
                <i class="fas fa-save mr-1"></i>
                Simpan Perubahan
            </button>

        </div>

    </form>

</div>

<script>
$(document).ready(function() {

    // Tambah bahan
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
                        placeholder="Komposisi">
                </td>

                <td>
                    <input type="text"
                        name="satuan[]"
                        class="form-control"
                        placeholder="Satuan">
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

    // Hapus bahan
    $(document).on('click', '.btnHapusBahan', function() {

        if ($('#bodyBahan tr').length <= 1) {
            return;
        }

        $(this).closest('tr').remove();

        updateNomorBahan();
    });

    // Perbarui nomor urut
    function updateNomorBahan() {

        const jumlah = $('#bodyBahan tr').length;

        $('#bodyBahan tr').each(function(index) {

            $(this).find('.nomor-bahan').text(index + 1);

            $(this).find('.btnHapusBahan')
                .prop('disabled', jumlah <= 1);
        });
    }

    updateNomorBahan();

    // Validasi form
    $('#formMasterFormula').on('submit', function(e) {

        const produk = $('#produk_uuid').val();
        const jumlah = $('#bodyBahan tr').length;

        if (!produk || jumlah < 1) {
            e.preventDefault();

            Swal.fire(
                'Perhatian',
                'Nama produk dan minimal satu bahan wajib diisi.',
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
