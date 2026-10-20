<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Tambah Proses Produksi</h1>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?= base_url('produksi') ?>">
                    <i class="fas fa-arrow-left"></i>
                    Daftar Laporan Verifikasi Proses Produksi
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Tambah</li>
        </ol>
    </nav>

    <div class="card shadow mb-4">
        <div class="card-body">
            <?php if (!empty($duplicate_error)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?= htmlspecialchars($duplicate_error) ?>
                </div>
            <?php endif; ?>
            <form class="user" method="post" action="<?= base_url('produksi/tambah'); ?>">
                <?php
                $produksi_data = $this->session->userdata('produksi_data');
                $tanggal_sess  = $produksi_data['tanggal'] ?? date('Y-m-d');
                $shift_sess    = $produksi_data['shift'] ?? '';
                ?>
                <div class="form-group row">
                    <div class="col-md-4">
                        <label class="font-weight-bold">Tanggal</label>
                        <input type="date" name="date"
                        class="form-control <?= form_error('date') ? 'is-invalid' : '' ?>"
                        value="<?= set_value('date', $tanggal_sess) ?>">
                        <div class="invalid-feedback"><?= form_error('date') ?></div>
                    </div>

                    <div class="col-md-4">
                        <label class="font-weight-bold">Shift</label>
                        <select name="shift" class="form-control <?= form_error('shift') ? 'is-invalid' : '' ?>">
                            <option value="" disabled <?= empty($shift_sess) ? 'selected' : '' ?>>Pilih Shift</option>
                            <option value="1" <?= set_select('shift', '1', $shift_sess == '1') ?>>Shift 1</option>
                            <option value="2" <?= set_select('shift', '2', $shift_sess == '2') ?>>Shift 2</option>
                            <option value="3" <?= set_select('shift', '3', $shift_sess == '3') ?>>Shift 3</option>
                        </select>
                        <div class="invalid-feedback"><?= form_error('shift') ?></div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label class="form-label font-weight-bold">Nama Produk</label>
                        <select name="nama_produk" id="nama_produk"
                        class="form-control <?= form_error('nama_produk') ? 'is-invalid' : '' ?>">
                        <option value="">- Pilih Produk -</option>
                        <?php foreach ($produk_list as $produk): ?>
                            <option value="<?= htmlspecialchars($produk->nama_produk) ?>"
                                <?= set_value('nama_produk') == $produk->nama_produk ? 'selected' : '' ?>>
                                <?= htmlspecialchars($produk->nama_produk) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback <?= form_error('nama_produk') ? 'd-block' : '' ?>">
                        <?= form_error('nama_produk') ?>
                    </div>
                </div>
                <!-- BC MIX MULTIPLE -->
                <div class="col-sm-4" id="bcMixWrapper" style="display: none;">
                    <label class="form-label font-weight-bold">Kode Produksi BC MIX</label>
                    <div class="position-relative">
                        <input type="text" id="bc_mix_kode" autocomplete="off"
                        class="form-control" placeholder="Ketik kode produksi BC MIX...">
                        <div id="bcMixSuggestion" class="bc-mix-suggestion"></div>
                    </div>
                    <!-- Kode yang sudah dipilih -->
                    <div id="bcMixSelected" class="mt-2"></div>
                    <!-- Notifikasi: nama produk + kode produksi sudah ada di database -->
                    <div id="bcMixDupeWarning" class="dupe-warning" style="display: none;">
                        <i class="fas fa-exclamation-triangle"></i>
                        Nama produk dan kode produksi sudah ada
                    </div>
                    <!-- Field yang dikirim ke controller, contoh: BCMIX001,BCMIX002,BCMIX003 -->
                    <input type="hidden" name="kode_produksi" id="kode_produksi"
                    value="<?= set_value('kode_produksi', $kode_produksi_terakhir); ?>">
                    <small class="form-text text-muted">Bisa memilih lebih dari satu kode produksi.</small>
                </div>
                <!-- KODE PRODUKSI NORMAL -->
                <div class="col-sm-4" id="kodeProduksiWrapper">
                    <label class="form-label font-weight-bold">Kode Produksi</label>
                    <input type="text" name="kode_produksi_normal" id="kode_produksi_normal"
                    class="form-control <?= form_error('kode_produksi') ? 'is-invalid' : '' ?>"
                    value="<?= set_value('kode_produksi', $kode_produksi_terakhir); ?>">
                    <div class="invalid-feedback <?= !empty(form_error('kode_produksi')) ? 'd-block' : '' ?>">
                        <?= form_error('kode_produksi') ?>
                    </div>
                    <!-- Notifikasi: nama produk + kode produksi sudah ada di database -->
                    <div id="normalDupeWarning" class="dupe-warning" style="display: none;">
                        <i class="fas fa-exclamation-triangle"></i>
                        Nama produk dan kode produksi sudah ada
                    </div>
                    <small id="kodeTerakhir" class="form-text text-muted">
                        <?php if (!empty($kode_produksi_terakhir)): ?>
                            Kode terakhir hari ini:
                            <strong class="text-danger"><?= htmlspecialchars($kode_produksi_terakhir) ?></strong>
                        <?php else: ?>
                            Belum ada kode produksi hari ini
                        <?php endif; ?>
                    </small>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <button type="submit" class="btn btn-md btn-success mr-2">
                        <i class="fa fa-save"></i> Simpan
                    </button>
                    <a href="<?= base_url('produksi') ?>" class="btn btn-md btn-danger">
                        <i class="fa fa-times"></i> Batal
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
</div>
</div>
<style type="text/css">
    .breadcrumb {
        background-color: #2E86C1;
    }
    /* SUGGESTION BC MIX */
    .bc-mix-suggestion {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: #fff;
        border: 1px solid #ddd;
        border-top: none;
        max-height: 250px;
        overflow-y: auto;
        z-index: 9999;
        display: none;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.08);
    }
    .bc-mix-item {
        padding: 10px 12px;
        cursor: pointer;
        background-color: #fff;
        border-bottom: 1px solid #eee;
    }
    .bc-mix-item:hover {
        background-color: #f1f1f1;
    }
    /* KODE YANG SUDAH DIPILIH */
    .bc-mix-selected-item {
        display: inline-flex;
        align-items: center;
        background-color: #e9ecef;
        border: 1px solid #ced4da;
        border-radius: 4px;
        padding: 5px 8px;
        margin: 3px 3px 3px 0;
        font-size: 14px;
    }
    .bc-mix-selected-item .remove-bc-mix {
        margin-left: 8px;
        cursor: pointer;
        color: #dc3545;
        font-weight: bold;
    }
    .bc-mix-selected-item .remove-bc-mix:hover {
        color: #a71d2a;
    }
    /* NOTIFIKASI DUPLIKAT NAMA PRODUK + KODE PRODUKSI */
    .dupe-warning {
        margin-top: 6px;
        padding: 6px 10px;
        font-size: 13px;
        color: #856404;
        background-color: #fff3cd;
        border: 1px solid #ffeeba;
        border-radius: 4px;
    }
    .dupe-warning i {
        margin-right: 4px;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const namaProduk         = document.getElementById('nama_produk');
        const bcMixWrapper       = document.getElementById('bcMixWrapper');
        const bcMixKode          = document.getElementById('bc_mix_kode');
        const bcMixSuggestion    = document.getElementById('bcMixSuggestion');
        const bcMixSelected      = document.getElementById('bcMixSelected');
        const bcMixDupeWarning   = document.getElementById('bcMixDupeWarning');
        const kodeProduksiWrapper = document.getElementById('kodeProduksiWrapper');
        const kodeProduksi        = document.getElementById('kode_produksi');
        const kodeProduksiNormal  = document.getElementById('kode_produksi_normal');
        const normalDupeWarning   = document.getElementById('normalDupeWarning');
        const kodeTerakhir        = document.getElementById('kodeTerakhir');

    /* Nama produk yang memakai mode BC MIX (multiple kode) */
        const produkHalus = 'BREADCRUMBS MIX SINTETIS HALUS (CPI)';

    /* List BC MIX dari PHP */
        const bcMixList = [
            <?php foreach ($bc_mix_list as $bc_mix): ?>
                <?= json_encode($bc_mix->kode_produksi) ?>,
            <?php endforeach; ?>
        ];

    /* Kode BC MIX yang sedang dipilih */
        let selectedBcMix = [];

    /* Ambil data lama jika form sebelumnya gagal validasi.
       Format: BCMIX001,BCMIX002,BCMIX003 */
        const oldKodeProduksi = kodeProduksi.value.trim();
        if (oldKodeProduksi !== '') {
            selectedBcMix = oldKodeProduksi
            .split(',')
            .map(kode => kode.trim())
            .filter(kode => kode !== '');
        }

        function updateKodeProduksi() {
            kodeProduksi.value = selectedBcMix.join(',');
        }

        function renderSelectedBcMix() {
            bcMixSelected.innerHTML = '';

            selectedBcMix.forEach((kode, index) => {
                const item = document.createElement('span');
                item.className = 'bc-mix-selected-item';

                const kodeText = document.createElement('span');
                kodeText.textContent = kode;

                const remove = document.createElement('span');
                remove.className = 'remove-bc-mix';
                remove.setAttribute('data-index', index);
                remove.innerHTML = '&times;';

                item.appendChild(kodeText);
                item.appendChild(remove);
                bcMixSelected.appendChild(item);
            });

            updateKodeProduksi();
            cekDuplikatBcMix();
        }

    /* Hapus kode BC MIX */
        bcMixSelected.addEventListener('click', function (e) {
            if (!e.target.classList.contains('remove-bc-mix')) return;

            const index = parseInt(e.target.getAttribute('data-index'), 10);
            selectedBcMix.splice(index, 1);
            renderSelectedBcMix();
        });

    /* ==========================
       CEK PRODUK (BC MIX vs NORMAL)
    =========================== */
        function cekProduk() {

            if (namaProduk.value === produkHalus) {
            /* Mode BC MIX */
                bcMixWrapper.style.display = 'block';
                kodeProduksiWrapper.style.display = 'none';
                kodeTerakhir.style.display = 'none';
                normalDupeWarning.style.display = 'none';

                renderSelectedBcMix();

            } else {
            /* Mode kode produksi normal */
                bcMixWrapper.style.display = 'none';
                kodeProduksiWrapper.style.display = 'block';
                kodeTerakhir.style.display = 'block';
                bcMixDupeWarning.style.display = 'none';

                selectedBcMix = [];
                bcMixKode.value = '';
                bcMixSuggestion.innerHTML = '';
                bcMixSuggestion.style.display = 'none';
                bcMixSelected.innerHTML = '';

                kodeProduksiNormal.value = '<?= htmlspecialchars($kode_produksi_terakhir) ?>';
                kodeProduksi.value = kodeProduksiNormal.value;

                cekDuplikatNormal();
            }
        }

    /* ==========================
       SEARCH / SUGGESTION BC MIX
    =========================== */
        bcMixKode.addEventListener('input', function () {
            const keyword = this.value.toLowerCase().trim();
            bcMixSuggestion.innerHTML = '';

            if (keyword === '') {
                bcMixSuggestion.style.display = 'none';
                return;
            }

            const hasil = bcMixList.filter(kode => {
                if (selectedBcMix.includes(kode)) return false;
                return kode.toLowerCase().includes(keyword);
            });

            if (hasil.length === 0) {
                bcMixSuggestion.style.display = 'none';
                return;
            }

            hasil.forEach(kode => {
                const item = document.createElement('div');
                item.className = 'bc-mix-item';
                item.textContent = kode;

                item.addEventListener('click', function () {
                    if (!selectedBcMix.includes(kode)) {
                        selectedBcMix.push(kode);
                    }

                    bcMixKode.value = '';
                    bcMixSuggestion.innerHTML = '';
                    bcMixSuggestion.style.display = 'none';

                    renderSelectedBcMix();
                });

                bcMixSuggestion.appendChild(item);
            });

            bcMixSuggestion.style.display = 'block';
        });

    /* Input kode produksi normal */
        kodeProduksiNormal.addEventListener('input', function () {
            if (namaProduk.value !== produkHalus) {
                kodeProduksi.value = this.value;
            }
            cekDuplikatNormalDebounced();
        });

    /* Produk berubah */
        namaProduk.addEventListener('change', cekProduk);

    /* Klik di luar suggestion menutup dropdown */
        document.addEventListener('click', function (e) {
            if (!bcMixKode.contains(e.target) && !bcMixSuggestion.contains(e.target)) {
                bcMixSuggestion.style.display = 'none';
            }
        });

    /* ==========================================================
       CEK DUPLIKAT: NAMA PRODUK + KODE PRODUKSI SUDAH ADA DI DB
       Berlaku untuk kode produksi tunggal (normal) maupun
       multiple (BC MIX).

       CATATAN: Endpoint di bawah ini ("produksi/cek_kode_produksi")
       adalah ASUMSI dan perlu dibuatkan method di controller
       (lihat catatan di akhir chat). Endpoint menerima POST JSON:
           { nama_produk: "...", kode_produksi: ["...", "..."] }
       dan mengembalikan JSON:
           { exists: true|false }
    =========================================================== */

        function debounce(fn, delay) {
            let timer = null;
            return function (...args) {
                clearTimeout(timer);
                timer = setTimeout(() => fn.apply(this, args), delay);
            };
        }

        async function cekDuplikat(kodeList, warningEl) {
            const nama = namaProduk.value.trim();
            const kode = kodeList.map(k => k.trim()).filter(k => k !== '');

            if (nama === '' || kode.length === 0) {
                warningEl.style.display = 'none';
                return;
            }

            try {
                const response = await fetch('<?= base_url('produksi/cek_kode_produksi') ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ nama_produk: nama, kode_produksi: kode })
                });

                const data = await response.json();
                warningEl.style.display = data.exists ? 'block' : 'none';

            } catch (error) {
                console.error('Gagal mengecek duplikasi kode produksi:', error);
            }
        }

        function cekDuplikatNormal() {
            cekDuplikat([kodeProduksiNormal.value], normalDupeWarning);
        }

        function cekDuplikatBcMix() {
            cekDuplikat(selectedBcMix, bcMixDupeWarning);
        }

        const cekDuplikatNormalDebounced = debounce(cekDuplikatNormal, 400);
        cekProduk();

    });
</script>
