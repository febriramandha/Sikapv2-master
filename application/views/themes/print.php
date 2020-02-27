<html lang="en">

<head>
    <title><?php echo $site_name; ?></title>
    <meta name="resource-type" content="document" />
    <meta name="robots" content="all, index, follow" />
    <meta name="googlebot" content="all, index, follow" />

    <!-- Le styles -->
    <link href="<?php echo base_url(); ?>assets/themes/print/css/bootstrap.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/themes/print/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/themes/print/css/custom.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/fa/css/font-awesome.min.css" rel="stylesheet">


</head>

<body>
    <div class="container">
            <?php if($this->load->get_section('text_header') != '') { ?>
                <?php echo $this->load->get_section('text_header');?>
            <?php }?>
            <div class="row">
            <div class="content">
                <?php echo $output;?>
            </div>
            </div>
             <?php if($this->load->get_section('text_footer') != '') { ?>
                <?php echo $this->load->get_section('text_footer');?>
            <?php }?>
    </div>
    <div class="footer">
        <div class="container">
             <div class="row">
                    
            </div>
        </div>
    </div>
</body>

</html>
