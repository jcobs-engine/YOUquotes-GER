<?php

//       SETUSER

$userid=$_COOKIE['userid'];

if($userid == "")
{
    setcookie( 'userid', rand(11111111, 99999999), strtotime( '+1 years' ) );
    $load='document.formular.submit();';
}

// [END] SETUSER

$host = "localhost";
$benutzer = '*USER*';
$passwort = '*PASSWORD*';
$bindung = mysqli_connect($host, $benutzer, $passwort) or die("Verbindungsaufbau zur Daten-Zentrale nicht m&ouml;glich!");
$db = '*DATABASE*';

function mdq($bindung, $query)
{
    mysqli_select_db($bindung, 'YOUquotes');
    return mysqli_query($bindung, $query);
}

//       STYLE

$style="

body{
background-color:#2fa34e;
}

#quotediv{
cursor:default;
position:fixed;
top:50%;
width:60%;
left:50%;
color:black;
text-shadow:0px 0px 4px black;
padding:20px;
font-size:25px;
font-family:sans-serif;
box-shadow:0px 0px 5px black;
border-radius:10px;
transform: translate( -50%, -50px );
background-color:#1f6e34;
}

#LIKESquotediv{
cursor:default;
position:relative;
width:calc( 60% - 40px);
left:20%;
color:black;
text-shadow:0px 0px 4px black;
padding:20px;
margin-bottom:20px;
font-size:25px;
font-family:sans-serif;
box-shadow:0px 0px 5px black;
border-radius:10px 0px 10px 0px;
background-color:#1f6e34;
}

#vonspan{
cursor:default;
float:right;
padding:10px;
padding-bottom:0px;
font-size:18px;
background-color:#2fa34e;
padding:5px;
padding-left:15px;
padding-right:15px;
border-radius:4px;'
}

#heart{
width:50px;
position:absolute;
top:-20px;
right:-26px;
cursor:pointer;
}

#arrow{
width:50px;
position:absolute;
top:-20px;
left:-26px;
cursor:pointer;
background-color:#2fa34e;
border-radius:25px;
}

#LIKESdiv{
position:absolute;
top:100%;
width:100%;
left:0px;
background-color:rgba(0%, 0%, 0%, 0.8);
box-shadow:0px 0px 10px black;
padding-top:20px;
}
";

// [END] STYLE

// =========================================== //

//       CONTENT

$delete=$_POST['delete'];

$content="
<input type='hidden' name='delete' value='$delete' id='deleta'>
<a href='add.php' target='_blank'><img src='add.png' style='position:fixed;top:10px;right:10px;width:80px;'></a>
<img src='logo.png' style='position:fixed; top:10px; left:calc(50% - 200px); width:400px;'>
";



if($doch == 1)
    $doch='or 1=1';
    
$sql = "select id, content, von from quotes where seen NOT LIKE '%|$userid|%' $doch order by likes desc LIMIT 1;";
$ask = mdq($bindung, $sql);
while ($row = mysqli_fetch_row($ask)) {
    $rowi=explode('***>', $row[1]);
    $row[1]=$rowi[0];
    if($rowi[1] == 1){
        $humor='box-shadow:0px 0px 0px 2px yellow, 0px 0px 5px 2px black;';        
    }
    else
        $humor='';
    
    $content.="<div id='quotediv' style='$humor'><b>$row[1]</b> <span id='vonspan'><i>-$row[2]</i><img src='arrow.png' id='arrow' onclick=\"document.formular.submit();\"><img src='heart.png' id='heart' onclick=\"deleta.value='$row[0]'; document.formular.submit();\"></div>";
    if($doch != 'or 1=1'){
        $sqla = "update quotes set seen=concat( seen, '|$userid|' ) where id=$row[0];";
        $aska = mdq($bindung, $sqla);
    }
    $drinn=1;
}

if($drinn != 1){
    $content.="<div id='quotediv'><i>Bravo, du hast alle Zitate gelesen.<p>Mit einem Klick auf den Button oben rechts, kannst du unsere Zitatsammlung erweitern.</i></div>";
}


$sql = "select id, likes from quotes;";
$ask = mdq($bindung, $sql);
while ($row = mysqli_fetch_row($ask)) {
    $id=$row[0];
    $likes=$row[1]+1;
    if($delete == $id){
        $sqla = "update quotes set likes=$likes, likeuser=concat( likeuser, '|$userid|' ) where id=$id;";
        $aska = mdq($bindung, $sqla);
    }
}

$content.="<div id='LIKESdiv'>";
$sql = "select id, content, von from quotes where likeuser LIKE '%|$userid|%' order by likes desc;";
$ask = mdq($bindung, $sql);
while ($row = mysqli_fetch_row($ask)) {
$rowi=explode('***>', $row[1]);
    $row[1]=$rowi[0];
    if($rowi[1] == 1){
        $humor='box-shadow:0px 0px 0px 2px yellow, 0px 0px 5px 2px black;';
    }
    else
        $humor='';
    
    $content.="<div id='LIKESquotediv' style='$humor'><b>$row[1]</b> <span id='vonspan'><i>-$row[2]</i></div>";
}
$content.="</div>";

// [END] CONTENT



echo "
<html>
<head>
<title>YOUquotes</title>
<style>
$style
</style>
</head>
<body onload='$load'>
<form id='formular' name='formular' method='POST'>
$content
</form>
</body>
</html>
";
?>
