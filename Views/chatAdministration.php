<?php
namespace Views;
require_once("validate-session.php");
foreach ($groupRoleList as $group) {
    $groupArray[] = [
        'id' => $group->getId(),
        'name' => $group->getName(),
        'is_active' => $group->getIsActive(),
        'description' => $group->getDescription()
    ];
}
$groupListJson = json_encode($groupArray);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Grupos</title>
        <!--Boostrap-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- JS de DataTables -->
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <!-- CSS de DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <!-- CSS para Botones de Exportación de DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.5/css/buttons.dataTables.min.css">

    <!-- JS para los botones de exportación -->
    <script src="https://cdn.datatables.net/buttons/2.3.5/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.5/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.5/js/buttons.print.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

</head>
<style>
    .img-fluid:hover {
        transform: scale(1.05);
    }
    .border {
        border: 1px solid #007bff;
    }
</style>
<body>
<div class="container my-5">
    <h1 class="mb-4 text-center">Administración de Grupos</h1>
    <input type="hidden" id="currentUserID" name="currentUserID" value="<?php echo $currentUser; ?>">
    <!-- Bloque de Group Status -->
    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h3>Group Status</h3>
            <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#newGroupStatus">
            <i class="bi bi-plus-circle"> Nuevo Group status</i>
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="groupStatusTable" class="table table-striped table-hover table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Activo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($groupStatusList as $status): ?>
                            <tr>
                                <td><?php echo $status->getId(); ?></td>
                                <td><?php echo $status->getName(); ?></td>
                                <td><?php echo $status->getDescription(); ?></td>
                                <td><?php echo $status->getIsActive() ? 'Sí' : 'No'; ?></td>
                                <td>
                                    <div class="d-flex">
                                    <button class="btn btn-sm btn-warning" 
                                                data-id="<?php echo $status->getId(); ?>" 
                                                data-name="<?php echo $status->getName(); ?>" 
                                                data-description="<?php echo $status->getDescription(); ?>" 
                                                data-title="Eliminar Privacidad"
                                                data-status="<?php echo $status->getIsActive();?>"  
                                                data-type="statusType" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#changeStatusModal" 
                                                onclick="setEditModalData(this)">
                                                <i class="bi bi-pencil-square"></i> Editar
                                        </button>
                                        <?php if($status->getIsActive()==='1'):?>
                                        <button class="btn btn-sm btn-danger" 
                                                data-id="<?php echo $status->getId(); ?>" 
                                                data-description="el estado" 
                                                data-name="<?php echo $status->getName(); ?>" 
                                                data-detail="<?php echo $status->getDescription(); ?>" 
                                                data-title="Eliminar Estado" 
                                                data-type="statusType" 
                                                data-bs-toggle="modal"
                                                data-status="<?php echo $status->getIsActive();?>" 
                                                data-bs-target="#changeStatusModal" 
                                                onclick="setChangeModalData(this)">
                                            <i class="bi bi-trash"></i> Eliminar
                                        </button>
                                        <?php else: ?>
                                        <button class="btn btn-sm btn-success" 
                                                data-id="<?php echo $status->getId(); ?>" 
                                                data-description="el estado" 
                                                data-name="<?php echo $status->getName(); ?>" 
                                                data-detail="<?php echo $status->getDescription(); ?>" 
                                                data-title="Eliminar Estado"
                                                data-status="<?php echo $status->getIsActive();?>"   
                                                data-type="statusType" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#changeStatusModal" 
                                                onclick="setChangeModalData(this)">
                                            <i class="bi bi-arrow-clockwise"></i> Reactivar
                                        </button>
                                        <?php endif;?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Bloque de Group Privacy -->
    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h3>Group Privacy</h3>
            <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#newGroupPrivacy">
            <i class="bi bi-plus-circle"> Nuevo Group Privacy</i>
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="groupPrivacyTable" class="table table-striped table-hover table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Activo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($groupPrivacyList as $privacy): ?>
                            <tr>
                                <td><?php echo $privacy->getId(); ?></td>
                                <td><?php echo $privacy->getName(); ?></td>
                                <td><?php echo $privacy->getDescription(); ?></td>
                                <td><?php echo $privacy->getIsActive() ? 'Sí' : 'No'; ?></td>
                                <td>
                                    <div class="d-flex">
                                    <button class="btn btn-sm btn-warning" 
                                                data-id="<?php echo $privacy->getId(); ?>" 
                                                data-name="<?php echo $privacy->getName(); ?>" 
                                                data-description="<?php echo $privacy->getDescription(); ?>" 
                                                data-title="Modificar Privacidad"
                                                data-status="<?php echo $privacy->getIsActive();?>"  
                                                data-type="privacy" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#changeStatusModal" 
                                                onclick="setEditModalData(this)">
                                                <i class="bi bi-pencil-square"></i> Editar
                                        </button>
                                        <?php if($privacy->getIsActive()==='1'):?>
                                        <button class="btn btn-sm btn-danger" 
                                                data-id="<?php echo $privacy->getId(); ?>" 
                                                data-description="el estado de privacidad" 
                                                data-name="<?php echo $privacy->getName(); ?>" 
                                                data-detail="<?php echo $privacy->getDescription(); ?>" 
                                                data-title="Eliminar Privacidad"
                                                data-status="<?php echo $privacy->getIsActive();?>"  
                                                data-type="privacy" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#changeStatusModal" 
                                                onclick="setChangeModalData(this)">
                                            <i class="bi bi-trash"></i> Eliminar
                                        </button>
                                        <?php else:?>
                                        <button class="btn btn-sm btn-success" 
                                                data-id="<?php echo $privacy->getId(); ?>" 
                                                data-description="el estado de privacidad" 
                                                data-name="<?php echo $privacy->getName(); ?>" 
                                                data-detail="<?php echo $privacy->getDescription(); ?>" 
                                                data-title="Eliminar Privacidad"
                                                data-status="<?php echo $privacy->getIsActive();?>"  
                                                data-type="privacy" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#changeStatusModal" 
                                                onclick="setChangeModalData(this)">
                                                <i class="bi bi-arrow-clockwise"></i> Reactivar
                                        </button>
                                        <?php endif;?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Bloque de Group Roles -->
    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h3>Group Roles</h3>
            <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#newGroupRole">
                <i class="bi bi-plus-circle"> Nuevo Group Roles</i>
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="groupRoleTable" class="table table-striped table-hover table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Activo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($groupRoleList as $role): ?>
                            <tr>
                                <td><?php echo $role->getId(); ?></td>
                                <td><?php echo $role->getName(); ?></td>
                                <td><?php echo $role->getDescription(); ?></td>
                                <td><?php echo $role->getIsActive() ? 'Sí' : 'No'; ?></td>
                                <td>
                                    <div class="d-flex">
                                        <button class="btn btn-sm btn-warning" 
                                                data-id="<?php echo $role->getId(); ?>" 
                                                data-name="<?php echo $role->getName(); ?>" 
                                                data-description="<?php echo $role->getDescription(); ?>" 
                                                data-title="Editar Role"
                                                data-status="<?php echo $role->getIsActive();?>"  
                                                data-type="role" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#changeStatusModal" 
                                                onclick="setEditModalData(this)">
                                                <i class="bi bi-pencil-square"></i> Editar
                                        </button>
                                    <?php if($role->getIsActive()==='1'):?>
                                    <button class="btn btn-sm btn-danger" 
                                            data-id="<?php echo $role->getId(); ?>" 
                                            data-description="el role de usuario" 
                                            data-name="<?php echo $role->getName(); ?>" 
                                            data-detail="<?php echo $role->getDescription(); ?>" 
                                            data-title="Eliminar Rol"
                                            data-status="<?php echo $role->getIsActive();?>"  
                                            data-type="role" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#changeStatusModal" 
                                            onclick="setChangeModalData(this)">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </button>
                                    <?php else:?>
                                        <button class="btn btn-sm btn-success" 
                                            data-id="<?php echo $role->getId(); ?>" 
                                            data-description="el role de usuario" 
                                            data-name="<?php echo $role->getName(); ?>" 
                                            data-detail="<?php echo $role->getDescription(); ?>" 
                                            data-title="Eliminar Rol"
                                            data-status="<?php echo $role->getIsActive();?>"  
                                            data-type="role" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#changeStatusModal" 
                                            onclick="setChangeModalData(this)">
                                            <i class="bi bi-arrow-clockwise"></i> Reactivar
                                    </button>
                                    <?php endif;?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Bloque de Group Types -->
    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h3>Group Types</h3>
            <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#newGroupType">
                <i class="bi bi-plus-circle"> Nuevo Group Types</i>
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="groupTypeTable" class="table table-striped table-hover table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Activo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($groupTypeList as $type): ?>
                            <tr>
                                <td><?php echo $type->getId(); ?></td>
                                <td><?php echo $type->getName(); ?></td>
                                <td><?php echo $type->getDescription(); ?></td>
                                <td><?php echo $type->getIsActive() ? 'Sí' : 'No'; ?></td>
                                <td>
                                    <div class="d-flex">
                                    <button class="btn btn-sm btn-warning" 
                                                data-id="<?php echo $type->getId(); ?>" 
                                                data-name="<?php echo $type->getName(); ?>" 
                                                data-description="<?php echo $type->getDescription(); ?>" 
                                                data-title="Editar Type"
                                                data-status="<?php echo $type->getIsActive();?>"  
                                                data-type="groupType" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#changeStatusModal" 
                                                onclick="setEditModalData(this)">
                                                <i class="bi bi-pencil-square"></i> Editar
                                        </button>
                                        <?php if($type->getIsActive()==='1'):?>
                                        <button class="btn btn-sm btn-danger" 
                                                data-id="<?php echo $type->getId(); ?>" 
                                                data-description="el tipo de grupo" 
                                                data-name="<?php echo $type->getName(); ?>" 
                                                data-detail="<?php echo $type->getDescription(); ?>" 
                                                data-title="Eliminar Tipo de Grupo"
                                                data-status="<?php echo $type->getIsActive();?>"  
                                                data-type="groupType" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#changeStatusModal" 
                                                onclick="setChangeModalData(this)">
                                            <i class="bi bi-trash"></i> Eliminar
                                        </button>
                                        <?php else:?>
                                            <button class="btn btn-sm btn-success" 
                                                data-id="<?php echo $type->getId(); ?>" 
                                                data-description="el tipo de grupo" 
                                                data-name="<?php echo $type->getName(); ?>" 
                                                data-detail="<?php echo $type->getDescription(); ?>" 
                                                data-title="Eliminar Tipo de Grupo"
                                                data-status="<?php echo $type->getIsActive();?>"  
                                                data-type="groupType" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#changeStatusModal" 
                                                onclick="setChangeModalData(this)">
                                                <i class="bi bi-arrow-clockwise"></i> Reactivar
                                        </button>
                                        <?php endif;?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Bloque de Invitaciones --> 
    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h3>Estados de invitaciones</h3>
            <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#newGroupInvitation">
                <i class="bi bi-plus-circle"> Nuevo estado de invitaciones</i>
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="groupInvitationTable" class="table table-striped table-hover table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Activo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($groupInvitation as $invitations): ?>
                            <tr>
                                <td><?php echo $invitations->getId(); ?></td>
                                <td><?php echo $invitations->getName(); ?></td>
                                <td><?php echo $invitations->getDescription(); ?></td>
                                <td><?php echo $invitations->getIsActive() ? 'Sí' : 'No'; ?></td>
                                <td>
                                    <div class="d-flex">
                                    <button class="btn btn-sm btn-warning" 
                                                data-id="<?php echo $invitations->getId(); ?>" 
                                                data-name="<?php echo $invitations->getName(); ?>" 
                                                data-description="<?php echo $invitations->getDescription(); ?>" 
                                                data-title="Editar Type"
                                                data-status="<?php echo $invitations->getIsActive();?>"  
                                                data-type="invitationType" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#changeStatusModal" 
                                                onclick="setEditModalData(this)">
                                                <i class="bi bi-pencil-square"></i> Editar
                                        </button>
                                        <?php if($invitations->getIsActive()==='1'):?>
                                        <button class="btn btn-sm btn-danger" 
                                                data-id="<?php echo $invitations->getId(); ?>"
                                                data-description="el estado de invitación"
                                                data-name="<?php echo $invitations->getName(); ?>" 
                                                data-detail="<?php echo $invitations->getDescription(); ?>"
                                                data-title="Estados de invitación"
                                                data-type="invitationType"
                                                data-status="<?php echo $invitations->getIsActive();?>" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#changeStatusModal"
                                                onclick="setChangeModalData(this)">
                                                <i class="bi bi-trash"></i>Eliminar
                                        </button>
                                        <?php else:?>
                                            <button class="btn btn-sm btn-success" 
                                                data-id="<?php echo $invitations->getId(); ?>"
                                                data-description="el estado de invitación"
                                                data-name="<?php echo $invitations->getName(); ?>" 
                                                data-detail="<?php echo $invitations->getDescription(); ?>"
                                                data-title="Estados de invitación"
                                                data-type="invitationType"
                                                data-status="<?php echo $invitations->getIsActive();?>" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#changeStatusModal"
                                                onclick="setChangeModalData(this)">
                                                <i class="bi bi-arrow-clockwise"></i> Reactivar
                                        </button>
                                        <?php endif;?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Bloque de Grupos -->
    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h3>Grupos</h3>
            <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#createGroupModal">
                <i class="bi bi-plus-circle"> Nuevo Grupo</i>
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="groupTable" class="table table-striped table-hover table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Privacidad</th>
                            <th>Detalles</th>
                            <th>Miembros</th>
                            <th>Invitaciones</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($groupList as $group): ?>
                            <tr>
                                <td><?php echo $group->getId(); ?></td>
                                <td><?php echo $group->getName(); ?></td>
                                <td><?php echo $group->getGroupInfo()->getDescription(); ?></td>
                                <td><?php echo $group->getGroupPrivacy()->getName(); ?></td>
                                <td>
                                    <button type="button" class="btn btn-info btn-sm shadow d-flex align-items-center justify-content-center" onclick="loadGroupDetails(<?php echo $group->getId(); ?>)">
                                        <i class="fas fa-info-circle mx-1"></i> Detalles
                                    </button>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-info btn-sm shadow d-flex align-items-center justify-content-center" onclick="loadGroupMembers(<?php echo $group->getId(); ?>)">
                                    <i class="bi bi-people"></i>
                                        Miembros
                                    </button>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-info btn-sm shadow d-flex align-items-center justify-content-center" onclick="loadGroupInvitations(<?php echo $group->getId(); ?>)">
                                    <i class="bi bi-people"></i>
                                        Invitaciones
                                    </button>
                                </td>
                                <td><?php echo $group->getStatusId()->getName(); ?></td>
                                <td>
                                <div class="d-flex">
                                    <button type="button" class="btn btn-warning" onclick="loadGroupData(<?php echo $group->getId(); ?>)">
                                        <i class="bi bi-pencil-square"></i> Editar
                                    </button>
                                    <button class="btn btn-sm btn-success" 
                                            data-id="<?php echo $group->getId(); ?>" 
                                            data-current-status="<?php echo $group->getStatusId()->getId(); ?>" 
                                            data-name="<?php echo $group->getName();?>" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#changeGroupStatusModal" 
                                            onclick="openChangeGroupStatusModal(this)">
                                        <i class="bi bi-arrow-repeat"></i> Cambiar Estado
                                    </button>
                                </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!--Modales-->
