<?php
session_start();

if(!isset($_SESSION)){
    header( "Location: ./?test_no=<?=$test_no?>" ) ;
}
$test_no = $_POST['test_no'];

$eng = $_SESSION['eng'];
$jp = $_SESSION['jp'];
$dic = $_SESSION['dic'];
$ans = $_POST['ans'];
$full_eng = $_SESSION['full_eng'];

$result = [];

$count = 0;

$words_kugire_count = 0;

for($i = 1; $i <= 10; $i++){
    for($j = 0; $j < mb_substr_count($full_eng[$i], '(') ; $j++){
        $words_kugire_count++;
        $words_kugire[$i][$j] = $words_kugire_count;
    }

}

for($i = 1; $i <= 10; $i++){
    for($j = 1; $j <= count($words_kugire[$i]); $j++){
        $k[$i][$j] = ($eng[$i][($j * 2) - 1] == $ans[$i][$j]) ? 1 : 0;
    }
    $result[$i] = (count($words_kugire[$i]) == array_sum($k[$i])) ? 1 : 0;
}

?>
<html>
<head>
    <title>第<?=$_GET['test_no']?>回 模擬テスト 結果 | 英単語学習アプリ</title>

    <meta name="viewport" content="width=device-width">

    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif:ital@0;1&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.4/css/all.css">
    <style>
        body {
            padding: 10px;
        }
        h2, h3, h4, .center{
            text-align: center;
            margin: 20px auto;
        }
        .word {
            margin-top: 5px;
            border-radius: 5px 5px 5px 5px / 5px 5px 5px 5px;
            background-color: black;
            color: white;
        }
        span {
            display: inline-block;
        }
    </style>
</head>
<body style="padding: 20px">
    <div style="margin: 10px auto 10px; text-align: center">
        <div class="btn-group" role="group" aria-label="Basic outlined example">
            <a class="btn btn-outline-primary" href="/">トップへ</a>
            <a class="btn btn-outline-primary" href="/eng-B/">ページ選択へ</a>
        </div>
    </div>
    <div style="margin: 10px auto 30px; text-align: center">
        <div class="btn-group" role="group" aria-label="Basic outlined example">
            <a class="btn btn-primary" href="/eng-B/test/?test_no=<?=$test_no?>">もう一度テストをする</a>
        </div>
    </div>

    <h2>第<?=$_GET['test_no']?>回 模擬テスト</h2>
    <h4>テスト結果</h4>
    <p style="font-size: 125%; text-align: center"><span style="color: red"><?=array_sum($result);?></span> / 10</p>
    <?php $count = 0;?>
    <table style="margin: auto; width: 100%; max-width: 800px">
        <?php for($count = 1; $count <= 10; $count++){?>
        <tr>
            <td style="text-align: right"><?php if($result[$count] == 1){echo "<span style='text-align: center; color: red; font-weight: bold'>〇 　</span>";}else{echo "<span style='text-align: center; color: blue; font-weight: bold; font-size: 140%'>×　</span>";} ?></td>
            <td>(<?=$count?>)</td>
        </tr>
        <tr>
            <td style="width: 70px; text-align: right">本文：</td>
            <td><?=$jp[$count]?>　[<?=$dic[$count]?>]</td>
        </tr>
        <tr>
            <td style="text-align: right">正解：</td>
            <td style="font-family: 'Noto Serif', serif;">
                <?php
                for($i = 0; $i <= count($eng[$count]) + 1; $i++){
                    if($i % 2) {
                        if($words_kugire[$count][($i - 1)/ 2] != ''){
                            echo ("(<span style=\"color: red\">".$eng[$count][$i]."</span>)");
                        }
                    }else{
                        echo $eng[$count][$i];
                    }
                }
                ?>
            </td>
        </tr>
        <tr>
            <td style="text-align: right"><span>あなたの</span><span>回答：</span></td>
            <td>
                <?php
                for($i = 1; $i <= count($words_kugire[$count]); $i++){
                    echo(($ans[$count][$i] == "") ? "（無回答）" : "<b style='font-family: serif;'>".$ans[$count][$i]."</b>　");
                }
                ?>
            </td>
        </tr>
        <tr><td>　</td><td>　</td><td>　</td></tr>
        <?php } ?>
    </table>

    <div style="margin: 30px auto 10px; text-align: center">
        <div class="btn-group" role="group" aria-label="Basic outlined example">
            <a class="btn btn-primary" href="./?test_no=<?=$test_no?>">もう一度テストをする</a>
        </div>
    </div>
    <div style="margin: 30px auto; text-align: center">
        <a class="btn btn-danger" href="../word/?test_no=<?=$test_no?>" style="padding: 5px 30px;">例文一覧</a>
    </div>
    <div style="margin: 10px auto 30px; text-align: center">
        <div class="btn-group" role="group" aria-label="Basic outlined example">
            <a class="btn btn-outline-primary" href="/">トップへ</a>
            <a class="btn btn-outline-primary" href="/eng-B/">ページ選択へ</a>
        </div>
    </div>
<script type="text/javascript" src="/script.js"></script>
</body>
</html>