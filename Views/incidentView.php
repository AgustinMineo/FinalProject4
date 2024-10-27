<?php
namespace Views; 
require_once("validate-session.php");

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Incidencias</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>

</style>
<body>
    <div class="container mt-5">
        <div id="user-info" data-user-id="<?php echo $userID; ?>"></div>
        <h2 class="mb-4">Lista de Incidencias</h2>
        
        <div class="mb-4">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newIncidentModal">Agregar Nueva Incidencia</button>
        </div>

        <table id="incidentTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Tipo de Incidencia</th>
                    <th>Estado</th>
                    <th>Descripción</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="incidentTableBody">
                <?php foreach ($incidentsList as $incident): ?>
                    <tr>
                        <td><?php echo $incident->getId(); ?></td>
                        <td><?php echo $incident->getUserId()->getLastName(); ?> <?php echo $incident->getUserId()->getfirstName(); ?></td>
                        <td><?php echo $incident->getIncidentTypeId()->getName(); ?></td>
                        <td><?php echo $incident->getStatusId()->getName(); ?></td>
                        <td><?php echo $incident->getDescription(); ?></td>
                        <td><?php echo $incident->getIncidentDate(); ?></td>
                        <td class="d-flex justify-content-center align-items-center">
                            <button class="btn btn-info" onclick="viewIncident(<?php echo $incident->getId(); ?>)">Ver</button>
                            <?php if($userRole===1):?>
                            <button class="btn btn-success" onclick="changeStatusIncident(<?php echo $incident->getId(); ?>,<?php echo $incident->getStatusId()->getId(); ?>)">Cambiar de estado</button>
                            <?php endif;?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
                            
    <!-- Modal para agregar nueva incidencia -->
    <div class="modal fade" id="newIncidentModal" tabindex="-1" aria-labelledby="newIncidentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newIncidentModalLabel">Nueva Incidencia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form id="newIncidentForm">
                        <div class="mb-3">
                            <label for="incidentType" class="form-label">Tipo de Incidencia</label>
                            <select id="incidentType" class="form-select" required>
                                <?php foreach ($incidentTypeList as $type): ?>
                                    <option value="<?php echo $type->getId(); ?>"><?php echo $type->getName(); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Descripción de la incidencia</label>
                            <textarea id="description" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="container d-flex aling-items-center justify-content-center">
                            <button type="button" class="btn btn-primary" onclick="newIncident()">Agregar Incidencia</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal para agregar nueva incidencia -->
    <!-- Modal Detalle incidencia -->
    <div class="modal fade" id="incidentDetailModal" tabindex="-1" aria-labelledby="incidentDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="incidentDetailModalLabel">Detalles de la Incidencia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="incidentDetailContent">
                    </div>
                </div>
                <div class="modal-footer" id="incidentDetailModalFooter">
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Detalle incidencia -->
    <!-- Modal Respuesta incidencia -->
    <div class="modal fade" id="answerModal" tabindex="-1" role="dialog" aria-labelledby="answerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content" id="answerModalContent">
            </div>
        </div>
    </div>
    <!-- Modal Respuesta incidencia -->
    <!-- Modal Cambiar Estado -->
    <div class="modal fade" id="changeStatusModal" tabindex="-1" aria-labelledby="changeStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changeStatusModalLabel">Cambiar Estado de Incidencia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form id="changeStatusForm">
                        <div class="mb-3">
                            <label for="statusSelect" class="form-label">Selecciona el nuevo estado:</label>
                            <select id="statusSelect" class="form-select">
                                <?php foreach($incidentStatusList as $incidentStatus):?>
                                <option value="<?php echo $incidentStatus->getId();?>"><?php echo $incidentStatus->getName();?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="submitChangeStatus(currentIncidentId)">Guardar cambios</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Cambiar Estado -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    const currentUserId = document.getElementById('user-info').dataset.userId;
    let currentIncidentId;
    function changeStatusIncident(incidentId,incidentStatus) {
        currentIncidentId = incidentId;
        $('#changeStatusModal').modal('show');

        document.getElementById('statusSelect').value = incidentStatus;
    }
    $(document).ready(function() {
        $('#incidentTable').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "pageLength": 5,
            "lengthMenu": [5, 10, 25, 50],
            "language": {
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
            "order": [[ 0, "asc" ]]
        });
    });

    function newIncident() {
        let description = $('#description').val();
        let incidentTypeId = $('#incidentType').val();
        let userId = <?php echo $userID?>; 
        if (!description || !incidentTypeId) {
            Swal.fire(
                'Error!',
                'Por favor, completa todos los campos requeridos.',
                'error'
            );
            return;
        }
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¿Deseas crear este nuevo incidente?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, crear!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?php echo FRONT_ROOT ?>Incident/newIncident',
                    type: 'POST',
                    data: {
                        userId: userId,
                        incidentTypeId: incidentTypeId,
                        description: description,
                    },
                    success: function (response) {
                        response= JSON.parse(response);
                        console.log(response);
                        if (response.success) {
                            Swal.fire(
                                '¡Éxito!',
                                'El incidente ha sido creado exitosamente.',
                                'success'
                            ).then(() => {
                                $('#answerModal').modal('hide');
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                'No se pudo crear el incidente. Intenta de nuevo.',
                                'error'
                            );
                        }
                    },
                    error: function () {
                        Swal.fire(
                            'Error!',
                            'Error en la conexión al servidor.',
                            'error'
                        );
                    }
                });
            }
        });
    }
    function submitAnswer(incidentId) {
        const userId = <?php echo $userID?>;
        const answer = document.getElementById('answer').value;
        $.ajax({
            type: "POST",
            url: "<?php echo FRONT_ROOT ?>IncidentAnswer/newIncidentAnswer",
            data: {
                incidentID: incidentId,
                incidentUserId: userId,
                incidentAnswer: answer
            },
            success: function(response) {
                response = JSON.parse(response);
                if (response.success) {
                    Swal.fire({
                    title: 'Éxito',
                    text: response.message,
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    $('#answerModal').modal('hide');
                    viewIncident(incidentId);
                });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: response.message,
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    title: 'Error',
                    text: 'Error al realizar la solicitud.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    }
    function viewIncident(incidentId) {
        $.ajax({
            url: '<?php echo FRONT_ROOT ?>Incident/getIncidentById',
            type: 'POST',
            data: { incidentId: incidentId },
            success: function(response) {
                response = JSON.parse(response);
                if (response.success) {
                    const statusClasses = {
                        1: 'text-warning',
                        2: 'text-info',
                        3: 'text-success',
                        4: 'text-muted', 
                        5: 'text-danger'
                    };
                    const statusClass = statusClasses[response.incident.statusId.id];
                    let detailsHtml = `
                        <div class="card p-3 border-0 shadow-sm bg-light" id="incident-${incidentId}">
                            <div class="card-header d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle me-2 text-warning" aria-hidden="true"></i>
                                <h5 class="mb-0">Detalles de la Incidencia</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3 d-flex align-items-center">
                                    <i class="fas fa-user me-2 text-muted" aria-hidden="true"></i>
                                    <strong>Usuario:</strong> 
                                    <span class="text-muted ms-2">${response.incident.userId.firstName} ${response.incident.userId.lastName}</span>
                                </div>
                                <div class="mb-3 d-flex align-items-center">
                                    <i class="fas fa-tag me-2 text-muted" aria-hidden="true"></i>
                                    <strong>Tipo de Incidencia:</strong> 
                                    <span class="text-muted ms-2">${response.incident.incidentTypeId.name}</span>
                                </div>
                                <div class="mb-3 d-flex align-items-center">
                                    <i class="fas fa-info-circle me-2 text-${response.incident.statusId.name === 'Cerrado' ? 'success' : 'danger'}" aria-hidden="true"></i>
                                    <strong>Estado:</strong> 
                                    <span class="${statusClass} ms-2">${response.incident.statusId.name}</span>
                                </div>
                                <div class="mb-3 d-flex align-items-center">
                                    <i class="fas fa-comment-dots me-2 text-muted" aria-hidden="true"></i>
                                    <strong>Descripción:</strong> 
                                    <span class="text-muted ms-2">${response.incident.description}</span>
                                </div>
                                <div class="mb-3 d-flex align-items-center">
                                    <i class="fas fa-calendar-alt me-2 text-muted" aria-hidden="true"></i>
                                    <strong>Fecha:</strong> 
                                    <span class="text-muted ms-2">${new Date(response.incident.incidentDate).toLocaleDateString()}</span>
                                </div>
                                <div>
                                    <strong>Respuestas (${response.incident.answers.length}):</strong>
                                    <ul class="list-group mt-2" id="answer-${response.incident.answers.id}-${incidentId}" style="max-height: 450px; overflow-y: auto;">
                    `;
                    response.incident.answers.forEach(answer => {
                        const isCurrentUser = answer.userId.userID === currentUserId;
                        detailsHtml += `
                            <div class="d-flex ${isCurrentUser ? 'justify-content-end' : 'justify-content-start'} mb-3">
                                <div class="card w-75" id="answer-${answer.id}-${incidentId}">
                                    <div class="card-body">
                                        <div class="d-flex align-items-start">
                                                <img src="<?php echo FRONT_ROOT ?>${answer.userId.image}" 
                                                    alt="${answer.userId.firstName} ${answer.userId.lastName}" 
                                                    class="rounded-circle me-3" 
                                                    style="width: 40px; height: 40px;">
                                            <div class="w-100">
                                                <strong>${answer.userId.firstName} ${answer.userId.lastName}</strong><br>
                                                <small class="text-muted">${answer.userId.email}</small>
                                                <p class="mt-2 mb-0">${answer.answer}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer text-muted text-end">
                                        ${new Date(answer.answerDate).toLocaleString()}
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    detailsHtml += `
                                    </ul>
                                </div>
                            </div>
                        </div>
                    `;
                    $('#incidentDetailContent').empty();
                    $('#incidentDetailContent').html(detailsHtml);
                    $('#incidentDetailModal').modal('show');
                    $('#incidentDetailModalFooter').html(`
                        <div class="container d-flex align-items-center justify-content-center mx-3">
                        ${(response.incident.statusId.id === '1' || response.incident.statusId.id === '2' || response.incident.statusId.id === '5') ? 
                            `<button class="btn btn-primary" onclick="showAnswerModal(${response.incident.id})">Responder</button>` : ''}
                        ${response.incident.statusId.id === '3' ? 
                            `<button class="btn btn-warning" onclick="submitChangeStatus(<?php echo $incident->getId(); ?>, 5)">Reabrir</button>` : ''}
                    </div>
                    `);
                } else {
                    alert('Error al cargar los detalles de la incidencia.');
                }
            },
            error: function() {
                alert('Error al realizar la solicitud.');
            }
        });
    }
    function showAnswerModal(incidentId) {
        let answerModalHtml = `
            <div class="modal-header">
                <h5 class="modal-title">Responder a la Incidencia</h5>
                <button type="button" class="btn-close" onclick="$('#answerModal').modal('hide');" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <textarea id="answer" rows="3" class="form-control" placeholder="Escribe tu respuesta aquí..."></textarea>
            </div>
            <div class="modal-footer">
                <div class="container d-flex align-items-center justify-content-center mx-3">
                    <button type="button" class="btn btn-secondary" onclick="$('#answerModal').modal('hide');">Cerrar</button>
                    <button class="btn btn-primary mx-3" onclick="submitAnswer(${incidentId})">Enviar Respuesta</button>
                </div>
            </div>
        `;
        $('#answerModalContent').html(answerModalHtml);
        $('#answerModal').modal('show');
    }
    function submitChangeStatus(currentIncidentId,statusId=null) {
        const newStatusId = statusId || document.getElementById('statusSelect').value;
        $.ajax({
            type: "POST",
            url: "<?php echo FRONT_ROOT ?>Incident/changeStatusIncident",
            data: {
                incidentId: currentIncidentId,
                statusId: newStatusId
            },
            success: function(response) {
                response = JSON.parse(response);
                if (response.success) {
                    Swal.fire({
                        title: 'Éxito',
                        text: 'El estado de la incidencia se ha cambiado correctamente.',
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: response.message,
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    title: 'Error',
                    text: 'No se pudo cambiar el estado de la incidencia.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    }
</script>
</body>
</html>

