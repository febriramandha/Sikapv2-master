<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo $app_name; ?> - <?php echo $title ?> <?php echo $sub_title ?></title>
    <meta content="<?php echo $author ?>" name="author" />
    <meta content="<?php echo $development ?>" name="development" />
    <?php foreach($meta as $name=>$content){ ?>
    <meta name="<?php echo $name; ?>" content="<?php echo $content; ?>" />
    <?php } ?>
    <link rel="icon" href="<?php echo $favicon ?>" type="image/gif">
    <!-- Global stylesheets -->
    <link href="<?php echo base_url() ?>public/themes/material/css/font_family.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>public/themes/material/global_assets/css/icons/icomoon/styles.css"
        rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>public/themes/material/css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>public/themes/material/css/bootstrap_limitless.css" rel="stylesheet"
        type="text/css">
    <link href="<?php echo base_url() ?>public/themes/material/css/layout.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>public/themes/material/css/components.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>public/themes/material/css/colors.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>public/themes/custom/custom.css" rel="stylesheet" type="text/css">
    <?php foreach($css as $file){ ?>
    <link rel="stylesheet" href="<?php echo $file; ?>" type="text/css" />
    <?php } ?>
    <!-- /global stylesheets -->
    <!-- Core JS files -->
    <script src="<?php echo base_url() ?>public/themes/material/global_assets/js/main/jquery.min.js"></script>
    <script src="<?php echo base_url() ?>public/themes/material/global_assets/js/main/bootstrap.bundle.min.js"></script>
    <script src="<?php echo base_url() ?>public/themes/material/global_assets/js/plugins/loaders/blockui.min.js">
    </script>
    <script src="<?php echo base_url() ?>public/themes/material/global_assets/js/plugins/ui/ripple.min.js"></script>
    <!-- /core JS files -->
    <script src="<?php echo base_url() ?>public/themes/plugin/bootbox/bootbox.js"></script>

    <script src="<?php echo base_url() ?>public/themes/material/global_assets/js/plugins/forms/selects/select2.min.js">
    </script>
    <script
        src="<?php echo base_url() ?>public/themes/material/global_assets/js/plugins/tables/datatables/datatables.min.js">
    </script>
    <!-- Theme JS files -->
    <script src="<?php echo base_url() ?>public/themes/material/js/app.js"></script>
    <script src="<?php echo base_url() ?>public/themes/material/global_assets/js/plugins/forms/styling/uniform.min.js">
    </script>
    <script
        src="<?php echo base_url() ?>public/themes/material/global_assets/js/plugins/forms/styling/switchery.min.js">
    </script>
    <!-- /theme JS files -->
    <?php foreach($js as $file){ ?>
    <script src="<?php echo $file; ?>"> </script>
    <?php } ?>
    <script type="text/javascript">
    var uri_dasar = '<?= site_url() ?>';
    var csrf_value = '<?php echo $this->security->get_csrf_hash(); ?>';
    </script>

</head>

<body class="navbar-top">
    <?php echo $this->load->get_section('nav');?>
    <!-- Page content -->
    <div class="page-content">
        <?php echo $this->load->get_section('sidebar');?>
        <!-- Main content -->
        <div class="content-wrapper">
            <!-- Page header -->
            <div class="page-header page-header-light">
                <div class="page-header-content header-elements-md-inline d-none d-md-flex">
                    <div class="page-title d-flex">
                        <h6>
                            <i class="icon-arrow-left52 mr-2"></i> <span
                                class="font-weight-semibold"><?php echo $title ?></span>
                            <small class="d-block text-muted "
                                style="margin-bottom: -20px;"><?php echo $sub_title ?></small>
                        </h6>
                        <a href="#" class="header-elements-toggle text-default d-md-none">
                            <i class="icon-more"></i>
                        </a>
                    </div>
                    <div class="header-elements d-none">
                        <div class="d-flex justify-content-center">
                            <a href="#" class="btn btn-link btn-float font-size-sm font-weight-semibold text-default">
                                <img src="<?php echo $regency_logo ?>" height="50px">
                                <!-- <span><?php echo $regency ?></span> -->
                            </a>
                        </div>
                    </div>
                </div>
                <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
                    <div class="d-flex">
                        <?php echo $breadcrumb ?>
                    </div>
                </div>
            </div>
            <!-- /page header -->
            <!-- Content area -->
            <div class="content">
                <?php echo $output; ?>
            </div>
            <!-- /content area -->
            <!-- Footer -->
            <div class="navbar navbar-light">
                <div class="text-center w-100">
                    <button type="button" class="navbar-toggler">
                        SIKAP 2.1.4
                        &copy; 2018 - <?php echo date('Y') ?> Powered by <a href="#">Web Programmer Dinas Komunikasi dan
                            Informatika Kab. Agam </a>
                    </button>
                </div>
            </div>
            <!-- /footer -->
        </div>
        <!-- /main content -->
    </div>
    <!-- /page content -->
    <script type="text/javascript">
    var url1 = window.location;
    var pgclass = "<?= str_replace('_', '-', $this->router->fetch_class()); ?>";
    </script>
    <script src="<?php echo base_url() ?>public/themes/material/js/custom.js"></script>
    <script src="<?php echo base_url() ?>public/themes/custom/js/datatables_script.js"> </script>
    <?php echo $this->load->get_section('analyticstracking');?>
</body>

</html>

<!--
* Created By: Rian Reski A
* 2019
-->