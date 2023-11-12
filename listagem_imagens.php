<?php
function listarImagens($diretorio, $pastaInicial) {
    global $ultimoDiretorio;
    global $ultimoDiretorioSub;
    $imagens = glob("$diretorio/*.{jpg,jpeg,png,gif}", GLOB_BRACE);

    if (!empty($imagens)) {
        $imagem = reset($imagens); 
        echo "
            <div class='col-lg-4 col-md-4 col-sm-4'>
                <a href='index.php?tecnologia=$pastaInicial' class='card-tecnologia'>
                    <div class='card-texto-tecnologia'>
                        <h2><b>$pastaInicial</b></h2>
                    </div>
                    <figure>
                        <div class='overlay'></div>
                        <img src='$imagem' alt='$pastaInicial' class='img-responsive'>
                    </figure>
                </a>
            </div>
        ";
    }

    $subpastas = glob("$diretorio/*", GLOB_ONLYDIR);

    foreach ($subpastas as $subpasta) {
        if (dirname($subpasta) != $ultimoDiretorioSub) {
            listarImagens($subpasta, $pastaInicial, 'N');
        }

        $ultimoDiretorioSub = dirname($subpasta);
    }
}

function listarImagensSub($diretorio, $pastaInicial) {
    $imagens = glob("$diretorio/*.{jpg,jpeg,png,gif}", GLOB_BRACE);

    if (!empty($imagens)) {
        $imagem = reset($imagens);
        echo "
            <div class='col-lg-4 col-md-4 col-sm-4'>
                <a href='index.php?tecnologia=" . basename(dirname($diretorio)) . "&&tipo_tecnologia=$pastaInicial' class='card-tecnologia'>
                    <div class='card-texto-tecnologia'>
                        <h2><b>" . $pastaInicial . "</b></h2>
                    </div>
                    <figure>
                        <div class='overlay'></div>
                        <img src='$imagem' alt='Imagem' class='img-responsive'>
                    </figure>
                </a>
            </div>
        ";
    }

    $subpastas = glob("$diretorio/*", GLOB_ONLYDIR);

    foreach ($subpastas as $subpasta) {
        listarImagensSub($subpasta, $pastaInicial);
    }
}

function listarImagensTipo($diretorio, $pastaInicial) {
    $imagens = glob("$diretorio/*.{jpg,jpeg,png,gif}", GLOB_BRACE);
    $extensoesPermitidas = array('jpg', 'jpeg', 'png', 'gif', 'bmp');
    $conteudoDiretorio = array_diff(scandir($diretorio), array('.', '..'));
    $existemArquivosValidos = false;

    foreach ($imagens as $imagem) {
        $infoDoArquivo = pathinfo($imagem);
        echo "
            <div class='col-lg-4 col-md-4 col-sm-4'>
                <a href='$imagem' class='fh5co-card-item image-popup'>
                    <figure>
                        <div class='overlay'></div>
                        <img src='$imagem' alt='Imagem' class='img-responsive'>
                    </figure>
                </a>
            </div>
        ";
    }

    $subpastas = glob("$diretorio/*", GLOB_ONLYDIR);

    foreach ($subpastas as $subpasta) {
        listarImagens($subpasta, $subpasta);
    }
}

if (empty($_GET['tecnologia'])) {
    $diretorioRaiz = "./images/";
    $subpastas = glob("$diretorioRaiz/*", GLOB_ONLYDIR);

    foreach ($subpastas as $subpasta) {
        listarImagens($subpasta, basename($subpasta));
    }
} elseif (isset($_GET['tecnologia']) && empty($_GET['tipo_tecnologia'])) {
    $diretorioRaiz = "./images/" . $_GET['tecnologia'];
    $subpastas = glob("$diretorioRaiz/*", GLOB_ONLYDIR);

    foreach ($subpastas as $subpasta) {
        listarImagensSub($subpasta, basename($subpasta));
    }
} elseif (isset($_GET['tecnologia']) && isset($_GET['tipo_tecnologia'])) {
    $pastaInicial = $_GET['tecnologia'];
    $tipo = $_GET['tipo_tecnologia'];
    $diretorioRaiz = "./images/$pastaInicial/$tipo";

    listarImagensTipo($diretorioRaiz, $pastaInicial);
}
?>
