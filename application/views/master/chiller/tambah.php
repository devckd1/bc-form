<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-2 text-gray-800">
            Tambah Master Chiller
        </h1>

        <a href="<?= base_url('master-chiller'); ?>"
           class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i>
            Kembali
        </a>
    </div>

    <div class="card shadow mb-4">

        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Form Master Chiller
            </h6>
        </div>

        <div class="card-body">

            <?= form_open('master-chiller/tambah'); ?>

            <div class="form-group">
                <label>Nama Chiller</label>

                <input type="text"
                       name="nama_chiller"
                       class="form-control"
                       value="<?= set_value('nama_chiller'); ?>"
                       placeholder="Contoh: Chiller 1">

                <?= form_error('nama_chiller', '<small class="text-danger">', '</small>'); ?>
            </div>

            <div class="form-group">
                <label>Range Standar</label>

                <div class="input-group">
                    <input type="text"
                           name="range_standar"
                           class="form-control"
                           value="<?= set_value('range_standar', '0 - 4'); ?>"
                           placeholder="Contoh: 0 - 4">

                    <div class="input-group-append">
                        <span class="input-group-text">°C</span>
                    </div>
                </div>

                <?= form_error('range_standar', '<small class="text-danger">', '</small>'); ?>
            </div>

            <input type="hidden"
                   name="satuan"
                   value="°C">

            <div class="form-group">
                <label>Keterangan</label>

                <textarea name="keterangan"
                          class="form-control"
                          rows="3"
                          placeholder="Keterangan (opsional)"><?= set_value('keterangan'); ?></textarea>

                <?= form_error('keterangan', '<small class="text-danger">', '</small>'); ?>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save mr-1"></i>
                Simpan
            </button>

            <a href="<?= base_url('master-chiller'); ?>"
               class="btn btn-secondary">
                Batal
            </a>

            <?= form_close(); ?>

        </div>
    </div>

</div>
