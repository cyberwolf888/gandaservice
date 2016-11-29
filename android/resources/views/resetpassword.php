<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
</head>
<body style="background-color: #01a9da">
<div class="container">
    <div class="row">
        <div class="col-xs-12" style="font-family: 'Roboto', sans-serif;color: #FFFFFF; margin-top: 20px;">
            <p style="font-size: 16px;">
                Silahkan masukan alamat email anda saat register.
            </p>
        </div>
    </div>

    <?php if (isset($success)): ?>
    <div class="row">
        <div class="col-xs-12">
            <div class="alert alert-success" role="alert"><?= $success ?></div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="row">
            <div class="col-xs-12">
                <div class="alert alert-danger" role="alert"><?= $error ?></div>
            </div>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-xs-12">
            <form action="<?= url('prosesresetpassword') ?>" role="form" method="POST">
                <div class="form-group">
                    <label for="exampleInputEmail1" style="color: white;">Email address</label>
                    <input name="email" type="email" class="form-control" placeholder="Email" required autofocus>
                </div>
                <button type="submit" class="btn btn-default" style="width: 100%;">Submit</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>