<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
</head>
<body style="background-color: #01a9da">
<div class="container">

    <?php if (isset($success)): ?>
        <div class="row">
            <div class="col-xs-12" style="font-family: 'Roboto', sans-serif;color: #FFFFFF; margin-top: 20px;">
                <div class="alert alert-success" role="alert"><?= $success ?></div>
            </div>
        </div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="row">
            <div class="col-xs-12" style="font-family: 'Roboto', sans-serif;color: #FFFFFF; margin-top: 20px;">
                <div class="alert alert-danger" role="alert"><?= $error ?></div>
            </div>
        </div>
    <?php endif; ?>
</div>

</body>
</html>