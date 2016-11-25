<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
</head>
<body style="background-color: #01a9da">
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <center><h3 style="font-family: 'Roboto', sans-serif;color: #FFFFFF;"><?= $article->judul ?></h3></center>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <center>
            <?php if($article->cover != ''): ?>
                <img src="<?= 'https://www.edukezy.com/images/article/'.$article->cover ?>" class="img-responsive">
            <?php else: ?>
                <!-- <img src="https://www.edukezy.com/images/logo.png" class="img-responsive"> -->
            <?php endif; ?>
            </center>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12" style="font-family: 'Roboto', sans-serif;color: #FFFFFF;">
            <?= $article->content ?>
        </div>
    </div>
</div>

</body>
</html>