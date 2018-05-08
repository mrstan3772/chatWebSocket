<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Gravatar</title>
    <link rel="stylesheet" href="style.css">
    <script
        src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous">
    </script>
    <script type="text/javascript" src="../dist/jQuery-Gravatar/md5.js"></script>
    <script type="text/javascript" src="../dist/jQuery-Gravatar/jquery.gravatar.js"></script>
</head>
<body>
    <script>
        $('body').append($.gravatar('timothee.rodbert@gmail.com', {secure: true}));
        //alert($.gravatar('lukamilic1997@gmail.com', {secure: true, rating: 'pg'}).attr('src'));
</script>
</body>
</html>
