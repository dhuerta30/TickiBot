
<?php require "layouts/header.php"; ?>
<?php require "layouts/sidebar.php"; ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link href="<?=$_ENV["BASE_URL"]?>css/sweetalert2.min.css" rel="stylesheet">
<style>
    .chosen-container {
        width: 100%!important;
    }
</style>
<div class="content-wrapper">
    <section class="content">
        <div class="card mt-4">
            <div class="card-body">

                <div class="row">
                    <div class="col-md-12">
                        
                        <div class="row">

                            <div class="col-md-8">
                                <div class="chat-container">
                                    <div class="chat-header">Tickibot - Soporte en tiempo real con IA</div>

                                    <input type="text" id="search-messages" class="form-control mt-2 mb-2" placeholder="Buscar mensajes...">

                                    <div id="chatbox">
                                        <?php 
                                            $usuario = $_SESSION['usuario'][0]["id"];
                                            $historial_chat = App\Controllers\HomeController::historial_chat($usuario);
                                        ?>

                                        <?php if($historial_chat): ?>
                                            
                                            <?php foreach($historial_chat as $chat): ?>
                                            <div class="message user w-100 d-flex align-items-center">
                                                <div class="mr-2">
                                                    <img src="<?=$_ENV["BASE_URL"]?>app/libs/artify/uploads/<?=$_SESSION["usuario"][0]["avatar"]?>" alt="<?=$usuario?>" style="width: 50px; height: 50px; border-radius: 50%;">
                                                </div>
                                                <div><?=$chat["mensaje_usuario"]?></div>
                                            </div>

                                            <div class="message bot d-block w-100">
                                                <img src="<?=$_ENV["BASE_URL"]?>theme/img/boot.png" alt="Bot" style="width: 50px; height: 50px; border-radius: 50%;">
                                                <?=$chat["respuesta_bot"]?>
                                            </div>
                                            <?php endforeach; ?>
                                        
                                        <?php endif; ?>

                                    </div>
                                    <div class="chat-footer">
                                        <button class="btn btn-danger clear_chat" title="Limpiar todo el Historial"><i class="fa fa-trash"></i></button>
                                        <input type="text" id="userInput" class="form-control" placeholder="Escribe tu mensaje y presiona enter...">
                                        <button class="btn btn-primary" onclick="sendMessage()"><i class="fa-solid fa-paper-plane"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">

                                <div class="card">
                                    <div class="card-header bg-secondary">
                                        Sugerencias para preguntar al Bot
                                    </div>
                                    <div class="card-body">
                                        <?=$render?>
                                        <?=$chosen?>
                                        <a href="javascript:;" class="btn btn-primary btn-block usar">Usar</a>
                                    </div>
                                </div>


                                <div class="card">
                                    <div class="card-header bg-secondary">
                                       Ingresar Tickets de Soporte
                                    </div>
                                    <div class="card-body">
                                        <?=$render2?>
                                    </div>
                                </div>
            
                            </div>
                        </div>

                    </div>
                    <div class="col-md-12 mt-3">
                        <?=$render3?>
                    </div>
                </div>


            </div>
        </div>
    </section>
</div>
<div id="artify-ajax-loader">
    <img width="300" src="<?=$_ENV["BASE_URL"]?>app/libs/artify/images/ajax-loader.gif" class="artify-img-ajax-loader"/>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
<script src="<?=$_ENV["BASE_URL"]?>js/sweetalert2.all.min.js"></script>
<script>

    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("search-messages").addEventListener("keyup", function() {
            let query = this.value.toLowerCase();
            let messages = document.querySelectorAll("#chatbox .message");

            messages.forEach(msg => {
                let text = msg.innerText.toLowerCase();
                if (text.includes(query)) {
                    msg.style.setProperty("display", "block", "important");
                } else {
                    msg.style.setProperty("display", "none", "important");
                }
            });
        });
    });

    $(document).on("artify_after_ajax_action", function(event, obj, data){
        var dataAction = obj.getAttribute('data-action');
        var dataId = obj.getAttribute('data-id');

        if(dataAction == "add"){
        
        }

        if(dataAction == "edit"){
        
        }

        if(dataAction == "delete"){

        }
    });
    $(document).on("artify_after_submission", function(event, obj, data) {
        let json = JSON.parse(data);

        $(".alert-success, .alert-danger").remove();

        if (json.message) {
            Swal.fire({
                icon: "success",
                text: json["message"],
                confirmButtonText: "Aceptar",
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $(".titulo").val("");
                    $(".contenido").val("");
                    $("#artify_search_btn").click();
                }
            });
        }
    });
