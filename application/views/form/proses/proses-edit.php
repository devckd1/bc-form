
<?php
defined('BASEPATH') or exit('No direct script access allowed');

$is_audit = isset($mode) && $mode === 'audit';

$back_url = $is_audit
    ? 'proses/audit'
    : 'proses';

$form_url = $is_audit
    ? 'proses/edit-audit/' . rawurlencode($proses->uuid)
    : 'proses/edit/' . rawurlencode($proses->uuid);

$tanggal_sess = set_value('date', $proses->date);
$shift_sess = set_value('shift', $proses->shift);
$nama_produk_sess = set_value('nama_produk', $proses->nama_produk);

$decoded_proses = json_decode($proses->proses_produksi ?? '', true);
$proses_data = is_array($decoded_proses) ? $decoded_proses : [];

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
    'tepung_terigu' => '',
    'tepung_tapioka' => '2.270',
    'yeast' => '0.372',
    'bread_improver' => '',
    'premix' => '',
    'shortening' => '0.252',
    'chill_water' => '18'
];

$default_mixing = [
    'waktu_mixing_1' => 3,
    'waktu_mixing_2' => 8
];

$default_baking = [
    'baking_time_high' => 5,
    'baking_time_low' => 7
];
?>

<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-3">
        <h1 class="h3 mb-2 text-gray-800">
            <?= $is_audit ? 'Edit Audit Proses Produksi' : 'Edit Verifikasi Proses Produksi' ?>

            <?php if ($is_audit): ?>
                <span class="badge-mode-audit">MODE AUDIT</span>
            <?php endif; ?>
        </h1>
    </div>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?= base_url($back_url) ?>">
                    <i class="fas fa-arrow-left"></i>
                    <?= $is_audit ? 'Daftar Audit Proses Produksi' : 'Daftar Laporan Verifikasi Proses Produksi' ?>
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Edit</li>
        </ol>
    </nav>

	<!-- Info Audit -->
	<?php if ($is_audit): ?>
		<div class="alert" style="background-color: #f3e8ff; border-left: 4px solid #6f42c1; color: #4b2a75;">
			<i class="fas fa-info-circle mr-1"></i>

			<strong>Informasi:</strong>

			Halaman ini merupakan menu Edit Proses Produksi.
			Perubahan data pada halaman ini tidak akan mengubah data aktual
			pada menu pemeriksaan.
		</div>
	<?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-body">

            <form class="user" method="post" action="<?= base_url($form_url) ?>">

                <div class="form-group row">
                    <div class="col-md-3">
                        <label class="font-weight-bold">Tanggal</label>
                        <input
                            type="date"
                            name="date"
                            class="form-control"
                            value="<?= html_escape($tanggal_sess) ?>"
                            required>
                    </div>

                    <div class="col-md-3">
                        <label class="font-weight-bold">Shift</label>
                        <select name="shift" class="form-control" required>
                            <?php for ($s = 1; $s <= 3; $s++): ?>
                                <option value="<?= $s ?>" <?= (string) $shift_sess === (string) $s ? 'selected' : '' ?>>
                                    Shift <?= $s ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="font-weight-bold">Nama Produk</label>
                        <select name="nama_produk" id="select_nama_produk" class="form-control" required>
                            <option value="">-- Pilih Produk --</option>

                            <?php foreach ($produk_list as $p): ?>
                                <option
                                    value="<?= html_escape($p->nama_produk) ?>"
                                    <?= $nama_produk_sess == $p->nama_produk ? 'selected' : '' ?>>
                                    <?= html_escape($p->nama_produk) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="table-responsive mt-4" style="overflow-x:auto;">
                    <table class="table table-bordered table-sm" style="min-width:1300px; table-layout:fixed;">
                        <thead class="thead-light">
                            <tr>
                                <th>Jenis Produksi</th>
                                <th>Std. Berat</th>
                                <?php for ($i = 1; $i <= 10; $i++): ?>
                                    <th class="text-center">Input ke-<?= $i ?></th>
                                <?php endfor; ?>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($proses_produksi as $kategori => $params): ?>
                                <tr style="background:#f1f1f1;font-weight:bold;">
                                    <td colspan="12">
                                        <?= ucwords(str_replace('_', ' ', $kategori)) ?>
                                    </td>
                                </tr>

                                <?php foreach ($params as $param): ?>
                                    <?php
                                    if (is_array($param)) {
                                        $label = $param['label'];
                                        $subParams = $param['params'];
                                    } else {
                                        $label = $label_param[$param] ?? ucwords(str_replace('_', ' ', $param));
                                        $subParams = [$param];
                                    }
                                    ?>

                                    <tr>
                                        <td><?= html_escape($label) ?></td>

                                        <td>
                                            <?php if ($kategori === 'kondisi_rm'): ?>
                                                <?php
                                                $stdParam = $subParams[0];
                                                $stdVal = $proses_data[$kategori][$stdParam][0]
                                                    ?? $standar_berat[$stdParam]
                                                    ?? '';
                                                ?>
                                                <input
                                                    type="text"
                                                    name="proses_produksi[<?= $kategori ?>][<?= $stdParam ?>][0]"
                                                    class="form-control form-control-sm"
                                                    value="<?= html_escape((string) $stdVal) ?>">
                                            <?php endif; ?>
                                        </td>

                                        <?php for ($col = 1; $col <= 10; $col++): ?>
                                            <td class="text-center">
                                                <?php foreach ($subParams as $sub): ?>
                                                    <?php
                                                    $value = $proses_data[$kategori][$sub][$col] ?? '';

                                                    if ($value === '' && isset($default_mixing[$sub])) {
                                                        $value = $default_mixing[$sub];
                                                    }

                                                    if ($value === '' && isset($default_baking[$sub])) {
                                                        $value = $default_baking[$sub];
                                                    }

                                                    $subLower = strtolower($sub);
                                                    $isCheckbox = false;
                                                    $isNumber = false;

                                                    if (
                                                        $kategori === 'kondisi_rm'
                                                        && $subLower !== 'chill_water'
                                                        && !str_contains($subLower, 'suhu')
                                                    ) {
                                                        if ($col === 8) {
                                                            ?>
                                                            <input
                                                                type="text"
                                                                name="proses_produksi[<?= $kategori ?>][<?= $sub ?>][<?= $col ?>]"
                                                                class="form-control form-control-sm"
                                                                value="<?= html_escape((string) $value) ?>">
                                                            <?php
                                                            continue;
                                                        }

                                                        $isCheckbox = true;
                                                    } elseif (in_array($subLower, ['sensori', 'hasil_proofing', 'sensori_produk'], true)) {
                                                        $isCheckbox = true;
                                                    } elseif ($subLower === 'chill_water' || str_contains($subLower, 'suhu')) {
                                                        $isNumber = true;
                                                    }

                                                    $isKodeProduksi = ($kategori === 'dough_mixing' && $sub === 'kode_produksi');
                                                    $checked = in_array((string) $value, ['✓', '1'], true);
                                                    ?>

                                                    <?php if ($isCheckbox): ?>
                                                        <input
                                                            type="checkbox"
                                                            class="checkbox-lg toggle-check"
                                                            name="proses_produksi[<?= $kategori ?>][<?= $sub ?>][<?= $col ?>]"
                                                            value="✓"
                                                            <?= $checked ? 'checked' : '' ?>>

                                                    <?php elseif ($isNumber): ?>
                                                        <input
                                                            type="number"
                                                            step="0.01"
                                                            name="proses_produksi[<?= $kategori ?>][<?= $sub ?>][<?= $col ?>]"
                                                            value="<?= html_escape((string) $value) ?>"
                                                            class="form-control form-control-sm"
                                                            placeholder="0.00">

                                                    <?php else: ?>
                                                        <input
                                                            type="<?= in_array($sub, ['jam_mulai', 'jam_selesai'], true) ? 'time' : 'text' ?>"
                                                            name="proses_produksi[<?= $kategori ?>][<?= $sub ?>][<?= $col ?>]"
                                                            value="<?= html_escape((string) $value) ?>"
                                                            class="form-control form-control-sm <?= $isKodeProduksi ? 'kode_produksi_field' : '' ?>"
                                                            <?= $isKodeProduksi && $col === 1 ? 'id="kode_produksi_1"' : '' ?>>
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
                        <textarea class="form-control" name="catatan"><?= html_escape(set_value('catatan', $proses->catatan ?? '')) ?></textarea>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col">
                        <button type="submit" class="btn btn-success mr-2">
                            <i class="fa fa-save"></i> Simpan
                        </button>

                        <a href="<?= base_url($back_url) ?>" class="btn btn-danger">
                            <i class="fa fa-times"></i> Batal
                        </a>
                    </div>
                </div>

            </form>
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

    .badge-mode-audit {
        display: inline-block;
        background: #5a3298;
        color: #fff;
        font-size: 12px;
        font-weight: 600;
        padding: 6px 10px;
        border-radius: 4px;
        vertical-align: middle;
        margin-left: 6px;
    }