<!-- Modal para Nuevo Grupo de Estado -->
<div class="modal fade" id="newGroupStatus" tabindex="-1" aria-labelledby="newGroupStatusLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg" style="background-color: #f8f9fa;">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="newGroupStatusLabel">
                    <i class="bi bi-plus-circle"></i> Nuevo Grupo de Estado
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="newGroupStatusForm">
                    <div class="mb-3">
                        <label for="statusName" class="form-label">Nombre</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-pencil-fill"></i></span>
                            <input type="text" class="form-control" id="statusName" name="statusName" placeholder="Ingrese el nombre del estado" required aria-describedby="statusNameHelp">
                        </div>
                        <div id="statusNameHelp" class="form-text">El nombre debe ser descriptivo y único.</div>
                        <div class="invalid-feedback">
                            Por favor, introduce un nombre válido.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="statusDescription" class="form-label">Descripción</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-file-earmark-text"></i></span>
                            <textarea class="form-control" id="statusDescription" name="statusDescription" rows="3" placeholder="Describa el estado" required aria-describedby="statusDescriptionHelp"></textarea>
                        </div>
                        <div id="statusDescriptionHelp" class="form-text">Máximo 250 caracteres.</div>
                        <div class="invalid-feedback">
                            Por favor, introduce una descripción válida.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="statusActive" class="form-label">Activo</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-check-circle"></i></span>
                            <select class="form-select" id="statusActive" name="statusActive" required>
                                <option value="" disabled selected>Seleccione...</option>
                                <option value="1">Sí</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                        <div class="invalid-feedback">
                            Por favor, selecciona una opción.
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="saveGroupStatus()" id="saveGroupStatusButton">
                    <i class="bi bi-save"></i> Guardar
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Nuevo Estado de Privacidad -->
<div class="modal fade" id="newGroupPrivacy" tabindex="-1" aria-labelledby="newGroupPrivacyLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg" style="background-color: #f8f9fa;">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="newGroupPrivacyLabel">
                    <i class="fas fa-shield-alt"></i> Nuevo Estado de Privacidad
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="privacyForm">
                    <div class="mb-3">
                        <label for="privacyName" class="form-label">Nombre del Estado</label>
                        <input type="text" class="form-control" id="privacyName" placeholder="Ingrese el nombre del estado" required aria-describedby="privacyNameHelp">
                        <div id="privacyNameHelp" class="form-text">El nombre debe ser descriptivo y único.</div>
                    </div>
                    <div class="mb-3">
                        <label for="privacyDescription" class="form-label">Descripción</label>
                        <textarea class="form-control" id="privacyDescription" rows="4" placeholder="Describa el estado de privacidad" required aria-describedby="privacyDescriptionHelp"></textarea>
                        <div id="privacyDescriptionHelp" class="form-text">Máximo 250 caracteres.</div>
                    </div>
                    <div class="mb-3">
                        <label for="privacyActive" class="form-label">¿Estado Activo?</label>
                        <select class="form-select" id="privacyActive" required>
                            <option value="" disabled selected>Seleccione una opción</option>
                            <option value="1">Sí</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times"></i> Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="saveGroupPrivacy()" id="saveButton">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Nuevo Rol de Grupo -->
