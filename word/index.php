<?php

header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma:no-cache');

session_start();
require_once("../env.php"); //DB接続情報の読み込み
$pdo = db();

if(empty($_GET['test_no'])){
    header( "Location: /" );
    exit;
}else{
    $test_no = explode(",", $_GET['test_no']);
}


$stmt = $pdo->query("select * from WORD_SAMPLE_DATA");
$array = $stmt->fetchAll();

$words = [];
foreach($test_no as $key => $value) {
    $word_array[$key] = array_filter($array, function($val) use ($value) {
        return $val['test_no'] == $value;
    });
    $words += $word_array[$key];
}

$words = array_values($words);

$words_count = count($words);

$count_kakko = 1;
$random = rand(0,$words_count-1);

$eng_data = $words[$random]['English'];

$start[$words_count][0] = 0;
$end[$words_count][0] = mb_strpos($eng_data,'(', 0)+1;;

for($i = 0; $i < mb_substr_count($eng_data, '(') * 2 + 1; $i++ ){
    if($i == 0){
        $start[$words_count][$i] = 0;
    }elseif($i % 2){
        $start[$words_count][$i] = mb_strpos($eng_data,'(', $start[$words_count][$i - 1])+1;

    }else{
        $start[$words_count][$i] = mb_strpos($eng_data,')', $start[$words_count][$i - 1]);
    }
    if($i == (mb_substr_count($eng_data, '(')* 2)){
        $end[$words_count][$i] = strlen($eng_data);
    }elseif($i % 2){
        $end[$words_count][$i] = mb_strpos($eng_data,')', $start[$words_count][$i]);
    }else{
        $end[$words_count][$i] = mb_strpos($eng_data,'(', $start[$words_count][$i])+1;
    }
    $eng[$words_count][$i] = mb_substr($eng_data, $start[$words_count][$i], $end[$words_count][$i]-$start[$words_count][$i]);
}

$read_eng = str_replace('(', '', $eng_data);
$read_eng = str_replace(')', '', $read_eng);
$read_eng = str_replace('<i>', '', $read_eng);
$read_eng = str_replace('</i>', '', $read_eng);
$read_eng = str_replace("'", "\'", $read_eng);

$words_kugire[] = [0];
$words_kugire_count = 0;
foreach($words as $key => $value) {
    for($i = 0; $i < mb_substr_count($words[$key]['English'], '('); $i++){
        $words_kugire_count++;
        $words_kugire[$key][$i] = $words_kugire_count;
    }
}
?>
<html lang="ja">
<head>
    <title>第<?=$_GET['test_no']?>回 例文一覧 | 英単語学習アプリ</title>

    <meta name="viewport" content="width=device-width">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif:ital@0;1&display=swap" rel="stylesheet">

    <style>
        body {
            padding: 10px;
        }
        h2, h3, .center{
            text-align: center;
            margin: 20px auto;
        }
        .word {
            margin-top: 5px;
            border-radius: 5px 5px 5px 5px / 5px 5px 5px 5px;
            background-color: black;
            color: white;
        }
        #page_top{
            width: 50px;
            height: 50px;
            position: fixed;
            right: 0;
            bottom: 0;
            background: dimgray;
            opacity: 0.6;
        }
        #page_top a{
            position: relative;
            display: block;
            width: 50px;
            height: 50px;
            text-decoration: none;
        }
        #page_top a::before{
            font-weight: bold;
            content: "";
            font-size: 25px;
            color: #fff;
            position: absolute;
            width: 25px;
            height: 25px;
            top: 0;
            bottom: 0;
            right: 0;
            left: 0;
            margin: auto;
            text-align: center;
        }
    </style>
</head>
<body>
<div style="margin: 30px auto; text-align: center">
    <a class="btn btn-outline-primary" href="/">トップへ</a>
</div>

<h1 class="center">第<?=$_GET['test_no']?>回 単語テスト 和文英訳</h1>
<h4 class="center">ランダム生成</h4>
<div style="border: 1px solid #333333; padding: 20px; width: 100%; max-width: 550px; margin: auto;">
    <table style="margin:auto; width: 100%" >
        <tr>
            <td style="width: 60px">
                <input class="btn btn-primary btn-sm" type="button" value="再読込" onclick="window.location.reload(false);" style="background-color: dodgerblue; color: white">
            </td>
            <td></td>
        </tr>
        <tr>
            <td>
                <div class="text-center">
                    <input class="btn btn-secondary btn-sm" type="button" value="切替" onclick="<?php foreach($words_kugire[$random] as $key => $value){echo("red_change(".($key + 1)."); ");}?>">
                </div>
            </td>
            <td style="text-align: left;"><?=$words[$random]['dic_no']; ?>.<?=$words[$random]['Japanese']; ?></td>
        </tr>
        <tr>
            <td>
                <div class="text-center">
                    <button type="button" class="btn btn-info btn-sm" onclick="readEnglish('<?=$read_eng;?>');">
                        <i class="bi bi-volume-up-fill" style="font-size: 1.5em"></i>
                    </button>
                </div>
            </td>
            <?php /*
            <td style="font-family: 'Noto Serif', serif;">
                <?php
                for($i = 0; $i < count($eng[$words_count]); $i++){
                    if($i % 2) {
                        if($words_kugire[$random][($i - 1)/ 2] != ''){
                            echo ("<a id=\"red_change_".(($i + 1) / 2 )."\" style=\"color: white\" onclick=\"readEnglish('".$eng[$words_count][$i]."');\">".$eng[$words_count][$i]."</a>");
                        }
                    }else{
                        echo $eng[$words_count][$i];
                    }
                }
                ?>
            </td> */?>
            <td style="font-family: 'Noto Serif', serif;">
                <?php
                
                foreach($eng[$words_count] as $key_eng => $value_eng) {
                    if($key_eng % 2) {
                        if($words_kugire[$random][($key_eng - 1)/ 2] != ''){
                            echo ("<a id=\"red_change_".(($key_eng + 1)/ 2)."\" style=\"color: white\" onclick=\"readEnglish('".$value_eng."');\">".$value_eng."</a>");
                        }
                    }else{
                        echo $value_eng;
                    }
                }
                ?> 
            </td>
        </tr>
    </table>