</style>

<script>
$(document).ready(function () {
    // Pastikan checkbox yang dicentang mengirim nilai ✓.
    $('input[type="checkbox"]').each(function () {
        $(this).val($(this).is(':checked') ? '✓' : '');
    });

    function parseIndexFromName(name) {
        if (!name) return null;

        const match = name.match(/\[(\d+)\]$/);
        return match ? parseInt(match[1], 10) : null;
    }

    function calcDurationByIndex(idx) {
        if (!idx || isNaN(idx)) return;

        const jamMulaiSel = $(`input[name="proses_produksi[proofing][jam_mulai][${idx}]"]`);
        const jamSelesaiSel = $(`input[name="proses_produksi[proofing][jam_selesai][${idx}]"]`);
        const durasiSel = $(`input[name="proses_produksi[proofing][durasi_waktu][${idx}]"]`);

        if (!durasiSel.length) return;

        const jamMulai = jamMulaiSel.val();
        const jamSelesai = jamSelesaiSel.val();

        if (!jamMulai || !jamSelesai) {
            durasiSel.val('');
            return;
        }

        const sParts = jamMulai.split(':').map(Number);
        const eParts = jamSelesai.split(':').map(Number);

        if (
            sParts.length !== 2 ||
            eParts.length !== 2 ||
            sParts.some(isNaN) ||
            eParts.some(isNaN)
        ) {
            durasiSel.val('');
            return;
        }

        const start = sParts[0] * 60 + sParts[1];
        let end = eParts[0] * 60 + eParts[1];

        if (end < start) end += 24 * 60;

        durasiSel.val(end - start);
    }

    $(document).on(
        'change',
        'input[name*="[jam_mulai]"], input[name*="[jam_selesai]"]',
        function () {
            const idx = parseIndexFromName($(this).attr('name') || '');
            if (idx) calcDurationByIndex(idx);
        }
    );

    $('input[name*="[jam_mulai]"]').each(function () {
        const idx = parseIndexFromName($(this).attr('name') || '');
        if (idx) calcDurationByIndex(idx);
    });

    $(document).on('change', 'input[type="checkbox"]', function () {
        $(this).val($(this).is(':checked') ? '✓' : '');
    });
});
</script>
