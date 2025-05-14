<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo e(config('app.name')); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta content="telephone=no" name="format-detection" />
<meta name="color-scheme" content="light">
<meta name="supported-color-schemes" content="light">

<style type="text/css">
    body {
  margin: 0;
  padding: 0;
  background-color: #f6f7f9;
  font-size: 14px;
  line-height: 171.4285714286%;
  mso-line-height-rule: exactly;
  color: #3A4859;
  width: 100%;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  -webkit-font-feature-settings: "cv02", "cv03", "cv04", "cv11";
          font-feature-settings: "cv02", "cv03", "cv04", "cv11";
}
@media only screen and (max-width: 560px) {
  body {
    font-size: 14px !important;
  }
}

body, table, td {
  font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
}

table {
  border-collapse: collapse;
  width: 100%;
}
table:not(.main) {
  -premailer-cellpadding: 0;
  -premailer-cellspacing: 0;
}

.preheader {
  padding: 0;
  font-size: 0;
  display: none;
  max-height: 0;
  mso-hide: all;
  line-height: 0;
  color: transparent;
  height: 0;
  max-width: 0;
  opacity: 0;
  overflow: hidden;
  visibility: hidden;
  width: 0;
}

.main {
  -webkit-text-size-adjust: 100%;
  -ms-text-size-adjust: 100%;
}

.wrap {
  width: 100%;
  max-width: 640px;
  text-align: left;
}

.wrap-narrow {
  max-width: 500px;
}

.box {
  background: #ffffff;
  border-radius: 4px;
  -webkit-box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
          box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
  border: 1px solid #dce0e5;
}
.box + .box {
  margin-top: 24px;
}

.content,
.content-image-text {
  padding: 40px 48px;
}
@media only screen and (max-width: 560px) {
  .content,
  .content-image-text {
    padding: 24px !important;
  }
}

.content-image-text {
  padding: 24px;
}

.content-big {
  padding: 48px;
}

.content-image {
  height: 360px;
  background-position: center;
  background-size: cover;
}
@media only screen and (max-width: 560px) {
  .content-image {
    height: 100px !important;
  }
}

.content-image-sm {
  height: 200px;
}

.content-image-text {
  background-repeat: repeat;
  vertical-align: bottom;
  color: #fff;
  font-weight: 400;
}
@media only screen and (max-width: 560px) {
  .content-image-text {
    padding-top: 96px !important;
  }
}


h1, .h1,
h2, .h2,
h3, .h3,
h4, .h4,
h5, .h5 {
  font-weight: 600;
  margin: 0 0 0.5em;
  color: #232b42;
}
h1 a, .h1 a,
h2 a, .h2 a,
h3 a, .h3 a,
h4 a, .h4 a,
h5 a, .h5 a {
  color: inherit;
}

h1, .h1 {
  font-size: 28px;
  line-height: 130%;
}
@media only screen and (max-width: 560px) {
  h1, .h1 {
    font-size: 24px !important;
  }
}

h2, .h2 {
  font-size: 24px;
  line-height: 130%;
}
@media only screen and (max-width: 560px) {
  h2, .h2 {
    font-size: 20px !important;
  }
}

h3, .h3 {
  font-size: 20px;
  line-height: 130%;
}
@media only screen and (max-width: 560px) {
  h3, .h3 {
    font-size: 18px !important;
  }
}

h4, .h4 {
  font-size: 16px;
}

h5, .h5 {
  font-size: 14px;
}

hr,
.hr {
  border: none;
  height: 1px;
  background-color: #dce0e5;
  margin: 32px 0;
}

figure {
  margin: 0;
}

pre {
  font-family: Consolas, Monaco, Andale Mono, Ubuntu Mono, monospace;
  font-size: 12px;
  white-space: pre-wrap;
  max-width: 100%;
  word-break: break-word;
  overflow: auto;
  background: #f6f7f9;
  color: #3A4859;
  border-radius: 4px;
  padding: 8px 12px;
  -moz-tab-size: 3;
    -o-tab-size: 3;
       tab-size: 3;
  margin: 0;
}
pre code {
  color: inherit;
  background: none;
  padding: 0;
  font-size: 12px;
}

code {
  color: #3A4859;
  font-family: Consolas, Monaco, Andale Mono, Ubuntu Mono, monospace;
  font-weight: 400;
  font-size: 13px;
  white-space: pre-wrap;
  padding: 0.2em 0.4em;
  border-radius: 4px;
  background: #f6f7f9;
  word-break: break-word;
}

