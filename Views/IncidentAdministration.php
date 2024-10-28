<?php
namespace Views;
require_once("validate-session.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
                        <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">

    <title>Administrador de incidencias</title>
</head>

<body>
    <input type="hidden" id="currentUserID" name="currentUserID" value="<?php echo $userID; ?>">
    <div class="container mt-5">
        <div class="container">
            <hr>
            <h2>Tipos de Incidencias</h2>
        </div>
        <div class="d-flex align-items-center justify-content-end mb-3">
            <button type="button" class="btn btn-primary" onclick="createNew('incidentType')" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="bi bi-plus-circle me-1"></i>
                Crear Tipo de Incidente
            </button>
        </div>

        <table id="incidentTypeTable" class="table table-striped table-hover">
            <thead class="table-dark text-center">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th>Modificar</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($incidentTypeList as $incidentType): ?>
                    <tr class="text-center align-middle">
                        <td><?php echo htmlspecialchars($incidentType->getId()); ?></td>
                        <td><?php echo htmlspecialchars($incidentType->getName()); ?></td>
                        <td><?php echo htmlspecialchars($incidentType->getDescription()); ?></td>
                        <td>
                            <span class="badge <?php echo $incidentType->getIsActive() ? 'bg-success' : 'bg-secondary'; ?>">
                                <?php echo $incidentType->getIsActive() ? 'Activo' : 'Inactivo'; ?>
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-primary btn-sm d-flex align-items-center"
                                onclick="openEditModal('incidentType', {
                                    id: '<?php echo $incidentType->getId(); ?>',
                                    name: '<?php echo htmlspecialchars($incidentType->getName()); ?>',
                                    description: '<?php echo htmlspecialchars($incidentType->getDescription()); ?>',
                                    isActive: <?php echo $incidentType->getIsActive() ? 'true' : 'false'; ?>
                                })">
                                <i class="bi bi-pencil-square me-1"></i>
                                Modificar
                            </button>
                        </td>
                        <td>
                            <button class="btn <?php echo $incidentType->getIsActive() ? 'btn-danger' : 'btn-success'; ?> btn-sm d-flex align-items-center"
                                onclick="changeStatus('typeIncident', '<?php echo $incidentType->getId(); ?>', <?php echo $incidentType->getIsActive() ? 0 : 1; ?>, this)">
                                <i class="bi <?php echo $incidentType->getIsActive() ? 'bi-x-circle' : 'bi-check-circle'; ?> me-1"></i>
                                <?php echo $incidentType->getIsActive() ? 'Eliminar' : 'Reactivar'; ?>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="container">
            <hr>
            <h2 >Estados de Incidencias</h2>
        </div>
        <div class="d-flex align-items-center justify-content-end mb-3">
            <button type="button" class="btn btn-primary" onclick="createNew('incidentStatus')" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="bi bi-plus-circle me-1"></i>
                Crear Estado de Incidente
            </button>
        </div>

        <table id="incidentStatusTable" class="table table-striped table-hover">
            <thead class="table-dark text-center">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th>Modificar</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($incidentStatusList as $incidentStatus): ?>
                    <tr class="text-center align-middle">
                        <td><?php echo htmlspecialchars($incidentStatus->getId()); ?></td>
                        <td><?php echo htmlspecialchars($incidentStatus->getName()); ?></td>
                        <td><?php echo htmlspecialchars($incidentStatus->getDescription()); ?></td>
                        <td>
                            <span class="badge <?php echo $incidentStatus->getIsActive() ? 'bg-success' : 'bg-secondary'; ?>">
                                <?php echo $incidentStatus->getIsActive() ? 'Activo' : 'Inactivo'; ?>
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-primary btn-sm d-flex align-items-center" 
                                onclick="openEditModal('incidentStatus', {
                                    id: '<?php echo $incidentStatus->getId(); ?>',
                                    name: '<?php echo htmlspecialchars($incidentStatus->getName()); ?>',
                                    description: '<?php echo htmlspecialchars($incidentStatus->getDescription()); ?>',
                                    isActive: <?php echo $incidentStatus->getIsActive() ? 'true' : 'false'; ?>
                                })">
                                <i class="bi bi-pencil-square me-1"></i>
                                Modificar
                            </button>
                        </td>
                        <td>
                            <button class="btn <?php echo $incidentStatus->getIsActive() ? 'btn-danger' : 'btn-success'; ?> btn-sm d-flex align-items-center"
                                onclick="changeStatus('statusIncident', '<?php echo $incidentStatus->getId(); ?>', <?php echo $incidentStatus->getIsActive() ? 0 : 1; ?>, this)">
                                <i class="bi <?php echo $incidentStatus->getIsActive() ? 'bi-x-circle' : 'bi-check-circle'; ?> me-1"></i>
                                <?php echo $incidentStatus->getIsActive() ? 'Eliminar' : 'Reactivar'; ?>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>
    <!--Edit-->         
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="editForm">
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title d-flex align-items-center" id="editModalLabel">
                            <i class="bi bi-pencil-square me-2"></i>
                            Editar Elemento
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="editId" name="id">
                        
                        <div class="mb-3">
                            <label for="editName" class="form-label">
                                <i class="bi bi-person-circle me-1"></i>
                                Nombre
                            </label>
                            <input type="text" class="form-control" id="editName" name="name" placeholder="Escribe el nombre..." required>
                        </div>
                        <div class="mb-3">
                            <label for="editDescription" class="form-label">
                                <i class="bi bi-card-text me-1"></i>
                                Descripción
                            </label>
                            <textarea class="form-control" id="editDescription" name="description" placeholder="Escribe una descripción..." rows="3" required></textarea>
                        </div>
                        <div class="mb-3 form-check form-switch">
                            <input type="checkbox" class="form-check-input" id="editIsActive" name="isActive">
                            <label class="form-check-label" for="editIsActive">
                                <i class="bi bi-toggle-on me-1"></i>
                                Activo
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i>
                            Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!--New Record-->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title d-flex align-items-center" id="createModalLabel">
                        <i class="bi bi-plus-circle me-2"></i>
                        Crear Nuevo
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="createName" class="form-label">
                            <i class="bi bi-person-circle me-1"></i>
                            Nombre
                        </label>
                        <input type="text" class="form-control" id="createName" placeholder="Escribe el nombre..." required>
                    </div>
                    <div class="mb-3">
                        <label for="createDescription" class="form-label">
                            <i class="bi bi-card-text me-1"></i>
                            Descripción
                        </label>
                        <textarea class="form-control" id="createDescription" rows="3" placeholder="Escribe una descripción..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="createIsActive" class="form-label">
                            <i class="bi bi-toggle-on me-1"></i>
                            Estado
                        </label>
                        <select class="form-select" id="createIsActive" required>
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>
                        Cancelar
                    </button>
                    <button type="button" class="btn btn-primary" id="saveButton" onclick="">
                        <i class="bi bi-save me-1"></i>
                        Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>




