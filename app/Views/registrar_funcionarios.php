<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?=$_ENV["APP_NAME"]?> | Registro</title>
	<!-- Google Font: Source Sans Pro -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="<?=$_ENV["BASE_URL"]?>theme/plugins/fontawesome-free/css/all.min.css">
    <link href="<?=$_ENV["BASE_URL"]?>css/sweetalert2.min.css" rel="stylesheet">
</head>
<body>
<style>
    body {
        background: #5d6d7e!important;
    }
    li.list-group-item.bg-primary.text-white.text-center {
        font-size: 20;
        font-weight: 500;
    }
</style>
<div class="container">
    <div class="row mt-5">
        <div class="col-md-10 m-auto">
            <?= $render; ?>
            <?= $mask; ?>
        </div>
    </div>
</div>
<div id="artify-ajax-loader">
    <img width="300" src="<?=$_ENV["BASE_URL"]?>app/libs/artify/images/ajax-loader.gif" class="artify-img-ajax-loader"/>
</div>
<script src="<?=$_ENV["BASE_URL"]?>js/sweetalert2.all.min.js"></script>
<script>
     $(document).on("artify_after_submission", function(event, obj, data) {
        $('.artify_error').hide();
        $('.artify_message').hide();

        let json = JSON.parse(data);

        if(json.message){
            Swal.fire({
                title: "Genial!",
                text: "Ya puede Iniciar Sesión",
                icon: "success",
                confirmButtonText: "Aceptar",
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href="<?=$_ENV["BASE_URL"]?>login";
                }
            });
        } else {
            Swal.fire({
                title: "Genial!",
                text: json.error,
                icon: "error",
                confirmButtonText: "Aceptar",
                allowOutsideClick: false
            })
        }
     });
</script>
</body>
</html>