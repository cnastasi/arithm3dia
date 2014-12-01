<?php
  include './classes/wordcloud.class.php';
?>

<link rel="stylesheet" href="./css/wordcloud.css" type="text/css">
<?php

$name = isset($_GET['name']) ? $_GET['name'] : 'obama';

$connection = mysql_connect('localhost', 'root', '') or die("Connessione non riuscita: " . mysql_error());

$db = mysql_select_db('arithm3dia', $connection);

$query = "SELECT T1.name as name1, T2.name as name2, L.weight FROM terms T join links L ON (T.id = L.link1 OR T.id = L.link2) join terms T1 on T1.id = L.link1 join terms T2 on T2.id = L.link2 where T.name = '{$name}' order by L.weight DESC";

$result = mysql_query($query);

$cloud = new wordCloud();

while (($row = mysql_fetch_array($result)) != null) {
    if ($row['name1'] === $name) {
        $n = $row['name2'];
    }
    else {
        $n = $row['name1'];
        
        
    }
    
    $cloud->addWord(['word'=>$n, 'size'=>$row['weight'], 'url'=>"http://localhost/test/index.php?name={$n}"]); // Basic Method of Adding Keyword
    
}

//$cloud->addWord(array('word' => 'google', 'size' => 5, 'url' => 'http://www.google.com')); // Advanced user method
//$cloud->addWord(array('word' => 'digg', 'url' => 'http://digg.com'));
//$cloud->addWord(array('word' => 'lotsofcode', 'size' => 4, 'url' => 'http://www.lotsofcode.com'));
$myCloud = $cloud->showCloud('array');
    
foreach ($myCloud as $cloudArray) {
  echo ' &nbsp; <a href="'.$cloudArray['url'].'" class="word size'.$cloudArray['range'].'">'.$cloudArray['word'].'</a> &nbsp;';
}

