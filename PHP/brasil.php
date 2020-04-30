<?php

$dsn = 'mysql:host=localhost;dbname=brasil;charset=utf8';
$user = 'admin';
$pass = '123456';

$pdo = new PDO($dsn, $user, $pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$query = "SELECT MIN(latitude) AS minlat, MAX(latitude) AS maxlat,
                 MIN(longitude) AS minlng, MAX(longitude) AS maxlng,
                 MIN(populacao) AS minpop, MAX(populacao) AS maxpop
          FROM cidades
          WHERE latitude IS NOT NULL AND longitude IS NOT NULL AND populacao IS NOT NULL";
$result = $pdo->query($query);

$limite = $result->fetch(PDO::FETCH_ASSOC);

$maxlat = $limite['maxlat'] + 90;
$minlat = $limite['minlat'] + 90;
$maxlng = $limite['maxlng'] + 180;
$minlng = $limite['minlng'] + 180;
$diflat = $maxlat - $minlat;
$diflng = $maxlng - $minlng;

$maxpop = $limite['maxpop'];
$minpop = $limite['minpop'];

$options = array('options' => array('default'=>1000, 'min_range'=>500, 'max_range'=>3000));
$larguraMapa = filter_input(INPUT_GET, 'largura', FILTER_VALIDATE_INT, $options);

$alturaMapa = $larguraMapa;
$margem = round($larguraMapa / 50);
$pontomin = round($larguraMapa / 500);
$correcao = round($pontomin - log($minpop, M_E));
$pontomax = round(log($maxpop, M_E)) + $correcao;

$frequencia = 0.2; // 2 * M_PI / $pontomax;
$amplitude = 128;
$centro = 128;
$faseR = 7; // 0;
$faseG = 8; // 2.1;
$faseB = 9; // 4.2;

$cores = array();
for ($incremento = $pontomin; $incremento <= $pontomax; $incremento++) {
    $red = floor(sin($frequencia * $incremento + $faseR) * $amplitude + $centro);
    $grn = floor(sin($frequencia * $incremento + $faseG) * $amplitude + $centro);
    $blu = floor(sin($frequencia * $incremento + $faseB) * $amplitude + $centro);
    $cores[$incremento] = array('R' => $red, 'G' => $grn, 'B' => $blu);
}

$query = "SELECT latitude, longitude, populacao
          FROM cidades
          WHERE latitude IS NOT NULL AND longitude IS NOT NULL AND populacao IS NOT NULL
          ORDER BY populacao";
$result = $pdo->query($query);

if ($result->rowCount() >= 1) {
    $largura = $larguraMapa + (2 * $margem);
    $altura = $alturaMapa + (2 * $margem);
    $imagem = imagecreatetruecolor($largura, $altura);
    $fundo = imagecolorallocate($imagem, 255, 255, 255);
    imagefill($imagem, 0, 0, $fundo);
    
    while ($cidade = $result->fetch(PDO::FETCH_ASSOC)) {
        $latitude = $cidade['latitude'] + 90;
        $longitude = $cidade['longitude'] + 180;
        $x = round((($longitude - $minlng) / $diflng) * $alturaMapa) + $margem;
        $y = round((($maxlat - $latitude) / $diflat) * $larguraMapa) + $margem;
        $ponto = round(log($cidade['populacao'], M_E)) + $correcao;
        $cor = imagecolorallocate($imagem, $cores[$ponto]['R'], $cores[$ponto]['G'], $cores[$ponto]['B']);
        imagefilledellipse($imagem, $x, $y, $ponto, $ponto, $cor);
    }
    
    /*
    ob_start();
    imagepng($imagem);
    $bin = ob_get_clean();
    $base64 = 'data:image/png;base64,' . base64_encode($bin);
    echo '<!DOCTYPE html><div><img src="'.$base64.'" width="'.$largura.'" height="'.$altura.'" /></div>';
    */
    
    header("Content-Type: image/png");
    header('Content-Disposition: inline; filename="brasil.png"');
    imagepng($imagem);
    
    imagedestroy($imagem);
}