.table-pre pre {
  padding: 0 8px;
  background: transparent;
}
.table-pre td {
  font-family: Consolas, Monaco, Andale Mono, Ubuntu Mono, monospace;
  font-size: 12px;
  background: #f6f7f9;
  color: #3A4859;
  padding-top: 0;
  padding-bottom: 0;
}
.table-pre .table-pre-line {
  text-align: right;
  padding: 0 12px;
  vertical-align: top;
  color: #667382;
  background: #f3f4f7;
  width: 1%;
}
.table-pre .table-pre-line-highlight-red td {
  background: #fbebeb;
  color: #d63939;
}
.table-pre .table-pre-line-highlight-red td pre {
  color: #d63939;
}
.table-pre .table-pre-line-highlight-green td {
  background: #eaf7ec;
  color: #2fb344;
}
.table-pre .table-pre-line-highlight-green td pre {
  color: #2fb344;
}
.table-pre tr:first-child td {
  padding-top: 8px;
}
.table-pre tr:last-child td {
  padding-bottom: 8px;
}

img {
  border: 0 none;
  line-height: 100%;
  outline: none;
  text-decoration: none;
  vertical-align: baseline;
  font-size: 0;
}

a {
  color: #206bc4;
  text-decoration: none;
}


/*
Margins, paddings
 */
 .m-0 {
  margin: 0;
}

.mt-0,
.my-0 {
  margin-top: 0;
}

.mr-0,
.mx-0 {
  margin-right: 0;
}

.mb-0,
.my-0 {
  margin-bottom: 0;
}

.ml-0,
.mx-0 {
  margin-left: 0;
}

.m-xs {
  margin: 4px;
}

.mt-xs,
.my-xs {
  margin-top: 4px;
}

.mr-xs,
.mx-xs {
  margin-right: 4px;
}

.mb-xs,
.my-xs {
  margin-bottom: 4px;
}

.ml-xs,
.mx-xs {
  margin-left: 4px;
}

.m-sm {
  margin: 8px;
}

.mt-sm,
.my-sm {
  margin-top: 8px;
}

.mr-sm,
.mx-sm {
  margin-right: 8px;
}

.mb-sm,
.my-sm {
  margin-bottom: 8px;
}

.ml-sm,
.mx-sm {
  margin-left: 8px;
}

.m-md {
  margin: 16px;
}

.mt-md,
.my-md {
  margin-top: 16px;
}

.mr-md,
.mx-md {
  margin-right: 16px;
}

.mb-md,
.my-md {
  margin-bottom: 16px;
}

.ml-md,
.mx-md {
  margin-left: 16px;
}

.m-lg {
  margin: 24px;
}

.mt-lg,
.my-lg {
  margin-top: 24px;
}

.mr-lg,
.mx-lg {
  margin-right: 24px;
}

.mb-lg,
.my-lg {
  margin-bottom: 24px;
}

.ml-lg,
.mx-lg {
  margin-left: 24px;
}

.m-xl {
  margin: 48px;
}

.mt-xl,
.my-xl {
  margin-top: 48px;
}

.mr-xl,
.mx-xl {
  margin-right: 48px;
}

.mb-xl,
.my-xl {
  margin-bottom: 48px;
}

.ml-xl,
.mx-xl {
  margin-left: 48px;
}

.m-xxl {
  margin: 96px;
}

.mt-xxl,
.my-xxl {
  margin-top: 96px;
}

.mr-xxl,
.mx-xxl {
  margin-right: 96px;
}

.mb-xxl,
.my-xxl {
  margin-bottom: 96px;
}

.ml-xxl,
.mx-xxl {
  margin-left: 96px;
}

.p-0 {
  padding: 0;
}

.pt-0,
.py-0 {
  padding-top: 0;
}

.pr-0,
.px-0 {
  padding-right: 0;
}

.pb-0,
.py-0 {
  padding-bottom: 0;
}

.pl-0,
.px-0 {
  padding-left: 0;
}

.p-xs {
  padding: 4px;
}

.pt-xs,
.py-xs {
  padding-top: 4px;
}

.pr-xs,
.px-xs {
  padding-right: 4px;
}

.pb-xs,
.py-xs {
  padding-bottom: 4px;
}

.pl-xs,
.px-xs {
  padding-left: 4px;
}

.p-sm {
  padding: 8px;
}

.pt-sm,
.py-sm {
  padding-top: 8px;
}

.pr-sm,
.px-sm {
  padding-right: 8px;
}

.pb-sm,
.py-sm {
  padding-bottom: 8px;
}

.pl-sm,
.px-sm {
  padding-left: 8px;
}

.p-md {
  padding: 16px;
}

.pt-md,
.py-md {
  padding-top: 16px;
}

.pr-md,
.px-md {
  padding-right: 16px;
}

.pb-md,
.py-md {
  padding-bottom: 16px;
}

.pl-md,
.px-md {
  padding-left: 16px;
}

.p-lg {
  padding: 24px;
}

.pt-lg,
.py-lg {
  padding-top: 24px;
}

.pr-lg,
.px-lg {
  padding-right: 24px;
}

.pb-lg,
.py-lg {
  padding-bottom: 24px;
}

.pl-lg,
.px-lg {
  padding-left: 24px;
}

.p-xl {
  padding: 48px;
}

.pt-xl,
.py-xl {
  padding-top: 48px;
}