<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.7.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
<script>
    $(document).ready(function() {
        $('#incidentTypeTable').DataTable({
            "paging": true,
            "pageLength": 5,
            "lengthMenu": [5, 10, 25, 50, 100],
            "searching": true,
            "ordering": true,
            "order": [[0, "asc"]],
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "scrollY": "400px",
            "scrollCollapse": true,
            "processing": true,
            "deferRender": true,
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
            "dom": 'Bfrtip',
            "buttons": [
                {
                    extend: 'copy',
                    text: 'Copiar',
                    className: 'btn btn-secondary'
                },
                {
                    extend: 'csv',
                    text: 'CSV',
                    className: 'btn btn-secondary'
                },
                {
                    extend: 'excel',
                    text: 'Excel',
                    className: 'btn btn-success'
                },
                {
                    extend: 'pdf',
                    text: 'PDF',
                    className: 'btn btn-danger'
                },
                {
                    extend: 'print',
                    text: 'Imprimir',
                    className: 'btn btn-primary'
                }
            ]
        });

        $('#incidentStatusTable').DataTable({
            "paging": true,
            "pageLength": 5,
            "lengthMenu": [5, 10, 25, 50, 100],
            "searching": true,
            "ordering": true,
            "order": [[0, "asc"]],
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "scrollY": "400px",
            "scrollCollapse": true,
            "processing": true,
            "deferRender": true,
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
            "dom": 'Bfrtip',
            "buttons": [
                {
                    extend: 'copy',
                    text: 'Copiar',
                    className: 'btn btn-secondary'
                },
                {
                    extend: 'csv',
                    text: 'CSV',
                    className: 'btn btn-secondary'
                },
                {
                    extend: 'excel',
                    text: 'Excel',
                    className: 'btn btn-success'
                },
                {
                    extend: 'pdf',
                    text: 'PDF',
                    className: 'btn btn-danger'
                },
                {
                    extend: 'print',
                    text: 'Imprimir',
                    className: 'btn btn-primary'
                }
            ]
        });
    });
    function openEditModal(type, data) {
        document.getElementById('editModalLabel').innerText = `Editar ${type === 'incidentType' ? 'Tipo de Incidente' : 'Estado de Incidente'}`;

        document.getElementById('editId').value = data.id;
        document.getElementById('editName').value = data.name;
        document.getElementById('editDescription').value = data.description;
        document.getElementById('editIsActive').checked = data.isActive;
        document.getElementById('editForm').onsubmit = function(event) {
            event.preventDefault();
            saveChanges(type);
        };
        var editModal = new bootstrap.Modal(document.getElementById('editModal'));
        editModal.show();
    }

    function saveChanges(type) {
        const id = document.getElementById('editId').value;
        const name = document.getElementById('editName').value.trim();
        const description = document.getElementById('editDescription').value.trim();
        const isActive = document.getElementById('editIsActive').checked ? 1 : 0;

        if (!name) {
            Swal.fire({
                title: 'Error',
                text: 'El nombre no puede estar vacío.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
            return;
        }

        if (!description) {
            Swal.fire({
                title: 'Error',
                text: 'La descripción no puede estar vacía.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
            return;
        }

        let endpoint = <?php echo FRONT_ROOT?>;

        switch (type) {
            case 'incidentType':
                endpoint += `IncidentType/updateStatusIncidentType`;
                break;
            case 'incidentStatus':
                endpoint += `IncidentStatus/updateIncidentStatus`;
                break;
            default:
                console.error('Tipo no válido');
                return;
        }

        Swal.fire({
            title: 'Confirmación',
            text: '¿Estás seguro de que deseas guardar los cambios?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#dc3545',
            confirmButtonText: 'Sí, guardar cambios',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: endpoint,
                    type: 'POST',
                    data: {
                        id: id,
                        name: name,
                        description: description,
                        isActive: isActive
                    },
                    success: function(response) {
                        console.log(response);
                        response = JSON.parse(response);
                        if (response.success) {
                            Swal.fire({
                                title: 'Éxito',
                                text: response.message,
                                icon: 'success',
                                timer: 3000,
                                showConfirmButton: false
                            }).then(() => {
                                $('#editModal').modal('hide');
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: response.message,
                                icon: 'error',
                                confirmButtonColor: '#dc3545',
                                confirmButtonText: 'Aceptar',
                                reverseButtons: true
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error',
                            text: 'Hubo un error al actualizar. Inténtalo de nuevo.',
                            icon: 'error',
                            confirmButtonColor: '#dc3545',
                            confirmButtonText: 'Aceptar',
                            reverseButtons: true
                        });
                    }
                });
            }
        });
    }


    function changeStatus(type, id, newStatus) {
        const action = newStatus === 1 ? 'Reactivar' : 'Eliminar';
        const confirmButtonColor = newStatus === 1 ? '#28a745' : '#dc3545';
        let endpoint = <?php echo FRONT_ROOT ?>;

        switch (type) {
            case 'statusIncident':
                endpoint += 'IncidentStatus/changeStatusIncident';
                break;
            case 'typeIncident':
                endpoint += 'IncidentType/changeStatusIncidentType';
                break;
            default:
                Swal.fire({
                    title: 'Error',
                    text: 'Tipo no válido proporcionado.',
                    icon: 'error',
                    reverseButtons: true
                });
                return;
        }

        Swal.fire({
            title: `¿Estás seguro de que deseas ${action} este ${type === 'typeIncident' ? 'Tipo de Incidente' : 'Estado de Incidente'}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: confirmButtonColor,
            cancelButtonColor: '#6c757d',
            confirmButtonText: `Sí, ${action.toLowerCase()}`,
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: endpoint,
                    type: 'POST',
                    data: { incidentID: id, incidentStatus: newStatus },
                    success: function(response) {
                        console.log(response);
                        response=JSON.parse(response);
                        if (response.success) {
                            Swal.fire({
                                title: `${action.charAt(0).toUpperCase() + action.slice(1)} exitosamente!`,
                                text: `El ${type === 'typeIncident' ? 'Tipo de Incidente' : 'Estado de Incidente'} ha sido ${action.toLowerCase()}.`,
                                icon: 'success',
                                confirmButtonText: 'Aceptar',
                                reverseButtons: true
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: 'No se pudo completar la acción. Intenta de nuevo.',
                                icon: 'error',
                                confirmButtonText: 'Aceptar',
                                reverseButtons: true
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error',
                            text: 'Hubo un error. Inténtalo de nuevo.',
                            icon: 'error',
                            confirmButtonText: 'Aceptar',
                            reverseButtons: true
                        });
                    }
                });
            }
        });
    }
    function createNew(type) {
        const saveButton = document.getElementById('saveButton');
        if (type === 'incidentStatus') {
            saveButton.setAttribute('onclick', "newRecord('incidentStatus')");
            saveButton.innerHTML = '<i class="bi bi-save me-1"></i> Guardar Estado de Incidente';
        } else if (type === 'incidentType') {
            saveButton.setAttribute('onclick', "newRecord('incidentType')");
            saveButton.innerHTML = '<i class="bi bi-save me-1"></i> Guardar Tipo de Incidente';
        }

        document.getElementById('createName').value = '';
        document.getElementById('createDescription').value = '';
        document.getElementById('createIsActive').value = '1';
    }
    function newRecord(type) {
        const name = document.getElementById('createName').value.trim();
        const description = document.getElementById('createDescription').value.trim();
        const isActive = document.getElementById('createIsActive').value;
        if (!name) {
            Swal.fire({
                title: 'Error',
                text: 'El nombre no puede estar vacío.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
            return;
        }

        if (!description) {
            Swal.fire({
                title: 'Error',
                text: 'La descripción no puede estar vacía.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
            return;
        }

        let endpoint = <?php echo FRONT_ROOT; ?>;

        switch (type) {
            case 'incidentType':
                endpoint += 'IncidentType/newStatusIncidentType';
                break;
            case 'incidentStatus':
                endpoint += 'IncidentStatus/newIncidentStatus';
                break;
            default:
                console.error('Tipo no válido');
                return;
        }

        Swal.fire({
            title: `¿Estás seguro de que deseas crear este ${type === 'incidentType' ? 'Tipo de Incidente' : 'Estado de Incidente'}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, crear',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: endpoint,
                    type: 'POST',
                    data: {
                        name: name,
                        description: description,
                        isActive: isActive
                    },
                    success: function(response) {
                        console.log(response);
                        response = JSON.parse(response);
                        if (response.success) {
                            Swal.fire({
                                title: `${type === 'incidentType' ? 'Tipo de Incidente' : 'Estado de Incidente'} creado exitosamente!`,
                                text: response.message,
                                icon: 'success',
                                reverseButtons: true
                            }).then(() => {
                                $('#createModal').modal('hide');
                                location.reload(); 
                            });
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: response.message,
                                icon: 'error',
                                reverseButtons: true
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error',
                            text: 'Hubo un error al crear el registro. Inténtalo de nuevo.',
                            icon: 'error',
                            reverseButtons: true
                        });
                    }
                });
            }
        });
    }


</script>
</html>