</script>
<script>
function sendMessage() {
    let input = document.getElementById("userInput");
    let message = input.value.trim();
    if (message === ""){
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "Ingresa un mensaje para continuar",
            confirmButtonText: "Aceptar"
        });
        return;
    }

    let chatbox = document.getElementById("chatbox");

    // Agregar mensaje del usuario
    chatbox.innerHTML += `
        <div class="message user w-100 d-flex align-items-center">
            <div class="mr-2">
                <img src="<?=$_ENV["BASE_URL"]?>app/libs/artify/uploads/<?=$_SESSION["usuario"][0]["avatar"]?>" alt="Usuario" style="width: 70px; height: 70px; border-radius: 50%;">
            </div>
            <div>${message}</div>
        </div>`;

    // Mostrar spinner de carga
    let loadingSpinner = `
        <div id="loading" class="message bot w-100">
            <img src="<?=$_ENV["BASE_URL"]?>theme/img/boot.png" alt="Bot">
            <div class="loading">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Cargando...</span>
                </div>
            </div>
        </div>`;
    chatbox.innerHTML += loadingSpinner;
    chatbox.scrollTop = chatbox.scrollHeight;

    // Enviar el mensaje al backend
    fetch("<?=$_ENV["BASE_URL"]?>mensajes", {
        method: "POST",
        body: JSON.stringify({ message: message }),
        headers: { "Content-Type": "application/json" }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById("loading").remove();
        chatbox.innerHTML += `
            <div class="message bot d-block w-100">
                <img src="<?=$_ENV["BASE_URL"]?>theme/img/boot.png" alt="Bot">
                ${data.response}
            </div>`;
        chatbox.scrollTop = chatbox.scrollHeight;
    });

    input.value = "";
}

$(document).on("click", ".usar", function(){
    let frases = document.querySelector(".frases").value.trim();

    if(frases != ""){
        document.getElementById("userInput").value = frases;
        $("#sugerencias").modal('hide');
        sendMessage();
        $("#artify_search_btn").click();
        $(".frases").chosen("destroy");
        $(".frases").val("");
        $(".frases").chosen();
    } else {
        Swal.fire({
            icon: "warning",
            title: "Error",
            text: "Por favor, selecciona una sugerencia antes de continuar.",
            confirmButtonColor: "#007bff",
            confirmButtonText: "Aceptar"
        });
    }
});

// Permitir enviar mensaje al presionar Enter
document.getElementById("userInput").addEventListener("keypress", function(event) {
    if (event.key === "Enter") {
        event.preventDefault();
        $("#artify_search_btn").click();
        sendMessage();
    }
});

function enviarDatosFuncionario(){
    let nombre = document.getElementById("nombre").value.trim();
    let data_rut = document.getElementById("data_rut").value.trim();
    let area = document.getElementById("area").value.trim();

    if(nombre === "" || data_rut === "" || area === ""){
        Swal.fire({
            icon: "warning",
            title: "Campos vacíos",
            text: "Por favor, ingresa todos los datos antes de continuar.",
            confirmButtonColor: "#007bff",
            confirmButtonText: "Aceptar"
        });
        return;
    }
}

let inactivityTimeout;

// Función para mostrar mensaje de inactividad
function showInactivityMessage() {
    let chatbox = document.getElementById("chatbox");
    chatbox.innerHTML += `
        <div class="message bot w-100">
            <img src="<?=$_ENV["BASE_URL"]?>theme/img/boot.png" alt="Bot">
            Parece que no hay actividad. ¿Necesitas ayuda?
        </div>`;
    chatbox.scrollTop = chatbox.scrollHeight;
}

// Reiniciar temporizador de inactividad
function resetInactivityTimer() {
    clearTimeout(inactivityTimeout);
    inactivityTimeout = setTimeout(showInactivityMessage, 120000); // 2 minutos (120,000 ms)
}

// Detectar eventos de actividad
document.addEventListener("mousemove", resetInactivityTimer);
document.addEventListener("keypress", resetInactivityTimer);
document.addEventListener("click", resetInactivityTimer);

// Iniciar temporizador al cargar la página
resetInactivityTimer();


$(document).on("click", ".clear_chat", function(){
    Swal.fire({
        icon: "warning",
        text: "¿Estas seguro que deseas Limpiar el Historial de mensajes?",
        confirmButtonText: "Aceptar",
        showCancelButton: true
    }).then((result) => {
        if (result.isConfirmed) {

            fetch(`<?=$_ENV["BASE_URL"]?>eliminar_historial/<?=$_SESSION["usuario"][0]["id"]?>`, {
                method: "GET",
                headers: { "Content-Type": "application/json" }
            })
            .then(response => response.json())
            .then(data => {
                $(".user").remove();
                $(".bot").remove();
                $("#artify_search_btn").click();
                Swal.fire({
                    icon: "success",
                    text: "Se ha Limpiado el Historial!",
                    confirmButtonText: "Aceptar"
                });
            })
            .catch(error => {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Hubo un problema al enviar los datos. Inténtalo de nuevo.",
                    confirmButtonColor: "#dc3545",
                    confirmButtonText: "Aceptar"
                });
                console.error("Error:", error);
            });

        }
    });
});
</script>
<?php require "layouts/footer.php"; ?>