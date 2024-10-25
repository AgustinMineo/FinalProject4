<?php
namespace Views;
require_once("validate-session.php");
$countMessage = isset($_SESSION['messageCount']) ? $_SESSION['messageCount'] : 0;
$countMessageGroup = isset($_SESSION['messageCountGroup']) ? $_SESSION['messageCountGroup'] : 0;
// Transformo los arrays para poder usarlos en la vista de modifyRole
$rolesArray = [];
$typeArray = [];
$privacyArray = [];


foreach ($roleList as $role) {
    $rolesArray[] = [
        'id' => $role->getId(),
        'name' => $role->getName(),
        'is_active' => $role->getIsActive(),
        'description' => $role->getDescription()
    ];
}
$roleListJson = json_encode($rolesArray);

foreach ($typeList as $type) {
    $typeArray[] = [
        'id' => $type->getId(),
        'name' => $type->getName(),
    ];
}
$typeListJson = json_encode($typeArray);

foreach ($privacyList as $privacy) {
    $privacyArray[] = [
        'id' => $privacy->getId(),
        'name' => $privacy->getName(),
    ];
}
$privacyListJson = json_encode($privacyArray);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<style>
    #messageContainer {
    max-height: 75vh; 
    overflow-y: auto;  
    padding: 10px;
    background-color: #f9f9f9;
    display: flex;
    flex-direction: column-reverse;
    }

    .message {
        border-radius: 10px;
        padding: 10px;
        margin-bottom: 10px;
        max-width: 70%; 
    }

    .message.sent {
        background-color: #dcf8c6; 
        align-self: flex-end; 
    }

    .message.received {
        background-color: #ffffff; 
        align-self: flex-start; 
    }

    .chatList {
        max-height: 75vh;
        overflow-y: auto;  
        padding-right: 10px;
    }

    .container-fluid {
        height: 60vh; 
    }

    .chatBoxContainer {
        flex-grow: 1;
        min-height: auto;
    }

    .list-group-item.active {
        background-color: #007bff; 
        color: white; 
    }
    .checkmark {
        font-size:1.0rem;
        margin-left:5px;
    }
    /* Estilo general para el contenedor de mensajes */
    .message {
    width: 15%; 
    margin-bottom: 15px; 
    padding: 10px 15px; 
    border-radius: 20px; 
    position: relative; 
    }

    /* Estilo para los mensajes enviados */
    .message.sent {
    background-color: #d4edda; 
    color: #155724; 
    align-self: flex-end; 
    margin-left: auto;
    width: 25%;
    }

    /* Estilo para los mensajes recibidos */
    .message.received {
    background-color: #e8e7e3;
    color:white;
    word-wrap: break-word;
    align-self: flex-start; 
    margin-right: auto;
    width: 25%;
    }

    .message p {
    color:black;
    word-wrap: break-word;
    margin: 0; 
    }
    .date{
    margin-right:29px;
    }

    .checkmark {
    position: absolute;
    bottom: 14px;
    right: 8px; 
    font-size: 12px; 
    }

    .modal.fade .modal-dialog {
        transform: translateY(-100px); 
        opacity: 0; 
    }

    .modal.show .modal-dialog {
        transform: translateY(0);
        opacity: 1; 
        transition: transform 0.3s ease, opacity 0.3s ease; 
    }

    /*Imagen listado de grupos */
    .list-group-item {
    display: flex;
    align-items: center;
    padding: 10px; 
    }

    .group-image {
        width: 40px; 
        height: 40px; 
        border-radius: 50%;
        margin-right: 10px;
    }
    /*Imagen listado de grupos */
</style>
<body>
<div class="container-fluid d-flex flex-column mb-5">
    <div class="row flex-grow-1 mb-5">
        <!-- Sidebar de chats -->
        <div class="col-md-3 bg-light border-right p-3 d-flex flex-column">
            <input type="hidden" name="userRole" id="userRole" value="<?php echo $userRole?>">
            <h4>Chats</h4>
            <button id="newChatButton" class="btn btn-primary mb-3">Nuevo Chat</button>
            <div class="chatList flex-grow-1">
                <h5>Mensajes Privados</h5>
                <ul class="list-group mb-4" id="privateMessageList">
                    <?php foreach($chats as $chatUser): ?>
                        <input type="hidden" id="userNameSend<?php echo $chatUser->getUserID()?>" value="<?php echo htmlspecialchars($chatUser->getLastName() . ' ' . $chatUser->getFirstName()); ?>">

                        <li class="list-group-item d-flex align-items-center" 
                            data-userid="<?php echo $chatUser->getUserID(); ?>"
                            data-name="<?php echo htmlspecialchars($chatUser->getLastName() . ' ' . $chatUser->getFirstName()); ?>">
                            
                            <?php 
                                $imagePath = $chatUser->getImage(); 
                                if ($imagePath && file_exists($imagePath)) {
                                    $imageData = base64_encode(file_get_contents($imagePath));
                                    $imgSrc = "data:image/jpeg;base64," . $imageData;
                                } else {
                                    $defaultImagePath = 'Upload/UserImages/userDefaultImage.jpg';
                                    $imgSrc = "data:image/jpeg;base64," . base64_encode(file_get_contents($defaultImagePath));
                                }
                            ?>
                            <img src="<?php echo $imgSrc; ?>" alt="User Image" class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
                            <?php echo htmlspecialchars($chatUser->getLastName() . ' ' . $chatUser->getFirstName()); ?>
                            <?php 
                            $unreadMessages = 0;
                            if ($countMessage) {
                                foreach ($countMessage as $message) {
                                    if ($message['idUsuario'] == $chatUser->getUserID()) {
                                        $unreadMessages = $message['cantidad']; 
                                        break;
                                    }
                                }
                            }
                            ?>
                            <?php if ($unreadMessages > 0): ?>
                                <span class="badge bg-danger" id="badge-<?php echo $chatUser->getUserID(); ?>"><?php echo $unreadMessages; ?></span> <!-- Mostrar la cantidad de mensajes pendientes-->
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="container text-center">
                    <h5>Grupos</h5>
                    <div class="container d-flex align-items-center justify-content-between">
                        <button id="newGroupButton" class="btn btn-outline-success mb-3 w-auto" data-bs-toggle="modal" data-bs-target="#createGroupModal">Nuevo Grupo</button>
                        <button id="searchGroupButton" class="btn btn-outline-primary mb-3 w-auto" data-bs-toggle="modal" data-bs-target="#searchGroupModal" onclick="getPublicGroups(<?php echo $currentUser?>)">Buscar Grupo</button>
                        <button id="getInvitations" class="btn btn-outline-info mb-3 w-auto" data-bs-toggle="modal" onclick="getInvitations(<?php echo $currentUser?>)" data-bs-target="#">Invitaciones</button>
                    </div>
                    <?php if($groupList):?>
                        <ul class="list-group" id="groupList">
                            <?php foreach($groupList as $group): ?>
                                <?php 
                                $imagePath = $group->getGroupInfo()->getImage(); 
                                if (file_exists($imagePath)) {
                                    $imageData = base64_encode(file_get_contents($imagePath));
                                    $imgSrc = "data:image/jpeg;base64," . $imageData;
                                } else {
                                    $imgSrc = GROUPS_PATH . "Default/DefaultGroupImage.jpg";
                                }
                                $groupTypeName = $group->getGroupType()->getName();

                                $unreadGroupMessages = 0;
                                if ($countMessageGroup) {
                                    foreach ($countMessageGroup as $message) {
                                        if ($message['idGrupo'] == $group->getId()) {
                                            $unreadGroupMessages = $message['cantidad']; 
                                            break;
                                        }
                                    }
                                }
                                ?>
                                <li class="list-group-item d-flex align-items-center"
                                    data-group-id="<?php echo $group->getId(); ?>"
                                    id="GroupID<?php echo $group->getId(); ?>"
                                    onclick="loadGroupMessages(<?php echo $group->getId(); ?>); setGroupID(<?php echo $group->getId();?>);">
                                    <img src="<?php echo $imgSrc; ?>" alt="Group Image" class="group-image me-2 groupImage<?php echo $group->getId(); ?>" style="width: 40px; height: 40px; border-radius: 50%;">
                                    <span class="groupNameDisplay<?php echo $group->getId(); ?>"><?php echo $group->getName(); ?></span>
                                    <?php if ($unreadGroupMessages > 0): ?>
                                        <span class="badge bg-danger ms-2" id="group-badge-<?php echo $group->getId(); ?>"><?php echo $unreadGroupMessages; ?></span>
                                    <?php endif; ?>
                                    <span class="badge bg-success ms-auto"><?php echo $groupTypeName; ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>

                    <?php endif;?>
                </div>
            </div>
        </div>
        <!-- Area de chat -->
        <div class="col-md-9 d-flex flex-column">
            <div id="chatBox" class="chatBoxContainer p-3" style="display:none;">
                <button type="button" id="chatDetailsButton" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#modalDetail">
                    Detalles del chat
                </button>
                <div id="messageContainer" class="overflow-auto border rounded p-3 bg-light">
            </div>
        </div>
        <!-- Area de chat -->
        
            <!-- Formulario de mensajes -->
            <div class="messageFormContainer" id="blockMessageSend" style="display:none;">
                <form id="messageForm" class="d-flex align-items-center" onsubmit="sendMessage(event)">
                    <input type="hidden" name="receiverId" id="receiverId" value="" />
                    <input type="hidden" name="groupID" id="groupID" value="" />
                    <input type="hidden" name="currentID" id="currentID" value="<?php echo $currentUser ?>" />
                    
                    <div class="input-group w-100" id="messageInputID">
                        <input type="text" name="message" id="message" class="form-control" placeholder="Escribe tu mensaje..." required aria-describedby="basic-addon1" />
                        <button type="submit" class="btn btn-primary"><i class="bi bi-send-check"></i> Enviar</button>
                    </div>
                    <div class="input-group w-100 text-center alert alert-danger" id="InvitedUserDetails" style="display:none;">
                        Como usuario invitado, no tienes permisos para enviar mensajes hasta no ser miembro.
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<!-- Modal nuevo chat-->
<div class="modal fade" id="newChatModal" tabindex="-1" aria-labelledby="newChatModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content shadow-lg" style="background-color: #f8f9fa;">
            <div class="modal-header bg-gradient-primary text-primary">
                <h5 class="modal-title" id="newChatModalLabel">
                    <i class="bi bi-chat-dots me-2"></i>
                    Usuarios sin mensajes
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="userList" class="container d-flex flex-wrap justify-content-between">
                    
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between align-items-center">
                <div>
                    <i class="bi bi-info-circle me-2"></i>
                    <small class="text-muted">Selecciona un usuario para iniciar un chat.</small>
                </div>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Modal nuevo chat-->

<!-- Modal nuevo grupo-->
<div class="modal fade" id="createGroupModal" tabindex="-1" aria-labelledby="createGroupModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content shadow-lg" style="">
            <div class="modal-header bg-gradient-primary text-primary">
                <h5 class="modal-title" id="createGroupModalLabel">
                    <i class="bi bi-plus-circle me-2"></i>
                    Crear Nuevo Grupo
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createGroupForm" enctype="multipart/form-data">
                    <input type="hidden" id="currentUserID" name="currentUserID" value="<?php echo $currentUser?>">
                    <div class="mb-4">
                        <label for="groupName" class="form-label">Nombre del Grupo *</label>
                        <input type="text" class="form-control" id="groupName" name="groupName" required>
                    </div>
                    <div class="mb-4">
                        <label for="groupType" class="form-label">Tipo de Grupo *</label>
                        <select class="form-select" id="groupType" name="groupType" required>
                            <option value="">Seleccione un tipo de grupo</option>
                            <?php foreach ($typeList as $type): ?>
                                <option value="<?php echo $type->getId(); ?>"><?php echo $type->getName(); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="groupPrivacy" class="form-label">Privacidad del Grupo *</label>
                        <select class="form-select" id="groupPrivacy" name="groupPrivacy" required>
                            <option value="">Seleccione la privacidad</option>
                            <?php foreach ($privacyList as $privacy): ?>
                                <option value="<?php echo $privacy->getId(); ?>"><?php echo $privacy->getName(); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="description" class="form-label">Descripción *</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="rules" class="form-label">Reglas *</label>
                        <textarea class="form-control" id="rules" name="rules" rows="3" required></textarea>
                    </div>
                    <div class="mb-4" id="dateFields" style="display: none;">
                        <label for="startDate" class="form-label">Fecha de Inicio</label>
                        <input type="date" class="form-control" id="startDate" name="start_date">
                    </div>
                    <div class="mb-4" id="dateFieldsEnd" style="display: none;">
                        <label for="endDate" class="form-label">Fecha de Fin</label>
                        <input type="date" class="form-control" id="endDate" name="end_date">
                    </div>
                    <div class="mb-4">
                        <label for="groupImage" class="form-label">Imagen del Grupo</label>
                        <input type="file" class="form-control" id="groupImage" name="groupImage[]" accept="image/*">
                    </div>
                    <div class="mb-4 w-100 d-flex align-items-center justify-content-center">
                        <div id="imagePreview" class="border p-3" style="height: 200px; display: none;">
                            <img id="previewImg" src="" alt="Vista previa de la imagen" style="max-width: 100%; max-height: 100%;">
                        </div>
                    </div>
                    <div class="container text-center">
                        <button type="button" class="btn btn-primary w-100" id="submitGroupButton">Crear Grupo</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal nuevo grupo-->

