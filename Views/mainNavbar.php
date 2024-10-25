<?php
namespace Views;
require_once("validate-session.php");
$countMessage = isset($_SESSION['messageCount']) ? $_SESSION['messageCount'] : 0;
$countMesageGroup=isset($_SESSION['messageCountGroup']) ? $_SESSION['messageCountGroup'] : 0;
$total =0;
if($countMessage){
    foreach($countMessage as $unread){
        $total+=$unread['cantidad'];
    }
}
if($countMesageGroup){
    foreach($countMesageGroup as $unread){
        $total+=$unread['cantidad'];
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<style>
        #containerID {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            max-width: 1200px;
        }

        .nav-item {
            transition: transform 0.2s ease-in-out;
        }

        .nav-item:hover {
            transform: scale(1.05);
        }

        .nav-link {
            padding: 10px 20px;
            border-radius: 50px;
            color: #495057 !important;
            font-weight: bold;
        }

        .nav-link:hover {
            background-color: #0d6efd;
            color: white !important;
        }

        .navbar {
            background-color: #343a40;
        }

        .navbar .container {
            flex-direction: row;
            justify-content: space-around;
        }

        .nav-link i {
            margin-right: 8px;
        }
        .nav-link {
            font-size: 0.9rem;
            padding-top: 0.25rem;
            padding-bottom: 0.25rem; 
        }

</style>
</head>
<body>
    <?php if ($userRole == 3):?>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
            <div class="container d-flex flex-wrap justify-content-center mt-3" id="containerID">
                <ul class="nav flex-column flex-sm-row">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo FRONT_ROOT ?>User/editUser"><i class="fas fa-user"></i> My profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo FRONT_ROOT ?>Keeper/updateDaysAvailables"><i class="fas fa-calendar-alt"></i> Update Availability</a>
                    </li>   
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo FRONT_ROOT ?>Booking/showBookings"><i class="fas fa-book"></i> Show reservations</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo FRONT_ROOT ?>Review/getAllReviews"><i class="fas fa-star"></i> My Reviews</a>
                    </li> 
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo FRONT_ROOT ?>Keeper/showCalendarData"><i class="fas fa-calendar"></i> Calendar</a>
                    </li>
                    <li class="nav-item position-relative">
                            <a class="nav-link d-inline-block position-relative" href="<?php echo FRONT_ROOT ?>Message/getChats">
                                <i class="fas fa-calendar"></i> Chats
                                <?php if ($total > 0): ?>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="currentCountID">
                                        <?php echo $total; ?>
                                    </span>
                                <?php endif; ?>
                            </a>
                        </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo FRONT_ROOT ?>User/logOut"><i class="fas fa-sign-out-alt"></i> Log Out</a>
                    </li>  
                </ul>  
            </div>
        </nav>
    </header> 
    <?php endif; ?>

    <?php if ($userRole == 2):?>
        <header>
            <nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
                <div class="container d-flex flex-wrap justify-content-center mt-3" id="containerID">
                    <ul class="nav flex-column flex-sm-row">
                        <li class="nav-item">
                            <a class="nav-link p-1" href="<?php echo FRONT_ROOT ?>User/editUser"><i class="fas fa-user"></i> My profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link p-1" href="<?php echo FRONT_ROOT ?>Pet/goNewPet"><i class="fas fa-paw"></i> New Pet</a>
                        </li>   
                        <li class="nav-item">
                            <a class="nav-link p-1" href="<?php echo FRONT_ROOT ?>Pet/showPets"><i class="fas fa-dog"></i> Show My Pets</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link p-1" href="<?php echo FRONT_ROOT ?>Keeper/showKeepers"><i class="fas fa-users"></i> Show Keepers</a>
                        </li> 
                        <li class="nav-item">
                            <a class="nav-link p-1" href="<?php echo FRONT_ROOT ?>Booking/showBookings"><i class="fas fa-calendar-check"></i> Show Bookings</a>
                        </li> 
                        <li class="nav-item">
                            <a class="nav-link p-1" href="<?php echo FRONT_ROOT ?>Booking/searchBooking"><i class="fas fa-search"></i> Booking with Keepers</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link p-1" href="<?php echo FRONT_ROOT ?>Review/getAllReviews"><i class="fas fa-comment"></i> Review Menu</a>
                        </li> 
                        <li class="nav-item position-relative">
                            <a class="nav-link d-inline-block position-relative" href="<?php echo FRONT_ROOT ?>Message/getChats">
                                <i class="fas fa-calendar"></i> Chats
                                <?php if ($total > 0): ?>
                                    <!-- Mostrar el contador de mensajes sobre el enlace "Chats" -->
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="currentCountID">
                                        <?php echo $total; ?>
                                    </span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link p-1" href="<?php echo FRONT_ROOT ?>User/logOut"><i class="fas fa-sign-out-alt"></i> Log Out</a>
                        </li> 
                    </ul>
                </div>
            </nav>
        </header>
    <?php endif; ?>

    <?php if ($userRole == 1):?>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
            <div class="container d-flex flex-wrap justify-content-center mt-3" id="containerID">
                <ul class="nav flex-column flex-sm-row">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo FRONT_ROOT ?>User/editUser"><i class="fas fa-users-cog"></i> All Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo FRONT_ROOT ?>Keeper/showKeepers"><i class="fas fa-users-cog"></i> All Keepers</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo FRONT_ROOT ?>Review/getAllReviews"><i class="fas fa-star"></i> All Reviews</a>
                    </li>   
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo FRONT_ROOT ?>Pet/showPets"><i class="fas fa-dog"></i> All Pets</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo FRONT_ROOT ?>Booking/showBookings"><i class="fas fa-calendar-check"></i> All Bookings</a>
                    </li>
                    <li class="nav-item position-relative">
                            <a class="nav-link d-inline-block position-relative" href="<?php echo FRONT_ROOT ?>Message/getChats">
                                <i class="fas fa-calendar"></i> Chats
                                <?php if ($total > 0): ?>
                                    <!-- Mostrar el contador de mensajes sobre el enlace "Chats" -->
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="currentCountID">
                                        <?php echo $total; ?>
                                    </span>
                                <?php endif; ?>
                            </a>
                        </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo FRONT_ROOT ?>ChatAdmin/getViewChatInformation"><i class="fas fa-calendar-check"></i> Chat Administration</a>
                    </li> 
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo FRONT_ROOT ?>User/logOut"><i class="fas fa-sign-out-alt"></i> Log Out</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <?php endif; ?>
</body>
</html>
