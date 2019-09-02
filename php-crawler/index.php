<?php

include '../src/simple_html_dom.php';

$html = file_get_html('https://www.lider.cl/supermercado/category/Frutas-y-Verduras/Frutas/Frutas-de-temporada/_/N-mmwe14');
$p_name = [];
$p_attr = [];
$p_link = [];
$p_price = [];
$emtp_array = []; $arr = [];

foreach ($html->find('span[class=product-name]') as $element)
    $p_name[] = $element->plaintext;

foreach ($html->find('span[class=product-attribute]') as $element)
    $p_attr[] = $element->plaintext;

foreach ($html->find('a[class=product-link]') as $element):
    $tmp = explode('/', $element->href);
    $tmp2 = explode('-', $tmp[3]);

    $emtp_array[] = $tmp2;
endforeach;

foreach ($emtp_array as $i => $a)
    if ($i % 2 == 0)
        $arr[] = $a;

foreach ($arr as $el):
    $desc = '';
    foreach ($el as $i => $vi):
        if ($i > 0)
            $desc .= $vi . " ";
    endforeach;

    $p_link[] = $desc;
endforeach;

foreach ($html->find('span[class=price-sell]') as $element)
    $p_price[] = $element->plaintext;

echo 'FRUTAS<br><br>';
for ($i = 0; $i < count($p_name); $i++):
    echo 'Producto: ' . $p_name[$i] . '<br>';
    echo 'Presentacion: ' . $p_attr[$i] . '<br>';
    echo 'Descripcion: ' . $p_link[$i] . '<br>';
    echo 'Precio: ' . $p_price[$i] . '<br><br>';
endfor;

$html = file_get_html('https://www.lider.cl/supermercado/category/Frutas-y-Verduras/Verduras/Verduras-de-temporada/_/N-1ecstku');
$p_name = [];
$p_attr = [];
$p_link = [];
$p_price = [];
$emtp_array = []; $arr = [];

foreach ($html->find('span[class=product-name]') as $element)
    $p_name[] = $element->plaintext;

foreach ($html->find('span[class=product-attribute]') as $element)
    $p_attr[] = $element->plaintext;

foreach ($html->find('a[class=product-link]') as $element):
    $tmp = explode('/', $element->href);
    $tmp2 = explode('-', $tmp[3]);

    $emtp_array[] = $tmp2;
endforeach;

foreach ($emtp_array as $i => $a)
    if ($i % 2 == 0)
        $arr[] = $a;

foreach ($arr as $el):
    $desc = '';
    foreach ($el as $i => $vi):
        if ($i > 0)
            $desc .= $vi . " ";
    endforeach;

    $p_link[] = $desc;
endforeach;

foreach ($html->find('span[class=price-sell]') as $element)
    $p_price[] = $element->plaintext;

echo 'VERDURAS<br><br>';
for ($i = 0; $i < count($p_name); $i++):
    echo 'Producto: ' . $p_name[$i] . '<br>';
    echo 'Presentacion: ' . $p_attr[$i] . '<br>';
    echo 'Descripcion: ' . $p_link[$i] . '<br>';
    echo 'Precio: ' . $p_price[$i] . '<br><br>';
endfor;
