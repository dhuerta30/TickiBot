
<?php require "layouts/header.php"; ?>
<?php require "layouts/sidebar.php"; ?>
<link href="<?=$_ENV["BASE_URL"]?>css/sweetalert2.min.css" rel="stylesheet">
<style>
    body #cke_notifications_area_editor1,
    body .cke_notifications_area {
        display: none !important;
    }
</style>
<div class="content-wrapper">
    <section class="content">
        <div class="card mt-4">
            <div class="card-body">

                <div class="row">
                    <div class="col-md-12">
                        <?=$render?>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>
<div id="artify-ajax-loader">
    <img width="300" src="<?=$_ENV["BASE_URL"]?>app/libs/artify/images/ajax-loader.gif" class="artify-img-ajax-loader"/>
</div>
<?php require "layouts/footer.php"; ?>
<script src="<?=$_ENV["BASE_URL"]?>js/sweetalert2.all.min.js"></script>
<script src="https://cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ckfinder/3.5.2/ckfinder.js"></script>
<script>
    $(document).on("artify_after_ajax_action", function(event, obj, data){
        var dataAction = obj.getAttribute('data-action');
        var dataId = obj.getAttribute('data-id');

        if(dataAction == "add"){
        
        }

        if(dataAction == "edit"){
        
        }
    });
    $(document).on("artify_after_submission", function(event, obj, data) {
        let json = JSON.parse(data);

        if (json.message) {
            Swal.fire({
                icon: "success",
                text: json["message"],
                confirmButtonText: "Aceptar",
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $(".artify-back").click();
                }
            });
        }
    });

    $(document).on("artify_on_load artify_after_submission artify_after_ajax_action", function (event, obj, data) {
        CKEDITOR.replace("bWVzc2FnZXMjJGNvbnRlbmlkb0AzZHNmc2RmKio5OTM0MzI0", {
            height: 300,
            toolbar: [
                { name: "document", items: ["Source"] },
                { name: "clipboard", items: ["Cut", "Copy", "Paste", "Undo", "Redo"] },
                { name: "editing", items: ["Find", "Replace", "SelectAll"] },
                {
                    name: "basicstyles",
                    items: ["Bold", "Italic", "Underline", "Strike", "RemoveFormat"]
                },
                {
                    name: "paragraph",
                    items: [
                        "NumberedList",
                        "BulletedList",
                        "-",
                        "Outdent",
                        "Indent",
                        "-",
                        "Blockquote",
                        "JustifyLeft",
                        "JustifyCenter",
                        "JustifyRight",
                        "JustifyBlock"
                    ]
                },
                {
                    name: "insert",
                    items: ["Image", "Table", "HorizontalRule", "SpecialChar"]
                },
                { name: "tools", items: ["Maximize", "ShowBlocks"] }
            ],
            filebrowserBrowseUrl: 'https://yourdomain.com/ckfinder/ckfinder.html', // Ajusta la URL
            filebrowserUploadUrl: 'https://yourdomain.com/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files'
        });

        CKFinder.setupCKEditor(editor);
    });
</script>