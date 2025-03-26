<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?=$_ENV["APP_NAME"]?> | Login</title>
	<!-- Google Font: Source Sans Pro -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="<?=$_ENV["BASE_URL"]?>theme/plugins/fontawesome-free/css/all.min.css">
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
            <?= $login; ?>
            <?= $mask; ?>
        </div>
    </div>
</div>
<div id="artify-ajax-loader">
    <img width="300" src="<?=$_ENV["BASE_URL"]?>app/libs/artify/images/ajax-loader.gif" class="artify-img-ajax-loader"/>
</div>
<script>
    $(document).on("change", ".seleccion_de_acceso", function(){
        let val = $(this).val();

        if(val == "rut_clave"){
            $(".rut_col").removeClass("d-none");
            $(".usuario_col").addClass("d-none");
            $(".usuario").attr("disabled", "disabled");
            $(".usuario").removeAttr("required", "required");
            $(".rut").removeAttr("disabled", "disabled");
            $(".rut").attr("required", "required");
            $(".botones").removeClass("d-none");
        }
        
        if(val == "usuario_clave"){
            $(".rut_col").addClass("d-none");
            $(".usuario_col").removeClass("d-none");
            $(".rut").attr("disabled", "disabled");
            $(".rut").removeAttr("required", "required");
            $(".usuario").removeAttr("disabled", "disabled");
            $(".usuario").attr("required", "required");
            $(".botones").removeClass("d-none");
        } 

        if(val == ""){
            $(".usuario_col").addClass("d-none");
            $(".rut_col").addClass("d-none");

            $(".rut").attr("disabled", "disabled");
            $(".usuario").attr("disabled", "disabled");

            $(".usuario").attr("required", "required");
            $(".rut").attr("required", "required");
            $(".botones").addClass("d-none");
        }
    });
</script>
</body>
</html>