
<?php require "layouts/header.php"; ?>
<?php require "layouts/sidebar.php"; ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link href="<?=$_ENV["BASE_URL"]?>css/sweetalert2.min.css" rel="stylesheet">
<div class="content-wrapper">
    <section class="content">
        <div class="card mt-4">
            <div class="card-body">

                <div class="row">
                    <div class="col-md-12">
                       
                    <div class="modal fade" id="sugerencias" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="staticBackdropLabel">Selecciona una sugerencia</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                
                                <label>Sugenercias</label>
                                <select class="form-control" name="frases" id="frases">
                                    <option value="">Seleccionar</option>
                                    <option value="hola">hola</option>
                                    <option value="¿cómo estás?">¿cómo estás?</option>
                                    <option value="¿qué puedes hacer?">¿qué puedes hacer?</option>
                                    <option value="adiós">adiós</option>
                                </select>
                                <button class="btn btn-primary mt-3" id="usar">Usar</button>

                            </div>
                            </div>
                        </div>
                        </div>

                        <div class="chat-container">
                            <div class="chat-header">Tickibot - Soporte en tiempo real </div>
                            <div id="chatbox">
                                <?php 
                                    $usuario = $_SESSION['usuario'][0]["usuario"];
                                    $historial_chat = App\Controllers\HomeController::historial_chat($usuario);
                                ?>
                            </div>
                            <div class="chat-footer">
                                <button class="btn btn-info" data-toggle="modal" data-target="#sugerencias"><i class="fa-solid fa-info"></i></button>
                                <input type="text" id="userInput" class="form-control" placeholder="Escribe y presiona enter...">
                                <button class="btn btn-primary" onclick="sendMessage()"><i class="fa-solid fa-paper-plane"></i></button>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
<script src="<?=$_ENV["BASE_URL"]?>js/sweetalert2.all.min.js"></script>
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
</script>
<script>
function sendMessage() {
    let input = document.getElementById("userInput");
    let message = input.value.trim();
    if (message === "") return;

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
        document.getElementById("loading").remove(); // Remover el spinner

        if (data.response === "Ingrese los datos a continuación para restablecer su contraseña de HIS") {
            chatbox.innerHTML += `
                <div class="message bot d-block w-100">
                    <img src="<?=$_ENV["BASE_URL"]?>theme/img/boot.png" alt="Bot">
                    <div class="mt-2">${data.response}</div> <!-- Agregamos un margen superior para separar el texto del formulario -->
                    
                    <div class="form-group mt-3">
                        <label><strong>Rut:</strong></label>
                        <input type="text" class="form-control mb-2" id="rut" placeholder="Ingresa tu RUT">
                        <label><strong>Contraseña:</strong></label>
                        <input type="password" class="form-control mb-2" id="pass" placeholder="¿Qué contraseña desea?">
                        <button class="btn btn-info btn-block" onclick="enviarDatos()">Enviar Datos</button>
                    </div>
                </div>
                `;
        } else if (data.response === "Muy bien te enviré un técnico para que resuelva tu problema") {
            chatbox.innerHTML += `
                <div class="message bot d-block w-100">
                    <img src="<?=$_ENV["BASE_URL"]?>theme/img/boot.png" alt="Bot">
                    <div class="mt-2">${data.response} Ingresa tus Datos</div>
                    
                    <div class="form-group mt-3">
                        <label><strong>Rut:</strong></label>
                        <input type="text" class="form-control mb-2" id="rut" placeholder="Ingresa tu RUT">
                        <label><strong>Contraseña:</strong></label>
                        <input type="password" class="form-control mb-2" id="pass" placeholder="¿Qué contraseña desea?">
                        <button class="btn btn-info btn-block" onclick="enviarDatos()">Enviar Datos</button>
                    </div>
                </div>
                `;
        } else {
            chatbox.innerHTML += `
                <div class="message bot w-100">
                    <img src="<?=$_ENV["BASE_URL"]?>theme/img/boot.png" alt="Bot">
                    ${data.response}
                </div>`;
        }

        chatbox.scrollTop = chatbox.scrollHeight;
    });

    input.value = "";
}

document.getElementById("usar").addEventListener("click", function(){
    let frases = document.getElementById("frases").value.trim();

    if(frases != ""){
        document.getElementById("userInput").value = frases;
        $("#sugerencias").modal('hide');
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

function enviarDatos() {
    let rut = document.getElementById("rut").value.trim();
    let pass = document.getElementById("pass").value.trim();

    if (rut === "" || pass === "") {
        Swal.fire({
            icon: "warning",
            title: "Campos vacíos",
            text: "Por favor, ingresa todos los datos antes de continuar.",
            confirmButtonColor: "#007bff",
            confirmButtonText: "Aceptar"
        });
        return;
    }

    fetch("<?=$_ENV["BASE_URL"]?>mesajes", {
        method: "POST",
        body: JSON.stringify({ rut: rut, pass: pass }),
        headers: { "Content-Type": "application/json" }
    })
    .then(response => response.json())
    .then(data => {
        Swal.fire({
            icon: "success",
            title: "Éxito",
            text: data.response,
            confirmButtonColor: "#28a745",
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
</script>
<?php require "layouts/footer.php"; ?>