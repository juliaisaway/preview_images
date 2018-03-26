<?php
    // Pega o valor da URL ativa
    $url = (isset($_GET['url']))?$_GET['url']:'';
    $url = explode('/', $url);
    $page = strtolower($url[0]);

    if(isset($page) && ($page == "" || !file_exists($page)))
        $page = "default";


    // Busca as imagens, cria uma array com as mesmas e contabiliza-as
    $images = array_map('basename', glob($page.'/'."*.{jpg,gif,png}", GLOB_BRACE));
    $pg = (isset($_GET["pag"]))?$_GET["pag"]:1;
    $link = (count($images) < ($pg+1))?1:$pg+1;

?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ilton.me - Preview - Página <?= $pg.' de '.count($images) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0/css/bootstrap-grid.min.css">
    <style type="text/css">
        <?php foreach($images as $key => $row) {

            // Pega o tamanho de cada imagem
            list($width, $height) = getimagesize(dirname(__FILE__).'/'.$page.'/'.$row);

            // Pega a proporção de cada imagem
            $ratio = $width / $height;
            $percentage = 1 / $ratio * 100;
            $new_key = $key + 1;

            // Gera o CSS para cada imagem com tamanho de porcentagem
            $code = <<<CODE

        .c{$new_key} {background-image:url("http://{$_SERVER['HTTP_HOST']}/preview/{$page}/{$row}");background-size:cover;}
        .c{$new_key}::before{content:'';float: left;padding-bottom: {$percentage}%;margin-right: -100%;}
        .c{$new_key}::after{content:'';display:table;clear:both;}
        .f{$new_key} {background-image:url("http://{$_SERVER['HTTP_HOST']}/preview/{$page}/{$row}");height:{$height}px;}
CODE;

            echo $code;
            echo "\n";
           }
        ?>

        body{margin:0; border:0; padding:0;width:100%}

        a,a:hover,a:focus,a:active{text-decoration:none;color:inherit;}

        .fundo{background-position:top center;background-repeat:no-repeat};

        #resolution{cursor:pointer;}

        #fixa{position:relative;padding:5px;color:#000;font:12px Arial;background:#fff;border:1px solid #ccc;opacity:0.7;text-align:center;}

    </style>
</head>
<body>

	<div id="fixa">
		<div class="container">
			<div class="row justify-content-between">
				<span>Site melhor visualizado em resolução 1920x1080</span>
				<span id="resolution" onclick="resolution()">Ver em resolução original</span>
				<span>Clique na tela para alterar a página</span>
			</div>
		</div>
	</div>

	<a href="?pag=<?= $link ?>" title="">
	    <div id="page" class="c<?= $pg ?> fundo"></div>
	</a>


    <script>
        function resolution() {
            var element = document.getElementById("page");

            if (element.classList) { 
                element.classList.toggle("f<?= $pg ?>");
                document.getElementById("resolution").textContent="Ver em resolução proporcional";
            } else {
                var classes = element.className.split(" ");
                var i = classes.indexOf("f<?= $pg ?>");
                if (i >= 0) 
                    classes.splice(i, 1);
                else 
                    classes.push("f<?= $pg ?>");
                    element.className = classes.join(" ");
            }
        }
    </script>

</body>
</html>