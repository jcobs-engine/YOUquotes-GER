<?php


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
position:fixed;
top:50%;
width:820px;
left:50%;
font-weight:bold;
border:0px;
color:black;
text-shadow:0px 0px 4px black;
padding:20px;
font-size:25px;
font-family:sans-serif;
box-shadow:0px 0px 5px black;
border-radius:10px 0px 10px 0px;
transform: translate(-50%, -50%);
background-color:#1f6e34;
height:250px;
}

#LIKESquotediv{
cursor:default;
position:relative;
width:calc( 60% - 40px);
left:20%;
color:black;
text-shadow:0px 0px 4px black;
padding:20px;
padding-bottom:40px;
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
}
";

// [END] STYLE

// =========================================== //

//       CONTENT

$content.="<textarea id='quotediv' placeholder='Zitat...' name='quote'>Autor des Zitats
===================================================
Zitat...
===================================================
Bei Humor, bitte diese Zeile durch \"HUMOR\" ersetzen.
</textarea>
<input type='submit' name='send' value='Senden' style='position:fixed; bottom:0px; left:0px; width:100%;font-size:25px;padding:10px;box-shadow:0px 0px 5px black;cursor:pointer;border:0px;background-color:#1f6e34'>
";

$zitat=explode('===================================================', $_POST['quote']);

$autor=$zitat[0];
$contenta=$zitat[1];
$humor=$zitat[2];

if(md5($humor) == '9d217fb48accc6a5695572fb7a2a2ce9' or md5($humor) == '6912c3b4f2f7131af8f32e9d48d8e937' )
    $humor=1;
else
    $humor=0;

$contenta=str_replace('<br />', '', nl2br($contenta));

if( md5($autor) != '2c285da8fe1bdb2aa8f198b8f09f3c8c' and md5($contenta) != '2c285da8fe1bdb2aa8f198b8f09f3c8c|' and $_POST['quote'] != "" ){
    $close='window.close();';
    
    $sql = "insert into quotes set von=\"$autor\", content=\"$contenta ***>$humor\", seen='|".$_COOKIE['userid']."|', likes=1, likeuser='|".$_COOKIE['userid']."|';";
    $ask = mdq($bindung, $sql);
}


// [END] CONTENT

echo "
<html>
<head>
<title>YOUquotes</title>
<style>
$style
</style>
</head>
<body onload=\"$close\">
<form id='formular' name='formular' method='POST'>
$content
</form>
</body>
</html>
";
?>
