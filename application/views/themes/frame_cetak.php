<html>
<head>
    <title>Print PDF</title>
    <script>
        function printTrigger(elementId) {
            var getMyFrame = document.getElementById(elementId);
            getMyFrame.focus();
            getMyFrame.contentWindow.print();
        }
    </script>
</head>

<body>
    <!-- <iframe id="iFramePdf" src="<?php echo $uri_cetak ?>" style="width: 100%;
height: 100%;" ></iframe> -->
<object data="<?php echo $uri_cetak ?>" type="application/pdf">
</body>
</html>