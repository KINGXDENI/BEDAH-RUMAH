<div class="page-header">
    <h1>Perhitungan</h1>
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
            $categories = array();
            $series = array();
            foreach ($rank as $key => $val) :
                $categories[$key] = $ALTERNATIF[$key];
                $series[$key] = $saw->total[$key] * 1; ?>
                <tr>
                    <td><?= $val ?></td>
                    <td><?= $key ?></td>
                    <td><?= $ALTERNATIF[$key] ?></td>
                    <td><?= round($saw->total[$key], 4) ?></td>
                </tr>
            <?php endforeach ?>
        </table>
    </div>
    <div class="panel-body">
        <a class="btn btn-default" target="_blank" href="cetak.php?m=hitung"><span class="glyphicon glyphicon-print"></span> Cetak</a>
    </div>
</div>
<div class="panel panel-primary">
    <div class="panel-body">
        <script src="assets/highcharts/highcharts.js"></script>
        <script src="assets/highcharts/modules/exporting.js"></script>
        <script src="assets/highcharts/modules/export-data.js"></script>
        <script src="assets/highcharts/modules/accessibility.js"></script>

        <figure class="highcharts-figure">
            <div id="container"></div>
        </figure>

        <script>
            Highcharts.chart('container', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Grafik Hasil Perhitungan'
                },
                xAxis: {
                    categories: <?= json_encode(array_values($categories)) ?>,
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Total'
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y:.3f}</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [{
                    name: 'Total',
                    data: <?= json_encode(array_values($series)) ?>

                }]
            });
        </script>
    </div>
</div>