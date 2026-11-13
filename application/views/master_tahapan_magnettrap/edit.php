
<div class="container-fluid">

    <h1 class="h3 mb-2 text-gray-800">
        Edit Tahapan Magnet Trap
    </h1>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color:#2E86C1;">
            <li class="breadcrumb-item">
                <a href="<?= base_url('master_tahapan_magnettrap') ?>"
                    class="text-white">
                    <i class="fas fa-arrow-left"></i>
                    Master Tahapan Magnet Trap
                </a>
            </li>
            <li class="breadcrumb-item active text-white">
                Edit
            </li>
        </ol>
    </nav>

    <?php if ($this->session->flashdata('error_msg')): ?>
        <div class="alert alert-danger">
            <?= $this->session->flashdata('error_msg') ?>
        </div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-body">

            <form method="post"
                action="<?= base_url('master_tahapan_magnettrap/edit/' . $item->uuid) ?>">

                <div class="form-group">
                    <label class="font-weight-bold">
                        Nama Tahapan
                    </label>

                    <input type="text"
                        name="nama_tahapan"
                        class="form-control <?= form_error('nama_tahapan') ? 'is-invalid' : '' ?>"
                        value="<?= set_value('nama_tahapan', $item->nama_tahapan) ?>"
                        placeholder="Masukkan nama tahapan">

                    <div class="invalid-feedback">
                        <?= form_error('nama_tahapan') ?>
                    </div>
                </div>

                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Update
                </button>

                <a href="<?= base_url('master_tahapan_magnettrap') ?>"
                    class="btn btn-danger">
                    <i class="fas fa-times"></i> Batal
                </a>

            </form>

        </div>
    </div>

</div>
