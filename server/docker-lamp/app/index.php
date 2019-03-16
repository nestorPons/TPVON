<?php
$db = new PDO('mysql:host=localhost', 'root', null);
?>
<!doctype html>
<html lang=en>
<head>
    <meta charset=utf-8>
    <title>Hello World from Docker-LAMP</title>

    <style>
        @import 'https://fonts.googleapis.com/css?family=Montserrat|Raleway|Source+Code+Pro';

        body { font-family: 'Raleway', sans-serif; }
        h2 { font-family: 'Montserrat', sans-serif; }
        pre {
            font-family: 'Source Code Pro', monospace;

            padding: 16px;
            overflow: auto;
            font-size: 85%;
            line-height: 1.45;
            background-color: #f7f7f7;
            border-radius: 3px;

            word-wrap: normal;
        }

        .container {
            max-width: 1024px;
            width: 100%;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <img src="https://cdn.rawgit.com/mattrayner/docker-lamp/831976c022782e592b7e2758464b2a9efe3da042/docs/logo.svg" alt="Docker LAMP logo" />
            <h2>Welcome to <a href="https://github.com/mattrayner/docker-lamp" target="_blank">Docker-Lamp</a> a.k.a mattrayner/lamp</h2>
        </header>
        <article>
            <p>
                For documentation, <a href="https://github.com/mattrayner/docker-lamp" target="_blank">click here</a>.
            </p>
        </article>
        <section>
            <pre>
OS: <?php echo php_uname('s'); ?><br/>
Apache: <?php echo apache_get_version(); ?><br/>
MySQL Version: <?php echo $db->getAttribute( PDO::ATTR_SERVER_VERSION ); ?><br/>
PHP Version: <?php echo phpversion(); ?><br/>
phpMyAdmin Version: <?php echo getenv('PHPMYADMIN_VERSION'); ?><span>&nbsp;</span>
            </pre>
        </section>
    </div>
</body>
</html>