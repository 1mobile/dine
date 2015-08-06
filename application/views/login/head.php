<!DOCTYPE html>
<html  class='bg-kinda-white'>
    <head>
        <meta charset="UTF-8">
        <title>iPOS</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link rel="icon" type="image/png" href="<?php echo base_url(); ?>favicon-192x192.png" sizes="192x192">
        <link rel="icon" type="image/png" href="<?php echo base_url(); ?>favicon-160x160.png" sizes="160x160">
        <link rel="icon" type="image/png" href="<?php echo base_url(); ?>favicon-96x96.png" sizes="96x96">
        <link rel="icon" type="image/png" href="<?php echo base_url(); ?>favicon-16x16.png" sizes="16x16">
        <link rel="icon" type="image/png" href="<?php echo base_url(); ?>favicon-32x32.png" sizes="32x32">
        <?php
            if(isset($css))
                echo $css;
        ?>
        <?php
            if(isset($add_css)){
                if(is_array($add_css)){
                    foreach ($add_css as $path) {
                        echo "<link href='".base_url().$path."' rel='stylesheet'>\n";
                    }
                }
                else
                    echo "<link href='".base_url().$add_css."' rel='stylesheet'>\n";
            }
        ?>
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>