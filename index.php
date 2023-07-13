<html lang="ja">
<head>
    <title>英単語学習アプリ</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <meta name="viewport" content="width=device-width">

    <style>
        h2, h3, h4, h5, h6, p{
            text-align: center;
            margin: 20px auto;
        }
        .practice-exam-btn-group {
            margin: 30px auto 50px;
            text-align: center;
        }
        .btn-padding {
            padding: 5px 30px;
        }
    </style>
</head>
<body>
    <h2>英単語学習アプリ</h2>
    <h4>サンプル問題（空欄1個）</h4>
    <div class="practice-exam-btn-group">
        <div class="btn-group" role="group" aria-label="Basic outlined example">
            <a class="btn btn-primary btn-padding" href="./word/?test_no=1">例文一覧</a>
            <a class="btn btn-danger btn-padding" href="./test/?test_no=1">単語テスト</a>
        </div>
    </div>
    <h4>サンプル問題（空欄複数）</h4>
    <div class="practice-exam-btn-group">
        <div class="btn-group" role="group" aria-label="Basic outlined example">
            <a class="btn btn-primary btn-padding" href="./word/?test_no=2">例文一覧</a>
            <a class="btn btn-danger btn-padding" href="./test/?test_no=2">単語テスト</a>
        </div>
    </div>
</body>
</html>