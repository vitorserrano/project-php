<?php

require("../config/conexao.php");

$dados = array(
    'titulo' => $_POST['titulo_postagem'],
    'resumo' => $_POST['resumo_postagem'],
    'conteudo' => $_POST['conteudo_postagem'],
    'urlImg' => $_POST['url_postagem'],
    'autor' => $_POST['autor_postagem'],
);

if (trim(empty($dados['titulo']))) {
    header('location: ../formulario.php?titulo=false');
    exit;    
}

if (trim(empty($dados['resumo']))) {
    header('location: ../formulario.php?resumo=false');
    exit;
}

if (trim(empty($dados['conteudo']))) {
    header('location: ../formulario.php?conteudo=false');
    exit;
}

if (trim(empty($dados['urlImg']))) {
    header('location: ../formulario.php?urlImg=false');
    exit;
}

if (trim(empty($dados['autor']))) {
    header('location: ../formulario.php?autor=false');
    exit;
}

$consultarTitulo = ("SELECT titulo_postagem FROM `postagem` WHERE titulo_postagem = :titulo");
$stmt = Db::connection()->prepare($consultarTitulo);
$stmt->bindValue(":titulo", $dados['titulo']);
$stmt->execute();
$retorno = $stmt->rowCount();

if ($retorno > 0) {
     header('location: ../formulario.php?erro=true');     
    exit;
} else {
    date_default_timezone_set('America/Sao_Paulo');
    $hoje = date('Y-m-d');

    $criarPostagem = ("INSERT INTO `postagem` 
                        (`titulo_postagem`, `resumo_postagem`, `conteudo_postagem`, `url_postagem`, `data_postagem`, `autor_postagem`) 
                            VALUES 
                        (:titulo, :resumo, :conteudo, :urlimg, :data_postagem, :autor)");
    
    $exec = Db::connection()->prepare($criarPostagem);
    $exec->bindValue(":titulo", $dados['titulo']);
    $exec->bindValue(":resumo", $dados['resumo']);
    $exec->bindValue(":conteudo", $dados['conteudo']);
    $exec->bindValue(":urlimg", $dados['urlImg']);
    $exec->bindValue(":data_postagem", $hoje);
    $exec->bindValue(":autor", $dados['autor']);
    
    $r = $exec->execute();
    
    if($r){
        header('location: ../formulario.php?success=true');
        exit;
    }else{
        header('location: ../login.php?success=true');
        exit;
    }

};

