<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo e(config('app.name')); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta content="telephone=no" name="format-detection" />
<meta name="color-scheme" content="light">
<meta name="supported-color-schemes" content="light">

<style data-premailer="ignore">
    @media screen and (max-width: 600px) {
        u+.body {
            width: 100vw !important;
        }
    }

    a[x-apple-data-detectors] {
        color: inherit !important;
        text-decoration: none !important;
        font-size: inherit !important;
        font-family: inherit !important;
        font-weight: inherit !important;
        line-height: inherit !important;
    }
</style>
<!--[if mso]>
  <style type="text/css">
    body, table, td {
        font-family: Arial, Helvetica, sans-serif !important;
    }

    img {
        -ms-interpolation-mode: bicubic;
    }

    .box {
        border-color: #eee !important;
    }
  </style>
<![endif]-->
<!--[if !mso]><!-->
<link href="https://rsms.me/inter/inter.css" rel="stylesheet" type="text/css" data-premailer="ignore" />
<style type="text/css" data-premailer="ignore">
    @import url(https://rsms.me/inter/inter.css);
</style>
<!--<![endif]-->
    <link href="<?php echo e(asset('assets/css/email-theme.css?'.time())); ?>" rel="stylesheet"/>

</head>

<body class="bg-body">
    <center>
        <table class="main bg-body" width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <td align="center" valign="top">
                    <!--[if (gte mso 9)|(IE)]>
                <table border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td align="center" valign="top" width="640">
              <![endif]-->
                    <span class="preheader"></span>
                    <table class="wrap" cellspacing="0" cellpadding="0">
                        <tr>
                            <td class="p-sm">
                                <!-- Header -->
                                <?php echo e($header ?? ''); ?>

                                <!-- Header -->
                                
                                <!-- Email Body -->
                                <div class="main-content">
                                    <table class="box" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td>
                                                <?php echo e($slot); ?>

                                                
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                
                                <!-- Footer -->
                                <?php echo e($footer ?? ''); ?>

                                <!-- Footer -->
                            </td>
                        </tr>
                    </table>
                    <!--[if (gte mso 9)|(IE)]>
              </td>
            </tr>
          </table>
              <![endif]-->
                </td>
            </tr>
        </table>
    </center>
</body>
</html>
<?php /**PATH D:\My Laravel Startup\ndako\resources\views/vendor/mail/html/layout.blade.php ENDPATH**/ ?>