<div class="modal fade" id="newGroupRole" tabindex="-1" aria-labelledby="newGroupRoleLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg" style="background-color: #f8f9fa;">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="newGroupRoleLabel">
                    <i class="bi bi-person-plus-fill"></i> Nuevo Rol de Grupo
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="roleForm">
                    <div class="mb-3">
                        <label for="roleName" class="form-label">Nombre</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person-circle"></i></span>
                            <input type="text" class="form-control" id="roleName" placeholder="Ingrese el nombre del rol" required aria-describedby="roleNameHelp">
                        </div>
                        <div id="roleNameHelp" class="form-text">El nombre debe ser único y descriptivo.</div>
                        <div class="invalid-feedback">
                            Por favor, introduce un nombre válido.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="roleDescription" class="form-label">Descripción</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-file-earmark-text"></i></span>
                            <textarea class="form-control" id="roleDescription" rows="3" placeholder="Describa el rol" required aria-describedby="roleDescriptionHelp"></textarea>
                        </div>
                        <div id="roleDescriptionHelp" class="form-text">Máximo 250 caracteres.</div>
                        <div class="invalid-feedback">
                            Por favor, introduce una descripción válida.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="roleActive" class="form-label">Activo</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-check-circle"></i></span>
                            <select class="form-select" id="roleActive" required>
                                <option value="" disabled selected>Seleccione...</option>
                                <option value="1">Sí</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                        <div class="invalid-feedback">
                            Por favor, selecciona una opción.
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="saveGroupRole()" id="saveGroupRoleButton">
                    <i class="bi bi-save"></i> Guardar
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Nuevo Tipo de Grupo -->
<div class="modal fade" id="newGroupType" tabindex="-1" aria-labelledby="newGroupTypeLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg" style="background-color: #f8f9fa;">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="newGroupTypeLabel">
                    <i class="bi bi-folder-plus"></i> Nuevo Tipo de Grupo
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="typeForm">
                    <div class="mb-3">
                        <label for="typeName" class="form-label">Nombre</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-pencil-fill"></i></span>
                            <input type="text" class="form-control" id="typeName" placeholder="Ingrese el nombre del tipo" required aria-describedby="typeNameHelp">
                        </div>
                        <div id="typeNameHelp" class="form-text">El nombre debe ser único y descriptivo.</div>
                        <div class="invalid-feedback">
                            Por favor, introduce un nombre válido.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="typeDescription" class="form-label">Descripción</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-file-earmark-text"></i></span>
                            <textarea class="form-control" id="typeDescription" rows="3" placeholder="Describa el tipo" required aria-describedby="typeDescriptionHelp"></textarea>
                        </div>
                        <div id="typeDescriptionHelp" class="form-text">Máximo 250 caracteres.</div>
                        <div class="invalid-feedback">
                            Por favor, introduce una descripción válida.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="typeActive" class="form-label">Activo</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-check-circle"></i></span>
                            <select class="form-select" id="typeActive" required>
                                <option value="" disabled selected>Seleccione...</option>
                                <option value="1">Sí</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                        <div class="invalid-feedback">
                            Por favor, selecciona una opción.
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="saveGroupTypeButton" onclick="saveGroupType()">
                    <i class="bi bi-save"></i> Guardar
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Nuevo Estado de Invitación -->
<div class="modal fade" id="newGroupInvitation" tabindex="-1" aria-labelledby="newGroupInvitationLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg" style="background-color: #f8f9fa;">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="newGroupInvitationLabel">
                    <i class="bi bi-card-checklist"></i> Nuevo Estado de Invitación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="invitationForm">
                    <div class="mb-3">
                        <label for="invitationName" class="form-label">Nombre</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-pencil-fill"></i></span>
                            <input type="text" class="form-control" id="invitationName" placeholder="Ingrese el nombre del estado" required aria-describedby="invitationNameHelp">
                        </div>
                        <div id="invitationNameHelp" class="form-text">El nombre debe ser único y descriptivo.</div>
                        <div class="invalid-feedback">
                            Por favor, introduce un nombre válido.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="invitationDescription" class="form-label">Descripción</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-file-earmark-text"></i></span>
                            <textarea class="form-control" id="invitationDescription" rows="3" placeholder="Describa el estado" required aria-describedby="invitationDescriptionHelp"></textarea>
                        </div>
                        <div id="invitationDescriptionHelp" class="form-text">Máximo 250 caracteres.</div>
                        <div class="invalid-feedback">
                            Por favor, introduce una descripción válida.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="invitationActive" class="form-label">Estado</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-check-circle"></i></span>
                            <select class="form-select" id="invitationActive" required>
                                <option value="" disabled selected>Seleccione...</option>
                                <option value="1">Sí</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                        <div class="invalid-feedback">
                            Por favor, selecciona una opción.
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="saveInvitationStatusButton" onclick="saveInvitationStatus()">
                    <i class="bi bi-save"></i> Guardar
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Nuevo Grupo -->
<div class="modal fade" id="createGroupModal" tabindex="-1" aria-labelledby="createGroupModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content shadow-lg">
            <div class="modal-header bg-gradient-primary ">
                <h5 class="modal-title" id="createGroupModalLabel">
                    <i class="bi bi-plus-circle me-2"></i>
                    Crear Nuevo Grupo
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="createGroupForm" enctype="multipart/form-data">
                <div class="mb-4">
                        <label for="selectedUser" class="form-label">Seleccionar Usuario *</label>
                        <select class="form-select" id="selectedUser" name="selectedUser" required>
                            <option value="<?php echo $currentUser; ?>" selected>
                                Asignar a mí
                            </option>
                            <?php foreach ($userList as $user): ?>
                                <?php if($user->getUserId() !== $currentUser): ?>
                                    <option value="<?php echo $user->getUserId(); ?>">
                                        <?php echo $user->getEmail(); ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label for="groupName" class="form-label">Nombre del Grupo <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="groupName" name="groupName" placeholder="Ingrese el nombre del grupo" required>
                        <div class="invalid-feedback">Por favor, ingrese un nombre válido para el grupo.</div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="groupType" class="form-label">Tipo de Grupo <span class="text-danger">*</span></label>
                        <select class="form-select" id="groupType" name="groupType" required>
                            <option value="" disabled selected>Seleccione un tipo de grupo</option>
                            <?php foreach ($groupTypeList as $type): ?>
                                <?php if ($type->getIsActive() === '1'): ?>
                                    <option value="<?php echo $type->getId(); ?>">
                                        <?php echo $type->getName(); ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">Por favor, seleccione un tipo de grupo.</div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="groupPrivacy" class="form-label">Privacidad del Grupo <span class="text-danger">*</span></label>
                        <select class="form-select" id="groupPrivacy" name="groupPrivacy" required>
                            <option value="" disabled selected>Seleccione la privacidad</option>
                            <?php foreach ($groupPrivacyList as $privacy): ?>
                                <?php if ($privacy->getIsActive() === '1'): ?>
                                    <option value="<?php echo $privacy->getId(); ?>">
                                        <?php echo $privacy->getName(); ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">Por favor, seleccione la privacidad del grupo.</div>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label">Descripción <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Describa el grupo" required></textarea>
                        <div class="invalid-feedback">Por favor, ingrese una descripción del grupo.</div>
                    </div>

                    <div class="mb-4">
                        <label for="rules" class="form-label">Reglas <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="rules" name="rules" rows="3" placeholder="Escriba las reglas del grupo" required></textarea>
                        <div class="invalid-feedback">Por favor, defina las reglas del grupo.</div>
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
                        <input type="file" class="form-control" id="groupImage" name="groupImage[]" accept="image/*" multiple>
                        <div class="form-text">Puede subir una imagen</div>
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
<!-- Modal Cambiar Estado de Grupo -->
<div class="modal fade" id="changeGroupStatusModal" tabindex="-1" aria-labelledby="changeGroupStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content shadow-lg border-0 rounded-3">
            <div class="modal-header bg-gradient-primary">
                <h5 class="modal-title d-flex align-items-center" id="changeGroupStatusModalLabel">
                    <i class="bi bi-pencil-square me-2"></i>
                    <span>Cambiar Estado del Grupo</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="changeGroupStatusForm">
                    <div class="mb-4">
                        <label for="groupStatus" class="form-label fw-bold">Nuevo Estado del Grupo</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-primary">
                                <i class="bi bi-info-circle-fill text-primary"></i>
                            </span>
                            <select class="form-select border-primary" id="groupStatus" name="groupStatus" required>
                                <option value="" disabled selected>Seleccione un estado</option>
                                <?php foreach ($groupStatusList as $status): ?>
                                    <?php if ($status->getIsActive() === '1'): ?>
                                        <option value="<?php echo $status->getId(); ?>">
                                            <?php echo $status->getName(); ?>
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" id="groupId" name="groupId">
                    <div class="text-center">
                        <button type="button" class="btn btn-success w-100 fw-bold" id="updateGroupStatusButton" onclick="updateGroupStatus()">
                            <i class="bi bi-arrow-repeat me-2"></i> Actualizar Estado
                        </button>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Editar Grupo -->
<div class="modal fade" id="editGroupModal" tabindex="-1" aria-labelledby="editGroupModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content shadow-lg">
            <div class="modal-header bg-gradient-primary text-primary">
                <h5 class="modal-title" id="editGroupModalLabel">
                    <i class="bi bi-pencil-square me-2"></i> Editar Grupo
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editGroupForm" enctype="multipart/form-data">
                    <input type="hidden" id="groupIDEdit" name="groupID">
                    <div class="mb-4">
                        <label for="groupName" class="form-label">Nombre del Grupo *</label>
                        <input type="text" class="form-control" id="groupNameEdit" name="groupName" required>
                    </div>
                    <div class="mb-4">
                        <label for="groupType" class="form-label">Tipo de Grupo *</label>
                        <select class="form-select" id="groupTypeEdit" name="groupType" required>
                            <option value="">Seleccione un tipo de grupo</option>
                            <?php foreach ($groupTypeList as $type): ?>
                                <?php if ($type->getIsActive() === '1'): ?>
                                    <option value="<?php echo $type->getId(); ?>">
                                        <?php echo $type->getName(); ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="groupPrivacy" class="form-label">Privacidad del Grupo *</label>
                        <select class="form-select" id="groupPrivacyEdit" name="groupPrivacy" required>
                            <option value="">Seleccione la privacidad</option>
                            <?php foreach ($groupPrivacyList as $privacy): ?>
                                <?php if ($privacy->getIsActive() === '1'): ?>
                                    <option value="<?php echo $privacy->getId(); ?>">
                                        <?php echo $privacy->getName(); ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="description" class="form-label">Descripción *</label>
                        <textarea class="form-control" id="descriptionEdit" name="description" rows="3" required></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="rules" class="form-label">Reglas *</label>
                        <textarea class="form-control" id="rulesEdit" name="rules" rows="3" required></textarea>
                    </div>
                    <div id="startDateContainer" class="mb-4" style="display: none;">
                        <label for="startDateEdit" class="form-label">Fecha de Inicio</label>
                        <input type="date" class="form-control" id="startDateEdit" name="start_date">
                    </div>
                    <div id="endDateContainer" class="mb-4" style="display: none;">
                        <label for="endDateEdit" class="form-label">Fecha de Fin</label>
                        <input type="date" class="form-control" id="endDateEdit" name="end_date">
                    </div>
                    <div class="mb-4">
                        <label for="groupImage" class="form-label">Imagen del Grupo</label>
                        <input type="file" class="form-control" id="groupImageEdit" name="groupImage[]" accept="image/*">
                    </div>
                    <div class="mb-4 w-100 d-flex align-items-center justify-content-center">
                        <div id="imagePreviewEdit" class="border p-3" style="height: 200px; display: none;">
                            <img id="previewImgEdit" src="" alt="Vista previa de la imagen" style="max-width: 100%; max-height: 100%;">
                        </div>
                    </div>
                    <div class="container text-center">
                        <button type="button" class="btn btn-primary w-100" id="saveGroupButton" onclick="updateGroup()">Guardar Cambios</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!--Modales-->

