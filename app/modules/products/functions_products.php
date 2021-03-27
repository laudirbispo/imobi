<?php
/**
 * Funcão recursiva para gerar lista de categorias e subcategorias infinitas
 *
 * @param \Cake\ORM\Table $table A classe Table a ser usada.
 * @param array $array Algum valor em formato array.
 * @param callable $callback Algum callback.
 * @param bool $boolean Algum valor booleano.
 */
 
function recursiveListCategories($categoryId, $ulInitialClass)
{
    $ulInitialClass = ($ulInitialClass === false) ? '' : $ulInitialClass;
    $con_db = new config\connect_db();
    $con = $con_db->connect();

    $categories = $con->query("SELECT * FROM `products_categories` WHERE `category_parent` = '$categoryId' ORDER BY `category_name` ASC");
    $rowsCategories = $categories->num_rows;

    if($categories and $rowsCategories > 0)
    {
    $list = '<ul class="'.$ulInitialClass.'">';

    while ($reg = $categories->fetch_array())
    {
        $parent = recursiveListCategories($reg['category_id'], $ulInitialClass = false);
        $iconClass = ($parent === false) ? '' : 'fa fa-plus' ;

        $list .= '<li>';
        $list .= '<span class="bg-teal flat span-category-name"><i class="'.$iconClass.'"></i> '.$reg['category_name'].'</span>';
        $list .= '<input type="radio" id="'.$reg['category_id'].'" value="'.$reg['category_id'].'" name="category-selected" data-category-name="'.$reg['category_name'].'" >';
        $list .= '<i class="fa fa-check text-success ico-category-selected"></i>';
        $list .= $parent;
        $list .= '</li>';
    }

    $list .= '</ul>';
    return $list;
    }
    else
    {
     return false;
    }
 }