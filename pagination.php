<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<?php
$tab = [0,1,2,3,4,5,6,7,8,9,0,1,2,3,4,5,6,7,8,9,0,1,2,3,4,5,6,7,8,9];

$s=0;
if(isset($_GET['page'])){
    $_GET['page'] = intval($_GET['page']);
    $currentPage= $_GET['page'];
}
else{
    $currentPage = 1;
}
$count = count($tab);
$perPage = 2;
$pages = ceil($count/$perPage);
$offeset = $perPage * ($currentPage - 1);
$tab = array_slice($tab,$offeset, $perPage);
echo '<table border="1" width="400">';
echo '<tr>';
    for ($j=0; $j< sizeof($tab); $j++) {
        echo '<td>';
        echo $tab[$j];
        echo '</td>';
    }

echo '</tr>';
echo '</table>';

$precedent = $currentPage-1;
$suivant = $currentPage + 1;

if($currentPage>1){
    echo ' <a href="pagination.php?page='.$precedent.'"><button>&laquo;Precedent</button></a> ';
}
if($currentPage<$pages){
    echo ' <a href="pagination.php?page='.$suivant.'"><button>Suivant &raquo;</button></a> ';
}



?>
</body>
</html>
