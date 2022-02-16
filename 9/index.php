<?php
 
$random_data <?php
 
$random_data = [32508, 98134, "234984", "3249832", "38", 123, "Apple"];
 
sort($random_data);
echo "Regular Sorting  — ";
foreach($random_data as $element) {
    echo str_pad($element, 9)." ";
}
// Regular Sorting  — 38        123       32508     98134     234984    3249832   Apple
 
sort($random_data, SORT_NUMERIC);
echo "\nNumeric Sorting  — ";
foreach($random_data as $element) {
    echo str_pad($element, 9)." ";
}
// Numeric Sorting  — Apple     38        123       32508     98134     234984    3249832
 
sort($random_data, SORT_STRING);
echo "\nString Sorting   — ";
foreach($random_data as $element) {
    echo str_pad($element, 9)." ";
}
// String Sorting   — 123       234984    3249832   32508     38        98134     Apple= [32508, 98134, "234984", "3249832", "38", 123, "Apple"];
 
sort($random_data);
echo "Regular Sorting  — ";
foreach($random_data as $element) {
    echo str_pad($element, 9)." ";
}
// Regular Sorting  — 38        123       32508     98134     234984    3249832   Apple
 
sort($random_data, SORT_NUMERIC);
echo "\nNumeric Sorting  — ";
foreach($random_data as $element) {
    echo str_pad($element, 9)." ";
}
// Numeric Sorting  — Apple     38        123       32508     98134     234984    3249832
 
sort($random_data, SORT_STRING);
echo "\nString Sorting   — ";
foreach($random_data as $element) {
    echo str_pad($element, 9)." ";
}
// String Sorting   — 123       234984    3249832   32508     38        98134     Apple


//Реализовать удаление элемента массива по его значению

$myArr = array(1,2,3,4,5);
foreach($myArr as $key => $item){
    if ($item == 3){
      unset($myArr[$key]);
    }
}