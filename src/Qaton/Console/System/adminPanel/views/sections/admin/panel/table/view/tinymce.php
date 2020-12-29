<script src="<?php $this->baseUrl() ?>assets/admin/tinymce/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
tinymce.init({
    selector: '.editor',
    browser_spellcheck: true,
    plugins: 'code searchreplace wordcount textcolor table media fullscreen colorpicker autoresize',
    height: 600,
    branding: false,
    toolbar: '',
    menubar: '',
    draggable_modal: true
});
</script>