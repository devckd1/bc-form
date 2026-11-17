<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="mb-3">
    <table class="table table-sm table-borderless mb-0">
        <tr>
            <th width="30%">Nama Produk</th>
            <td>: <?= html_escape($formula->nama_produk ?? '-'); ?></td>
        </tr>
        <tr>
            <th>Nama Formulasi</th>
            <td>: <?= html_escape($formula->nama_formulasi ?? '-'); ?></td>
        </tr>
        <tr>
            <th>No. Dokumen</th>
            <td>: <?= html_escape($formula->no_dokumen ?: '-'); ?></td>
        </tr>
        <tr>
            <th>Revisi</th>
            <td>: <?= html_escape($formula->revisi ?: '-'); ?></td>
        </tr>
        <tr>
            <th>Formula</th>
            <td>: <?= nl2br(html_escape($formula->formula ?: '-')); ?></td>
        </tr>
    </table>
</div>

<hr>

<h6 class="font-weight-bold text-primary mb-3">
    <i class="fas fa-flask mr-1"></i>
    Formulasi Bahan
</h6>

<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead class="thead-light text-center">
            <tr>
                <th width="8%">No.</th>
                <th>Nama Bahan</th>
                <th width="30%">Standar Berat</th>
            </tr>
        </thead>

        <tbody>
            <?php if (!empty($detail)): ?>
                <?php $no = 1; ?>
                <?php foreach ($detail as $row): ?>
                    <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td><?= html_escape($row->nama_bahan); ?></td>
                        <td class="text-right">
                            <?= html_escape($row->standar_berat ?? '-'); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" class="text-center text-muted">
                        Belum ada data bahan.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
