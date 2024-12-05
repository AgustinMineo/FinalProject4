<?php
namespace Views;
require_once("validate-session.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <title>Keeper disponibles</title>
    <style>
        #keeperAvailabilityModal .modal-body {
            min-height: 400px;
        }
        .info-item {
            transition: background-color 0.3s ease;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 0;
        }
        .info-item:hover {
            cursor: pointer;
            background-color: #e9ecef; 
        }
        .separator {
            margin: 10px 0;
            border: none;
            border-top: 1px solid #ccc; 
        }
        .filter-input {
            margin-bottom: 10px;
        }
        .card:hover {
            transform: scale(1.05);
            transition: transform 0.2s;
        }

    </style>
</head>
<body>
<main class="py-5">
    <div class="container">
        <h2 class="mb-4 text-center">Listado de Keepers</h2>
        
        <!-- Formulario de filtros -->
        <div class="mb-4">
            <input type="text" id="emailFilter" placeholder="Buscar por correo" class="form-control filter-input" oninput="filterKeepers()">
            <input type="number" id="priceFilter" placeholder="Precio máximo" class="form-control filter-input" oninput="filterKeepers()">
            <select id="sizeFilter" class="form-select filter-input" onchange="filterKeepers()">
                <option value="">Seleccionar Tamaño</option>
                <option value="Small">Small</option>
                <option value="Medium">Medium</option>
                <option value="Big">Big</option>
            </select>
        </div>

        <div class="row g-4" id="keepersList">
            <div id="noKeepersMessage" class="alert alert-danger text-center ">No se encontraron keepers.</div>
            <?php foreach ($listKeepers as $keeper) { ?>
                <div class="col-md-4 keeper-item" 
                    data-email="<?php echo $keeper->getEmail(); ?>"
                    data-price="<?php echo $keeper->getPrice(); ?>"
                    data-size="<?php echo $keeper->getAnimalSize(); ?>">
                    
                    <div class="card h-100 shadow-sm border-1">
                        <div class="text-center mt-3">
                            <img src="<?php echo $keeper->getImage() ? FRONT_ROOT . $keeper->getImage() : FRONT_ROOT . USER_PATH . 'userDefaultImage.jpg'; ?>" 
                                alt="Imagen de <?php echo $keeper->getFirstName(); ?>" 
                                class="card-img-top rounded-circle img-fluid" style="width: 150px; height: 150px; object-fit: cover;">
                        </div>
                        
                        <div class="card-body text-center">

                            <h5 class="card-title fw-bold mb-0"><?php echo $keeper->getFirstName() . ' ' . $keeper->getLastName(); ?></h5>
                            <p class="text-muted small mb-2"><i class="fas fa-mobile-alt"></i> <?php echo $keeper->getCellPhone(); ?></p>
                            <p class="text-muted small mb-2"><i class="fas fa-calendar-alt"></i> <?php echo date_format(date_create($keeper->getBirthDate()), "d/m/Y"); ?></p>
                            <p class="text-muted small mb-2"><i class="fas fa-envelope"></i> <?php echo $keeper->getEmail(); ?></p>
                            
                            <p class="text-muted small mb-2"><i class="fas fa-paw"></i> Tamaño de Animal: <?php echo $keeper->getAnimalSize(); ?></p>
                            
                            <p class="text-primary fs-5 fw-bold mt-3"><i class="fas fa-dollar-sign"></i> <?php echo number_format($keeper->getPrice(), 2); ?></p>
                            
                            <button type="button" class="btn btn-info btn-sm mt-3 px-4" 
                                data-bs-toggle="modal" 
                                data-bs-target="#availabilityModal<?php echo $keeper->getKeeperId(); ?>" 
                                onclick='initializeCalendar(<?php echo $keeper->getKeeperId(); ?>, <?php echo json_encode($keeper->getAvailability()); ?>)'>
                                <i class="fas fa-calendar-check"></i> Ver Disponibilidad
                            </button>
                        </div>
                    </div>

                    <div class="modal fade" id="availabilityModal<?php echo $keeper->getKeeperId(); ?>" tabindex="-1" aria-labelledby="availabilityModalLabel<?php echo $keeper->getKeeperId(); ?>" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="availabilityModalLabel<?php echo $keeper->getKeeperId(); ?>">Disponibilidad de <?php echo $keeper->getFirstName() . ' ' . $keeper->getLastName(); ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div id="calendar<?php echo $keeper->getKeeperId(); ?>"></div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="container d-flex align-items-center justify-content-center my-3">
            <nav class="mt-3">
                <ul class="pagination" id="pagination"></ul>
            </nav>
        </div>
    </div>
</main>

<script src="../Views/js/datepicker.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        filterKeepers();
        initializeCalendar();
    });

    const keepersPerPage = 6; 
    let currentPage = 1; 
    let filteredKeepers = []; 

    function filterKeepers() {
        const emailFilter = document.getElementById('emailFilter').value.toLowerCase();
        const priceFilter = parseFloat(document.getElementById('priceFilter').value);
        const sizeFilter = document.getElementById('sizeFilter').value.toLowerCase();
        
        const keepers = document.querySelectorAll('.keeper-item');
        filteredKeepers = []; 
        let foundKeeper = false; 

        keepers.forEach(keeper => {
            const email = keeper.getAttribute('data-email').toLowerCase();
            const price = parseFloat(keeper.getAttribute('data-price'));
            const size = keeper.getAttribute('data-size').toLowerCase();
            
            const matchesEmail = email.includes(emailFilter);
            const matchesPrice = isNaN(priceFilter) || price <= priceFilter;
            const matchesSize = !sizeFilter || size.includes(sizeFilter);

            if (matchesEmail && matchesPrice && matchesSize) {
                keeper.style.display = ''; 
                filteredKeepers.push(keeper); // Agregar a la lista de keepers filtrados
                foundKeeper = true; 
            } else {
                keeper.style.display = 'none'; 
            }
        });

        const noKeepersMessage = document.getElementById('noKeepersMessage');
        if (foundKeeper) {
            noKeepersMessage.style.display = 'none'; // Ocultar mensaje
            paginateKeepers(); // Llamar a la función de paginación
        } else {
            noKeepersMessage.style.display = 'block'; // Mostrar mensaje
            document.getElementById('pagination').innerHTML = ''; // Limpiar paginación
        }
    }

    function paginateKeepers() {
        const pagination = document.getElementById('pagination');
        pagination.innerHTML = ''; // Limpiar elementos de paginación

        const totalPages = Math.ceil(filteredKeepers.length / keepersPerPage); // Total de páginas

        // Crear botones de paginación
        for (let i = 1; i <= totalPages; i++) {
            const li = document.createElement('li');
            li.className = 'page-item';
            li.innerHTML = `<a class="page-link" href="#" onclick="changePage(${i})">${i}</a>`;
            pagination.appendChild(li);
        }

        displayKeepers(); // Mostrar la primera página de keepers
    }

    function changePage(page) {
        currentPage = page;
        displayKeepers(); // Mostrar la página seleccionada
    }

    function displayKeepers() {
        const startIndex = (currentPage - 1) * keepersPerPage; // Índice inicial
        const endIndex = startIndex + keepersPerPage; // Índice final

        filteredKeepers.forEach((keeper, index) => {
            keeper.style.display = (index >= startIndex && index < endIndex) ? '' : 'none'; // Mostrar u ocultar keepers
        });
    }

    function convertAvailability(data) {
    return data.map(item => {
        return {
            title: item.available === "1" ? 'Disponible' : 'No disponible',
            start: item.day,
            end: item.available === "1" ? item.day : null,
            allDay: true,
            backgroundColor: item.available === "1" ? 'green' : 'red'
        };
    });
}

let isInitialRender = true; 

function initializeCalendar(keeperId, availabilityData) {
    var calendarEl = document.getElementById('calendar' + keeperId);
    calendarEl.innerHTML = '';
    if (!calendarEl) {
        console.error("Calendar element not found");
        return;
    }
    var events = convertAvailability(availabilityData);

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 'auto',
        events: events,
        datesSet: function(dateInfo) {
            if (isInitialRender) {
                setTimeout(() => {
                    calendar.gotoDate(new Date());
                    isInitialRender = false;
                }, 70); 
            }
        }
    });

    calendar.render();
    calendar.updateSize();


    setTimeout(() => {
        calendar.gotoDate(new Date(new Date().getFullYear(), new Date().getMonth() - 1, 1));
        setTimeout(() => {
            calendar.gotoDate(new Date()); 
        }, 500); 
    }, 0); 
}
</script>
</body>
</html>