<!-- Modal Detalle chat -->
<?php foreach($chats as $chatUser): ?>
    <div class="modal fade" id='modalUser<?php echo $chatUser->getUserID(); ?>' tabindex="-1" aria-labelledby='userModalLabel<?php echo $chatUser->getUserID(); ?>' aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content shadow-lg">
                <div class="modal-header bg-primary text-white py-3">
                    <h5 class="modal-title d-flex align-items-center" id='userModalLabel<?php echo $chatUser->getUserID(); ?>'>
                        <i class="bi bi-person-circle me-2"></i>
                        Detalles del Usuario: <?php echo $chatUser->getLastName(); ?>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card shadow-sm p-4">
                        <div class="row justify-content-center text-center">
                            <div class="col-lg-3 mb-4">
                                <div class="card-profile-image mt-2">
                                    <img src="<?php 
                                    if($chatUser->getImage()){
                                        echo FRONT_ROOT . $chatUser->getImage();
                                    }else{
                                        echo FRONT_ROOT . USER_PATH .'\userDefaultImage.jpg';
                                    }?>
                                    " class="rounded-circle border border-3 border-primary" alt="Profile Image" style="width: 150px; height: 150px;">
                                </div>
                            </div>
                        </div>
                        <div class="card-body text-center">
                            <h3 class="text-primary mb-3">
                                <?php echo $chatUser->getFirstName() . ' ' . $chatUser->getLastName(); ?>
                                <br>
                                <span class="font-weight-light text-muted"><?php echo date_diff(date_create($chatUser->getBirthDate()), date_create('today'))->y; ?> años</span>
                            </h3>

                            <div class="row">
                                <div class="col-12 mb-3">
                                    <div class="h5 text-muted">
                                        <i class="bi bi-person-fill me-2"></i> Usuario perfil: 
                                        <span class="fw-bold"><?php
                                            $rol = $chatUser->getRol();
                                            echo ($rol == 1) ? 'Admin' : (($rol == 2) ? 'Owner' : 'Keeper');
                                        ?></span>
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <div class="h5 text-muted">
                                        <i class="bi bi-telephone-fill me-2"></i> 
                                        <?php echo $chatUser->getCellPhone(); ?>
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <div class="h5 text-muted">
                                        <i class="bi bi-envelope-fill me-2"></i> 
                                        <?php echo $chatUser->getEmail(); ?>
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <div class="h5 text-muted">
                                        <i class="bi bi-info-circle me-2"></i> 
                                        <?php echo $chatUser->getDescription(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
<!-- Modal Detalle chat -->

<!--Modal Detalle grupo-->
<?php foreach($groupList as $group): ?>
    <div class="modal fade" id="modalGroup<?php echo $group->getId(); ?>" tabindex="-1" aria-labelledby="groupModalLabel<?php echo $group->getId(); ?>" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-gradient-primary text-white py-4">
                    <span class="badge bg-<?php echo ($group->getStatusId()->getId() == 1) ? 'success' : 'warning'; ?> me-3 p-2" style="font-size: 1rem;">
                        <?php echo $group->getStatusId()->getName(); ?>
                    </span>
                    <h5 class="modal-title d-flex align-items-center" id="groupModalLabel<?php echo $group->getId(); ?>">
                    <div class="container d-flex align-items-center justify-content-center">
                        <p class="fs-5 fw-bold text-black mb-0 groupNameDisplay<?php echo $group->getId();?>"><?php echo $group->getName(); ?></p>
                        <div id="adminBlock<?php echo $group->getId(); ?>" class="ms-2">
                            <button type="button" class="btn btn-warning" 
                            onclick="editField(<?php echo $group->getId(); ?>, 'name', '<?php echo $group->getName(); ?>')" 
                            style="--bs-btn-padding-y: .10rem; --bs-btn-padding-x: .2rem; --bs-btn-font-size: .25rem;">
                                <i class="bi bi-pencil" style="font-size: 0.8rem;"></i>
                            </button>
                        </div>
                    </div>
                    </h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row justify-content-center mb-4">
                        <div class="col-auto text-center">
                            <?php 
                                if ($image = $group->getGroupInfo()->getImage()) {
                                    $imageData = base64_encode(file_get_contents($image));
                                    echo '<img src="data:image/jpeg;base64,'.$imageData.'" class="img-fluid img-thumbnail shadow-lg rounded-circle border border-light groupImage'.$group->getId().'" style="width: 300px; height: 300px; object-fit: cover;">';
                                } else {
                                    echo '<h3>Sin imagen</h3>';
                                }
                            ?>
                            <div class="container d-flex flex-wrap align-items-center justify-content-center">
                                <h6 class="mt-3 text-muted">Imagen del Grupo</h6>
                                <div id="adminBlock<?php echo $group->getId();?>" class="ms-2">
                                    <button type="button" class="btn btn-warning" 
                                        onclick="editField(<?php echo $group->getId(); ?>, 'image', '<?php echo $group->getGroupInfo()->getImage(); ?>')" 
                                        style="--bs-btn-padding-y: .10rem; --bs-btn-padding-x: .2rem; --bs-btn-font-size: .25rem;">
                                        <i class="bi bi-pencil" style="font-size: 0.8rem;"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="container">
                        <div class="row mb-4">
                            <div class="col text-center">
                                <h6 class="text-muted"><i class="bi bi-person-circle me-2"></i>Creador del Grupo</h6>
                                <div class="card shadow-sm mt-3">
                                    <div class="card-body d-flex align-items-center flex-column">
                                        <?php 
                                        $creator = $group->getCreated_by();
                                        $imagePath = $creator->getImage();
                                        
                                        if ($imagePath && file_exists($imagePath)): 
                                            $imageData = base64_encode(file_get_contents($imagePath));
                                            $imgSrc = "data:image/jpeg;base64," . $imageData;
                                        else:
                                            $defaultImagePath = 'Upload/UserImages/userDefaultImage.jpg';
                                            $imgSrc = "data:image/jpeg;base64," . base64_encode(file_get_contents($defaultImagePath));
                                        endif;
                                        ?>
                                        
                                        <img src="<?php echo $imgSrc; ?>" 
                                            class="rounded-circle mb-3" 
                                            style="width: 100px; height: 100px; object-fit: cover;">
                                        <div class="text-center">
                                            <p class="mb-0 fw-bold"><?php echo htmlspecialchars($creator->getFirstName() . ' ' . $creator->getLastName()); ?></p>
                                            <p class="mb-0"><i class="bi bi-telephone me-2"></i>Teléfono: <?php echo htmlspecialchars($creator->getCellPhone()); ?></p>
                                            <p class="mb-0"><i class="bi bi-envelope me-2"></i>Email: <?php echo htmlspecialchars($creator->getEmail()); ?></p>
                                            <p class="mb-0"><i class="bi bi-info-circle me-2"></i>Descripción: <?php echo htmlspecialchars($creator->getDescription()); ?></p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="row mb-4 text-center">
                            <div class="col-md-6">
                                <div class="container d-flex flex-wrap justify-content-center align-items-center">
                                <h6 class="text-muted m-0"><i class="bi bi-type"></i> Tipo de Grupo</h6>
                                    <div id="adminBlock<?php echo $group->getId(); ?>" class="ms-2 adminBlock<?php echo $group->getId();?>">
                                        <button type="button" class="btn btn-warning" 
                                            onclick="editField(<?php echo $group->getId(); ?>, 'type', '<?php echo $group->getGroupType()->getId(); ?>')" 
                                            style="--bs-btn-padding-y: .10rem; --bs-btn-padding-x: .2rem; --bs-btn-font-size: .25rem;">
                                            <i class="bi bi-pencil" style="font-size: 0.8rem;"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card shadow-sm mt-3">
                                    <div class="card-body">
                                        <p class="fw-bold" id="groupTypeName<?php echo $group->getId(); ?>"><?php echo $group->getGroupType()->getName(); ?></p>
                                        <p id="groupTypeDescription<?php echo $group->getId();?>"><?php echo $group->getGroupType()->getDescription(); ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-muted"><i class="bi bi-check-circle"></i> Estado del Grupo</h6>
                                <div class="card shadow-sm mt-3">
                                    <div class="card-body">
                                        <p class="fw-bold"><?php echo $group->getStatusId()->getName(); ?></p>
                                        <p><?php echo $group->getStatusId()->getDescription(); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3 text-center">
                            <div class="col-12">
                                <div class="container d-flex flex-wrap justify-content-center align-items-center">
                                    <h6 class="text-muted m-0"><i class="bi bi-lock"></i> Privacidad del Grupo</h6>
                                    <div id="adminBlock<?php echo $group->getId(); ?>" class="ms-2 adminBlock<?php echo $group->getId();?>">
                                        <button type="button" class="btn btn-warning" 
                                            onclick="editField(<?php echo $group->getId(); ?>, 'privacy', '<?php echo $group->getGroupInfo()->getDescription(); ?>')" 
                                            style="--bs-btn-padding-y: .10rem; --bs-btn-padding-x: .2rem; --bs-btn-font-size: .25rem;">
                                            <i class="bi bi-pencil" style="font-size: 0.8rem;"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card shadow-sm mt-3">
                                    <div class="card-body">
                                        <p class="fw-bold m-0" id="groupPrivacyName<?php echo $group->getId();?>"><?php echo $group->getGroupPrivacy()->getName(); ?></p>
                                        <p id="groupPrivacyDescription<?php echo $group->getId();?>"><?php echo $group->getGroupPrivacy()->getDescription(); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-muted m-0"><i class="bi bi-info-circle"></i> Información del Grupo</h6>
                                <div class="card shadow-sm mb-3">
                                    <div class="card-body">
                                        <div class="container d-flex flex-wrap m-0">
                                            <h6 class="fw-bold m-0">Descripción</h6>
                                            <div id="adminBlock<?php echo $group->getId(); ?>" class="ms-2 adminBlock<?php echo $group->getId();?>">
                                                <button type="button" class="btn btn-warning" 
                                                onclick="editField(<?php echo $group->getId(); ?>, 'description', '<?php echo $group->getGroupInfo()->getDescription(); ?>')" 
                                                    style="--bs-btn-padding-y: .10rem; --bs-btn-padding-x: .2rem; --bs-btn-font-size: .25rem;">
                                                    <i class="bi bi-pencil" style="font-size: 0.8rem;"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <p class="mb-0" id="groupDescShort<?php echo $group->getId(); ?>" data-group-id="<?php echo $group->getId(); ?>">
                                            <?php echo $group->getGroupInfo()->getDescription(); ?>
                                        </p>

                                    </div>
                                </div>
                                <div class="card shadow-sm">
                                    <div class="card-body">
                                        <div class="container d-flex flex-wrap m-0">
                                            <h6 class="fw-bold m-0">Reglas</h6>
                                            <div id="adminBlock<?php echo $group->getId(); ?>" class="ms-2 adminBlock<?php echo $group->getId();?>">
                                                <button type="button" class="btn btn-warning" 
                                                onclick="editField(<?php echo $group->getId(); ?>, 'rules', '<?php echo $group->getGroupInfo()->getRules(); ?>')" 
                                                    style="--bs-btn-padding-y: .10rem; --bs-btn-padding-x: .2rem; --bs-btn-font-size: .25rem;">
                                                    <i class="bi bi-pencil" style="font-size: 0.8rem;"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <p class="mb-0" id="groupRules<?php echo $group->getId(); ?>"><?php echo $group->getGroupInfo()->getRules(); ?></p>
                                    </div>
                                </div>
                                <div class="card shadow-sm" id="membersListID">
                                    <div class="card-body">
                                        <h6 class="fw-bold">Miembros</h6>
                                        <input type="hidden" name="currentUserIdRole" id="currentUserIdRole" value="<?php echo $currentUser ?>">
                                        <div class="row mb-3">
                                            <div class="row mb-3">
                                            <!-- Filtro por correo -->
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <span class="input-group-text" id="email-addon">
                                                        <i class="fas fa-envelope"></i>
                                                    </span>
                                                    <input type="text" class="form-control" id="emailFilter_<?php echo $group->getId(); ?>" placeholder="Filtrar por correo" 
                                                        onkeyup="filterMembers(<?php echo $group->getId(); ?>)" aria-label="Filtrar por correo" aria-describedby="email-addon">
                                                </div>
                                            </div>
                                            <!-- Filtro por nombre -->
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <span class="input-group-text" id="name-addon">
                                                        <i class="fas fa-user"></i>
                                                    </span>
                                                    <input type="text" class="form-control" id="nameFilter_<?php echo $group->getId(); ?>" placeholder="Filtrar por nombre" 
                                                        onkeyup="filterMembers(<?php echo $group->getId(); ?>)" aria-label="Filtrar por nombre" aria-describedby="name-addon">
                                                </div>
                                            </div>
                                            <!-- Filtro por rol -->
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <span class="input-group-text" id="role-addon">
                                                        <i class="fas fa-user-tag"></i>
                                                    </span>
                                                    <select class="form-select" id="roleFilter_<?php echo $group->getId(); ?>" onchange="filterMembers(<?php echo $group->getId(); ?>)">
                                                        <option value="">Filtrar por rol</option>
                                                        <?php foreach($roleList as $role): ?>
                                                        <option value='<?php echo $role->getId() ?>'><?php echo $role->getName() ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                        <div id="membersList<?php echo $group->getId(); ?>" class="list-group" style="max-height: 400px; overflow-y: auto;">
                                        </div>
                                    </div>
                                </div>

                                <div id="adminBlock<?php echo $group->getId(); ?>" class="container shadow-sm my-4 p-4 bg-light rounded adminBlock<?php echo $group->getId();?>" style="display: block;">
                                    <h6 class="mb-4 text-primary">Administrar Grupo</h6>
                                    <div class="list-group">
                                        <?php if($group->getCreated_by()->getUserID()  === $currentUser): ?>
                                            <button type="button" class="list-group-item list-group-item-action btn btn-danger my-2" data-bs-toggle="modal" data-bs-target="#deleteGroupModal<?php echo $group->getId(); ?>">Eliminar Grupo</button>
                                        <?php endif; ?>
                                        <button type="button" class="list-group-item list-group-item-action btn btn-warning my-2" data-bs-toggle="modal" data-bs-target="#inviteGroupModal<?php echo $group->getId(); ?>">Invitar al Grupo</button>
                                        <button type="button" class="list-group-item list-group-item-action btn btn-warning my-2" onclick="loadGroupInvitations(<?php echo $group->getId(); ?>)">
                                            <i class="bi bi-people mx-1"></i>
                                            Invitaciones
                                        </button>
                                    </div>
                                </div>

                                <?php if(intval($group->getGroupType()->getId()) === 6): ?>
                                <div class="container mt-4">
                                    <hr>
                                    <div class="container d-flex flex-wrap justify-content-center align-items-center">
                                        <h6 class="text-muted mx-0"><i class="bi bi-calendar-check"></i> Disponibilidad del Grupo</h6>
                                        
                                        <div id="adminBlock<?php echo $group->getId(); ?>" class="ms-2 adminBlock<?php echo $group->getId();?>">
                                        <button type="button" class="btn btn-warning" 
                                            onclick="editField(<?php echo $group->getId(); ?>, 'date', '<?php echo htmlspecialchars(json_encode(['startDate' => $group->getGroupInfo()->getStartDate(), 'endDate' => $group->getGroupInfo()->getEndDate()])); ?>')"
                                            style="--bs-btn-padding-y: .10rem; --bs-btn-padding-x: .2rem; --bs-btn-font-size: .25rem;">
                                            <i class="bi bi-pencil" style="font-size: 0.8rem;"></i>
                                        </button>
                                        </div>
                                    </div>
                                    <div class="row justify-content-center">
                                        <div class="col-md-6 text-center">
                                            <div class="card border-light shadow-sm">
                                                <div class="card-body">
                                                    <h5 class="card-title">Fecha de Inicio</h5>
                                                    <p class="card-text fw-bold fs-5 text-primary" id="startDate<?php echo $group->getId(); ?>"><?php echo $group->getGroupInfo()->getStartDate(); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 text-center">
                                            <div class="card border-light shadow-sm">
                                                <div class="card-body">
                                                    <h5 class="card-title">Fecha de Finalización</h5>
                                                    <p class="card-text fw-bold fs-5 text-primary" id="endDate<?php echo $group->getId(); ?>"><?php echo $group->getGroupInfo()->getEndDate(); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para eliminar grupo -->
    <div class="modal fade" id="deleteGroupModal<?php echo $group->getId(); ?>" tabindex="-1" aria-labelledby="deleteGroupLabel<?php echo $group->getId(); ?>" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteGroupLabel<?php echo $group->getId(); ?>">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas eliminar el grupo "<?php echo $group->getName(); ?>"? Esta acción no se puede deshacer.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" onclick="deleteGroup(<?php echo $group->getId(); ?>,<?php echo $currentUser?>)">Eliminar Grupo</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal para invitar a miembros -->
    <div class="modal fade" id="inviteGroupModal<?php echo $group->getId(); ?>" tabindex="-1" aria-labelledby="inviteGroupLabel<?php echo $group->getId(); ?>" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="inviteGroupLabel<?php echo $group->getId(); ?>">Invitar Miembros</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="inviteEmail<?php echo $group->getId(); ?>" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="inviteEmail<?php echo $group->getId(); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="messageInvitation<?php echo $group->getId(); ?>" class="form-label">Mensaje</label>
                        <input type="text" class="form-control" id="messageInvitation<?php echo $group->getId(); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="roleSelector<?php echo $group->getId(); ?>" class="form-label">Seleccionar Rol</label>
                        <!--<select class="form-select" id="roleInvited" name="roleInvited" required>-->
                        <select class="form-select" id="roleInvited<?php echo $group->getId(); ?>" name="roleInvited" required>
                            <option value="" selected disabled>Seleccione un rol</option>
                            <?php if($roleList):?>
                                <?php foreach($roleList as $role):?>
                                    <?php if($role->getId() !=1):?>
                                        <option value="<?php echo $role->getId(); ?>"><?php echo $role->getName(); ?></option>
                                    <?php endif;?>
                                <?php endforeach;?>
                            <?php endif;?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="sendInvitation(<?php echo $group->getId(); ?>, <?php echo $currentUser; ?>, $('#roleInvited<?php echo $group->getId(); ?>').val())">Enviar Invitación</button>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
<!--Modal Detalle grupo-->
<!-- Modal Invitaciones -->
<div class="modal fade" id="invitationLoader" tabindex="-1" aria-labelledby="invitationLoader" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="invitationLoader">Invitaciones pendientes</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex flex-wrap">
                <div id="invitationLoader"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Invitaciones -->
<!-- Modal Cambio role -->
<div class="modal fade" id="roleModal" tabindex="-1" aria-labelledby="roleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="roleModalLabel">Modificar Rol del Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="roleChangeForm">
                    <div class="mb-3">
                        <label for="userName" class="form-label">Usuario</label>
                        <input type="text" class="form-control" id="userName" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="userRoleName" class="form-label">Rol Actual</label>
                        <input type="text" class="form-control" id="userRoleName" name="userRoleName" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="newRole" class="form-label">Nuevo Rol</label>
                        <select id="newRole" class="form-select">
                        </select>
                    </div>
                    <input type="hidden" id="userId" name="userId">
                    <input type="hidden" name="groupIdRole" id="groupIdRole">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="changeMemberRole($('#userId').val(), $('#newRole').val(), $('#groupIdRole').val())">Guardar cambios</button>
            </div>
        </div>
    </div>
</div>
<!--Modal Cambio role -->

<!--Modal Grupos publicos -->
<div class="modal fade" id="searchGroupModal" tabindex="-1" aria-labelledby="searchGroupModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="searchGroupModalLabel">Grupos Públicos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex flex-wrap align-items-center justify-content-center" id="publicGroupsList">
                <p>Cargando grupos...</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!--Modal Grupos publicos -->
<script>

    let messageInterval;
    let offset = 0;
    let isLoading = false;
    let isUserAtBottom = false;
    let chatUserID;
    let isGroupChatActive = false;
    let messagePollingInterval;
    let currentChatId = null;
    let invitationId;
    let userLog;
    const userColors = {};
    const typeList = <?php echo $typeListJson; ?>;
    const privacyList = <?php echo $privacyListJson; ?>;
    const currentRolesList = <?php echo $roleListJson; ?>;
    const currentUserIdRole= <?php echo $currentUser ?>;

    //Seteo el minimo de la fecha a dia de hoy
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('startDate').setAttribute('min', today);
    document.getElementById('endDate').setAttribute('min', today);

    document.addEventListener('DOMContentLoaded', function () {
        const chatItems = document.querySelectorAll('#privateMessageList .list-group-item');
        const groupItems = document.querySelectorAll('#groupList .list-group-item');
        const userLog = document.getElementById('currentID').value;
        const receiverIdInput = document.getElementById('receiverId');
        const groupIDInput = document.getElementById('GroupID');

        sendMessageButton = document.getElementById('sendMessageButton');
        const messageInput = document.getElementById('messageInput');
        
        if (sendMessageButton) {
            sendMessageButton.addEventListener('click', sendMessage);
        }

        if (messageInput) {
            messageInput.addEventListener('keypress', function(event) {
                if (event.key === 'Enter') {
                    sendMessage(event); // Enviar el mensaje al presionar Enter
                    event.preventDefault(); // Evitar el comportamiento predeterminado del Enter en el input
                }
            });
        }

    document.getElementById('newChatButton').addEventListener('click', function () {
        $.ajax({
            url: '<?php echo FRONT_ROOT ?>Message/getUsersWithoutChat',
            type: 'POST',
            success: function(response) {
                try {
                    var users = JSON.parse(response);
                    var userList = document.getElementById('userList');
                    userList.innerHTML = '';

                    if (users.length === 0) {
                        var errorMessage = document.createElement('h5');
                        errorMessage.className = 'text-danger text-center';
                        errorMessage.textContent = 'No tienes usuarios disponibles para agregar actualmente!';
                        userList.appendChild(errorMessage); 
                    } else {
                        users.forEach(function(user) {
                            let role;
                            if (user.roleID === '1') {
                                role = 'Admin';
                            } else if (user.roleID === '2') {
                                role = 'Owner';
                            } else {
                                role = 'Keeper';
                            }

                            var card = document.createElement('div');
                            card.className = 'card mb-3 shadow-sm mx-auto';
                            card.style.width = '18rem';

                            var cardBody = document.createElement('div');
                            cardBody.className = 'card-body text-center';

                            var cardImage = document.createElement('img');
                            cardImage.src = user.userImage && user.userImage.trim() !== '' 
                            ? '<?php echo FRONT_ROOT ?>' + user.userImage 
                            : '<?php echo FRONT_ROOT . USER_PATH ?>userDefaultImage.jpg';

                            cardImage.alt = user.name;
                            cardImage.className = 'card-img-top rounded-circle mb-3';
                            cardImage.style.width = '100px'; 
                            cardImage.style.height = '100px'; 
                            cardImage.style.objectFit = 'cover'; 

                            var cardTitle = document.createElement('h5');
                            cardTitle.className = 'card-title text-primary fw-bold';
                            cardTitle.textContent = user.name;

                            var cardRole = document.createElement('p');
                            cardRole.className = 'card-text fw-bold';
                            cardRole.innerHTML = `<i class="fas fa-user-tag"></i> ${role}`;

                            var cardEmail = document.createElement('p');
                            cardEmail.className = 'card-text text-muted';
                            cardEmail.innerHTML = `<i class="fas fa-envelope"></i> Email: ${user.email}`;

                            var cardCellphone = document.createElement('p');
                            cardCellphone.className = 'card-text text-muted';
                            cardCellphone.innerHTML = `<i class="fas fa-phone"></i> Teléfono: ${user.cellphone}`;

                            var cardDescription = document.createElement('p');
                            cardDescription.className = 'card-text text-break';
                            cardDescription.innerHTML = `<i class="fas fa-info-circle"></i> Descripción: ${user.userDescription}`;

                            var startChatButton = document.createElement('button');
                            startChatButton.className = 'btn btn-primary mt-2 w-100';
                            startChatButton.innerHTML = '<i class="fas fa-comments"></i> Iniciar Chat';
                            startChatButton.setAttribute('data-newuUserId', user.userID);
                            startChatButton.setAttribute('data-newName', user.name);

                            startChatButton.addEventListener('click', function () {
                                startNewChat(user.userID, user.name);
                                $('#newChatModal').modal('hide');
                            });

                            // Añade la imagen al cuerpo de la tarjeta
                            cardBody.appendChild(cardImage);
                            cardBody.appendChild(cardTitle);
                            cardBody.appendChild(cardRole);
                            cardBody.appendChild(cardEmail);
                            cardBody.appendChild(cardCellphone);
                            cardBody.appendChild(cardDescription);
                            cardBody.appendChild(startChatButton);

                            card.appendChild(cardBody);

                            userList.appendChild(card);
                        });
                    }


                    $('#newChatModal').modal('show');
                } catch (error) {
                    console.error("Error al procesar la respuesta: ", error);
                }
            },
            error: function(error) {
                console.error("Error al obtener usuarios:", error);
                alert("Ocurrió un error al obtener la lista de usuarios.");
            }
        });
    });

    // Función para manejar el cambio de chat y guardar el estado en localStorage
    function changeChatState(receiverId, groupId) {
        localStorage.setItem('activeChat', JSON.stringify({ receiverId, groupId }));
    }
    function setGroupID(groupId) {
        document.getElementById('groupID').value = groupId;
    }
    // Funcion para iniciar el polling de mensajes
    function startMessagePolling(receiverId, groupId) {
        // Limpiar el intervalo anterior si existe
        if (messagePollingInterval) {
            clearInterval(messagePollingInterval);
        }
        // Iniciar el nuevo intervalo
        messagePollingInterval = setInterval(function() {

            if (groupId !== 0) {
                loadGroupMessages(groupId, true);
            } else {
                if (receiverId) {
                    loadPrivateMessages(receiverId, true);
                }
            }
        }, 4000);
    }
    //pre-view imagen
    $('#groupImage').on('change', function(event) {
        var input = event.target;
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                $('#previewImg').attr('src', e.target.result);
                $('#imagePreview').show();
            }

            reader.readAsDataURL(input.files[0]);
        } else {
            $('#imagePreview').hide();
            $('#previewImg').attr('src', '');
        }
    });
    // Evento para cambiar al chat de grupo
    // Al hacer clic en un grupo
    groupItems.forEach(item => {
        item.addEventListener('click', function () {
            const groupId = this.getAttribute('data-group-id');
            // Limpiar estados previos
            resetChatView(); // Limpia todo

            // Marca este grupo como activo
            this.classList.add('active');
            isGroupChatActive = true;
            currentChatId=groupId;
            validateGroupRole(groupId,userLog);
           // messageInput.value = '';
            // Establece el groupID en el formulario
            document.getElementById('groupID').value = groupId; // Asignar el ID del grupo
            getMembersByGroup(groupId);
            loadGroupMessages(groupId);
            changeChatState(0, groupId);
            startMessagePolling(0, groupId);
        });
    });
    // Al hacer clic en un chat privado
    chatItems.forEach(item => {
        item.addEventListener('click', function () {
            const userId = this.getAttribute('data-userid');

            // Limpiar estados previos
            resetChatView(); // Limpia todo
            currentChatId=userId;
            // Marca este chat como activo
            this.classList.add('active');
            document.getElementById('receiverId').value = userId; // Asigna el ID del receptor
            document.getElementById('groupID').value = ''; // Limpia el groupID para chats privados
            //messageInput.value = '';

            isGroupChatActive = false;
            loadPrivateMessages(userId, false);
            changeChatState(userId, 0);
            startMessagePolling(userId, 0);
        });
    });

    //Resetea el localStorage para evitar que tome el ultimo chat/grupo por defecto
    document.getElementById('chatsButton').addEventListener('click', function () {
        resetChatView();
    });
    //Resetea el estado de la vista de chats
    function resetChatView() {
        const chatBoxContainer = document.getElementById('chatBox');
        const messageInputContainer = document.getElementById('blockMessageSend');
        chatBoxContainer.style.display = 'block';
        messageInputContainer.style.display = '';
        //Evitar que se queden mensajes de otro chat hasta que refresca la vista
        var messageContainer = $('#messageContainer');
        messageContainer.html('');


        // Limpiar el intervalo de polling si está activo
        if (messagePollingInterval) {
            clearInterval(messagePollingInterval);
            messagePollingInterval = null; // Reiniciar la variable
        }
        
        // Limpiar las clases active
        chatItems.forEach(li => li.classList.remove('active'));
        groupItems.forEach(li => li.classList.remove('active'));
        // También puedes hacer cualquier otra limpieza necesaria para la vista de chats
        document.getElementById('receiverId').value = ''; // Reiniciar el ID del receptor
    }

    // Escucha los cambios en localStorage
    window.addEventListener('storage', function (event) {
        if (event.key === 'activeChat') {
            const { receiverId, groupId } = JSON.parse(event.newValue);

            // Limpia la clase active en todos los chats
            chatItems.forEach(li => li.classList.remove('active'));
            groupItems.forEach(li => li.classList.remove('active'));

            if (groupId !== 0) {
                // Si hay un chat de grupo activo
                const activeGroupItem = document.querySelector(`[data-group-id="${groupId}"]`);
                if (activeGroupItem) {
                    activeGroupItem.classList.add('active');
                    loadGroupMessages(groupId); // Carga los mensajes del grupo
                    startMessagePolling(0, groupId); // Inicia el polling para grupo
                }
            } else if (receiverId) {
                // Si hay un chat privado activo
                const activeChatItem = document.querySelector(`[data-userid="${receiverId}"]`);
                if (activeChatItem) {
                    activeChatItem.classList.add('active');
                    loadPrivateMessages(receiverId, false); // Carga los mensajes del usuario
                    startMessagePolling(receiverId, 0); // Inicia el polling para privado
                }
            }
        }
    });

    // Inicia el polling con el estado inicial si es necesario
    const initialChatState = JSON.parse(localStorage.getItem('activeChat'));
        if (initialChatState) {
            const { receiverId, groupId } = initialChatState;
            startMessagePolling(receiverId, groupId);
        }
    });

    function getMembersByGroup(groupId) {
        $.ajax({
            url: '<?php echo FRONT_ROOT ?>Member/getAllMembersByGroup',
            type: 'POST',
            data: { groupId: groupId },
            success: function(response) {
                try {
                    let members = JSON.parse(response);
                    let roleCurrentUser = null;

                    members.forEach(function(member) {
                        if (member.user.userID == currentUserIdRole) {
                            roleCurrentUser = parseInt(member.role.id); 
                        }
                    });

                    if (roleCurrentUser === null) {
                        console.error('No se pudo determinar el rol del usuario actual.');
                        return; 
                    }

                    if (!window.groupMembers) {
                        window.groupMembers = {};
                    }
                    // Guardar los miembros por ID de grupo
                    window.groupMembers[groupId] = members; 
                    window.roleCurrentUser = roleCurrentUser;

                    renderMembers(groupId, members, roleCurrentUser);

                } catch (ex) {
                    console.error('Error al parsear el JSON:', ex);
                }
            },
            error: function() {
                alert('Error en la request.');
            }
        });
    }
    // Función para renderizar los miembros
    function renderMembers(groupId, members, roleCurrentUser, filters = { email: '', name: '', role: '' }) {
        let membersHtml = '';

        const filteredMembers = members.filter(member => {
            const emailMatch = member.user.email.toLowerCase().includes(filters.email.toLowerCase());
            const nameMatch = member.user.firstName.toLowerCase().includes(filters.name.toLowerCase()) || 
                            member.user.lastName.toLowerCase().includes(filters.name.toLowerCase());
            const roleMatch = filters.role === '' || member.role.id.toString() === filters.role;
            return emailMatch && nameMatch && roleMatch;
        });

        filteredMembers.forEach(function(member) {
            const isCurrentUser = member.user.userID == currentUserIdRole;
            const imgSrc = member.user.image ? member.user.image : 'Upload/UserImages/userDefaultImage.jpg';

            membersHtml += `
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <img src="<?php echo FRONT_ROOT ?>${imgSrc}" alt="${member.user.firstName} ${member.user.lastName}" 
                            class="rounded-circle me-2" 
                            style="width: 40px; height: 40px; object-fit: cover;">
                        <div>
                            <strong class="${member.status === '0' ? 'text-danger' : ''}">${member.user.firstName} ${member.user.lastName}</strong>
                            <p class="mb-0 text-muted">${member.role.name}</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        ${(roleCurrentUser === 1 && parseInt(member.role.id) !== 1) || 
                        (roleCurrentUser === 2 && parseInt(member.role.id) > 2) || 
                        (roleCurrentUser > 2 && member.user.userID === currentUserID.value) ? 
                        (member.status === '1' ? `
                            <button class="btn btn-sm btn-danger me-3" 
                                    onclick="deleteMember('${member.group_id.id}','${member.user.userID}')">
                                <i class="fas fa-trash-alt"></i> Eliminar usuario
                            </button>
                        ` : `
                            <button class="btn btn-sm btn-success me-3" 
                                    onclick="reactivateMember('${member.group_id.id}','${member.user.userID}')">
                                <i class="fas fa-user-check"></i> Reactivar usuario
                            </button>
                        `) : ''}

                        ${!isCurrentUser && (
                            (roleCurrentUser === 1 && parseInt(member.role.id) !== 1) ||
                            (roleCurrentUser === 2 && parseInt(member.role.id) > 2)
                        ) ? `
                            <button class="btn btn-sm btn-warning me-3" 
                                    onclick="openRoleModal('${member.user.userID}', '${member.user.firstName} ${member.user.lastName}', '${member.role.name}', '${member.role.id}','${member.group_id.id}','${roleCurrentUser}')">
                                <i class="fas fa-user-edit"></i> Cambiar Rol
                            </button>
                        ` : ''}
                        <small class="${member.status === '0' ? 'text-danger' : 'text-muted'}">${member.status === '1' ? 'Active' : 'Inactive'}</small>
                    </div>
                </div>
            `;
        });

        if (filteredMembers.length === 0) {
            membersHtml = `
            <div class="alert alert-danger" role="alert">
                <p class="text-muted">No se encontraron miembros.</p>
            </div>
            `;
        }

        $(`#membersList${groupId}`).html(membersHtml);
    }
    // Filtro de miembros
    function filterMembers(groupId) {
        const emailFilter = document.getElementById(`emailFilter_${groupId}`).value;
        const nameFilter = document.getElementById(`nameFilter_${groupId}`).value;
        const roleFilter = document.getElementById(`roleFilter_${groupId}`).value;

        const filters = {
            email: emailFilter,
            name: nameFilter,
            role: roleFilter 
        };
        const members = window.groupMembers[groupId] || [];
        renderMembers(groupId, members, window.roleCurrentUser, filters);
    }
    //Muestro los modales de detalle
    document.getElementById('chatDetailsButton').addEventListener('click', function () {
        if (isGroupChatActive) {

            validateGroupRole(currentChatId,currentID.value); //Valido el role y muestro el modal
            $('#modalGroup' + currentChatId).modal('show');
        } else {
            $('#modalUser' + currentChatId).modal('show');
        }
    });
    //Muestro los modales de detalle

    function formatDate(dateString) {
        var date = new Date(dateString.replace(" ", "T"));

        if (isNaN(date.getTime())) {
            console.error('Fecha no válida:', dateString);
            return 'Fecha no válida';
        }

        var hours = date.getHours();
        var minutes = date.getMinutes();

        var ampm = hours >= 12 ? 'PM' : 'AM';

        hours = hours % 12; 
        hours = hours ? hours : 12; 

        var formattedTime = (hours < 10 ? '0' : '') + hours + ':' + (minutes < 10 ? '0' : '') + minutes + ' ' + ampm;
        return formattedTime;
    }

    // Cargar mensajes privados
    function loadPrivateMessages(chatUserID, initialLoad = false) {
        if (isLoading || !chatUserID) return;
        isLoading = true;
        const name = document.getElementById(`userNameSend${chatUserID}`).value;
        $.ajax({
            url: '<?php echo FRONT_ROOT ?>Message/getChatMessages',
            type: 'POST',
            data: { chatUserID: chatUserID },
            success: function(response) {
                var messages;
                
                try {
                    messages = JSON.parse(response);
                    var messageContainer = $('#messageContainer');

                    if (initialLoad) {
                        messageContainer.html(''); // Limpia el contenedor si es la carga inicial
                    }

                    messages.forEach(function(message) {

                        var messageClass = (message.sender_id == '<?php echo $currentUser ?>' ? 'sent' : 'received');
                        var messageHTML = '<div class="message ' + messageClass + '">' +
                            '<strong class="text-dark">' + (message.sender_id == '<?php echo $currentUser ?>' ? 'Tú' : name) + ':</strong>' +
                            '<p class="mt-1 mb-0 font-monospace">' + message.message + '</p>' +
                            '<div class="d-flex justify-content-end date">' + formatDate(message.sent_at) + '</div>';

                        
                        if (message.is_read == 1) {
                            messageHTML += '<span class="checkmark float-end">✔️✔️</span>'; 
                        } else {
                            messageHTML += '<span class="checkmark float-end">✔️</span>';
                        }

                        messageHTML += '</div>';
                        messageContainer.prepend(messageHTML);
                    });
                    document.getElementById(`badge-${chatUserID}`).style.display = 'none';//Escondo los mensajes pendientes del chat actual
                    // Llama a getUnreadMessages para actualizar los valores de la session de unreadMessages
                    updateTotalUnreadMessages();
                    isLoading = false; // Actualiza el estado de carga
                } catch (error) {
                    //console.error("Error al procesar la respuesta:", error);
                    isLoading = false;
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                isLoading = false;
            if (textStatus === 'error' && errorThrown === 'ERR_INTERNET_DISCONNECTED') {
                console.error("No hay conexión a internet. Verifica tu conexión.");
                console.error("Detalles de error: ", jqXHR.responseText);
            } else {
                console.error("Error en la solicitud AJAX: ", textStatus, errorThrown);
                console.error("Detalles de error: ", jqXHR.responseText);
            }
        }
        });
    }

    //Carga mensajes grupales
    function loadGroupMessages(groupId, initialLoad = true) {
        if (isLoading || !groupId) return;
        isLoading = true;
        
        $.ajax({
            url: '<?php echo FRONT_ROOT ?>Message/getGroupMessages',
            type: 'POST',
            data: { groupId: groupId },
            success: function(response) {
                var messages;
                try {
                    messages = JSON.parse(response);
                    var messageContainer = $('#messageContainer');

                    if (initialLoad) {
                        messageContainer.html(''); 
                        messageContainer.scrollTop(messageContainer.scrollHeight);
                    }

                    messages.forEach(function(message) {
                        var messageClass = (message.sender_id == '<?php echo $currentUser ?>' ? 'sent' : 'received');

                        var userColor = getUserColor(message.sender_id);
                        var userName = (message.sender_id == '<?php echo $currentUser ?>' ? 'Tú' : message.NombreUsuario);
                        
                        var messageHTML = '<div class="message ' + messageClass + '">' +
                            '<strong class="" style="color:' + userColor + ';">' + userName + ':</strong>' +
                            '<p class="mt-1 mb-0 font-monospace">' + message.message + '</p>' +
                            '<div class="d-flex justify-content-end date">' + formatDate(message.sent_at) + '</div>';

                        if (message.all_read == 1) {
                            messageHTML += '<span class="checkmark float-end">✔️✔️</span>'; 
                        } else {
                            messageHTML += '<span class="checkmark float-end">✔️</span>';
                        }
                        
                        messageHTML += '</div>';
                        
                        messageContainer.prepend(messageHTML);
                    });
                    document.getElementById(`group-badge-${groupId}`).style.display = 'none';
                    updateTotalUnreadMessages();
                    isLoading = false; 
                } catch (error) {
                    console.error("Error al procesar la respuesta:", error);
                    isLoading = false;
                }
            },
            error: function(xhr, status, error) {
                console.error("Error en la solicitud AJAX:", error);
                isLoading = false;
            }
        });
    }


    //Evento para envio de mensajes
    function sendMessage(event) {
        event.preventDefault();

        const messageInput = document.getElementById('message');
        const receiverId = document.getElementById('receiverId').value;
        const currentID = parseInt(document.getElementById('currentID').value, 10);
        const groupId = document.getElementById('groupID').value;
        const message = messageInput.value.trim(); // Trim para evitar mensajes vacios
        if (!message) {
            alert("No puedes enviar un mensaje vacío."); // Alerta si el mensaje está vacío
            return;
        }
        if(!receiverId && !groupId){
            alert("Necesitas enviar a un usuario");
            return;
        }

    //Envia mensaje directo
    $.ajax({
            url: "<?php echo FRONT_ROOT ?>Message/sendDirectMessage",
            method: "POST",
            data: {
                receiverId: receiverId,
                message: message,
                currentID: currentID,
                groupId: groupId,
            },
            success: function(response) {
                messageInput.value = '';

                // Agregar el nuevo mensaje enviado al chat sin recargar todo
                var messageContainer = $('#messageContainer');
                var messageHTML = '<div class="message sent">' + 
                    '<strong>Tú:</strong>' + 
                    '<p class="mt-1 mb-0">' + message + '</p>' + 
                    '<small class="text-muted float-end">Ahora</small>' + 
                    '</div>';
                if(groupId){
                    messageContainer.append(messageHTML);
                    messageContainer.scrollTop(messageContainer.scrollHeight); 
                    loadGroupMessages(groupId, true); 
                }else{
                    messageContainer.append(messageHTML);
                    messageContainer.scrollTop(messageContainer.scrollHeight); 
                    loadPrivateMessages(receiverId, true); 
                    
                }
            },
            error: function(xhr, status, error) {
                console.error("Error al enviar el mensaje:", error);
                alert("Ocurrió un error al enviar el mensaje: " + error);
            }
        });
    }
    //Llamado para crear un nuevo chat
    function startNewChat(userId, userName) {
        const messageInput = document.getElementById('message');
        const message = messageInput.value || `Hola ${userName}, como andas?`; // Mensaje predeterminado si el campo está vacío
        const currentID = parseInt(document.getElementById('currentID').value, 10);
        $.ajax({
            url: "<?php echo FRONT_ROOT ?>Message/sendDirectMessage",
            method: "POST",
            data: {
                receiverId: userId,
                message: message,
                currentID: currentID,
            },
            success: function(response) {
                messageInput.value = '';
                location.reload();
            },
            error: function(xhr, status, error) {
                console.error("Error al enviar el mensaje:", error);
                alert("Ocurrió un error al enviar el mensaje: " + error);
            }
        });
    }
    //Estado de mensajes no leidos
    function updateTotalUnreadMessages() {
        let totalUnread = 0;

        // chat privados
        $.ajax({
            url: '<?php echo FRONT_ROOT ?>Message/getUnreadMessages',
            type: 'POST',
            success: function(unreadResponse) {
                let unreadMessages;
                try {
                    unreadMessages = JSON.parse(unreadResponse);
                } catch (e) {
                    console.error("Error al analizar la respuesta:", e);
                    return;
                }

                if (Array.isArray(unreadMessages) && unreadMessages.length > 0) {
                    unreadMessages.forEach(message => {
                        totalUnread += parseInt(message.cantidad, 10); // Sumo los mensajes privados
                    });
                }

                // grupos
                $.ajax({
                    url: '<?php echo FRONT_ROOT ?>Message/getUnreadMessagesGroup',
                    type: 'POST',
                    success: function(unreadGroupResponse) {
                        let unreadGroupMessages;
                        try {
                            unreadGroupMessages = JSON.parse(unreadGroupResponse);
                        } catch (e) {
                            console.error("Error al analizar la respuesta de grupos:", e);
                            return;
                        }

                        if (Array.isArray(unreadGroupMessages) && unreadGroupMessages.length > 0) {
                            unreadGroupMessages.forEach(message => {
                                totalUnread += parseInt(message.cantidad, 10);
                            });
                        }

                        // Actualizo el contador
                        const totalCountElement = document.getElementById('currentCountID');
                        if (totalUnread > 0) {
                            totalCountElement.innerText = totalUnread;
                            totalCountElement.style.display = 'inline'; 
                        } else {
                            totalCountElement.style.display = 'none'; 
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error en la solicitud de mensajes no leídos del grupo:", error);
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error("Error en la solicitud de mensajes no leídos:", error);
            }
        });
    }

    //Evento en relacion a grupos de tipo Evento (Se agregaron un rango de fechas de disponiblidad)
    document.getElementById('groupType').addEventListener('change', function() {
        const selectedValue = this.value;
        const dateFields = document.getElementById('dateFields');
        const dateFieldsEnd = document.getElementById('dateFieldsEnd');
        const startDateInput = document.getElementById('startDate');
        const endDateInput = document.getElementById('endDate');

        if (selectedValue === '6') { 
            dateFields.style.display = 'block';
            dateFieldsEnd.style.display = 'block';
            startDateInput.setAttribute('required', 'required');
            endDateInput.setAttribute('required', 'required');
        } else {
            dateFields.style.display = 'none';
            dateFieldsEnd.style.display = 'none';
            startDateInput.removeAttribute('required');
            endDateInput.removeAttribute('required'); 
        }
    });

    //Nuevo grupo
    $(document).ready(function() {
        $('#submitGroupButton').click(function(event) {
            event.preventDefault();
            var formData = new FormData($('#createGroupForm')[0]); 

            var currentUserID = $('#currentUserID').val();
            var groupName = $('#groupName').val();
            var groupType = $('#groupType').val();
            var groupPrivacy = $('#groupPrivacy').val();
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            var description = $('#description').val();
            var rules = $('#rules').val();
            if (!currentUserID || !groupName || !groupType || !groupPrivacy || !description || !rules) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Por favor, completa todos los campos obligatorios.',
                    background: '#ffffff',
                    color: '#000000',
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'Entendido',
                    customClass: {
                        title: 'swal-title', 
                        content: 'swal-content', 
                        confirmButton: 'swal-button', 
                    },
                    iconColor: '#dc3545', 
                });
                return;
            } else if (groupType === 6) { 
                if (!startDate && !endDate) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Para grupos de tipo evento es requerido el rango de fecha.',
                        background: '#ffffff',
                        color: '#000000',
                        confirmButtonColor: '#dc3545',
                        confirmButtonText: 'Entendido',
                        customClass: {
                            title: 'swal-title', 
                            content: 'swal-content', 
                            confirmButton: 'swal-button', 
                        },
                        iconColor: '#dc3545', 
                    });
                    return;
                }
            }

            formData.append('currentUserID', currentUserID);
            formData.append('groupName', groupName);
            formData.append('groupType', groupType);
            formData.append('groupPrivacy', groupPrivacy);
            formData.append('description', description);
            formData.append('rules', rules);
            formData.append('start_date', startDate);
            formData.append('end_date', endDate);

            $.ajax({
                url: "<?php echo FRONT_ROOT ?>Group/newGroup",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: "El grupo ha sido creado correctamente",
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        $('#createGroupModal').modal('hide');
                        window.location.reload(); 
                    });
                },
                error: function() {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Algo ocurrió, por favor intenta nuevamente más tarde!"
                    });
                }
            });
        }); 
    }); 

    //Eliminar grupo
    function deleteGroup(groupId, currentUser) {
            $.ajax({
                url: '<?php echo FRONT_ROOT ?>Group/deleteGroup',
                type: 'POST',
                data: {
                    groupId: groupId,
                    currentUser: currentUser
                },
                success: function(response) {
                    Swal.fire({
                        title: '¡Eliminado!',
                        text: 'El grupo ha sido eliminado.',
                        icon: 'success',
                        color: "#716add",
                        confirmButtonColor: '#3085d6'
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function() {
                    Swal.fire({
                        title: 'Error',
                        text: 'Hubo un problema al eliminar el grupo. Intenta de nuevo.',
                        icon: 'error',
                        confirmButtonColor: '#3085d6'
                });
            }
        });
    }
    //Enviar invitacion
    function sendInvitation(groupId, currentUser,roleInvited) {
        const email = document.getElementById('inviteEmail' + groupId).value;
        const message = document.getElementById('messageInvitation' + groupId).value;

        if (email === '') {
            Swal.fire({
                icon: 'warning',
                title: 'Campo de correo vacío',
                text: 'Por favor, ingrese un correo electrónico.',
                background: '#ffffff', 
                color: '#000000'
            });
            return;
        }

        Swal.fire({
            title: '¿Enviar invitación?',
            text: `Enviarás la invitación a ${email}.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Enviar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true,
            background: '#ffffff', 
            color: '#000000'
        }).then((result) => {
            alert(groupId);
            alert(currentUser);
            alert(email);
            alert(message);
            alert(roleInvited);
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?php echo FRONT_ROOT ?>GroupInvitation/sendInvitation',
                    type: 'POST',
                    data: {
                        groupId: groupId,
                        invitedBy: currentUser,
                        invitedUser: email,
                        message: message,
                        roleInvited:roleInvited,
                    },
                    success: function(response) {
                        const jsonResponse = JSON.parse(response); 

                        if (jsonResponse.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Invitación Enviada',
                                text: jsonResponse.message, 
                                background: '#ffffff', 
                                color: '#000000', 
                                iconColor: '#28a745'
                            });

                            document.getElementById('inviteEmail' + groupId).value = '';
                            document.getElementById('messageInvitation' + groupId).value = '';

                            $('#inviteGroupModal' + groupId).modal('hide');
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: jsonResponse.message,
                                background: '#ffffff',
                                color: '#000000',
                                iconColor: '#dc3545'
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Hubo un problema al enviar la invitación.',
                            background: '#ffffff',
                            color: '#000000', 
                            iconColor: '#dc3545' 
                        });
                    }
                });
            }
        });
    }
    //Valido el rol del usuario actual
    function validateGroupRole(groupId, currentUser) {
        $.ajax({
            url: '<?php echo FRONT_ROOT ?>Member/getUserRoleByGroup', 
            type: 'POST',
            data: {
                groupId: groupId,
                userId: currentUser
            },
            success: function(response) {
                const data = JSON.parse(response);
                const userRole = data.role;
                const adminBlock = document.getElementById('adminBlock' + groupId);
                const adminBlocks = document.querySelectorAll(`[id^="adminBlock${groupId}"]`);
                const messageInput = document.getElementById('messageInputID');
                const detailInvited = document.getElementById('InvitedUserDetails');
                if (parseInt(userRole) === 1 || parseInt(userRole) === 2) {
                    adminBlocks.forEach(block => {
                        block.style.display = 'block';
                    });
                } else if(parseInt(userRole) === 4){
                    membersListID.style.display ='none';
                    //adminBlock.style.display = 'none';
                    messageInput.style.display = 'none';
                    detailInvited.style.display='block'
                    adminBlocks.forEach(block => {
                        block.style.display = 'none';
                    }); 
                }else {
                    adminBlocks.forEach(block => {
                        block.style.display = 'none';
                    }); 
                   // adminBlock.style.display = 'none';
                }
                
                //$('#modalGroup' + groupId).modal('show');
            },
            error: function() {
                console.error('Error al obtener el rol del usuario.');
            }
        });
    }
    //Muestra las invitaciones
    function getInvitations(currentUser) {
        $.ajax({
            url: '<?php echo FRONT_ROOT ?>GroupInvitation/getUserInvitations', 
            type: 'POST',
            data: {
                userId: currentUser
            },
            success: function(response) {
                const data = JSON.parse(response);
                console.table(data);
                $('#invitationLoader .modal-body').empty(); 
                
                if (data.length > 0) {
                    data.forEach(invitation => {
                        const card = `
                            <div class="card my-3 text-center shadow-sm mx-2" style="width: 25%; border: 1px solid #007bff;">
                                <div class="card-body">
                                    <h5 class="card-title text-primary">
                                        <i class="fas fa-users"></i> Grupo: ${invitation.groupId.name}
                                    </h5>
                                    <h6 class="card-subtitle mb-2 text-muted">
                                        <i class="fas fa-user-plus"></i> Invitado por:<br>
                                        <strong>${invitation.invited_by.firstName} ${invitation.invited_by.lastName}</strong>
                                    </h6>
                                    <ul class="list-unstyled">
                                        <li class="mb-1">
                                            <strong>Estado de la Invitación:</strong>
                                            <span class="badge bg-info text-white">${invitation.status.name}</span>
                                        </li>
                                    </ul>
                                    <button type="button" class="btn btn-outline-info open-details-modal"
                                            data-id="${invitation.id}"
                                            data-user="${invitation.invited_user.userID}"
                                            data-group-id="${invitation.groupId.id}"
                                            data-group-name="${invitation.groupId.name}"
                                            data-group-description="${invitation.groupId.groupInfo.description}"
                                            data-group-rules="${invitation.groupId.groupInfo.rules}"
                                            data-group-privacy="${invitation.groupId.groupPrivacy.name}"
                                            data-group-privacy-description="${invitation.groupId.groupPrivacy.description}"
                                            data-group-type="${invitation.groupId.groupType.name}"
                                            data-group-status="${invitation.groupId.statusId.name}"
                                            data-invited-by="${invitation.invited_by.firstName} ${invitation.invited_by.lastName}"
                                            data-invited-user="${invitation.invited_user.firstName} ${invitation.invited_user.lastName}"
                                            data-status="${invitation.status.name}"
                                            data-message="${invitation.message}"
                                            data-send="${invitation.send_at}"
                                            data-roleInvited-Id="${invitation.roleInvited.id}"
                                            data-roleInvited-Name="${invitation.roleInvited.name}"
                                            data-roleInvited-Description="${invitation.roleInvited.description}"

                                            data-responded="${invitation.responded_at ? invitation.responded_at : 'Pendiente'}">
                                        <i class="fas fa-eye"></i> Ver Detalles
                                    </button>
                                </div>
                            </div>
                        `;
                        $('#invitationLoader .modal-body').append(card);
                    });
                } else {
                    $('#invitationLoader .modal-body').append('<div class="alert alert-danger w-100" role="alert">No tienes invitaciones pendientes.</div>');
                }

                $('#invitationLoader').modal('show');
            },
            error: function() {
                console.error('Error al obtener las invitaciones');
            }
        });
    }

    // Detalle de invitacion
    $(document).on('click', '.open-details-modal', function() {
        const invitationId = $(this).data('id');
        const user = $(this).data('user');
        const groupId = $(this).data('group-id');
        const groupName = $(this).data('group-name');
        const groupDescription = $(this).data('group-description');
        const groupRules = $(this).data('group-rules');
        const groupPrivacy = $(this).data('group-privacy');
        const groupPrivacyDescription = $(this).data('group-privacy-description');
        const groupType = $(this).data('group-type');
        const groupStatus = $(this).data('group-status');
        const invitedBy = $(this).data('invited-by');
        const invitedUser = $(this).data('invited-user');
        const status = $(this).data('status');
        const message = $(this).data('message');
        const sendAt = $(this).data('send');
        const respondedAt = $(this).data('responded');
        const roleInvitedId= $(this).data('roleinvited-id');
        const roleInvitedName= $(this).data('roleinvited-name');
        const roleInvitedDescription= $(this).data('roleinvited-description');
        const detailsContent = `
                <div class="container my-5">
                <div class="card border-light shadow-lg">
                    <div class="card-header bg-info text-white text-center rounded-top">
                        <h5 class="mb-0"><i class="fas fa-users"></i> Detalles de la Invitación</h5>
                    </div>
                    <div class="card-body p-4 text-center">
                        <div class="mb-3">
                            <strong><i class="bi bi-house-door-fill"></i> Grupo:</strong><br> 
                            <span class="text-muted">${groupName}</span>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <strong><i class="bi bi-file-earmark-text"></i> Descripción:</strong><br> 
                            <span class="text-muted">${groupDescription}</span>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <strong><i class="bi bi-tags-fill"></i> Tipo:</strong><br> 
                            <span class="text-muted">${groupType}</span>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <strong><i class="bi bi-check-circle-fill"></i> Estado:</strong><br> 
                            <span class="text-muted">${groupStatus}</span>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <strong><i class="bi bi-file-earmark-lock-fill"></i> Reglas:</strong><br> 
                            <span class="text-muted">${groupRules}</span>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <strong><i class="bi bi-shield-lock-fill"></i> Privacidad:</strong><br> 
                            <span class="text-muted">${groupPrivacy} - ${groupPrivacyDescription}</span>
                        </div>
                    </div>
                </div>
                <div class="card border-light shadow-lg my-4">
                    <div class="card-header bg-success text-white text-center rounded-top">
                        <h5 class="mb-0"><i class="fas fa-user-check"></i> Información del Invitador</h5>
                    </div>
                    <div class="card-body p-4 text-center">
                        <div class="mb-3">
                            <strong><i class="bi bi-person-fill"></i> Invitado por:</strong><br> 
                            <span class="text-muted">${invitedBy}</span>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <strong><i class="bi bi-chat-left-text-fill"></i> Mensaje:</strong><br> 
                            <span class="text-muted">${message}</span>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <strong><i class="bi bi-calendar-date"></i> Enviado el:</strong><br> 
                            <span class="text-muted">${sendAt}</span>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <strong><i class="bi bi-calendar-check"></i> Respondido el:</strong><br> 
                            <span class="text-muted">${respondedAt}</span>
                        </div>
                    </div>
                </div>
                <div class="card border-light shadow-lg my-4">
                    <div class="card-header bg-warning text-white text-center rounded-top">
                        <h5 class="mb-0"><i class="fas fa-user"></i> Información del Usuario Invitado</h5>
                    </div>
                    <div class="card-body p-4 text-center">
                        <div class="mb-3">
                            <strong><i class="bi bi-person-fill"></i> Invitado a:</strong><br> 
                            <span class="text-muted">${invitedUser}</span>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <strong><i class="bi bi-exclamation-circle-fill"></i> Estado de la Invitación:</strong><br> 
                            <span class="text-muted">${status}</span>
                        </div>
                    </div>
                </div>
                <div class="card border-light shadow-lg my-4">
                    <div class="card-header bg-secondary text-white text-center rounded-top">
                        <h5 class="mb-0"><i class="fas fa-user"></i> Role a ocupar</h5>
                    </div>
                    <div class="card-body p-4 text-center">
                        <div class="mb-3">
                            <strong><i class="bi bi-shield-fill"></i> Role:</strong><br> 
                            <span class="text-muted">${roleInvitedName}</span>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <strong><i class="bi bi-file-earmark-text"></i> Descripción:</strong><br> 
                            <span class="text-muted">${roleInvitedDescription}</span>
                        </div>
                    </div>
                </div>
                <div class="text-center my-4">
                    <h4>¡Tú decides!</h4>
                    <p class="text-muted">Elige una de las siguientes opciones para continuar con tu invitación:</p>
                </div>
                <div class="d-flex flex-wrap justify-content-center align-items-center w-100 my-3">
                    <button type="button" class="btn btn-success mx-2 btn-lg shadow" onclick="acceptInvitation(${invitationId}, '${groupId}', '${user}', '${roleInvitedId}')">
                        <i class="fas fa-check-circle"></i> Aceptar Invitación
                    </button>
                    <button type="button" class="btn btn-danger mx-2 btn-lg shadow" onclick="rejectInvitation(${invitationId})">
                        <i class="fas fa-times-circle"></i> Rechazar Invitación
                    </button>
                </div>
            </div>
            `;
        $('#invitationLoader .modal-body').html(detailsContent);
    });

    //Aceptar invitacion
    function acceptInvitation(invitationId,groupId,user,roleInvitedId) {
        Swal.fire({
            title: '¿Seguro que deseas aceptar la invitación?',
            text: `Aceptarás la invitación.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Enviar',
            color: "#716add",
            cancelButtonText: 'Cancelar',
            reverseButtons: true,
            background: '#ffffff', 
            color: '#000000'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?php echo FRONT_ROOT ?>GroupInvitation/acceptInvitation', 
                    type: 'POST',
                    data: {
                        id: invitationId,
                        groupId: groupId,
                        user:user,
                        roleInvitedId:roleInvitedId
                    },
                    success: function(response) {
                        $('#invitationLoader').modal('hide');
                        Swal.fire({
                            position: "top-end",
                            icon: "success",
                            color: "#716add",
                            title: "Invitación aceptada",
                            showConfirmButton: false,
                            timer: 1500
                        });
                        getInvitations(currentUser);
                        $('#invitationLoader').modal('hide');
                        window.location.reload();
                    },
                    error: function() {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Error al aceptar la invitación. Inténtalo de nuevo.",
                        });
                        $('#invitationLoader').modal('hide');
                    }
                });
            } else {
                Swal.fire({
                    icon: "info",
                    color: "#716add",
                    title: "Cancelado",
                    text: "La invitación no ha sido aceptada."
                });
                $('#invitationLoader').modal('hide');
            }
        });
    }
    //Rechazar invitacion
    function rejectInvitation(invitationId) {
        Swal.fire({
            title: '¿Seguro que deseas rechazar la invitación?',
            text: `Rechazarás la invitación.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, rechazar',
            cancelButtonText: 'Cancelar',
            color: "#716add",
            reverseButtons: true,
            background: '#ffffff', 
            color: '#000000'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?php echo FRONT_ROOT ?>GroupInvitation/rejectInvitation', 
                    type: 'POST',
                    data: {
                        id: invitationId 
                    },
                    success: function(response) {
                        $('#invitationLoader').modal('hide');
                        Swal.fire({
                            position: "top-end",
                            icon: "success",
                            color: "#716add",
                            title: "Invitación rechazada",
                            showConfirmButton: false,
                            timer: 1500
                        });
                        
                        getInvitations(currentUser);
                    },
                    error: function() {
                        $('#invitationLoader').modal('hide');
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            color: "#716add",
                            text: "Error al rechazar la invitación. Inténtalo de nuevo.",
                        });
                    }
                });
            } else {
                $('#invitationLoader').modal('hide');
                Swal.fire({
                    icon: "info",
                    title: "Cancelado",
                    color: "#716add",
                    text: "La invitación no ha sido rechazada."
                });
            }
        });
    }
    //Cambiar role
    function openRoleModal(userId, userName, currentRole,idRole,groupId,roleUserActive) {

        $('#userId').val(userId);
        $('#userName').val(userName); 
        $('#userRoleName').val(currentRole);
        $('#newRole').val(idRole);
        $('#groupIdRole').val(groupId);
        $('#newRole').empty();

        currentRolesList.forEach(role => {
            if (role.id >= roleUserActive) { 
                $('#newRole').append(new Option(role.name, role.id));
            }
        });


        $('#roleModal').modal('show');
    }
    //Actualiza el role
    function changeMemberRole(userId, newRole,groupID) {
        $.ajax({
            url: '<?php echo FRONT_ROOT ?>Member/modifyMemberRole',
            type: 'POST',
            data: {
                memberId: userId,
                role: newRole,
                groupID: groupID
            },
            success: function(response) {
                $('#roleModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Rol actualizado con éxito',
                    showConfirmButton: false,
                    timer: 1500
                });
                getMembersByGroup(groupID);
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'No se pudo actualizar el rol del usuario.',
                });
            }
        });
    }
    //Cambiar role

    //Listar los grupos publicos
    function getPublicGroups(currentUserId) {
        $.ajax({
            url: '<?php echo FRONT_ROOT ?>Group/getPublicGroups',
            type: 'POST',
            data: {
                userId: currentUserId 
            },
            success: function(response) {
                const data = JSON.parse(response);
                $('#searchGroupModal .modal-body').empty(); 

                if (data.length > 0) {
                    data.forEach(group => {
                        const card = `
                            <div class="card my-3 shadow-sm rounded-lg mx-2" style="width: 18rem; overflow: hidden;">
                                <img src="<?php echo FRONT_ROOT ?>${group.groupInfo.image || '<?php FRONT_ROOT ?>Upload/ImageGroups/Default/DefaultGroupImage.jpg'}" 
                                    class="card-img-top" 
                                    alt="${group.groupInfo.name || group.name}" 
                                    style="object-fit: cover; height: 180px;">
                                
                                <div class="card-body">
                                    <h5 class="card-title text-primary text-center">
                                        <i class="fas fa-users"></i> ${group.groupInfo.name || group.name}
                                    </h5>

                                    <p class="card-text text-truncate" data-bs-toggle="tooltip" title="${group.groupInfo.description || 'Sin descripción disponible.'}">
                                        <strong><i class="fas fa-info-circle"></i> Descripción:</strong> 
                                        ${group.groupInfo.description || 'Sin descripción disponible.'}
                                    </p>

                                    <p class="card-text text-truncate" data-bs-toggle="tooltip" title="${group.groupInfo.rules || 'Sin reglas especificadas.'}">
                                        <strong><i class="fas fa-gavel"></i> Reglas:</strong> 
                                        ${group.groupInfo.rules || 'Sin reglas especificadas.'}
                                    </p>

                                    <p class="card-text">
                                        <strong><i class="fas fa-tags"></i> Tipo de Grupo:</strong> 
                                        <span class="badge bg-secondary">${group.groupType.name}</span>
                                    </p>

                                    <p class="card-text">
                                        <strong><i class="fas fa-lock"></i> Privacidad:</strong> 
                                        <span class="badge bg-info text-dark">${group.groupPrivacy.name}</span>
                                    </p>

                                    <p class="card-text">
                                        <strong><i class="fas fa-user"></i> Creador:</strong> 
                                        ${group.created_by.firstName} ${group.created_by.lastName}
                                    </p>

                                    <p class="card-text">
                                        <strong><i class="fas fa-check-circle"></i> Estado:</strong> 
                                        <span class="badge bg-${group.statusId.name === 'Activo' ? 'success' : 'danger'}">
                                            ${group.statusId.name}
                                        </span>
                                    </p>
                                </div>

                                <div class="card-footer bg-white text-center">
                                    <button class="btn btn-success w-100" onclick="joinPublicGroup('${group.id}','<?php echo $currentUser?>')">
                                        <i class="fas fa-sign-in-alt"></i> Unirme
                                    </button>
                                </div>
                            </div>
                        `;
                        //Cargo el tooltip para mostrar el detalle o las reglas completo
                        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (element) {
                            new bootstrap.Tooltip(element);
                        });
                        $('#searchGroupModal .modal-body').append(card);
                    });
                } else {
                    $('#searchGroupModal .modal-body').append('<div class="alert alert-danger w-100" role="alert">No hay grupos públicos disponibles.</div>');
                }

                $('#searchGroupModal').modal('show');
            },
            error: function() {
                console.error('Error al obtener los grupos públicos');
            }
        });
    }
    //Unirse a un grupo publico
    function joinPublicGroup(groupId,userID){
        $.ajax({
            url: '<?php echo FRONT_ROOT ?>Member/joinPublicGroup',
            type: 'POST',
            data: {
                groupId: groupId,
                userID: userID
            },
            success: function(response) {
                response = JSON.parse(response);
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        color: "#716add",
                        title: '¡Te has unido al grupo!',
                        text: 'Ahora eres miembro de este grupo.',
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        color: "#716add",
                        text: response.message || 'Hubo un problema al intentar unirte al grupo.',
                        confirmButtonText: 'Aceptar'
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    color: "#716add",
                    text: 'Ocurrió un error inesperado.',
                    confirmButtonText: 'Aceptar'
                });
                console.error('Error en la solicitud:', status, error);
            }
        });
    }

    function deleteMember(groupId,userID){
        $.ajax({
            url: '<?php echo FRONT_ROOT ?>Member/deleteMember',
            type: 'POST',
            data: {
                groupId: groupId,
                userID: userID
            },
            success: function(response) {
                response = JSON.parse(response);

                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡El usuario fue eliminado!',
                        color: "#716add",
                        text: 'El usuario fue eliminado correctamente.',
                        confirmButtonText: 'Aceptar',
                    }).then(() => {
                        if(parseInt(userID) === <?php echo $currentUser?>){ //Si sale del grupo el usuario, se refresca la vista
                            window.location.reload();
                        }else{//Sino actualizo el listado
                            getMembersByGroup(groupId);
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Hubo un problema al intentar eliminar el usuario del grupo.',
                        confirmButtonText: 'Aceptar'
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error inesperado.',
                    confirmButtonText: 'Aceptar'
                });
                console.error('Error en la solicitud:', status, error);
            }
        });
    }
    function reactivateMember(groupId,userID){
        $.ajax({
            url: '<?php echo FRONT_ROOT ?>Member/reactivateMember',
            type: 'POST',
            data: {
                groupId: groupId,
                userID: userID
            },
            success: function(response) {
                response = JSON.parse(response);
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        color: "#716add",
                        title: '¡El usuario fue reactivado!',
                        text: 'El usuario fue reactivado correctamente.',
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        getMembersByGroup(groupId);
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Hubo un problema al intentar reactivar el usuario del grupo.',
                        confirmButtonText: 'Aceptar'
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error inesperado.',
                    confirmButtonText: 'Aceptar'
                });
                console.error('Error en la solicitud:', status, error);
            }
        });
    }

    function editField(groupId, field, currentValue) {
        let modalTitle = "";
        let inputField = "";
        let previewImage = "";

        switch (field) {
            case 'image':
                modalTitle = "Modificar Imagen";
                inputField = `
                <form id="newImageForm" enctype="multipart/form-data">
                    <input type="file" id="newImage" name="newImage[]" accept="image/*" class="form-control" onchange="previewImageFile(this)">
                </form>
                    `;
                previewImage = `<div class="mt-3 d-flex flex-wrap align-items-center justify-content-center">
                                    <h6 class="w-100 text-center">Pre-view</h6>
                                    <img id="imagePreviewEdit" src="#" alt="Vista previa de la imagen" style="max-width: 100%; max-height: 300px; display: none;" class="img-thumbnail"/>
                                </div>`;
                break;
            case 'name':
            case 'description':
            case 'rules':
                modalTitle = `Modificar ${field.charAt(0).toUpperCase() + field.slice(1)}`;
                inputField = `
                <div class="container">
                    <p>Valor anterior:</p> 
                    <input type="text" value="${currentValue}" class="form-control" readonly>
                </div>
                <div class="container">
                    <p>Valor nuevo:</p>
                    <input type="text" id="newValue${field}" value="" class="form-control">
                </div>`;
                break;
            case 'privacy':
            case 'type':
                modalTitle = `Modificar ${field.charAt(0).toUpperCase() + field.slice(1)}`;
                let options = getOptions(field);
                inputField = `<select id="newValue${field}" class="form-control">${options}</select>`;
                break;
            case 'date':
                modalTitle = `Modificar ${field.charAt(0).toUpperCase() + field.slice(1)}`;
                const { startDate, endDate } = JSON.parse(currentValue);
                inputField = `
                    <div class="mb-3">
                        <label for="startDate" class="form-label">Fecha de Inicio</label>
                        <input type="date" id="startDate${field}${groupId}" value="${startDate}" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="endDate" class="form-label">Fecha de Finalización</label>
                        <input type="date" id="endDate${field}${groupId}" value="${endDate}" class="form-control">
                    </div>
                `;
                break;
            default:
                console.error("Campo no manejado");
                return;
        }

        const modalHtml = `
            <div class="modal fade" id="editFieldModal${field}" tabindex="-1" aria-labelledby="editFieldModalLabel${field}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editFieldModalLabel${field}">${modalTitle}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            ${inputField}
                            ${previewImage}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-primary" onclick="saveEdit(${groupId}, '${field}')">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>`;

        document.body.insertAdjacentHTML('beforeend', modalHtml);
        const editModal = new bootstrap.Modal(document.getElementById(`editFieldModal${field}`));
        editModal.show();
    }

    function previewImageFile(input) {
        const preview = document.getElementById('imagePreviewEdit');
        const file = input.files[0];
        const reader = new FileReader();

        reader.onloadend = function() {
            preview.src = reader.result;
            preview.style.display = 'block';
        }
        if (file) {
            reader.readAsDataURL(file);
        } else {
            preview.src = "#";
            preview.style.display = 'none';
        }
    }

    function getOptions(field) {
        let optionsList = [];
        switch (field) {
            case 'type':
                optionsList = typeList;
                break;
            case 'privacy':
                optionsList = privacyList;
                break;
            default:
                console.error("Campo no manejado en getOptions");
                return '';
        }
        let optionsHtml = '';
        optionsList.forEach(option => {
            optionsHtml += `<option value="${option.id}">${option.name}</option>`;
        });

        return optionsHtml;
    }

    function saveEdit(groupId, field) {
        let newValue;

        switch (field) {
            case 'image':
                const imageFile = document.getElementById(`newImage`).files[0];
                if (!imageFile) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No se seleccionó imagen',
                        text: 'Por favor, selecciona una imagen para continuar.',
                        confirmButtonText: 'Aceptar'
                    });
                    return;
                }
                newValue = imageFile;
                break;
            case 'name':
            case 'description':
            case 'rules':
            case 'privacy':
            case 'type':
                newValue = document.getElementById(`newValue${field}`).value;
                break;
            case 'date':
                const startDate = document.getElementById(`startDate${field}${groupId}`).value;
                const endDate = document.getElementById(`endDate${field}${groupId}`).value;
                newValue = JSON.stringify({ startDate, endDate });
                break;
            default:
                console.error("Campo no manejado");
                return;
        }
        console.log('Final newValue to send:', newValue[0]);
        const formData = new FormData();
        formData.append('groupId', groupId);
        formData.append('newValue', newValue);
       // alert(newValue);
        const endpointMap = {
            'name': { controller: 'Group', method: 'updateGroupName' },
            'description': { controller: 'GroupInfo', method: 'updateGroupInfoDescription' },
            'rules': { controller: 'GroupInfo', method: 'updateGroupInfoRules' },
            'privacy': { controller: 'Group', method: 'updateGroupPrivacy' },
            'type': { controller: 'Group', method: 'updateGroupType' },
            'date': { controller: 'GroupInfo', method: 'updateGroupInfoDates' },
            'image': { controller: 'GroupInfo', method: 'updateGroupInfoImage' }
        };
        const endpoint = endpointMap[field];

        $.ajax({
            url: `<?php echo FRONT_ROOT ?>${endpoint.controller}/${endpoint.method}`,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log(response);
                response = JSON.parse(response);
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        color: "#716add",
                        title: '¡Modificación exitosa!',
                        text: response.message,
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        if (field === 'name') {
                            //document.getElementById(`groupNameDisplay${groupId}`).innerText = response.newName;
                            document.querySelectorAll(`.groupNameDisplay${groupId}`).forEach(function(newName) {
                                newName.innerText = response.newName; 
                            });
                        }else if(field === 'rules'){
                            document.getElementById(`groupRules${groupId}`).innerText = response.newRules;
                        }else if(field === 'description'){
                            document.getElementById(`groupDescShort${groupId}`).innerText = response.newDescription;
                        }else if(field === 'privacy'){
                            document.getElementById(`groupPrivacyName${groupId}`).innerText = response.newPrivacyName;
                            document.getElementById(`groupPrivacyDescription${groupId}`).innerText = response.newPrivacyDescription;
                        }else if(field === 'type'){
                            document.getElementById(`groupTypeName${groupId}`).innerText = response.newTypeName;
                            document.getElementById(`groupTypeDescription${groupId}`).innerText = response.newTypeDescription;
                        }else if(field==='date'){
                            document.getElementById(`startDate${groupId}`).innerText = response.startDate;
                            document.getElementById(`endDate${groupId}`).innerText = response.endDate;
                        }else{
                           // document.getElementById(`groupImage${groupId}`).src = response.newImage;
                            document.querySelectorAll(`.groupImage${groupId}`).forEach(function(newImage) {
                                console.log('entre');
                                newImage.src = response.newImage; 
                            });
                        }
                        const modal = bootstrap.Modal.getInstance(document.getElementById(`editFieldModal${field}`));
                        modal.hide();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Hubo un problema al intentar guardar los cambios.',
                        confirmButtonText: 'Aceptar'
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error inesperado.',
                    confirmButtonText: 'Aceptar'
                });
                console.error('Error en la solicitud:', status, error);
            }
        });
    }
    
    //Colores para el nombre del usuario en un grupo
    function getRandomColor() {
        const letters = '0123456789ABCDEF';
        let color = '#';
        for (let i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

    function getUserColor(userId) {
        if (!userColors[userId]) {
            userColors[userId] = getRandomColor();
        }
        return userColors[userId];
    }
    //Invitaciones
    //Busca invitaciones por grupo
    function loadGroupInvitations(groupId) {
        $.ajax({
            url: '<?php echo FRONT_ROOT ?>GroupInvitation/getInvitationsByGroup',
            type: 'POST',
            data: { groupId: groupId },
            success: function(response) {
                const responseJ = JSON.parse(response);
                if (responseJ.success) {
                    showInvitationsModal(responseJ.invitations);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: responseJ.message,
                        background: '#ffffff',
                        color: '#000000',
                        iconColor: '#dc3545'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un problema al cargar las invitaciones.',
                    background: '#ffffff',
                    color: '#000000',
                    iconColor: '#dc3545'
                });
            }
        });
    }
    function showInvitationDetails(invitation) {
        const detailsModalContent = `
        <div class="modal fade" id="invitationDetailsModal" tabindex="-1" aria-labelledby="invitationDetailsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content shadow-xl">
                    <div class="modal-header bg-gradient-info">
                        <h5 class="modal-title" id="invitationDetailsModalLabel">Detalles de la Invitación</h5>
                        <button type="button" class="btn-close btn-close-black" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body text-center">
                        <!-- Información del Invitado -->
                        <div class="mb-4 border p-4 rounded bg-light">
                            <h5 class="mb-4 text-center">Invitado</h5>
                            <div class="d-flex flex-column align-items-center mb-4">
                                <img src="<?php echo FRONT_ROOT ?>${invitation.invited_user.image}" 
                                    alt="Imagen del usuario" 
                                    class="img-fluid rounded-circle mb-3" 
                                    style="width: 100px; height: 100px;">
                                
                                <div class="text-center">
                                    <p class="mb-1 fw-bold">${invitation.invited_user.firstName} ${invitation.invited_user.lastName}</p>
                                    <p class="mb-1 text-muted">Email: <span class="fw-normal">${invitation.invited_user.email}</span></p>
                                    <p class="mb-1 text-muted">Descripción: <span class="fw-normal">${invitation.invited_user.userDescription}</span></p>
                                </div>
                            </div>
                        </div>
                        <!-- Información de la Invitación -->
                        <div class="mb-4 p-4 rounded bg-light shadow-sm border border-secondary">
                            <div class="mb-4 p-4 rounded bg-light shadow-sm border border-secondary">
                            <h5 class="mb-3 text-center">Invitación</h5>
                            <div class="d-flex flex-column align-items-center justify-content-center">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi bi-person-check me-3" style="font-size: 1.5rem; color: #007bff;"></i>
                                    <p class="mb-0"><strong>Invitado por:</strong> <span class="fw-bold">${invitation.invited_by.firstName} ${invitation.invited_by.lastName}</span></p>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi bi-chat-text me-3" style="font-size: 1.5rem; color: #17a2b8;"></i>
                                    <p class="mb-0"><strong>Mensaje:</strong> <span class="text-muted">${invitation.message}</span></p>
                                </div>
                                <div class="d-flex align-items-center justify-content-center mb-3">
                                    <div class="w-100 d-flex align-items-center justify-content-center flex-wrap">
                                        <div class="mb-0 d-flex align-items-center">
                                            <i class="bi bi-flag" style="font-size: 1.5rem; color: #28a745;"></i>
                                            <span class="mx-1"><strong>Estado:</strong></span>
                                            <span class="badge m-0 ${invitation.status.id === '3' ? 'bg-danger' : invitation.status.id === '2' ? 'bg-success' : 'bg-primary'}">
                                                ${invitation.status.name}
                                            </span>
                                        </div>
                                        <div class="w-100">
                                            <p class="text-muted w-100 mb-0 ms-2">
                                            ${invitation.status.description}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi bi-clock me-3" style="font-size: 1.5rem; color: #ffc107;"></i>
                                    <p class="mb-0"><strong>Enviado el:</strong> <span class="text-muted">${new Date(invitation.send_at).toLocaleString()}</span></p>
                                </div>
                                ${invitation.responded_at ? 
                                    `<div class="d-flex align-items-center mb-3">
                                        <i class="bi bi-clock-history me-3" style="font-size: 1.5rem; color: #6c757d;"></i>
                                        <p class="mb-0"><strong>Respondido el:</strong> <span class="text-muted">${new Date(invitation.responded_at).toLocaleString()}</span></p>
                                    </div>` 
                                    : 
                                    `<div class="text-warning mb-1 d-flex align-items-center">
                                        <i class="bi bi-exclamation-triangle me-2"></i>
                                        <p class="mb-0">No respondido</p>
                                    </div>`
                                }
                            </div>
                        </div>
                        <!-- Información del Grupo -->
                        <div class="mb-4 p-4 rounded bg-light shadow-sm">
                            <h5 class="mb-4 text-center"><i class="fas fa-users"></i> Grupo</h5>
                            <div class="text-center mb-3">
                                <!-- Imagen del Grupo -->
                                <img src="<?php echo FRONT_ROOT ?>${invitation.groupId.groupInfo.image}" 
                                    alt="Imagen del grupo" 
                                    class="img-fluid rounded shadow" 
                                    style="max-width: 300px; height: auto; border: 2px solid #007bff; transition: transform 0.3s;">
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3 text-center border border-primary rounded p-3 shadow-sm">
                                    <p class="mb-1"><strong>Nombre:</strong></p>
                                    <p class="text-muted">${invitation.groupId.name}</p>
                                </div>

                                <div class="col-md-12 mb-3 text-center border border-primary rounded p-3 shadow-sm">
                                    <p class="mb-1"><strong>Descripción:</strong></p>
                                    <p class="text-muted">${invitation.groupId.groupInfo.description}</p>
                                </div>

                                <div class="col-md-6 mb-3 text-center border border-primary rounded p-3 shadow-sm">
                                    <p class="mb-1"><strong><i class="fas fa-tag"></i> Tipo:</strong></p>
                                    <p class="text-muted">${invitation.groupId.groupType.name} - ${invitation.groupId.groupType.description}</p>
                                </div>

                                <div class="col-md-6 mb-3 text-center border border-primary rounded p-3 shadow-sm">
                                    <p class="mb-1"><strong><i class="fas fa-lock"></i> Privacidad:</strong></p>
                                    <p class="text-muted">${invitation.groupId.groupPrivacy.name} - ${invitation.groupId.groupPrivacy.description}</p>
                                </div>

                                <div class="col-md-6 mb-3 text-center border border-primary rounded p-3 shadow-sm">
                                    <p class="mb-1"><strong><i class="fas fa-book"></i> Reglas:</strong></p>
                                    <p class="text-muted">${invitation.groupId.groupInfo.rules}</p>
                                </div>

                                <div class="col-md-6 mb-3 text-center border border-primary rounded p-3 shadow-sm">
                                    <p class="mb-1"><strong><i class="fas fa-check-circle"></i> Estado:</strong></p>
                                    <p class="text-muted">
                                        <span class="badge bg-success w-100">${invitation.groupId.statusId.name}</span>
                                        <span class="d-block text-muted">${invitation.groupId.statusId.description}</span>
                                    </p>
                                </div>

                                <div class="col-md-4 mb-3 text-center border border-primary rounded p-3 shadow-sm">
                                    <p class="mb-1"><strong><i class="fas fa-calendar-alt"></i> Fecha de Creación:</strong></p>
                                    <p class="text-muted">${new Date(invitation.groupId.created_at).toLocaleString()}</p>
                                </div>

                                <div class="col-md-4 mb-3 text-center border border-primary rounded p-3 shadow-sm">
                                    <p class="mb-1"><strong><i class="fas fa-calendar-check"></i> Fecha de Inicio:</strong></p>
                                    <p class="text-muted">${new Date(invitation.groupId.groupInfo.start_date).toLocaleDateString()}</p>
                                </div>

                                <div class="col-md-4 mb-3 text-center border border-primary rounded p-3 shadow-sm">
                                    <p class="mb-1"><strong><i class="fas fa-calendar-times"></i> Fecha de Fin:</strong></p>
                                    <p class="text-muted">${new Date(invitation.groupId.groupInfo.end_date).toLocaleDateString()}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Información del Rol -->
                        <div class="border p-4 rounded bg-light shadow-sm">
                            <h5 class="mb-3 text-center">Rol</h5>
                            <p class="mb-1">
                                <strong>Rol Invitado:</strong>
                            </p>
                            <p class="mb-1">
                                <span class="badge bg-info ms-2 p-2">${invitation.roleInvited.name}</span>
                            </p>
                            <p class="text-muted mb-0">- ${invitation.roleInvited.description}</p>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        `;
        $('body').append(detailsModalContent);

        $('#invitationDetailsModal').modal('show');

        $('#invitationDetailsModal').on('hidden.bs.modal', function () {
            $('#invitationDetailsModal').remove();
            $(this).remove();
        });
    }
    function showInvitationsModal(invitations) {
        const updateInvitationList = (filteredInvitations) => {
            const invitationList = filteredInvitations.map(invitation => `
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                    <div class="card shadow border-light rounded" style="cursor: pointer;">
                        <div class="card-body p-3">
                            <h5 class="card-title">
                                <i class="bi bi-person-fill me-2"></i>
                                ${invitation.invited_user.firstName} ${invitation.invited_user.lastName}
                            </h5>
                            <p><strong>Grupo:</strong> ${invitation.groupId.name}</p>
                            <p><strong>Invitado por:</strong> ${invitation.invited_by.firstName} ${invitation.invited_by.lastName}</p>
                            <p><strong>Estado:</strong> <span class="badge ${getStatusClass(invitation.status.name)}">${invitation.status.name}</span></p>
                            <p><strong>Correo:</strong> ${invitation.invited_user.email}</p>
                            <button class="btn btn-primary w-100" onclick="showInvitationDetails(${JSON.stringify(invitation).replace(/"/g, '&quot;')})">
                                <i class="bi bi-eye-fill me-2"></i>
                                Ver Detalles
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');

            $('#invitationsModal .modal-body .row').html(invitationList);
        };

        const getStatusClass = (status) => {
            switch (status) {
                case 'Aceptado':
                    return 'bg-success text-white';
                case 'Rechazado':
                    return 'bg-danger text-white';
                case 'Vencido':
                    return 'bg-warning text-dark';
                default:
                    return 'bg-primary text-white';
            }
        };

        const createModalContent = () => {
            return `
                <div class="modal fade" id="invitationsModal" tabindex="-1" aria-labelledby="invitationsModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-fullscreen">
                        <div class="modal-content">
                            <div class="modal-header bg-gradient-primary">
                                <h5 class="modal-title" id="invitationsModalLabel">Invitaciones Pendientes</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                            </div>
                            <div class="modal-body">
                                <h3>Filtros</h3>
                                <div class="mb-3">
                                    <input type="text" id="emailFilter" class="form-control" placeholder="Buscar por correo" />
                                </div>
                                <div class="mb-5">
                                    <select id="statusFilter" class="form-select">
                                        <option value="">Todas</option>
                                        <?php foreach ($groupInvitation as $invitations): ?>
                                            <option value="<?php echo $invitations->getName()?>"><?php echo $invitations->getName()?></option>
                                        <?php endforeach;?>
                                    </select>
                                </div>
                                <div class="row g-3">
                                    ${invitations.map(invitation => `
                                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                                            <div class="card shadow border-light rounded">
                                                <div class="card-body p-3">
                                                    <h5 class="card-title">
                                                    <div class="d-flex align-items-center mb-3">
                                                        <img src="<?php echo FRONT_ROOT ?>${invitation.invited_user.image}" alt="Imagen del usuario" class="img-fluid rounded-circle me-3" style="width: 50px; height: 50px;">
                                                    <div>
                                                        ${invitation.invited_user.firstName} ${invitation.invited_user.lastName}
                                                    </h5>
                                                    <p><strong>Grupo:</strong> ${invitation.groupId.name}</p>
                                                    <p><strong>Invitado por:</strong> ${invitation.invited_by.firstName} ${invitation.invited_by.lastName}</p>
                                                    <p><strong>Estado:</strong> <span class="badge ${getStatusClass(invitation.status.name)}">${invitation.status.name}</span></p>
                                                    <p><strong>Correo:</strong> ${invitation.invited_user.email}</p>
                                                    <button class="btn btn-primary w-100" onclick="showInvitationDetails(${JSON.stringify(invitation).replace(/"/g, '&quot;')})">
                                                        <i class="bi bi-eye-fill me-2"></i>
                                                        Ver Detalles
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        };

        $('body').append(createModalContent());
        $('#invitationsModal').modal('show');

        // Filtros
        $('#emailFilter').on('input', function() {
            const emailValue = $(this).val().toLowerCase();
            const statusValue = $('#statusFilter').val();
            
            const filteredInvitations = invitations.filter(invitation => {
                const emailMatches = invitation.invited_user.email.toLowerCase().includes(emailValue);
                const statusMatches = statusValue ? invitation.status.name === statusValue : true;
                return emailMatches && statusMatches;
            });
            
            updateInvitationList(filteredInvitations);
        });

        $('#statusFilter').on('change', function() {
            const emailValue = $('#emailFilter').val().toLowerCase();
            const statusValue = $(this).val();
            
            const filteredInvitations = invitations.filter(invitation => {
                const emailMatches = invitation.invited_user.email.toLowerCase().includes(emailValue);
                const statusMatches = statusValue ? invitation.status.name === statusValue : true;
                return emailMatches && statusMatches;
            });
            
            updateInvitationList(filteredInvitations);
        });

        $('#invitationsModal').on('hidden.bs.modal', function () {
            $(this).remove();
        });
    }
</script>
</body>
</html>
