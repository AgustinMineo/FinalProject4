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
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"  crossorigin="anonymous"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
</head>
<body>
    <div class="container mt-5">
        <div id="user-info" data-user-id="<?php echo $userID; ?>"></div>
        <h2 class="mb-4 text-center">Lista de Incidencias</h2>

        <div class="d-flex align-items-center justify-content-between mb-3">
            <span class="badge bg-info text-dark">Total de Incidencias: <?php echo count($incidentsList); ?></span>
            <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#newIncidentModal">
                <i class="fas fa-plus-circle me-2"></i>Agregar Nueva Incidencia
            </button>
        </div>

        <table id="incidentTable" class="table table-hover align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Correo</th>
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
                        <td class="text-center"><?php echo $incident->getId(); ?></td>
                        <td><?php echo $incident->getUserId()->getLastName(); ?> <?php echo $incident->getUserId()->getfirstName(); ?></td>
                        <td><?php echo $incident->getUserId()->getEmail(); ?></td>
                        <td><?php echo $incident->getIncidentTypeId()->getName(); ?></td>
                        <td>
                            <span class="badge <?php echo $incident->getStatusId()->getId() === '1' ? 'bg-success' : 'bg-warning'; ?>">
                                <?php echo $incident->getStatusId()->getName(); ?>
                            </span>
                        </td>
                        <td><?php echo $incident->getDescription(); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($incident->getIncidentDate())); ?></td>
                        <td class="text-center">
                            <div class="btn-group">
                                <button class="btn btn-outline-info btn-sm me-1" onclick="viewIncident(<?php echo $incident->getId(); ?>)">
                                    <i class="fas fa-eye"></i> Ver
                                </button>
                                <?php if($userRole === 1): ?>
                                    <button class="btn btn-outline-success btn-sm" onclick="changeStatusIncident(<?php echo $incident->getId(); ?>, <?php echo $incident->getStatusId()->getId(); ?>)">
                                        <i class="fas fa-sync-alt"></i> Cambiar Estado
                                    </button>
                                <?php endif; ?>
                            </div>
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
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="newIncidentModalLabel"><i class="fas fa-exclamation-triangle me-2"></i>Nueva Incidencia</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form id="newIncidentForm" enctype="multipart/form-data">
                        <div class="mb-4">
                            <label for="incidentType" class="form-label fw-bold">Tipo de Incidencia</label>
                            <select id="incidentType" class="form-select" required>
                                <option value="" selected disabled>Seleccione el tipo de incidencia</option>
                                <?php foreach ($incidentTypeList as $type): ?>
                                    <option value="<?php echo $type->getId(); ?>"><?php echo $type->getName(); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="description" class="form-label fw-bold">Descripción de la Incidencia</label>
                            <textarea id="description" class="form-control" rows="4" required placeholder="Describe brevemente el incidente..."></textarea>
                        </div>
                        <div class="mb-4">
                            <label for="mediaUpload" class="form-label fw-bold">Subir Imágenes o Videos:</label>
                            <div id="dropArea" class="border border-dashed p-4 text-center rounded" 
                                ondrop="dropHandler(event)" ondragover="dragOverHandler(event)">
                                <p class="mb-2 text-muted">Arrastra y suelta tus archivos aquí o</p>
                                <label for="mediaUpload" class="btn btn-outline-primary">Selecciona archivos</label>
                                <input type="file" id="mediaUpload" class="form-control d-none" multiple 
                                    accept=".jpg, .jpeg, .png, .gif, .webp, .mp4, .avi, .mov" onchange="previewMedia()">
                            </div>
                            <div id="mediaPreview" class="mt-4 d-flex flex-wrap gap-2"></div>
                        </div>
                        <div class="d-flex justify-content-center mt-4">
                            <button type="button" class="btn btn-success" onclick="newIncident()">
                                <i class="fas fa-plus-circle me-2"></i>Agregar Incidencia
                            </button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times"></i> Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para agregar nueva incidencia -->
    <!-- Modal Detalle incidencia -->
    <div class="modal fade" id="incidentDetailModal" tabindex="-1" aria-labelledby="incidentDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="incidentDetailModalLabel"><i class="fas fa-info-circle me-2"></i>Detalles de la Incidencia</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div id="incidentDetailContent" class="p-3">
                    </div>
                </div>
                <div class="modal-footer bg-light" id="incidentDetailModalFooter">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times"></i> Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="editIncident()"><i class="fas fa-edit"></i> Editar Incidencia</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detalle incidencia -->
    <!-- Modal Respuesta incidencia -->
    <div class="modal fade" id="answerModal" tabindex="-1" aria-labelledby="answerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="answerModalLabel"><i class="fas fa-reply me-2"></i> Respuestas de la Incidencia</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body" id="answerModalContent">
                    <!-- Contenido de respuestas cargado dinámicamente aquí -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Respuesta incidencia -->
    <!-- Modal Cambiar Estado -->
    <div class="modal fade" id="changeStatusModal" tabindex="-1" aria-labelledby="changeStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="changeStatusModalLabel"><i class="fas fa-exchange-alt me-2"></i> Cambiar Estado de Incidencia</h5>
                    <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form id="changeStatusForm">
                        <div class="mb-3">
                            <label for="statusSelect" class="form-label">Selecciona el nuevo estado:</label>
                            <select id="statusSelect" class="form-select" required>
                                <?php foreach($incidentStatusList as $incidentStatus): ?>
                                    <option value="<?php echo $incidentStatus->getId(); ?>">
                                        <?php echo htmlspecialchars($incidentStatus->getName()); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="button" class="btn btn-primary" onclick="submitChangeStatus(currentIncidentId)">
                        <i class="fas fa-save"></i> Guardar cambios
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Cambiar Estado -->
<script>
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
    const allowedImageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    const allowedVideoExtensions = ['mp4', 'avi', 'mov', 'webm'];
    const allowedTextExtensions = ['pdf'];
    let uploadedFiles = [];

    const currentUserId = document.getElementById('user-info').dataset.userId;
    let currentIncidentId;
    function changeStatusIncident(incidentId,incidentStatus) {
        currentIncidentId = incidentId;
        $('#changeStatusModal').modal('show');

        document.getElementById('statusSelect').value = incidentStatus;
    }
    
    function newIncident() {
            let description = $('#description').val();
            let incidentTypeId = $('#incidentType').val();
            let userId = <?php echo $userID ?>; 
            let fileInput = document.getElementById('mediaUpload');
            let files = fileInput.files;

            if (!description || !incidentTypeId) {
                Swal.fire(
                    'Error!',
                    'Por favor, completa todos los campos requeridos.',
                    'error'
                );
                return;
            }
            if (description.length <20) {
                Swal.fire({
                    title: 'Error',
                    text: 'La descripción tiene que tener mas de 20 caracteres.',
                    icon: 'warning',
                    confirmButtonText: 'Aceptar'
                });
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
                    let formData = new FormData();
                    formData.append('userId', userId);
                    formData.append('incidentTypeId', incidentTypeId);
                    formData.append('description', description);

                    for (let i = 0; i < files.length; i++) {
                        formData.append('media[]', files[i]); 
                    }
                    $.ajax({
                        url: '<?php echo FRONT_ROOT ?>Incident/newIncident',
                        type: 'POST',
                        data: formData,
                        processData: false, 
                        contentType: false, 
                        success: function (response) {
                            response = JSON.parse(response);
                            if (response.success) {
                                Swal.fire(
                                    '¡Éxito!',
                                    'El incidente ha sido creado exitosamente.',
                                    'success'
                                ).then(() => {
                                    window.location.reload();
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
        const answer = document.getElementById('answer').value.trim();
        if (!answer) {
            Swal.fire({
                title: 'Error',
                text: 'Por favor ingresa una respuesta válida.',
                icon: 'warning',
                confirmButtonText: 'Aceptar'
            });
            return;
        }
        if (answer.length <5) {
                Swal.fire({
                    title: 'Error',
                    text: 'La respuesta tiene que tener mas de 5 caracteres.',
                    icon: 'warning',
                    confirmButtonText: 'Aceptar'
                });
                return;
            }
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
                    if (response.incident) {
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
                                    ${response.incident.files && response.incident.files.length > 0 ? `
                                        <div class="mb-2">
                                        <strong>Archivos Adjuntos:</strong><br>
                                            <button class="btn btn-outline-primary" id="downloadAllAttachments${response.incident.id}">
                                                <i class="fas fa-file-download"></i> Descargar Adjuntos
                                            </button>
                                        </div>
                                    ` : ''}
                                    <div>
                                        <div style="max-height: 200px; overflow-y: auto;" class="mt-2">
                                            <ul class="list-group" id="attachmentList-${incidentId}">
                        `;
                        if (response.incident.files) {
                            response.incident.files.forEach(file => {
                                if (file.file_path.endsWith('.jpg') || file.file_path.endsWith('.jpeg') || file.file_path.endsWith('.png') || file.file_path.endsWith('.webp')) {
                                    detailsHtml += `
                                        <li class="list-group-item">
                                            <img src="<?php echo FRONT_ROOT ?>${file.file_path}" alt="Imagen adjunta" class="img-thumbnail" style="max-width: 100%; max-height: 200px;">
                                        </li>`;
                                } else if (file.file_path.endsWith('.mp4')) {
                                    detailsHtml += `
                                        <li class="list-group-item">
                                            <video controls class="w-100" style="max-height: 200px;">
                                                <source src="<?php echo FRONT_ROOT ?>${file.file_path}" type="video/mp4">
                                                Tu navegador no soporta el elemento de video.
                                            </video>
                                        </li>`;
                                }
                            });
                        }

                        detailsHtml += `
                                            </ul>
                                        </div>
                                    </div>
                                </div> <!-- Cierra .card-body -->
                            </div> <!-- Cierra .card -->
                        `;

                        // Respuestas
                        detailsHtml += `
                            <div class="card mt-3 p-3">
                                <div class="card-header d-flex align-items-center">
                                    <h5 class="mb-0">Respuestas (${response.incident.answers ? response.incident.answers.length : 0}):</h5>
                                </div>
                                <div class="card-body">
                                    <div style="max-height: 300px; overflow-y: auto;" class="mt-2">
                                        <ul class="list-group" id="answer-${incidentId}">
                        `;

                        if (response.incident.answers && response.incident.answers.length > 0) {
                            response.incident.answers.forEach(answer => {
                                const isCurrentUser = answer.userId.userID === currentUserId;
                                detailsHtml += `
                                    <li class="d-flex ${isCurrentUser ? 'justify-content-end' : 'justify-content-start'}">
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
                                    </li>
                                `;
                            });
                        } else {
                            detailsHtml += `
                                <li class="list-group-item">No hay respuestas para esta incidencia.</li>
                            `;
                        }

                        detailsHtml += `
                                    </ul>
                                </div> <!-- Cierra .max-height -->
                                </div> <!-- Cierra .card-body -->
                            </div> <!-- Cierra .card -->
                        `;

                        $('#incidentDetailContent').empty();
                        $('#incidentDetailContent').html(detailsHtml);
                        $('#incidentDetailModal').modal('show');

                        // Evento de descarga de adjuntos
                        if (response.incident.files && response.incident.files.length > 0) {
                        const downloadButton = document.getElementById(`downloadAllAttachments${response.incident.id}`);
                        if (downloadButton) {
                                downloadButton.addEventListener('click', function() {
                                    response.incident.files.forEach(file => {
                                        const link = document.createElement('a');
                                        link.href = `<?php echo FRONT_ROOT ?>${file.file_path}`;
                                        link.download = '';
                                        document.body.appendChild(link);
                                        link.click();
                                        document.body.removeChild(link); 
                                    });
                                });
                            }
                        }

                        $('#incidentDetailModalFooter').html(`
                            <div class="container d-flex align-items-center justify-content-center mx-3">
                            ${(response.incident.statusId.id === '1' || response.incident.statusId.id === '2' || response.incident.statusId.id === '5') ? 
                                `
                                <button class="btn btn-primary" onclick="showAnswerModal(${response.incident.id})" aria-label="Responder a la incidencia ${response.incident.id}">
                                    <i class="fas fa-reply me-2"></i> Responder
                                </button>
                                ` : ''}
                            ${response.incident.statusId.id === '3' ? 
                                `<button class="btn btn-warning" onclick="submitChangeStatus(${response.incident.id}, 5)">Reabrir</button>` : ''}
                        </div>
                        `);
                    } else {
                        alert('No se encontró información de la incidencia.');
                    }
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
            <div class="modal-body">
                <label for="answer" class="form-label">Escribe tu respuesta:</label>
                <textarea id="answer" rows="4" class="form-control" placeholder="Escribe tu respuesta aquí..." required></textarea>
            </div>
            <div class="modal-footer">
                <div class="container d-flex align-items-center justify-content-center">
                    <button type="button" class="btn btn-secondary" onclick="$('#answerModal').modal('hide');">
                        <i class="fas fa-times"></i> Cerrar
                    </button>
                    <button class="btn btn-primary ms-3" onclick="submitAnswer(${incidentId})">
                        <i class="fas fa-paper-plane"></i> Enviar Respuesta
                    </button>
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
    //Flujo imagenes y videos
    function previewMedia() {
        const fileInput = document.getElementById('mediaUpload');
        const previewContainer = document.getElementById('mediaPreview');
        previewContainer.innerHTML = '';

        const files = fileInput.files;

        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const extension = file.name.split('.').pop().toLowerCase(); 


            if (allowedImageExtensions.includes(extension) || allowedVideoExtensions.includes(extension) || allowedTextExtensions.includes(extension)) {
                const reader = new FileReader();

                reader.onload = function(event) {
                    let mediaElement;

                    if (file.type.startsWith('image/')) {
                        mediaElement = document.createElement('img');
                        mediaElement.src = event.target.result;
                        mediaElement.style.width = '250px';
                    } else if (file.type.startsWith('video/')) {
                        mediaElement = document.createElement('video');
                        mediaElement.src = event.target.result;
                        mediaElement.controls = true; 
                        mediaElement.style.width = '250px'; 
                    }else if (file.type === 'application/pdf') {
                        mediaElement = document.createElement('iframe');
                        mediaElement.src = event.target.result;
                        mediaElement.width = '250';
                        mediaElement.height = '300';
                        mediaElement.style.border = 'none'; 
                    }

                    mediaElement.style.marginRight = '10px';
                    mediaElement.style.marginBottom = '10px';

                    previewContainer.appendChild(mediaElement);
                };

                reader.readAsDataURL(file);
                uploadedFiles.push(file); 
            } else {
                Swal.fire({
                    title: 'Error',
                    text: `Tipo de archivo no permitido: ${file.name}`,
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        }
    }

    function dragOverHandler(event) {
        event.preventDefault();
        event.stopPropagation();
        event.dataTransfer.dropEffect = 'copy';
    }

    function dropHandler(event) {
        event.preventDefault();
        event.stopPropagation();

        const dataTransfer = event.dataTransfer;
        const files = dataTransfer.files;

        if (files.length > 0) {
            const fileInput = document.getElementById('mediaUpload');
            const newFiles = Array.from(files);
            
            const existingFiles = Array.from(fileInput.files);
            const combinedFiles = [...existingFiles, ...newFiles];

            const dataTransfer = new DataTransfer();
            combinedFiles.forEach(file => dataTransfer.items.add(file));
            fileInput.files = dataTransfer.files;
            previewMedia();
        }
    }

    document.getElementById('dropArea').onclick = function() {
        document.getElementById('mediaUpload').click();
    };
</script>
</body>
</html>

