<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>OILid Login</title>
    <!-- Load Bootstrap first -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css" />
    <!-- Load icons -->
    <link
        rel="stylesheet"
        href="https://unicons.iconscout.com/release/v2.1.9/css/unicons.css" />
    <!-- Load custom styles last -->
    <link rel="stylesheet" href="<?= base_url('css/login-style.css') ?>" />
    <link href="<?= base_url('img/favicon.png') ?>" rel="icon" />
    <!-- Add SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <?= $this->renderSection('page-content') ?>
</body>

</html>