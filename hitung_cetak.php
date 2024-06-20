<div class="page-header">
    <h2>Perhitungan</h2>
</div>
<?php
$rel_alternatif = get_rel_alternatif();
foreach ($rel_alternatif as $key => $val) {
    foreach ($val as $k => $v) {
        $data[$key][$k] = $CRIPS[$v]->nilai;
    }
}
foreach ($KRITERIA as $key => $val) {
    $atribut[$key] = $val->atribut;
    $bobot[$key] = $val->bobot;
}

$saw = new SAW($data, $atribut, $bobot);
//echo '<pre>' . print_r($saw, 1) . '</pre>';
?>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Hasil Analisa</h3>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    <?php foreach ($KRITERIA as $key => $val) : ?>
                        <th><?= $val->nama_kriteria ?></th>
                    <?php endforeach ?>
                </tr>
            </thead>
            <?php foreach ($rel_alternatif as $key => $value) : ?>
                <tr>
                    <td><?= $key ?></td>
                    <td><?= $ALTERNATIF[$key] ?></td>
                    <?php foreach ($value as $k => $v) : ?>
                        <td><?= $CRIPS[$v]->nama_crips ?></td>
                    <?php endforeach ?>
                </tr>
            <?php endforeach ?>
        </table>
    </div>
</div>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Nilai Alternatif</h3>
    </div>
    <table class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th>Kode</th>
                <?php foreach ($KRITERIA as $key => $val) : ?>
                    <th><?= $key ?></th>
                <?php endforeach ?>
        </thead>
        <?php
        $no = 1;
        foreach ($rel_alternatif as $key => $value) : ?>
            <tr>
                <td><?= $key ?></td>
                <?php foreach ($value as $k => $v) : ?>
                    <td><?= $CRIPS[$v]->nilai ?></td>
                <?php endforeach ?>
            </tr>
        <?php endforeach ?>
    </table>
</div>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Kriteria</h3>
    </div>
    <table class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama</th>
                <th>Bobot</th>
                <th>Bobot Normal</th>
                <th>Max</th>
                <th>Min</th>
        </thead>
        <?php
        $no = 1;
        foreach ($KRITERIA as $key => $val) : ?>
            <tr>
                <td><?= $key ?></td>
                <td><?= $val->nama_kriteria ?></td>
                <td><?= $saw->bobot[$key] ?></td>
                <td><?= round($saw->bobot_normal[$key], 4) ?></td>
                <td><?= $saw->minmax[$key]['max'] ?></td>
                <td><?= $saw->minmax[$key]['min'] ?></td>
            </tr>
        <?php endforeach ?>
    </table>
</div>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Normalisasi</h3>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>Kode</th>
                    <?php foreach ($KRITERIA as $key => $val) : ?>
                        <th><?= $key ?></th>
                    <?php endforeach ?>
                </tr>
            </thead>
            <?php foreach ($saw->normal as $key => $val) : ?>
                <tr>
                    <td><?= $key ?></td>
                    <?php foreach ($val as $k => $v) : ?>
                        <td><?= round($v, 4) ?></td>
                    <?php endforeach ?>
                </tr>
            <?php endforeach ?>
        </table>
    </div>
</div>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Perangkingan</h3>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Total</th>
            </thead>
            <?php
            $rank = get_rank($saw->total);
            foreach ($rank as $key => $val) : ?>
                <tr>
                    <td><?= $val ?></td>
                    <td><?= $key ?></td>
                    <td><?= $ALTERNATIF[$key] ?></td>
                    <td><?= round($saw->total[$key], 4) ?></td>
                </tr>
            <?php endforeach ?>
        </table>
    </div>
</div>