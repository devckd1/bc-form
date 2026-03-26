<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Tambah Verifikasi Proses Produksi</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?= base_url('proses') ?>">
                    <i class="fas fa-arrow-left"></i> Daftar Laporan Verifikasi Proses Produksi
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Tambah</li>
        </ol>
    </nav>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form class="user" method="post" action="<?= base_url('proses/tambah'); ?>">
                <?php
                $produksi_data = $this->session->userdata('produksi_data');
                $tanggal_sess = $produksi_data['tanggal'] ?? date('Y-m-d');
                $shift_sess = $produksi_data['shift'] ?? '';

                $proses_produksi = [
                    'dough_mixing' => [
                        ['label' => 'Acuan No. Dokumen / Revisi', 'params' => ['dokumen', 'revisi']],
                        'no_formula',
                        'nama_produk',
                        'kode_produksi'
                    ],
                    'kondisi_rm' => [
                        'tepung_terigu',
                        'tepung_tapioka',
                        'yeast',
                        'bread_improver',
                        'premix',
                        'shortening',
                        'chill_water'
                    ],
                    'mixing' => [
                        ['label' => 'Waktu Mixing (menit speed 1 / speed 2)', 'params' => ['waktu_mixing_1', 'waktu_mixing_2']],
                        'sensori',
                        'suhu_adonan',
                        'berat_adonan'
                    ],
                    'proofing' => [
                        ['label' => 'Jam Mulai / Selesai', 'params' => ['jam_mulai', 'jam_selesai']],
                        ['label' => 'Suhu Setting / Aktual (34 - 36°C)', 'params' => ['suhu_setting', 'suhu_aktual']],
                        ['label' => 'RH Setting / Aktual (78 - 82%)', 'params' => ['rh_setting', 'rh_aktual']],
                        'durasi_waktu',
                        'hasil_proofing'
                    ],
                    'electric_baking' => [
                        ['label' => 'Baking Time (High / Low)', 'params' => ['baking_time_high', 'baking_time_low']],
                    ],
                    'hasil_baking' => ['suhu_produk', 'sensori_produk']
                ];

                $label_param = [
                    'dokumen' => 'Acuan Dokumen',
                    'revisi' => 'Revisi',
                    'no_formula' => 'No. Formula',
                    'nama_produk' => 'Jenis Produk',
                    'kode_produksi' => 'Kode Produksi',
                    'tepung_terigu' => 'Tepung Terigu',
                    'tepung_tapioka' => 'Tepung Tapioka',
                    'yeast' => 'Yeast',
                    'bread_improver' => 'Bread Improver',
                    'premix' => 'Premix',
                    'shortening' => 'Shortening',
                    'chill_water' => 'Chill Water (14 - 16°C)',
                    'waktu_mixing_1' => 'Waktu Mixing 1',
                    'waktu_mixing_2' => 'Waktu Mixing 2',
                    'sensori' => 'Sensori',
                    'suhu_adonan' => 'Suhu Adonan (29 - 31°C)',
                    'berat_adonan' => 'Berat Adonan (630 - 670 g/pcs)',
                    'jam_mulai' => 'Jam Mulai',
                    'jam_selesai' => 'Jam Selesai',
                    'suhu_setting' => 'Suhu Setting (34 - 36°C)',
                    'suhu_aktual' => 'Suhu Aktual',
                    'rh_setting' => 'RH Setting (78 - 82%)',
                    'rh_aktual' => 'RH Aktual',
                    'durasi_waktu' => 'Durasi Waktu (60 - 70 menit)',
                    'hasil_proofing' => 'Hasil Proofing',
                    'baking_time_high' => 'Baking Time High',
                    'baking_time_low' => 'Baking Time Low',
                    'suhu_produk' => 'Suhu Produk (80 - 97°C)',
                    'sensori_produk' => 'Sensori Produk'
                ];

                $standar_berat = [
                    'tepung_terigu' => '32.960',
                    'tepung_tapioka' => '2.270',
                    'yeast' => '0.732',
                    'bread_improver' => '0.038',
                    'premix' => ['default' => '0.7468', 'col8' => '0.7653'],
                    'shortening' => '0.252',
                    'chill_water' => '18'
                ];

                $default_mixing = ['waktu_mixing_1' => 3, 'waktu_mixing_2' => 8];
                $default_baking = ['baking_time_high' => 5, 'baking_time_low' => 7];
                $default_suhu = ['suhu_setting' => 35, 'rh_setting' => 80];
                ?>

                <!-- Form Header -->
                <div class="form-group row">
                    <div class="col-md-3">
                        <label class="font-weight-bold">Tanggal</label>
                        <input type="date" name="date" class="form-control" value="<?= set_value('date', $tanggal_sess) ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="font-weight-bold">Shift</label>
                        <select name="shift" class="form-control">
                            <option disabled <?= empty($shift_sess) ? 'selected' : '' ?>>Pilih Shift</option>
                            <?php for ($s = 1; $s <= 3; $s++): ?>
                                <option value="<?= $s ?>" <?= set_select('shift', $s, $shift_sess == $s) ?>>Shift <?= $s ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="font-weight-bold">Nama Produk</label>
                        <select name="nama_produk" id="select_nama_produk" class="form-control">
                            <option value="">-- Pilih Produk --</option>
                            <?php foreach ($produk_list as $p): ?>
                                <option value="<?= $p->nama_produk ?>"><?= $p->nama_produk ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive mt-4" style="overflow-x:auto;">
                    <table class="table table-bordered table-sm" style="min-width:1300px; table-layout:fixed;">
                        <thead class="thead-light">
                            <tr>
                                <th>Jenis Produksi</th>
                                <th>Std. Berat</th>
                                <?php for ($i = 1; $i <= 10; $i++): ?>
                                    <th style="text-align:center;">Input ke-<?= $i ?></th>
                                <?php endfor; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($proses_produksi as $kategori => $params): ?>
                                <tr style="background:#f1f1f1;font-weight:bold;">
                                    <td colspan="12"><?= ucwords(str_replace('_', ' ', $kategori)) ?></td>
                                </tr>

                                <?php foreach ($params as $param):
                                    if (is_array($param)) {
                                        $label = $param['label'];
                                        $subParams = $param['params'];
                                    } else {
                                        $label = $label_param[$param] ?? ucwords(str_replace('_', ' ', $param));
                                        $subParams = [$param];
                                    }
                                ?>
                                    <tr>
                                        <td><?= $label ?></td>
                                        <td>
                                            <?php
                                            if ($kategori == 'kondisi_rm') {
                                                $rawStd = $standar_berat[$subParams[0]] ?? '';
                                                $stdVal = is_array($rawStd) ? $rawStd['default'] : $rawStd;
                                                echo '<input type="text" name="proses_produksi[' . $kategori . '][' . $subParams[0] . '][0]" class="form-control form-control-sm" value="' . $stdVal . '">';
                                            }
                                            ?>
                                        </td>

                                        <?php for ($col = 1; $col <= 10; $col++): ?>
                                            <td class="text-center">
                                                <?php foreach ($subParams as $sub):
                                                    $value = '';
                                                    if (isset($default_mixing[$sub])) $value = $default_mixing[$sub];
                                                    if (isset($default_baking[$sub])) $value = $default_baking[$sub];
                                                    if (isset($default_suhu[$sub])) $value = $default_suhu[$sub];

                                                    $subLower = strtolower($sub);
                                                    $isCheckbox = false;
                                                    $isNumber = false;

                                                    // ✅ Kondisi RM logic
                                                    if ($kategori == 'kondisi_rm' && $subLower !== 'chill_water' && !str_contains($subLower, 'suhu')) {
                                                        if ($col == 8) {
                                                            $rawStd = $standar_berat[$subLower] ?? '';
                                                            $stdVal = is_array($rawStd) ? ($rawStd['col8'] ?? $rawStd['default']) : $rawStd;
                                                            echo '<input type="text" name="proses_produksi[' . $kategori . '][' . $subLower . '][' . $col . ']" class="form-control form-control-sm" value="' . $stdVal . '">';
                                                            continue;
                                                        } else {
                                                            $isCheckbox = true;
                                                        }
                                                    }
                                                    // ✅ New: Checkbox for sensori, hasil_proofing, sensori_produk
                                                    elseif (in_array($subLower, ['sensori', 'hasil_proofing', 'sensori_produk'])) {
                                                        $isCheckbox = true;
                                                    }
                                                    // Numeric input
                                                    elseif ($subLower === 'chill_water' || str_contains($subLower, 'suhu')) {
                                                        $isNumber = true;
                                                    }

                                                    $isKodeProduksi = ($kategori == 'dough_mixing' && $sub == 'kode_produksi');
                                                ?>

                                                    <?php if ($isCheckbox): ?>
                                                        <input type="checkbox"
                                                            name="proses_produksi[<?= $kategori ?>][<?= $sub ?>][<?= $col ?>]"
                                                            value="✗"
                                                            class="checkbox-lg toggle-check">
                                                    <?php elseif ($isNumber): ?>
                                                        <input type="number" step="0.01" name="proses_produksi[<?= $kategori ?>][<?= $sub ?>][<?= $col ?>]" value="<?= $value ?>" class="form-control form-control-sm" placeholder="0.00">
                                                    <?php else: ?>
                                                        <input type="<?= in_array($sub, ['jam_mulai', 'jam_selesai']) ? 'time' : 'text' ?>"
                                                            name="proses_produksi[<?= $kategori ?>][<?= $sub ?>][<?= $col ?>]"
                                                            value="<?= $value ?>"
                                                            class="form-control form-control-sm <?= $isKodeProduksi ? 'kode_produksi_field' : '' ?>"
                                                            id="<?= $isKodeProduksi && $col == 1 ? 'kode_produksi_1' : '' ?>">
                                                    <?php endif; ?>

                                                <?php endforeach; ?>
                                            </td>
                                        <?php endfor; ?>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="form-group row mt-3">
                    <div class="col-sm-6">
                        <label class="font-weight-bold">Catatan</label>
                        <textarea class="form-control" name="catatan"></textarea>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col">
                        <button type="submit" class="btn btn-success mr-2"><i class="fa fa-save"></i> Simpan</button>
                        <a href="<?= base_url('proses') ?>" class="btn btn-danger"><i class="fa fa-times"></i> Batal</a>
                    </div>
                </div>
            </form>

            <script>
                $(document).ready(function() {
                    // --- Autofill Jenis Produk ---

                    $('#select_nama_produk').change(function() {
                        const selectedProdukRaw = $(this).val();
                        const selectedProduk = selectedProdukRaw.trim().toLowerCase();
                        const jenisProdukInputs = $('input[name^="proses_produksi[dough_mixing][nama_produk]"]');

                        jenisProdukInputs.val('');

                        if (selectedProduk === 'bc orange sintetis') {
                            jenisProdukInputs.each(function() {
                                $(this).val('BC Orange');
                            });

                        } else if (selectedProduk.includes('bc mix')) {
                            jenisProdukInputs.each(function(index) {
                                if (index < 7) {
                                    $(this).val('BC Yellow');
                                } else {
                                    $(this).val('BC Orange');
                                }
                            });

                        } else {
                            jenisProdukInputs.each(function() {
                                $(this).val(selectedProdukRaw);
                            });
                        }
                    });

                    // --- Kode Produksi Autofill ---
                    const kodeProduksiInputs = $('.kode_produksi_field');

                    const firstKode = $('#kode_produksi_1');
                    firstKode.on('blur', function() {
                        let baseVal = $(this).val().trim();
                        if (!baseVal) return;
                        if (!baseVal.match(/\d+$/)) {
                            baseVal = `${baseVal}1`;
                            $(this).val(baseVal);
                        }
                        kodeProduksiInputs.each(function(index) {
                            if (index === 0) return;
                            const suffix = index + 1;
                            $(this).val(`${baseVal.replace(/\d+$/, '')}${suffix}`);
                        });
                    });

                    // --- Duration Calculation ---
                    function calculateDuration(start, end) {
                        if (!start || !end) return '';
                        const [startHour, startMin] = start.split(':').map(Number);
                        const [endHour, endMin] = end.split(':').map(Number);
                        let startTotal = startHour * 60 + startMin;
                        let endTotal = endHour * 60 + endMin;
                        if (endTotal < startTotal) endTotal += 24 * 60;
                        return endTotal - startTotal;
                    }

                    $('input[name*="[jam_mulai]"], input[name*="[jam_selesai]"]').on('change', function() {
                        const td = $(this).closest('td');
                        let colIndex = td.index();
                        if (colIndex > 1) colIndex -= 1;
                        const inputIndex = colIndex;
                        const jamMulaiInput = $(`input[name="proses_produksi[proofing][jam_mulai][${inputIndex}]"]`);
                        const jamSelesaiInput = $(`input[name="proses_produksi[proofing][jam_selesai][${inputIndex}]"]`);
                        const durasiInput = $(`input[name="proses_produksi[proofing][durasi_waktu][${inputIndex}]"]`);
                        const jamMulai = jamMulaiInput.val();
                        const jamSelesai = jamSelesaiInput.val();
                        const durasi = calculateDuration(jamMulai, jamSelesai);
                        if (durasi !== '') durasiInput.val(durasi);
                    });

                    // --- Checkbox ✓ ✗ logic ---
                    $(document).on('change', '.toggle-check', function() {
                        if ($(this).is(':checked')) {
                            $(this).val('✓');
                        } else {
                            $(this).val('✗');
                        }
                    });
                });
            </script>

        </div>
    </div>
</div>

<style>
    .checkbox-lg {
        transform: scale(1.5);
        display: block;
        margin: 0 auto;
    }

    td input.form-control {
        width: 100%;
        box-sizing: border-box;
    }

    .breadcrumb {
        background-color: #2E86C1;
    }
</style>