<?php
namespace Views;
require_once("validate-session.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Grupos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container my-5">
    <h1 class="mb-4 text-center">Administración de Grupos</h1>
    
    <!-- Bloque de Group Status -->
    <div class="card mb-4">
        <div class="card-header">
            <h3>Group Status</h3>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
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
                                <button class="btn btn-sm btn-warning" data-id="<?php echo $status->getId(); ?>">Editar</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bloque de Group Privacy -->
    <div class="card mb-4">
        <div class="card-header">
            <h3>Group Privacy</h3>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
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
                                <button class="btn btn-sm btn-warning" data-id="<?php echo $privacy->getId(); ?>">Editar</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bloque de Group Roles -->
    <div class="card mb-4">
        <div class="card-header">
            <h3>Group Roles</h3>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
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
                                <button class="btn btn-sm btn-warning" data-id="<?php echo $role->getId(); ?>">Editar</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bloque de Group Types -->
    <div class="card mb-4">
        <div class="card-header">
            <h3>Group Types</h3>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
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
                                <button class="btn btn-sm btn-warning" data-id="<?php echo $type->getId(); ?>">Editar</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bloque de Grupos -->
    <div class="card mb-4">
        <div class="card-header">
            <h3>Grupos</h3>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Privacidad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($groupList as $group): ?>
                        <tr>
                            <td><?php echo $group->getId(); ?></td>
                            <td><?php echo $group->getName(); ?></td>
                            <td><?php echo $group->getGroupInfo()->getDescription(); ?></td>
                            <td><?php echo $group->getGroupPrivacy()->getName() ? 'Privado' : 'Público'; ?></td>
                            <td>
                                <button class="btn btn-sm btn-warning" data-id="<?php echo $group->getId(); ?>">Editar</button>
                                <button class="btn btn-sm btn-danger" data-id="<?php echo $group->getId(); ?>">Eliminar</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