<script>
    const groupListJson = <?php echo $groupListJson; ?>;
    //Mostrar o esconder las fechas del newGroup o editGroup
    document.addEventListener('DOMContentLoaded', function() {
        const groupTypeSelect = document.getElementById('groupType');
        const startDateContainer = document.getElementById('dateFields');
        const endDateContainer = document.getElementById('dateFieldsEnd');
        const startDateInput = document.getElementById('startDate');
        const endDateInput = document.getElementById('endDate');

        const today = new Date().toISOString().split('T')[0];
        startDateInput.setAttribute('min', today);
        endDateInput.setAttribute('min', today);

        function toggleDateFields() {
            if (groupTypeSelect.value === '6') { 
                startDateContainer.style.display = 'block';
                endDateContainer.style.display = 'block';
                startDateInput.setAttribute('required', 'required');
                endDateInput.setAttribute('required', 'required');
            } else {
                startDateContainer.style.display = 'none';
                endDateContainer.style.display = 'none';
                startDateInput.removeAttribute('required');
                endDateInput.removeAttribute('required');
            }
        }
        groupTypeSelect.addEventListener('change', toggleDateFields);
        toggleDateFields();
    });
    //Cambiar el valor de la variable de currentUser
    document.getElementById('selectedUser').addEventListener('change', function() {
        var selectedUserID = this.value;
        document.getElementById('currentUserID').value = selectedUserID;
    });
    //Inciialización de las tablas con dataTable
    $(document).ready(function() {
        initializeDataTable('#groupStatusTable', 'Group_Status_List');
        initializeDataTable('#groupPrivacyTable', 'Group_Privacy_List');
        initializeDataTable('#groupRoleTable', 'Group_Role_List');
        initializeDataTable('#groupTypeTable', 'Group_Type_List');
        initializeDataTable('#groupInvitationTable', 'Group_Invitation_List');
        initializeDataTable('#groupTable', 'Groups_List');
        createGroup();
        /*Para controlar el cambio de imagen en modificar grupo */
        $('#groupImageEdit').change(function(event) {
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#imagePreviewEdit').show(); 
                    $('#previewImgEdit').attr('src', e.target.result);
                };

                reader.readAsDataURL(file); 
            } else {
                $('#imagePreviewEdit').hide(); 
            }
        });
    });
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

    //Configuración de las tablas, paginado y opciones de exportación
    function initializeDataTable(tableId, exportFileName) {
        $(tableId).DataTable({
            dom: 'Bfrtip',
            pageLength: 5,
            buttons: [
                {
                    extend: 'excelHtml5',
                    title: exportFileName,
                    exportOptions: {
                        columns: ':not(:last-child)'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    title: exportFileName,
                    exportOptions: {
                        columns: ':not(:last-child)'
                    }
                },
                {
                    extend: 'csvHtml5',
                    title: exportFileName,
                    exportOptions: {
                        columns: ':not(:last-child)'
                    }
                },
                {
                    extend: 'print',
                    title: exportFileName,
                    exportOptions: {
                        columns: ':not(:last-child)'
                    }
                },
                'copy', 
                'colvis' 
            ],"language": {
                "lengthMenu": "Mostrar _MENU_ registros por página",
                "zeroRecords": "No se encontraron resultados",
                "info": "Mostrando página _PAGE_ de _PAGES_",
                "infoEmpty": "No hay registros disponibles",
                "infoFiltered": "(filtrado de _MAX_ registros totales)",
                "search": "Buscar:",
                "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                }
            },
            columnDefs: [
                { targets: 'no-sort', orderable: false }, 
            ],
            order: [[0, 'asc']],
        });
    }
    // Modal de eliminación
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            setChangeModalData(button);
        });
    });
    //Modal para cambiar de estado el grupo
    function openChangeGroupStatusModal(button) {
        var groupId = $(button).data('id');
        var currentStatusId = $(button).data('current-status'); 

        $('#groupId').val(groupId);
        $('#groupStatus').val(currentStatusId); 
        
        $('#changeGroupStatusModal').modal('show');
    }

    //Flujo de cambiar estado
    function setChangeModalData(button) {
        const id = button.getAttribute('data-id');
        const title = button.getAttribute('data-title');
        const description = button.getAttribute('data-description');
        const name = button.getAttribute('data-name');
        const detail = button.getAttribute('data-detail');
        const modalType = button.getAttribute('data-type');
        const modalStatus = button.getAttribute('data-status');


        const modalTitle = modalStatus == 1 ? "Eliminar Elemento" : "Reactivar Elemento";
        const actionText = modalStatus == 1 ? "eliminar" : "reactivar";
        const iconClass = modalStatus == 1 ? "bi bi-exclamation-triangle-fill text-danger" : "bi bi-arrow-clockwise text-success";
        const confirmButtonClass = modalStatus == 1 ? "btn-danger" : "btn-success";
        const modalBodyText = modalStatus == 1 
            ? `¡Advertencia! Estás a punto de eliminar <strong>${name}</strong>. Esta acción no se puede deshacer.`
            : `Vas a reactivar <strong>${name}</strong>. Esto restaurará su estado activo. ¿Estás seguro de que deseas continuar?`;

            const modalHtml = `
                    <div class="modal fade" id="changeStatusModal" tabindex="-1" aria-labelledby="changeStatusModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content shadow-lg rounded border-0">
                                <div class="modal-header bg-${modalStatus == 1 ? 'danger' : 'success'} text-white">
                                    <h5 class="modal-title d-flex align-items-center">
                                        <span class="me-2"><i class="${iconClass} fs-4"></i></span>
                                        <strong>${modalTitle}</strong>
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="alert alert-${modalStatus == 1 ? 'danger' : 'success'}" role="alert">
                                        <p class="fw-bold">${modalStatus == 1 ? "¡Advertencia!" : "Información Importante!"}</p>
                                        <p>${modalBodyText}</p>
                                    </div>
                                    <hr>
                                    <div class="mb-3">
                                        <h6 class="text-primary">Detalle</h6>
                                        <div class="p-3 border rounded bg-light">
                                            <p class="mb-0"><small>${detail}</small></p>
                                        </div>
                                    </div>
                                    <hr>
                                    <p class="fw-bold text-center">¿Estás seguro de que deseas <span class="text-${modalStatus == 1 ? 'danger' : 'success'}"><strong>${actionText}</strong></span> <span class="text-${modalStatus == 1 ? 'danger' : 'success'}"><strong>${name}</strong></span>?</p>
                                </div>
                                <div class="modal-footer d-flex flex-wrap align-items-center justify-content-center">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        <i class="bi bi-x-circle me-1"></i>Cancelar
                                    </button>
                                    <button type="button" class="btn ${confirmButtonClass} px-4" id="confirmChangeStatusButton">
                                        <i class="${modalStatus == 1 ? 'bi bi-trash-fill' : 'bi bi-check-circle'} me-1"></i>
                                        ${actionText.charAt(0).toUpperCase() + actionText.slice(1)}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
        document.body.insertAdjacentHTML('beforeend', modalHtml);

        const modalElement = document.getElementById('changeStatusModal');
        const bootstrapModal = new bootstrap.Modal(modalElement);
        bootstrapModal.show();

        document.getElementById('confirmChangeStatusButton').onclick = function() {
            changeStatus(id, modalType, modalStatus);
            bootstrapModal.hide();
            modalElement.remove();
        };

        modalElement.addEventListener('hidden.bs.modal', function() {
            modalElement.remove();
        });
    }

    function changeStatus(id, type, modalStatus) {
        let url = <?php echo FRONT_ROOT ?>;
        modalStatus = parseInt(modalStatus);

        switch (type) {
            case 'statusType':
                url = modalStatus ? url + 'GroupStatus/deleteGroupStatus' : url + 'GroupStatus/reactiveGroupStatus';
                break;
            case 'groupType':
                url = modalStatus ? url + 'GroupType/deleteGroupType' : url + 'GroupType/reactivateGroupType';
                break;
            case 'role':
                url = modalStatus ? url + 'GroupRole/deleteGroupRole' : url + 'GroupRole/reactivateGroupRole';
                break;
            case 'privacy':
                url = modalStatus ? url + 'GroupPrivacy/deleteGroupPrivacy' : url + 'GroupPrivacy/reactivateGroupPrivacy';
                break;
            case 'invitationType':
                url = modalStatus ? url + 'InvitationStatus/deleteInvitationStatus' : url + 'InvitationStatus/reactivateInvitationStatus';
                break;
            default:
                console.error('Tipo no reconocido:', type);
                return;
        }

        const actionText = modalStatus ? 'Eliminar' : 'Reactivar';
        const actionMessage = modalStatus ? "¡Esta acción no se puede deshacer!" : "Esto restaurará el estado activo.";
        const confirmButtonText = modalStatus ? 'Sí, eliminar' : 'Sí, reactivar';
        const confirmButtonColor = modalStatus ? '#d33' : '#28a745';

        Swal.fire({
            title: `¿Estás seguro de que deseas ${actionText.toLowerCase()}?`,
            text: actionMessage,
            icon: 'warning',
            confirmButtonColor: confirmButtonColor,
            showCancelButton: true,
            cancelButtonColor: '#3085d6',
            confirmButtonText: confirmButtonText,
            cancelButtonText: 'Cancelar',
            background: '#f8f9fa',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: { id: id },
                    success: function(response) {
                        console.log(response);
                        response = JSON.parse(response);
                        if (response.success) {
                            Swal.fire({
                                title: `${actionText.charAt(0).toUpperCase() + actionText.slice(1)}!`,
                                text: response.message,
                                icon: 'success',
                                timer: 3000,
                                showConfirmButton: false,
                                willClose: () => {
                                    location.reload();
                                }
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: response.message,
                                icon: 'error',
                                timer: 3000,
                                showConfirmButton: false
                            });
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('AJAX Error:', textStatus, errorThrown);
                        Swal.fire({
                            title: 'Error!',
                            text: 'Ocurrió un error al intentar realizar la acción.',
                            icon: 'error',
                            timer: 3000,
                            showConfirmButton: false
                        });
                    }
                });
            }
        });
    }

    //Flujo para editar los tipos
    function setEditModalData(button) {
        const id = button.getAttribute('data-id');
        const oldName = button.getAttribute('data-name');
        const oldDescription = button.getAttribute('data-description');
        const oldStatus = button.getAttribute('data-status');
        const modalType = button.getAttribute('data-type');
        
        const oldStatusText = oldStatus == 1 ? 'Activo' : 'Inactivo';

        const modalHtml = `
            <div class="modal fade" id="editTypeModal" tabindex="-1" aria-labelledby="editTypeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header d-flex align-items-center justify-content-between">
                            <h5 class="modal-title d-flex align-items-center">
                                <span class="me-2 text-primary"><i class="bi bi-pencil-fill"></i></span>
                                <strong>Modificar ${modalType}</strong>
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                            <form id="editTypeForm">
                                <div class="mb-3">
                                    <label for="oldName" class="form-label"><i class="bi bi-tag-fill me-2 text-secondary"></i>Nombre Actual</label>
                                    <input type="text" class="form-control" id="oldName" value="${oldName}" disabled>
                                </div>
                                <div class="mb-3">
                                    <label for="newName" class="form-label"><i class="bi bi-tag me-2 text-primary"></i>Nuevo Nombre</label>
                                    <input type="text" class="form-control" id="newName" value="${oldName}" required>
                                    <button type="button" class="btn btn-link" id="copyNameButton"><i class="bi bi-clipboard me-1"></i>Copiar Nombre Actual</button>
                                </div>
                                <div class="mb-3">
                                    <label for="oldDescription" class="form-label"><i class="bi bi-chat-left-text-fill me-2 text-secondary"></i>Descripción Actual</label>
                                    <textarea class="form-control" id="oldDescription" rows="3" disabled>${oldDescription}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="newDescription" class="form-label"><i class="bi bi-chat-left-text me-2 text-primary"></i>Nueva Descripción</label>
                                    <textarea class="form-control" id="newDescription" rows="3" required>${oldDescription}</textarea>
                                    <button type="button" class="btn btn-link" id="copyDescriptionButton"><i class="bi bi-clipboard me-1"></i>Copiar Descripción Actual</button>
                                </div>
                                <div class="mb-3">
                                    <label for="oldStatus" class="form-label"><i class="bi bi-toggle-on me-2 text-secondary"></i>Estado Actual</label>
                                    <input type="text" class="form-control" id="oldStatus" value="${oldStatusText}" disabled>
                                </div>
                                <div class="mb-3">
                                    <label for="newStatus" class="form-label"><i class="bi bi-toggle-off me-2 text-primary"></i>Nuevo Estado</label>
                                    <select class="form-select" id="newStatus" required>
                                        <option value="1" ${oldStatus == 1 ? 'selected' : ''}>Activo</option>
                                        <option value="0" ${oldStatus == 0 ? 'selected' : ''}>Inactivo</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle me-1"></i>Cancelar
                            </button>
                            <button type="button" class="btn btn-primary" id="confirmEditButton">
                                <i class="bi bi-check-circle me-1"></i>Guardar Cambios
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            `;
        document.body.insertAdjacentHTML('beforeend', modalHtml);

        const modalElement = document.getElementById('editTypeModal');
        const bootstrapModal = new bootstrap.Modal(modalElement);
        bootstrapModal.show();

        document.getElementById('copyNameButton').onclick = function() {
            document.getElementById('newName').value = document.getElementById('oldName').value;
        };
        document.getElementById('copyDescriptionButton').onclick = function() {
            document.getElementById('newDescription').value = document.getElementById('oldDescription').value;
        };

        document.getElementById('confirmEditButton').onclick = function() {
            editType(id, modalType);
            bootstrapModal.hide();
            modalElement.remove();
        };

        modalElement.addEventListener('hidden.bs.modal', function() {
            modalElement.remove();
        });
    }
    function editType(id, type) {
        let url = <?php echo FRONT_ROOT ?>;
        const newName = document.getElementById('newName').value;
        const newDescription = document.getElementById('newDescription').value;
        const newStatus = document.getElementById('newStatus').value;
        actionText = 'Actualizado!';
        switch (type) {
            case 'statusType':
                url += 'GroupStatus/updateGroupStatus';
                break;
            case 'groupType':
                url += 'GroupType/updateGroupType';
                break;
            case 'role':
                url += 'GroupRole/updateGroupRole';
                break;
            case 'privacy':
                url += 'GroupPrivacy/updateGroupPrivacy';
                break;
            case 'invitationType':
                url += 'InvitationStatus/updateInvitationStatus';
                break;
            default:
                console.error('Tipo no reconocido:', type);
                return;
        }
        const formData = {
            id: id,
            name: newName,
            description: newDescription,
            status: newStatus
        };
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Estás a punto de actualizar los datos.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, actualizar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                            response = JSON.parse(response);
                            if (response.success) {
                            Swal.fire({
                                title: `${actionText.charAt(0).toUpperCase() + actionText.slice(1)}!`,
                                text: response.message,
                                icon: 'success',
                                timer: 3000,
                                showConfirmButton: false,
                                willClose: () => {
                                    location.reload();
                                }
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: response.message,
                                icon: 'error',
                                timer: 3000,
                                showConfirmButton: false
                            });
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('AJAX Error:', textStatus, errorThrown);
                        Swal.fire(
                            'Error!',
                            'Ocurrió un error al intentar actualizar.',
                            'error'
                        );
                    }
                });
            }
        });
    }
    // Funcion para guardar el nuevo estado de grupo
    function saveGroupStatus() {
        const statusName = document.getElementById('statusName').value.trim();
        const statusDescription = document.getElementById('statusDescription').value.trim();
        const statusActive = document.getElementById('statusActive').value;

        if (!statusName || !statusDescription || !statusActive) {
            Swal.fire({
                title: 'Error',
                text: 'Por favor, completa todos los campos.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
            return;
        }
        $.ajax({
            url: '<?php echo FRONT_ROOT; ?>GroupStatus/createGroupStatus',
            type: 'POST',
            data: {
                name: statusName,
                description: statusDescription,
                status: statusActive
            },
            success: function(response) {
                const result = JSON.parse(response);
                if (result.success) {
                    Swal.fire({
                        title: '¡Éxito!',
                        text: result.message,
                        icon: 'success',
                        confirmButtonText: 'Aceptar',
                    }).then(() => {
                        location.reload(); 
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: result.message,
                        icon: 'error',
                        confirmButtonText: 'Aceptar',
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('AJAX Error:', textStatus, errorThrown);
                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un error al intentar guardar.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar',
                });
            }
        });
    }
    function saveGroupPrivacy() {
        const privacyName = document.getElementById('privacyName').value.trim();
        const privacyDescription = document.getElementById('privacyDescription').value.trim();
        const privacyActive = document.getElementById('privacyActive').value;

        if (!privacyName || !privacyDescription || !privacyActive) {
            Swal.fire({
                title: 'Error',
                text: 'Por favor, completa todos los campos.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
            return;
        }

        $.ajax({
            url: '<?php echo FRONT_ROOT; ?>GroupPrivacy/createGroupPrivacy',
            type: 'POST',
            data: {
                name: privacyName,
                description: privacyDescription,
                status: privacyActive
            },
            success: function(response) {
                const result = JSON.parse(response);
                if (result.success) {
                    Swal.fire({
                        title: '¡Éxito!',
                        text: result.message,
                        icon: 'success',
                        confirmButtonText: 'Aceptar',
                    }).then(() => {
                        location.reload(); 
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: result.message,
                        icon: 'error',
                        confirmButtonText: 'Aceptar',
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('AJAX Error:', textStatus, errorThrown);
                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un error al intentar guardar.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar',
                });
            }
        });
    }
    function saveGroupRole() {
        const roleName = document.getElementById('roleName').value.trim();
        const roleDescription = document.getElementById('roleDescription').value.trim();
        const roleActive = document.getElementById('roleActive').value;

        if (!roleName || !roleDescription || !roleActive) {
            Swal.fire({
                title: 'Error',
                text: 'Por favor, completa todos los campos.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
            return;
        }

        $.ajax({
            url: '<?php echo FRONT_ROOT; ?>GroupRole/createGroupRole',
            type: 'POST',
            data: {
                name: roleName,
                description: roleDescription,
                status: roleActive
            },
            success: function(response) {
                const result = JSON.parse(response);
                if (result.success) {
                    Swal.fire({
                        title: '¡Éxito!',
                        text: result.message,
                        icon: 'success',
                        confirmButtonText: 'Aceptar',
                    }).then(() => {
                        location.reload(); 
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: result.message,
                        icon: 'error',
                        confirmButtonText: 'Aceptar',
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('AJAX Error:', textStatus, errorThrown);
                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un error al intentar guardar.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar',
                });
            }
        });
    }
    function saveGroupType() {
        const typeName = document.getElementById('typeName').value.trim();
        const typeDescription = document.getElementById('typeDescription').value.trim();
        const typeActive = document.getElementById('typeActive').value;

        if (!typeName || !typeDescription || !typeActive) {
            Swal.fire({
                title: 'Error',
                text: 'Por favor, completa todos los campos.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
            return;
        }

        $.ajax({
            url: '<?php echo FRONT_ROOT; ?>GroupType/createGroupType',
            type: 'POST',
            data: {
                name: typeName,
                description: typeDescription,
                status: typeActive
            },
            success: function(response) {
                const result = JSON.parse(response);
                if (result.success) {
                    Swal.fire({
                        title: '¡Éxito!',
                        text: result.message,
                        icon: 'success',
                        confirmButtonText: 'Aceptar',
                    }).then(() => {
                        location.reload(); 
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: result.message,
                        icon: 'error',
                        confirmButtonText: 'Aceptar',
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('AJAX Error:', textStatus, errorThrown);
                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un error al intentar guardar.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar',
                });
            }
        });
    }
    function saveInvitationStatus() {
        const invitationName = document.getElementById('invitationName').value.trim();
        const invitationDescription = document.getElementById('invitationDescription').value.trim();
        const invitationActive = document.getElementById('invitationActive').value;

        if (!invitationName || !invitationDescription || !invitationActive) {
            Swal.fire({
                title: 'Error',
                text: 'Por favor, completa todos los campos.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
            return;
        }

        $.ajax({
            url: '<?php echo FRONT_ROOT; ?>InvitationStatus/createInvitationStatus',
            type: 'POST',
            data: {
                name: invitationName,
                description: invitationDescription,
                status: invitationActive
            },
            success: function(response) {
                const result = JSON.parse(response);
                if (result.success) {
                    Swal.fire({
                        title: '¡Éxito!',
                        text: result.message,
                        icon: 'success',
                        confirmButtonText: 'Aceptar',
                    }).then(() => {
                        location.reload(); 
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: result.message,
                        icon: 'error',
                        confirmButtonText: 'Aceptar',
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('AJAX Error:', textStatus, errorThrown);
                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un error al intentar guardar.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar',
                });
            }
        });
    }
    function createGroup() {
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
            // Validaciones
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
            } else if (groupType === '6') {
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
                if (startDate >= endDate) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error de fechas',
                            color: "#716add",
                            text: 'La fecha de fin no puede ser menor o igual a la fecha de inicio.',
                            confirmButtonText: 'Aceptar'
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
                    console.log(response);
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
    }
    function updateGroupStatus() {
        var groupId = $('#groupId').val();
        var newStatusId = $('#groupStatus').val();
        if (!newStatusId) {
            Swal.fire({
                icon: 'warning',
                title: 'Selección de Estado',
                text: 'Por favor, seleccione un nuevo estado.',
                confirmButtonText: 'Aceptar',
            });
            return;
        }

        $.ajax({
            url: '<?php echo FRONT_ROOT ?>Group/changeStatusGroup',
            type: 'POST',
            data: {
                groupID: groupId,
                status: newStatusId
            },
            success: function(response) {
                response = JSON.parse(response);
                if (response.success) {
                    $('#changeGroupStatusModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: 'Estado del grupo actualizado con éxito.',
                        confirmButtonText: 'Aceptar',
                    }).then(() => {
                        location.reload(); 
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error al actualizar',
                        text: 'Error al actualizar el estado del grupo: ' + response.message,
                        confirmButtonText: 'Aceptar',
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error en la solicitud',
                    text: 'Ocurrió un error en la solicitud: ' + error,
                    confirmButtonText: 'Aceptar',
                });
            }
        });
    }
    //Carga dinamicamente la información del modal
    function loadGroupDetails(groupId) {
        $.ajax({
            url: '<?php echo FRONT_ROOT ?>Group/getGroupByID',
            type: 'POST',
            data: { id: groupId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                const group = response;
                const modalContent = `
                        <div class="modal fade" id="modalGroup${group.data.id}" tabindex="-1" aria-labelledby="groupModalLabel${group.data.id}" aria-hidden="true">
                            <div class="modal-dialog modal-xl modal-dialog-centered">
                                <div class="modal-content shadow-lg border-0 rounded">
                                    <div class="modal-header bg-gradient-primary text-white py-4">
                                        <span class="badge bg-${group.data.statusId.id == "1" ? 'success' : 'warning'} me-3 p-2" style="font-size: 1rem;">
                                            ${group.data.statusId.name}
                                        </span>
                                        <h5 class="modal-title d-flex align-items-center" id="groupModalLabel${group.data.id}">
                                            <div class="container d-flex align-items-center justify-content-center">
                                                <p class="fs-5 fw-bold text-black mb-0 groupNameDisplay${group.data.id}">${group.data.name}</p>
                                            </div>
                                        </h5>
                                        <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                    </div>

                                    <div class="modal-body" id="modalBody${group.data.id}">
                                        <div class="text-center mb-4">
                                            ${group.data.groupInfo.image ? 
                                                `<img src="<?php echo FRONT_ROOT ?>${group.data.groupInfo.image}" class="img-fluid img-thumbnail shadow-lg rounded-circle border border-light" style="width: 300px; height: 300px; object-fit: cover;">` : 
                                                '<h3>Sin imagen</h3>'}
                                            <h6 class="mt-3 text-muted">Imagen del Grupo</h6>
                                        </div>

                                        <div class="container">
                                            <div class="row mb-4">
                                                <div class="col text-center">
                                                    <h6 class="text-muted"><i class="bi bi-person-circle me-2"></i>Creador del Grupo</h6>
                                                    <div class="card shadow-sm mt-3">
                                                        <div class="card-body d-flex align-items-center flex-column">
                                                            <img src="<?php echo FRONT_ROOT ?>${group.data.created_by.image}" class="rounded-circle mb-3" style="width: 100px; height: 100px; object-fit: cover;">
                                                            <div class="text-center">
                                                                <p class="mb-0 fw-bold">${group.data.created_by.firstName} ${group.data.created_by.lastName}</p>
                                                                <p class="mb-0"><i class="bi bi-telephone me-2"></i>Teléfono: ${group.data.created_by.cellPhone}</p>
                                                                <p class="mb-0"><i class="bi bi-envelope me-2"></i>Email: ${group.data.created_by.email}</p>
                                                                <p class="mb-0"><i class="bi bi-info-circle me-2"></i>Descripción: ${group.data.created_by.userDescription}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-4 text-center">
                                                <div class="col-md-6">
                                                    <h6 class="text-muted"><i class="bi bi-type"></i> Tipo de Grupo</h6>
                                                    <div class="card shadow-sm mt-3">
                                                        <div class="card-body">
                                                            <p class="fw-bold">${group.data.groupType.name}</p>
                                                            <p>${group.data.groupType.description}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6 class="text-muted"><i class="bi bi-check-circle"></i> Estado del Grupo</h6>
                                                    <div class="card shadow-sm mt-3">
                                                        <div class="card-body">
                                                            <p class="fw-bold">${group.data.statusId.name}</p>
                                                            <p>${group.data.statusId.description}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <h6 class="text-muted"><i class="bi bi-shield-lock"></i> Privacidad del Grupo</h6>
                                                    <div class="card shadow-sm mt-3">
                                                        <div class="card-body">
                                                            <p class="fw-bold">${group.data.groupPrivacy.name}</p>
                                                            <p>${group.data.groupPrivacy.description}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-4">
                                                <div class="container d-flex align-items-center justify-content-center my-2">
                                                    <h6 class="text-muted m-0"><i class="bi bi-info-circle"></i> Detalles del grupo</h6>
                                                </div>
                                                <div class="col-12">
                                                    <h6 class="text-muted m-0"><i class="bi bi-info-circle"></i> Descripción del Grupo</h6>
                                                    <div class="card shadow-sm mb-3">
                                                        <div class="card-body">
                                                            <p class="mb-0">${group.data.groupInfo.description || 'Sin descripción disponible'}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-4">
                                                <div class="col-12">
                                                    <h6 class="text-muted m-0"><i class="bi bi-file-earmark-text"></i> Reglas del Grupo</h6>
                                                    <div class="card shadow-sm mb-3">
                                                        <div class="card-body">
                                                            <p class="mb-0">${group.data.groupInfo.rules || 'Sin reglas disponibles'}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            ${group.data.groupType.id === "6" ? `
                                            <div class="container mt-4">
                                                <hr>
                                                <div class="container d-flex flex-wrap justify-content-center align-items-center">
                                                    <h6 class="text-muted mx-0"><i class="bi bi-calendar-check"></i> Disponibilidad del Grupo</h6>
                                                </div>
                                                <div class="row justify-content-center">
                                                    <div class="col-md-6 text-center">
                                                        <div class="card border-light shadow-sm">
                                                            <div class="card-body">
                                                                <h5 class="card-title">Fecha de Inicio</h5>
                                                                <p class="card-text fw-bold fs-5 text-primary" id="startDate${group.data.id}">${group.data.groupInfo.start_date || 'No disponible'}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 text-center">
                                                        <div class="card border-light shadow-sm">
                                                            <div class="card-body">
                                                                <h5 class="card-title">Fecha de Finalización</h5>
                                                                <p class="card-text fw-bold fs-5 text-primary" id="endDate${group.data.id}">${group.data.groupInfo.end_date || 'No disponible'}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>` : ''}

                                        </div>
                                    </div>

                                    <div class="modal-footer d-flex flex-wrap align-items-center justify-content-between">
                                        <div class="row mb-4">
                                            <div class="col">
                                                <p class="border rounded p-3 shadow-sm"><span class="text-muted">Fecha de Creación :</span> ${new Date(group.data.created_at).toLocaleDateString()}</p>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;

                    $('body').append(modalContent);
                    
                    $('#modalGroup' + group.data.id).modal('show');

                    $('#modalGroup' + group.data.id).on('hidden.bs.modal', function () {
                        $(this).remove();
                    });
                } else {
                    alert('Error al cargar los detalles del grupo.');
                }
            },
            error: function() {
                alert('Error de conexión.');
            }
        });
    }
    $('#modalGroup<?php echo $group->getId(); ?>').on('show.bs.modal', function () {
        const groupId = <?php echo $group->getId(); ?>;
        loadGroupDetails(groupId);
    });
    //Cargar vista de editar grupo
    function loadGroupData(groupId) {
        $.ajax({
            url: '<?php echo FRONT_ROOT ?>Group/getGroupByID',
            type: 'POST',
            data: { groupId: groupId },
            success: function(response) {
                const group = JSON.parse(response);

                $('#groupIDEdit').val(group.data.id);
                $('#groupNameEdit').val(group.data.name);
                $('#groupTypeEdit').val(group.data.groupType.id);
                $('#groupPrivacyEdit').val(group.data.groupPrivacy.id);
                $('#descriptionEdit').val(group.data.groupInfo.description);
                $('#rulesEdit').val(group.data.groupInfo.rules);
                $('#startDateEdit').val(group.data.groupInfo.start_date);
                $('#endDateEdit').val(group.data.groupInfo.end_date);
                if (group.data.groupType.id == 6) {
                    $('#startDateEdit').closest('.mb-4').show(); 
                    $('#endDateEdit').closest('.mb-4').show();
                } else {
                    $('#startDateEdit').closest('.mb-4').hide(); 
                    $('#endDateEdit').closest('.mb-4').hide();
                }
                if (group.data.groupInfo.image) {
                    $('#imagePreviewEdit').show();
                    $('#previewImgEdit').attr('src', '<?php echo FRONT_ROOT?>' + group.data.groupInfo.image);
                } else {
                    $('#imagePreviewEdit').hide();
                }
                $('#editGroupModal').modal('show');
            },
            error: function(err) {
                console.error("Error al cargar el grupo: ", err);
            }
        });
    }
    function updateGroup() {
        const formData = new FormData($('#editGroupForm')[0]);
        let startDate, endDate;
        for (const [key, value] of formData) {
            if (key === 'start_date') {
                startDate = value;
            } else if (key === 'end_date') {
                endDate = value;
            }
        }
        if (startDate && endDate) {
            if (new Date(endDate) <= new Date(startDate)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de fechas',
                    color: "#716add",
                    text: 'La fecha de fin no puede ser menor o igual a la fecha de inicio.',
                    confirmButtonText: 'Aceptar'
                });
                return;
            }
        }
        $.ajax({
            url: '<?php echo FRONT_ROOT ?>Group/updateGroup',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                console.log(response);
                const result = JSON.parse(response);
                if (result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: result.message,
                        confirmButtonText: 'Aceptar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                    $('#editGroupModal').modal('hide');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: result.message,
                        confirmButtonText: 'Aceptar'
                    });
                }
            },
            error: function(err) {
                console.error("Error al actualizar el grupo: ", err);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error al intentar actualizar el grupo.',
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    }

    function loadGroupMembers(groupId) {
        $.ajax({
            url: '<?php echo FRONT_ROOT ?>Member/getAllMembersByGroup',
            type: 'POST',
            data: { groupID: groupId },
            dataType: 'json',
            success: function(response) {
                if (response.length > 0) {
                    let membersList = '';
                    response.forEach(member => {
                        const user = member.user;
                        const role = member.role;
                        const isCurrentUser = user.userID == <?php echo $currentUser; ?>;
                        console.log(member.role.id);
                        membersList += `
                            <div class="card mb-3 shadow-sm member" data-email="${user.email}">
                                <div class="card-body d-flex align-items-center">
                                    <img src="<?php echo FRONT_ROOT ?>${user.image}" class="rounded-circle me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                    <div class="flex-grow-1">
                                        <h5 class="mb-0">${isCurrentUser ? 'Tú' : user.firstName + ' ' + user.lastName}</h5>
                                        <p class="mb-0"><i class="bi bi-telephone me-2"></i>${user.cellPhone}</p>
                                        <p class="mb-0"><i class="bi bi-envelope me-2"></i>${user.email}</p>
                                        <small class="text-muted">${role.name}</small>
                                    </div>
                                    <div>
                                        ${member.role.id !== "1" ? 
                                            `${member.status === "1" ? 
                                                `<button class="btn btn-danger btn-sm" onclick="showRemoveModal(${user.userID}, '${user.firstName} ${user.lastName}', ${groupId})"><i class="bi bi-x-circle"></i> Eliminar</button>` :
                                                `<button class="btn btn-success btn-sm" onclick="showReactivateModal(${user.userID}, '${user.firstName} ${user.lastName}', ${groupId})"><i class="bi bi-check-circle"></i> Reactivar</button>`}
                                            <button class="btn btn-warning btn-sm" onclick="showChangeRoleModal(${user.userID},${role.id},${groupId})"><i class="bi bi-pencil"></i> Cambiar Rol</button>` : ''
                                        }
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    const modalContent = `
                        <div class="modal fade" id="modalMembers${groupId}" tabindex="-1" aria-labelledby="membersModalLabel${groupId}" aria-hidden="true">
                            <div class="modal-dialog modal-fullscreen modal-dialog-centered">
                                <div class="modal-content shadow-lg border-0 rounded">
                                    <div class="modal-header bg-gradient-primary py-3">
                                        <h5 class="modal-title" id="membersModalLabel${groupId}">Miembros del Grupo</h5>
                                        <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                    </div>
                                    <div class="modal-body d-flex flex-column">
                                        <div class="mb-3">
                                            <input type="text" id="filterEmail${groupId}" class="form-control" placeholder="Buscar por correo...">
                                        </div>
                                        <div id="noUserFound" class="d-none container bg-light p-4 rounded shadow-sm border border-danger">
                                            <div class="d-flex align-items-center mb-3">
                                                <i class="bi bi-person-x-fill text-danger fs-3 me-2"></i>
                                                <h5 class="text-danger m-0">Usuario no encontrado</h5>
                                            </div>
                                            <p class="text-muted">No hemos podido encontrar el usuario que estás buscando. Puedes intentar nuevamente o invitar a un nuevo usuario a unirse a este grupo.</p>
                                            
                                            <div class="d-flex justify-content-center align-items-center mt-3">
                                                <button class="btn btn-outline-primary" onclick="showInviteUserModal(${groupId})">
                                                    <i class="bi bi-person-plus me-1"></i> Invitar Usuario
                                                </button>
                                            </div>
                                        </div>

                                        <div id="membersListContainer" class="flex-grow-1" style="max-height: 100vh; overflow-y: auto;">
                                            ${membersList || '<p>No hay miembros en este grupo.</p>'}
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;

                    $('#modalMembers' + groupId).remove();

                    $('body').append(modalContent);
                    $('#modalMembers' + groupId).modal('show');

                    const filterInput = document.getElementById(`filterEmail${groupId}`);
                    const membersListContainer = document.getElementById("membersListContainer");
                    const noUserFound = document.getElementById("noUserFound");

                    filterInput.addEventListener("input", function() {
                        const filterValue = filterInput.value.toLowerCase();
                        const members = membersListContainer.getElementsByClassName("member");
                        let foundUser = false;

                        Array.from(members).forEach(member => {
                            const email = member.getAttribute('data-email').toLowerCase();
                            if (email.includes(filterValue)) {
                                member.style.display = '';
                                foundUser = true;
                            } else {
                                member.style.display = 'none';
                            }
                        });

                        noUserFound.classList.toggle('d-none', foundUser);
                    });
                    $('#modalMembers' + groupId).on('hidden.bs.modal', function () {
                        $(this).remove();
                    });
                } else {
                    alert('No se encontraron miembros para este grupo.');
                }
            },
            error: function() {
                alert('Error de conexión.');
            }
        });
    }
    function showInviteUserModal(groupId) {
        let roleOptions = '';
        groupListJson.forEach(role => {
            roleOptions += `<option value="${role.id}">${role.name}</option>`;
        });
        const inviteModal = `
            <div class="modal fade" id="inviteUserModal" tabindex="-1" aria-labelledby="inviteModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-gradient-primary">
                            <h5 class="modal-title" id="inviteModalLabel">Invitar Usuario</h5>
                            <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="inviteEmail" class="form-label">Correo</label>
                                <input type="email" class="form-control" id="inviteEmail" placeholder="Introduce el correo del usuario">
                            </div>
                            <div class="mb-3">
                                <label for="inviteMessage" class="form-label">Mensaje</label>
                                <textarea class="form-control" id="inviteMessage" rows="3" placeholder="Escribe un mensaje opcional"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="inviteRole" class="form-label">Rol</label>
                                <select class="form-select" id="inviteRole">
                                    <option value="">Selecciona un rol</option>
                                    ${roleOptions}
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-primary" onclick="sendInvite('${groupId}')">Enviar Invitación</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        $('body').append(inviteModal);
        $('#inviteUserModal').modal('show');

        $('#inviteUserModal').on('hidden.bs.modal', function () {
            $(this).remove();
        });
    }
    function sendInvite(groupId) {
        const email = document.getElementById('inviteEmail').value.trim();
        const message = document.getElementById('inviteMessage').value;
        const roleInvited = document.getElementById('inviteRole').value;
        const currentUser = document.getElementById('currentUserID').value;
        //console.log(`Invitación enviada a ${email} con el mensaje: "${message}" y rol: ${role}`);
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
        if (roleInvited === '') {
            Swal.fire({
                icon: 'warning',
                title: 'El campo de role no puede ser vacío',
                text: 'Por favor, seleccione un role.',
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

                            // Limpia los campos usando los IDs correctos
                            document.getElementById('inviteEmail').value = '';
                            document.getElementById('inviteMessage').value = '';
                            document.getElementById('inviteRole').value = '';

                            // Cierra el modal
                            $('#inviteUserModal').modal('hide');
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
    //Filtro loadGroupMembers
    document.addEventListener("DOMContentLoaded", function() {
        const filterInput = document.getElementById(`filterEmail${groupId}`);
        const membersListContainer = document.getElementById("membersListContainer");

        filterInput.addEventListener("input", function() {
            const filterValue = filterInput.value.toLowerCase();
            const members = membersListContainer.getElementsByTagName("div");

            Array.from(members).forEach(member => {
                const email = member.querySelector('.email-class').textContent.toLowerCase(); // Cambia '.email-class' por la clase que contiene el correo
                if (email.includes(filterValue)) {
                    member.style.display = '';
                } else {
                    member.style.display = 'none';
                }
            });
        });
    });
    function showRemoveModal(userId, userName,groupID) {
        const modalHtml = `
            <div class="modal fade" id="removeUserModal" tabindex="-1" aria-labelledby="removeUserModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="removeUserModalLabel">Eliminar Usuario</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            ¿Estás seguro de que deseas eliminar a ${userName}?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-danger" onclick="removeUser(${userId},${groupID})">Eliminar</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        $('body').append(modalHtml);
        $('#removeUserModal').modal('show');
    }
    function showReactivateModal(userId, userName,groupID) {
        const modalHtml = `
            <div class="modal fade" id="reactivateUserModal" tabindex="-1" aria-labelledby="reactivateUserModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="reactivateUserModalLabel">Reactivar Usuario</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            ¿Estás seguro de que deseas reactivar a ${userName}?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-success" onclick="reactivateUser(${userId},${groupID})">Reactivar</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        $('body').append(modalHtml);
        $('#reactivateUserModal').modal('show');
    }

    function showChangeRoleModal(userId,currentRoleId,groupID) {
        let roleOptions = '';
        groupListJson.forEach(role => {
            if(role.is_active==='1'){
                const selected = parseInt(role.id) === parseInt(currentRoleId) ? 'selected' : '';
                roleOptions += `<option value="${role.id}" ${selected}>${role.name}</option>`;
            }
        });
        const modalHtml = `
            <div class="modal fade" id="changeRoleModal" tabindex="-1" aria-labelledby="changeRoleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="changeRoleModalLabel">Cambiar Rol de Usuario</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <label for="roleSelect">Selecciona un nuevo rol:</label>
                            <select id="roleSelect" class="form-select">
                                ${roleOptions}
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-primary" onclick="changeUserRole(${userId},${groupID})">Cambiar Rol</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        $('body').append(modalHtml);
        $('#changeRoleModal').modal('show');
    }
    function removeUser(userId, groupID) {
        $.ajax({
            url: '<?php echo FRONT_ROOT ?>Member/deleteMember',
            type: 'POST',
            data: {
                groupId: groupID,
                userID: userId
            },
            success: function(response) {
                response = JSON.parse(response);

                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡El usuario fue eliminado!',
                        text: 'El usuario fue eliminado correctamente.',
                        confirmButtonText: 'Aceptar',
                        timer: 3000,
                        showConfirmButton: false,
                        willClose: () => {
                            $('#removeUserModal').modal('hide');
                            $('#removeUserModal').on('hidden.bs.modal', function () {
                                $(this).remove();
                            });
                            loadGroupMembers(groupID);
                            $('.modal-backdrop').remove();//Elimino todos los modales que queden
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Hubo un problema al intentar eliminar el usuario del grupo.',
                        confirmButtonText: 'Aceptar',
                        timer: 3000,
                        showConfirmButton: false,
                        willClose: () => {
                            $('#removeUserModal').on('hidden.bs.modal', function () {
                                $(this).remove();
                            });
                        }
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

    function reactivateUser(userId, groupID) {
        $.ajax({
            url: '<?php echo FRONT_ROOT ?>Member/reactivateMember',
            type: 'POST',
            data: {
                groupId: groupID,
                userID: userId
            },
            success: function(response) {
                response = JSON.parse(response);
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡El usuario fue reactivado!',
                        text: 'El usuario fue reactivado correctamente.',
                        confirmButtonText: 'Aceptar',
                        timer: 3000,
                        showConfirmButton: false,
                        willClose: () => {
                            $('#reactivateUserModal').modal('hide');
                            $('#reactivateUserModal').on('hidden.bs.modal', function () {
                                $(this).remove();
                            });
                            loadGroupMembers(groupID);
                            $('.modal-backdrop').remove();
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Hubo un problema al intentar reactivar el usuario del grupo.',
                        confirmButtonText: 'Aceptar',
                        timer: 3000,
                        showConfirmButton: false,
                        willClose: () => {
                            $('#reactivateUserModal').on('hidden.bs.modal', function () {
                                $(this).remove(); // Eliminar el modal del dom para evitar que quede por detras del listado
                            });
                        }
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
    function changeUserRole(userId, groupID) {
        const newRole = $('#roleSelect').val();
        $.ajax({
            url: '<?php echo FRONT_ROOT ?>Member/modifyMemberRole',
            type: 'POST',
            data: {
                memberId: userId,
                role: newRole,
                groupID: groupID
            },
            success: function(response) {
                response = JSON.parse(response);
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Rol actualizado con éxito!',
                        text: 'El rol del usuario fue actualizado correctamente.',
                        confirmButtonText: 'Aceptar',
                        timer: 3000,
                        showConfirmButton: false,
                        willClose: () => {
                            $('#changeRoleModal').modal('hide');
                            $('#changeRoleModal').on('hidden.bs.modal', function () {
                            $(this).remove();
                            });
                            loadGroupMembers(groupID);
                            $('.modal-backdrop').remove();
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Hubo un problema al intentar actualizar el rol del usuario.',
                        confirmButtonText: 'Aceptar',
                        timer: 3000,
                        showConfirmButton: false,
                        willClose: () => {
                            $('#changeRoleModal').on('hidden.bs.modal', function () {
                                $(this).remove();
                            });
                        }
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Ocurrió un error al intentar actualizar el rol.',
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    }
    $('#modalGroup<?php echo $group->getId(); ?>').on('show.bs.modal', function () {
        const groupId = <?php echo $group->getId(); ?>;
        loadGroupDetails(groupId);
        loadGroupMembers(groupId);
    });
    $(document).on('click', '#saveGroupButton', function() {
        updateGroup();
    });
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
    function showInvitationsModal(invitations) {
        const updateInvitationList = (filteredInvitations) => {
            const invitationList = filteredInvitations.map(invitation => {
                const imgSrc = invitation.invited_user.image && invitation.invited_user.image.trim() !== "" ? 
                    `<?php echo FRONT_ROOT ?>${invitation.invited_user.image}` : 
                    '<?php echo FRONT_ROOT ?>Upload/UserImages/userDefaultImage.jpg';

                return `
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                        <div class="card shadow border-light rounded" style="cursor: pointer;" onclick="showInvitationDetails(${JSON.stringify(invitation).replace(/"/g, '&quot;')})">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center mb-3">
                                    <img src="${imgSrc}" alt="Imagen del usuario" class="img-fluid rounded-circle me-3" style="width: 50px; height: 50px;">
                                    <div>
                                        <h5 class="card-title mb-0">
                                            <i class="bi bi-person-fill me-2"></i>
                                            ${invitation.invited_user.firstName} ${invitation.invited_user.lastName}
                                        </h5>
                                        <h6 class="card-subtitle mb-1 text-muted">
                                            <i class="bi bi-person-check me-2"></i>
                                            Invitado por: ${invitation.invited_by.firstName} ${invitation.invited_by.lastName}
                                        </h6>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <p class="mb-1">
                                        <i class="bi bi-people-fill me-2"></i>
                                        <strong>Grupo:</strong> ${invitation.groupId.name}
                                    </p>
                                    <p class="mb-1">
                                        <i class="bi bi-chat-dots-fill me-2"></i>
                                        <strong>Mensaje:</strong> ${invitation.message}
                                    </p>
                                    <p class="mb-1">
                                        <i class="bi bi-check-circle-fill me-2"></i>
                                        <strong>Estado:</strong> ${invitation.status.name}
                                    </p>
                                    <p class="mb-1">
                                        <i class="bi bi-file-earmark-person me-2"></i>
                                        <strong>Rol Invitado:</strong> ${invitation.roleInvited.name}
                                    </p>
                                    <p class="mb-1">
                                        <i class="bi bi-calendar-event me-2"></i>
                                        <strong>Enviado el:</strong> ${new Date(invitation.send_at).toLocaleString()}
                                    </p>
                                    ${invitation.responded_at ? 
                                        `<p class="mb-1">
                                            <i class="bi bi-calendar-check me-2"></i>
                                            <strong>Respondido el:</strong> ${new Date(invitation.responded_at).toLocaleString()}
                                        </p>` : 
                                        `<p class="text-warning mb-1">
                                            <i class="bi bi-exclamation-circle me-2"></i> 
                                            <strong>No respondido</strong>
                                        </p>`}
                                </div>
                                
                                <button class="btn btn-primary w-100" onclick="showInvitationDetails(${JSON.stringify(invitation).replace(/"/g, '&quot;')})">
                                    <i class="bi bi-eye-fill me-2"></i>
                                    Ver Detalles
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');

            $('#invitationsModal .modal-body .row').html(invitationList);
        };

        const createModalContent = (invitations) => {
            const invitationList = invitations.map(invitation => {
                const imgSrc = invitation.invited_user.image && invitation.invited_user.image.trim() !== "" ? 
                    `<?php echo FRONT_ROOT ?>${invitation.invited_user.image}` : 
                    '<?php echo FRONT_ROOT ?>Upload/UserImages/userDefaultImage.jpg';

                return `
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                        <div class="card shadow border-light rounded" style="cursor: pointer;" onclick="showInvitationDetails(${JSON.stringify(invitation).replace(/"/g, '&quot;')})">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center mb-3">
                                    <img src="${imgSrc}" alt="Imagen del usuario" class="img-fluid rounded-circle me-3" style="width: 50px; height: 50px;">
                                    <div>
                                        <h5 class="card-title mb-0">
                                            <i class="bi bi-person-fill me-2"></i>
                                            ${invitation.invited_user.firstName} ${invitation.invited_user.lastName}
                                        </h5>
                                        <h6 class="card-subtitle mb-1 text-muted">
                                            <i class="bi bi-person-check me-2"></i>
                                            Invitado por: ${invitation.invited_by.firstName} ${invitation.invited_by.lastName}
                                        </h6>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <p class="mb-1">
                                        <i class="bi bi-people-fill me-2"></i>
                                        <strong>Grupo:</strong> ${invitation.groupId.name}
                                    </p>
                                    <p class="mb-1">
                                        <i class="bi bi-chat-dots-fill me-2"></i>
                                        <strong>Mensaje:</strong> ${invitation.message}
                                    </p>
                                    <p class="mb-1">
                                        <i class="bi bi-check-circle-fill me-2"></i>
                                        <strong>Estado:</strong> ${invitation.status.name}
                                    </p>
                                    <p class="mb-1">
                                        <i class="bi bi-file-earmark-person me-2"></i>
                                        <strong>Rol Invitado:</strong> ${invitation.roleInvited.name}
                                    </p>
                                    <p class="mb-1">
                                        <i class="bi bi-calendar-event me-2"></i>
                                        <strong>Enviado el:</strong> ${new Date(invitation.send_at).toLocaleString()}
                                    </p>
                                    ${invitation.responded_at ? 
                                        `<p class="mb-1">
                                            <i class="bi bi-calendar-check me-2"></i>
                                            <strong>Respondido el:</strong> ${new Date(invitation.responded_at).toLocaleString()}
                                        </p>` : 
                                        `<p class="text-warning mb-1">
                                            <i class="bi bi-exclamation-circle me-2"></i> 
                                            <strong>No respondido</strong>
                                        </p>`}
                                </div>
                                
                                <button class="btn btn-primary w-100" onclick="showInvitationDetails(${JSON.stringify(invitation).replace(/"/g, '&quot;')})">
                                    <i class="bi bi-eye-fill me-2"></i>
                                    Ver Detalles
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');

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
                                    ${invitationList}
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

        $('body').append(createModalContent(invitations));
        
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
</script>
</body>
</html>

