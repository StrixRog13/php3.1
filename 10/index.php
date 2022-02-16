<?php 
$A = array(
             'A' => array(
                            'B' => array( 'X', 'D', 'Y'),
                            'C' => array( 'Z' )
                            )
              );
function get_child($arr,$level=0){
    $res = '';
    if(is_array($arr)){
        foreach ($arr as $k => $value) {
            $res.= '<br/>'.str_pad("", $level, '-').$k;
            if(is_array($value))$res .= get_child($value,$level+1);
            if(is_string($value))$res .= '<br/>'.str_pad("", $level, '-').$value;
        }
    }
    return $res;
    return '<br/>'.str_pad("", $level, '-').$res;
}
echo get_child($A);


//CREATE  TABLE `categories` (
//`id` INT  NOT NULL AUTO_INCREMENT ,
//`parent_id`  INT NOT NULL ,
//`name`  VARCHAR( 50 ) NOT NULL ,
//PRIMARY KEY  ( `id` )
//);

//INSERT INTO `categories` (`id`, `parent_id`, `name`) VALUES
//(1, 0, 'Раздел 1'),
//(2, 0, 'Раздел 2'),
//(3, 0, 'Раздел 3'),
//(4, 1, 'Раздел 1.1'),
//(5, 1, 'Раздел 1.2'),
//(6, 4, 'Раздел 1.1.1'),
//(7, 2, 'Раздел 2.1'),
//(8, 2, 'Раздел 2.2'),
//(9, 3, 'Раздел 3.1');

//Выбираем данные из БД
//$result=mysql_query("SELECT * FROM  categories");
////Если в базе данных есть записи, формируем массив
//if   (mysql_num_rows($result) > 0){
//    $cats = array();
////В цикле формируем массив разделов, ключом будет id родительской категории, а также массив разделов, ключом будет id категории
//    while($cat =  mysql_fetch_assoc($result)){
//        $cats_ID[$cat['id']][] = $cat;
//        $cats[$cat['parent_id']][$cat['id']] =  $cat;
//    }
//}

//<ul>
//    <li>Раздел 1
//        <ul>
//            <li>Раздел 1.1
//                <ul>
//                    <li>Раздел 1.1.1</li>
//                </ul>
//            </li>
//            <li>Раздел 1.2</li>
//        </ul>
//    </li>
//    <li>Раздел 2
//        <ul>
//            <li>Раздел 1.1</li>
//            <li>Раздел 1.2</li>
//        </ul>
//    </li>
//    <li>Раздел 3
//        <ul>
//            <li>Раздел 3.1</li>
//        </ul>
//    </li>
//</ul>

//function build_tree($cats,$parent_id,$only_parent = false){
//    if(is_array($cats) and isset($cats[$parent_id])){
//        $tree = '<ul>';
//        if($only_parent==false){
//            foreach($cats[$parent_id] as $cat){
//                $tree .= '<li>'.$cat['name'].' #'.$cat['id'];
//                $tree .=  build_tree($cats,$cat['id']);
//                $tree .= '</li>';
//            }
//        }elseif(is_numeric($only_parent)){
//            $cat = $cats[$parent_id][$only_parent];
//            $tree .= '<li>'.$cat['name'].' #'.$cat['id'];
//            $tree .=  build_tree($cats,$cat['id']);
//            $tree .= '</li>';
//        }
//        $tree .= '</ul>';
//    }
//    else return null;
//    return $tree;
//}

//echo build_tree($cats,0);

//function find_parent ($tmp, $cur_id){
//    if($tmp[$cur_id][0]['parent_id']!=0){
//        return find_parent($tmp,$tmp[$cur_id][0]['parent_id']);
//    }
//    return (int)$tmp[$cur_id][0]['id'];
//}//

//echo build_tree($cats,0,find_parent($cats_ID,ВАШ_ID_КАТЕГОРИИ));