<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Dashboard</title>

    <!-- Custom fonts for this template -->
    <link href="<?php $this->baseUrl() ?>assets/admin/sb-admin-2/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ace-builds@1.15.3/css/ace.min.css">
    
    <?php $this->render('common/admin/ace') ?>

    <!-- Custom styles for this template -->
    <link href="<?php $this->baseUrl() ?>assets/admin/sb-admin-2/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="<?php $this->baseUrl() ?>assets/admin/sb-admin-2/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="<?php $this->baseUrl() ?>assets/admin/sb-admin-2/vendor/jquery/jquery.min.js"></script>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php $this->render('common/admin/sidebar') ?>
        
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <?php $this->yeild('main') ?>

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Powered By <a target="_blank" href="http://qaton.virx.net">VirX Qaton</a> by Antony Shan Peiris</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="<?php $this->baseUrl() ?>admin/login/logout">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    
    <script src="<?php $this->baseUrl() ?>assets/admin/sb-admin-2/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?php $this->baseUrl() ?>assets/admin/sb-admin-2/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php $this->baseUrl() ?>assets/admin/sb-admin-2/js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="<?php $this->baseUrl() ?>assets/admin/sb-admin-2/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="<?php $this->baseUrl() ?>assets/admin/sb-admin-2/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="<?php $this->baseUrl() ?>assets/admin/sb-admin-2/js/demo/datatables-demo.js"></script>

    <?php $this->render('common/admin/tinymce') ?>
    
    <?php $this->render('common/admin/keep_alive') ?>

    

</body>

</html>