</div>
<br>
<h4 class="text-center">英文一覧</h4>


<table class="m-auto">
    <tr>
        <td><input class="btn btn-danger btn-sm" type="button" value="全切替" onclick="<?php for($i = 1; $i <= $words_kugire[array_key_last($words_kugire)][array_key_last($words_kugire[array_key_last($words_kugire)])]; $i++){echo("red_change(".($i + 10)."); "); } ?>" style="background-color: crimson; color: white"></td>
        <td></td>
        <td></td>
    </tr>
    <?php foreach($words as $key_word => $value) {?>
        <?php
        $eng_data = $words[$key_word]['English'];

        $start[$key_word][0] = 0;
        $end[$key_word][0] = mb_strpos($eng_data,'(', 0)+1;;

        for($i = 0; $i < mb_substr_count($eng_data, '(') * 2 + 1; $i++ ){
            if($i == 0){
                $start[$key_word][$i] = 0;
            }elseif($i % 2){
                $start[$key_word][$i] = mb_strpos($eng_data,'(', $start[$key_word][$i - 1])+1;
            }else{
                $start[$key_word][$i] = mb_strpos($eng_data,')', $start[$key_word][$i - 1]);
            }
            if($i == (mb_substr_count($eng_data, '(')* 2)){
                $end[$key_word][$i] = strlen($eng_data);
            }elseif($i % 2){
                $end[$key_word][$i] = mb_strpos($eng_data,')', $start[$key_word][$i]);
            }else{
                $end[$key_word][$i] = mb_strpos($eng_data,'(', $start[$key_word][$i])+1;
            }
            $eng[$key_word][$i] = mb_substr($eng_data, $start[$key_word][$i], $end[$key_word][$i]-$start[$key_word][$i]);
        }

        $read_eng = str_replace('(', '', $eng_data);
        $read_eng = str_replace(')', '', $read_eng);
        $read_eng = str_replace('<i>', '', $read_eng);
        $read_eng = str_replace('</i>', '', $read_eng);
        $read_eng = str_replace("'", "\'", $read_eng);

        ?>
        <tr id="<?=$words[$key_word]['dic_no']; ?>">
            <td>
                <div style="text-align: center; ">
                    <input class="btn btn-secondary btn-sm" type="button" value="切替" onclick="<?php for($i = 0; $i < count($words_kugire[$key_word]); $i++){echo("red_change(".($i + $words_kugire[$key_word][0] + 10)."); ");}?>">
                </div>
            </td>
            <td><?=$words[$key_word]['dic_no']; ?>.<?=$words[$key_word]['Japanese']; ?></td>
        </tr>
        <tr>
            <td>
                <div style="text-align: center; ">
                    <button type="button" class="btn btn-info btn-sm" onclick="readEnglish('<?=$read_eng;?>');">
                    <i class="bi bi-volume-up-fill" style="font-size: 1.5em"></i>
                    </button>
                </div>
            </td>
            <td style="font-family: 'Noto Serif', serif;">
                <?php
                foreach($eng[$key_word] as $key_eng => $value_eng) {
                    if($key_eng % 2) {
                        echo ("<a id=\"red_change_".($words_kugire[$key_word][($key_eng - 1)/ 2]+ 10)."\" style=\"color: white\" onclick=\"readEnglish('".$value_eng."');\">".$value_eng."</a>");
                    }else{
                        echo $value_eng;
                    }
                }
                ?> 
            </td>
        </tr>
        <tr>
            <td>　</td>
            <td>　</td>
        </tr>
    <?php } ?>
</table>

<div style="margin: 30px auto; text-align: center">
    <a class="btn btn-danger" href="../test/?test_no=<?=$_GET['test_no']?>" style="padding: 5px 30px;">単語テスト</a>
</div>
<div style="margin: 30px auto; text-align: center">
    <a class="btn btn-outline-primary" href="/">トップへ</a>
</div>

<script type="text/javascript" src="/script.js"></script>
<script>
    function readEnglish(eng){
        // ブラウザにWeb Speech API Speech Synthesis機能があるか判定
        if ('speechSynthesis' in window) {
            window.speechSynthesis.cancel() // 発言を停止
            const uttr = new SpeechSynthesisUtterance() // 発言を設定
            uttr.text = eng // テキストを設定
            uttr.lang = 'en-US' // 言語を設定
            uttr.rate = 0.9 // 速度を設定
            uttr.pitch = 1 // 高さを設定
            uttr.volume = 1 // 音量を設定
            const voices = speechSynthesis.getVoices() // 英語に対応しているvoiceを設定
            for (let i = 0; i < voices.length; i++) {
                if (voices[i].lang === 'en-US') {
                    uttr.voice = voices[i]
                }
            }
            window.speechSynthesis.speak(uttr); // 発言を再生
        } else {
            alert('大変申し訳ありません。このブラウザは音声合成に対応していません。')
        }
    }

</script>
<div id="page_top"><a href="#" style="color: white; font-weight: bold; text-align: center; font-size: 185%; line-height: 1.8;">&#8743;</a></div>
</body>

</html>