.pr-xl,
.px-xl {
  padding-right: 48px;
}

.pb-xl,
.py-xl {
  padding-bottom: 48px;
}

.pl-xl,
.px-xl {
  padding-left: 48px;
}

.p-xxl {
  padding: 96px;
}

.pt-xxl,
.py-xxl {
  padding-top: 96px;
}

.pr-xxl,
.px-xxl {
  padding-right: 96px;
}

.pb-xxl,
.py-xxl {
  padding-bottom: 96px;
}

.pl-xxl,
.px-xxl {
  padding-left: 96px;
}



.h-0 {
  height: 0;
}

.w-0 {
  width: 0;
}

.h-xs {
  height: 4px;
}

.w-xs {
  width: 4px;
}

.h-sm {
  height: 8px;
}

.w-sm {
  width: 8px;
}

.h-md {
  height: 16px;
}

.w-md {
  width: 16px;
}

.h-lg {
  height: 24px;
}

.w-lg {
  width: 24px;
}

.h-xl {
  height: 48px;
}

.w-xl {
  width: 48px;
}

.h-xxl {
  height: 96px;
}

.w-xxl {
  width: 96px;
}

.d-block {
  display: block;
}

.table-fixed {
  table-layout: fixed;
}

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

<style type="text/css" data-premailer="ignore">
    @import url(https://rsms.me/inter/inter.css);
</style>
<!--<![endif]-->
    

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
                    <span class="preheader"><?php echo $__env->yieldContent('preview'); ?></span>
                    <table class="wrap" cellspacing="0" cellpadding="0">
                        <tr>
                            <td class="p-sm">
                                <!-- Header -->
                                <table cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td class="py-lg">
                                            <table cellspacing="0" cellpadding="0">
                                                <tr>
                                                    <td>
                                                        <a href="<?php echo e(config('app.url')); ?>">
                                                            <img src="https://app.ndako.tech/assets/images/logo/logo-black.png"  height="34px" alt="" />
                                                        </a>
                                                    </td>
                                                    <td class="text-right">
                                                        <a href="<?php echo e(config('app.url')); ?>" class="text-muted-light">
                                                            View online
                                                        </a>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                                <!-- Header -->

                                <!-- Email Body -->
                                <div class="main-content">
                                    <table class="box" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td>
                                                <?php echo $__env->yieldContent('content'); ?>
                                                
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <!-- Footer -->
                                <table cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td class="py-xl">
                                            <table class="text-center text-muted" cellspacing="0" cellpadding="0">
                                                <tr>
                                                    <td align="center" class="pb-md">
                                                        <table class="w-auto" cellspacing="0" cellpadding="0">
                                                            <tr>
                                                                <td class="px-sm">
                                                                    <a href="https://tabler.io/emails?utm_source=demo">
                                                                        <img src="https://app.ndako.tech/assets/images/email/icons-gray-brand-facebook.png" class=" va-middle" width="24" height="24" alt="brand-facebook" />
                                                                    </a>
                                                                </td>
                                                                <td class="px-sm">
                                                                    <a href="https://tabler.io/emails?utm_source=demo">
                                                                        <img src="https://app.ndako.tech/assets/images/email/icons-gray-brand-twitter.png" class=" va-middle" width="24" height="24" alt="brand-twitter" />
                                                                    </a>
                                                                </td>
                                                                <td class="px-sm">
                                                                    <a href="https://tabler.io/emails?utm_source=demo">
                                                                        <img src="https://app.ndako.tech/assets/images/email/icons-gray-brand-github.png" class=" va-middle" width="24" height="24" alt="brand-github" />
                                                                    </a>
                                                                </td>
                                                                <td class="px-sm">
                                                                    <a href="https://tabler.io/emails?utm_source=demo">
                                                                        <img src="https://app.ndako.tech/assets/images/email/icons-gray-brand-youtube.png" class=" va-middle" width="24" height="24" alt="brand-youtube" />
                                                                    </a>
                                                                </td>
                                                                <td class="px-sm">
                                                                    <a href="https://tabler.io/emails?utm_source=demo">
                                                                        <img src="https://app.ndako.tech/assets/images/email/icons-gray-brand-pinterest.png" class=" va-middle" width="24" height="24" alt="brand-pinterest" />
                                                                    </a>
                                                                </td>
                                                                <td class="px-sm">
                                                                    <a href="https://tabler.io/emails?utm_source=demo">
                                                                        <img src="https://app.ndako.tech/assets/images/email/icons-gray-brand-instagram.png" class=" va-middle" width="24" height="24" alt="brand-instagram" />
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="px-lg">
                                                        If you have any questions, feel free to message us at <a href="mailto:support@koverae.com" class="text-muted">support@koverae.com</a>
                                                        <p></p>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
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
<?php /**PATH D:\My Laravel Startup\ndako\resources\views/layouts/email.blade.php ENDPATH**/ ?>