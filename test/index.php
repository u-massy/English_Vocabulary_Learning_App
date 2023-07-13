<?php
session_start();
require_once("../env.php"); //DB接続情報の読み込み
$pdo = db();//DB名を引数として渡す

if(isset($_GET['test_no'])){
    $test_no = $_GET['test_no'];
}else{
    header( "Location: ./?test_no=1" );
    exit;
}

function selectWords($no) {
    if(isset($_GET['test_no'])){
        $test_no = $_GET['test_no'];
    }else{
        $test_no = 1;
    }
    return $no['test_no'] == $test_no;
}

$stmt = $pdo->query("select * from WORD_SAMPLE_DATA");
$array = $stmt->fetchAll();

if(empty($array)){
    header( "Location: ./?test_no=1" );
    exit;
}

$words = array_values(array_filter($array, "selectWords"));

if(empty($words)){
    header( "Location: ./?test_no=1" );
    exit;
}


?>
<html>
<head>
    <title>第<?=$_GET['test_no']?>回 模擬テスト | 英単語学習アプリ</title>

    <meta name="viewport" content="width=device-width">

    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif:ital@0;1&display=swap" rel="stylesheet">

    <script type="text/javascript">
        window.onload = function onLoad() {
            target_1 = document.getElementById("eng_1");
            target_1.innerHTML = "Penguin";
        }
    </script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.4/css/all.css">
    <style>
        h2, h3, .center{
            text-align: center;
            margin: 20px auto;
        }
        span {
            display: inline-block;
        }
        .word {
            margin-top: 5px;
            border-radius: 5px 5px 5px 5px / 5px 5px 5px 5px;
            background-color: black;
            color: white;
        }
        /*
        table {
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #333;
        }
        */
    </style>
    <script type="text/javascript">
        function checkForm($this)
        {
            var str=$this.value;
            while(str.match(/[^A-Z^a-z\d\-]/))
            {
                str=str.replace(/[^A-Z^a-z\d\-]/,"");
            }
            $this.value=str;
        }
    </script>
</head>
<body style="padding: 20px">

<div style="margin: 30px auto; text-align: center">
    <a class="btn btn-outline-primary" href="/">トップへ</a>
</div>
<h2>第<?=$_GET['test_no']?>回 模擬テスト</h2>

<form method="post" action="./result.php">
    <input type="hidden" name="test_no" value="<?=$test_no?>">
    <table style="margin: auto; width: 100%; max-width: 800px">
        <?php
        for($count = 1; $count <= 10; $count++){
            $random = rand(0,count($words)-1);

            $eng_data = $words[$random]['English'];

            $start[$count][0] = 0;
            $end[$count][0] = mb_strpos($eng_data,'(', 0)+1;;

            for($i = 0; $i < mb_substr_count($eng_data, '(') * 2 + 1; $i++ ){
                if($i == 0){
                    $start[$count][$i] = 0;
                }elseif($i % 2){
                    $start[$count][$i] = mb_strpos($eng_data,'(', $start[$count][$i - 1]) + 1;

                }else{
                    $start[$count][$i] = mb_strpos($eng_data,')', $start[$count][$i - 1]) + 1;
                }
                if($i == (mb_substr_count($eng_data, '(')* 2)){
                    $end[$count][$i] = strlen($eng_data);
                }elseif($i % 2){
                    $end[$count][$i] = mb_strpos($eng_data,')', $start[$count][$i]);
                }else{
                    $end[$count][$i] = mb_strpos($eng_data,'(', $start[$count][$i]);
                }
                $eng[$count][$i] = mb_substr($eng_data, $start[$count][$i], $end[$count][$i]-$start[$count][$i]);
            }
            $jp[$count] = $words[$random]['Japanese'];
            $dic[$count] = $words[$random]['dic_no'];
            $full_eng[$count] = $eng_data;
            unset($words[$random]);
            $words = array_values($words);
        ?>
        <tr>
            <td>(<?=$count;?>) <?=$jp[$count];?></td>
        </tr>
        <tr>
            <td style="font-family: 'Noto Serif', serif;">
                <?php
                for($i = 0; $i < count($eng[$count]) ; $i++){
                    if($i % 2) {
                        echo ("<span>( <input type=\"text\" name=\"ans[".$count."][".(($i + 1)/2)."]\" style=\"width: 120px; min-width: 50px; ime-mode: inactive; \" autocapitalize=\"off\" autocomplete=\"off\"   onInput=\"checkForm(this)\"> )</span>");
                    }else{
                        echo $eng[$count][$i];
                    }
                }
                ?>
            </td>
        </tr>
        <tr><td>　</td></tr>
        <tr><td>　</td></tr>
        <?php } ?>
    </table>
    <div class="center"><input class="btn btn-primary" type="submit" value="採点する"></div>
</form>

<?php
    $_SESSION['eng'] = $eng;
    $_SESSION['jp'] = $jp;
    $_SESSION['dic'] = $dic;
    $_SESSION['full_eng'] = $full_eng;
?>

<div style="margin: 30px auto; text-align: center">
    <a class="btn btn-outline-primary" href="/">トップへ</a>
</div>

<script type="text/javascript" src="/script.js"></script>
</body>

</html>