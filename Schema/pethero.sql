CREATE DATABASE petheros;

Use petheros;

CREATE TABLE Roles(
  roleID TINYINT(1) NOT NULL,
  roleName VARCHAR(50) NOT NULL,
  PRIMARY KEY (roleID)
)ENGINE=InnoDB;

CREATE TABLE User (
  userID int(11) NOT NULL AUTO_INCREMENT,
  firstName varchar(30) DEFAULT NULL,
  lastName varchar(30) DEFAULT NULL,
  email varchar(100) NOT NULL,
  cellphone varchar(20) DEFAULT NULL,
  birthdate date DEFAULT NULL,
  password varchar(32) NOT NULL,
  userDescription varchar(255) DEFAULT NULL,
  questionRecovery varchar(80) DEFAULT NULL,
  answerRecovery varchar(120) DEFAULT NULL,
  userImage varchar(255),
  roleID tinyint(1) NOT NULL,
  status BOOLEAN NOT NULL DEFAULT 1,--1 activo, 0 eliminado
  CONSTRAINT FK_UserRole FOREIGN KEY (roleID) REFERENCES Roles(roleID),
  PRIMARY KEY (userID),
  UNIQUE KEY email (email)
) ENGINE=InnoDB;

CREATE TABLE Owner (
  ownerID int(11) NOT NULL AUTO_INCREMENT,
  userID int(11) NOT NULL references User(userID),
  petAmount int(30) DEFAULT NULL,
  PRIMARY KEY (ownerID)
) ENGINE=InnoDB;

CREATE TABLE Keeper (
  keeperID int(11) NOT NULL AUTO_INCREMENT,
  userID int(11) NOT NULL references User(userID),
  animalSize varchar(30) DEFAULT NULL,
  price DECIMAL (10,3) DEFAULT NULL,
  cbu varchar (20) UNIQUE KEY, 
  rank DECIMAL (30,6) DEFAULT 0,
  PRIMARY KEY (keeperID)
) ENGINE=InnoDB;

CREATE TABLE Breed (
  breedID int(11) NOT NULL AUTO_INCREMENT,
  name varchar(50) NOT NULL UNIQUE KEY,
  PRIMARY KEY (breedID)
) ENGINE=InnoDB;

CREATE TABLE Pet (
  petID int(11) NOT NULL AUTO_INCREMENT,
  ownerID int(11) NOT NULL references Owner(ownerID),
  breedID int(11) NOT NULL references Breed(breedID),
  petDetails varchar(255) DEFAULT NULL,
  petName varchar(30) DEFAULT NULL,
  petSize varchar(20) NOT NULL,
  petVideo VARCHAR(255) NOT NULL,
  petImage VARCHAR(255) NOT NULL,
  petVaccinationPlan VARCHAR(255) NOT NULL,
  petWeight varchar(20) DEFAULT NULL,
  petAge int DEFAULT NULL,
  PRIMARY KEY (petID)
) ENGINE=InnoDB;

CREATE TABLE Booking (
  bookingID int(11) NOT NULL AUTO_INCREMENT,
  keeperID int(11) NOT NULL references Keeper(keeperID),
  petID int(11) NOT NULL references Pet(petID),
  status int(11) NOT NULL references Status(statusID),
  totalValue DECIMAL(10,3),
  amountReservation DECIMAL(10,3),
  startDate DATE NOT NULL, -- Fecha de inicio de la reserva
  endDate DATE NOT NULL,   -- Fecha de fin de la reserva
  payment varchar(255) DEFAULT NULL,
  PRIMARY KEY (bookingID)
) ENGINE=InnoDB;

CREATE TABLE KeeperDays (
  keeperDaysID int(11) NOT NULL AUTO_INCREMENT,
  keeperID int(11) NOT NULL references Keeper(keeperID),
  day DATE NOT NULL, -- Dia
  available BOOLEAN NOT NULL DEFAULT TRUE,  -- True es disponible
  PRIMARY KEY (keeperDaysID, keeperID, day)
) ENGINE=InnoDB;

CREATE TABLE Status(
	statusID int(11) NOT NULL AUTO_INCREMENT,
    name varchar(40) NOT NULL UNIQUE KEY,
    PRIMARY KEY (statusID)
) ENGINE=InnoDB;

CREATE TABLE Review(
	reviewID int(11) NOT NULL AUTO_INCREMENT,
    description varchar(255),
    rank tinyint(5),
    bookingID int(11) NOT NULL UNIQUE references Booking(bookingID),
    PRIMARY KEY (reviewID)
) ENGINE=InnoDB;

/*Mensajeria*/
CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT DEFAULT NULL,
    message TEXT NOT NULL,
    send_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_read BOOLEAN DEFAULT 0,
    group_id INT,
    FOREIGN KEY (group_id) REFERENCES  groups(id),
    FOREIGN KEY (sender_id) REFERENCES user(userID),
    FOREIGN KEY (receiver_id) REFERENCES user(userID)
)ENGINE=InnoDB;

CREATE TABLE group_message_reads (
    id INT AUTO_INCREMENT PRIMARY KEY,
    message_id INT NOT NULL,
    user_id INT NOT NULL,
    group_id INT NOT NULL,
    is_read BOOLEAN DEFAULT 0,
    read_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (message_id) REFERENCES messages(id),
    FOREIGN KEY (user_id) REFERENCES user(userID),
    FOREIGN KEY (group_id) REFERENCES groups(id)
) ENGINE=InnoDB;

/*Grupos*/
CREATE TABLE group_status (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE,
    is_active BOOLEAN DEFAULT 1,
    description TEXT NOT NULL
) ENGINE=InnoDB;

CREATE TABLE group_privacy (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL UNIQUE,
    is_active BOOLEAN DEFAULT 1,
    description TEXT NOT NULL
) ENGINE=InnoDB;

CREATE TABLE group_role (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE,
    is_active BOOLEAN DEFAULT 1,
    description TEXT NOT NULL
)ENGINE=InnoDB;

CREATE TABLE group_type(
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL UNIQUE,
  is_active BOOLEAN DEFAULT 1,
  description TEXT NOT NULL
)ENGINE=InnoDB;

CREATE TABLE groups (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100),
    created_by INT, -- Referencia al usuario que creó el grupo
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    group_type INT NOT NULL,
    status_id INT NOT NULL,
    group_privacy INT NOT NULL,
    groupInfo_id INT NOT NULL,
    FOREIGN KEY (groupInfo_id) REFERENCES group_info(id),
    FOREIGN KEY (created_by) REFERENCES user(userID),
    FOREIGN KEY (group_type) REFERENCES group_type(id),
    FOREIGN KEY (status_id) REFERENCES group_status(id),
    FOREIGN KEY (group_privacy) REFERENCES group_privacy(id)
)ENGINE=InnoDB;

CREATE TABLE `group_members`(
    id INT PRIMARY KEY AUTO_INCREMENT,
    group_id INT,
    user_id INT,
    joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    status TINYINT(3) DEFAULT 1,
    role INT DEFAULT 3,
	  FOREIGN KEY (user_id) REFERENCES user(userID),
    FOREIGN KEY (role) REFERENCES  group_role(id),
    FOREIGN KEY (group_id) REFERENCES groups(id)
)ENGINE=InnoDB;

CREATE TABLE group_info(
  id INT PRIMARY KEY AUTO_INCREMENT,
  description TEXT,
  rules TEXT,
  image VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  start_date DATE DEFAULT NULL,
  end_date DATE DEFAULT NULL
)ENGINE=InnoDB;

/*Flujo de invitaciones a grupos*/
CREATE TABLE invitation_status (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL UNIQUE, --Pendiente,aceptada,Rechazada
  is_active BOOLEAN DEFAULT 1, 
  description TEXT NOT NULL
)ENGINE=InnoDB;

CREATE TABLE group_invitations(
  id INT PRIMARY KEY AUTO_INCREMENT,
  group_id INT NOT NULL,
  invited_by INT NOT NULL,
  invited_user_id INT NOT NULL,
  status_id INT NOT NULL,
  sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  responded_at TIMESTAMP DEFAULT NULL,
  roleInvited INT(11) NOT NULL, 
  message TEXT,
  FOREIGN KEY (group_id) REFERENCES groups(id),
  FOREIGN KEY (invited_by) REFERENCES user(userID),
  FOREIGN KEY (invited_user_id) REFERENCES user(userID),
  FOREIGN KEY (status_id) REFERENCES invitation_status(id),
  FOREIGN KEY (roleInvited) REFERENCES group_role(id),
)ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Flujo incidencias*/
CREATE TABLE incident_type (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL UNIQUE,
  description TEXT NOT NULL,
  is_active BOOLEAN DEFAULT 1
) ENGINE=InnoDB;

CREATE TABLE incident_status (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL UNIQUE,
  description TEXT NOT NULL,
  is_active BOOLEAN DEFAULT 1
) ENGINE=InnoDB;

CREATE TABLE incidents (
  id INT PRIMARY KEY AUTO_INCREMENT,
  idUsuario INT NOT NULL,
  incidentTypeId INT NOT NULL,
  incidentStatus INT NOT NULL,
  incidentDate DATETIME DEFAULT CURRENT_TIMESTAMP,
  description TEXT NOT NULL,
  FOREIGN KEY (idUsuario) REFERENCES user(userId), 
  FOREIGN KEY (incidentTypeId) REFERENCES incident_type(id),
  FOREIGN KEY (incidentStatus) REFERENCES incident_status(id)
) ENGINE=InnoDB;

CREATE TABLE incident_answer (
  id INT PRIMARY KEY AUTO_INCREMENT,
  idIncident INT NOT NULL,
  idUser INT NOT NULL,
  answerDate DATETIME DEFAULT CURRENT_TIMESTAMP,
  answer TEXT NOT NULL,
  FOREIGN KEY (idIncident) REFERENCES incidents(id),
  FOREIGN KEY (idUser) REFERENCES user(userId)
) ENGINE=InnoDB;

CREATE TABLE incident_files (
    id INT AUTO_INCREMENT PRIMARY KEY,
    incident_id INT NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (incident_id) REFERENCES incidents(id)
) ENGINE=InnoDB;

INSERT INTO incident_status (name, description, is_active) VALUES
  ('Abierto', 'La incidencia ha sido reportada y está pendiente de atención.', 1),
  ('En Progreso', 'La incidencia está siendo atendida por el equipo correspondiente.', 1),
  ('Resuelto', 'La incidencia ha sido solucionada', 1),
  ('Cerrado', 'La incidencia está cerrada', 1),
  ('Reabierto', 'La incidencia ha sido reabierta para atención adicional.', 1);

INSERT INTO incident_type (name, description, is_active) VALUES 
  ('Reservas', 'Incidencias relacionadas con las reservas.', 1),
  ('Mascotas', 'Incidencias relacionadas con las mascotas.', 1),
  ('Grupos', 'Incidencias relacionadas con los grupos.', 1),
  ('Reseñas', 'Incidencias relacionadas con las reseñas.', 1),
  ('General', 'Incidencias generales.', 1),
  ('Usuarios', 'Incidencias relacionadas con los usuarios.', 1),
  ('Pagos', 'Incidencias relacionadas con los pagos.', 1),
  ('Notificaciones', 'Incidencias relacionadas con notificaciones del sistema.', 1);

  
/*Insets Groups*/
INSERT INTO group_status (id, name,is_active, description) VALUES 
  (1, 'Activo',1, 'El grupo está activo y puede ser accedido por los usuarios con los permisos adecuados.'),
  (2, 'Inactivo',1, 'El grupo está inactivo y no aparece en las búsquedas o listados, pero no está eliminado permanentemente.'),
  (3, 'Eliminado',1, 'El grupo ha sido eliminado y no puede ser recuperado ni accedido.'),
  (4, 'Bloqueado',1,'El grupo ha sido bloqueado, generalmente por incumplir alguna norma, y no puede ser accedido hasta que sea desbloqueado.');


INSERT INTO group_privacy (id, privacy_type,is_active, description) VALUES
  (1, 'Público',1, 'Los grupos públicos son accesibles para cualquier usuario. Cualquier persona puede ver los contenidos y unirse sin necesidad de aprobación.'),
  (2, 'Privado',1, 'Los grupos privados requieren aprobación para unirse. Solo los miembros pueden ver los contenidos y participar en las discusiones.'),
  (3, 'Educativo',1, 'Grupos diseñados específicamente para propósitos educativos, donde los administradores gestionan el acceso y los contenidos están orientados al aprendizaje.'),
  (4, 'Secreto',1, 'Los grupos secretos no son visibles en las búsquedas ni listados. Solo los miembros invitados pueden ver su existencia y acceder a los contenidos.'),
  (5, 'Eventos',1, 'Grupos creados para la organización y gestión de eventos. Pueden ser temporales y permitir diferentes tipos de acceso según el evento.');


INSERT INTO group_role (id, name,is_active, description) VALUES
  (1, 'Creador',1,'El usuario que fundó el grupo, con autoridad máxima, incluso sobre los administradores.'),
  (2, 'Administrador',1,'Tiene control total sobre el grupo, puede gestionar miembros, contenido y la configuración del grupo.'),
  (3, 'Miembro',1 ,'Participa en el grupo con acceso para ver y contribuir, pero no puede gestionar otros usuarios o configuración.'),
  (4, 'Invitado',1 ,'Un miembro temporal con permisos limitados, ideal para eventos o grupos de acceso restringido.');

INSERT INTO group_type (id, name,is_active, description) VALUES 
  (1,'Grupo', 1, 'Un grupo estándar donde los miembros pueden interactuar'),
  (2,'Canal', 1, 'Un canal donde solo los administradores pueden publicar contenido'),
  (3,'Comunidad', 1, 'Una comunidad para interactuar libremente en un tema general'),
  (4,'Foro', 1, 'Una estructura de discusión organizada por temas o hilos'),
  (5,'Proyecto', 1, 'Grupo enfocado en tareas y objetivos concretos'),
  (6,'Evento', 1, 'Grupo orientado a un evento específico con tiempo limitado de actividad');


INSERT INTO invitation_status (id, name, description) VALUES 
  (1, 'Pendiente', 'La invitación ha sido enviada pero aún no ha sido respondida.'),
  (2, 'Aceptada', 'La invitación ha sido aceptada por el usuario.'),
  (3, 'Rechazada', 'La invitación ha sido rechazada por el usuario.'),
  (4, 'Vencida', 'La invitación ha expirado y ya no es válida.');

/*Insets Groups*/

INSERT INTO roles (roleID, roleName) VALUES (1, 'Admin'), (2, 'Owner'), (3, 'Keeper');

/*                                INSERT DE STATUS EN LA TABLA STATUS                       */
INSERT INTO status VALUES ("1","Pending"),("2","Rejected"),("3","Waiting for Payment"),("4","Waiting for confirmation"),("5","Confirmed"),("6","Finish"),("7","Completed"),("8","Overdue");
/*                                INSERT DE STATUS EN LA TABLA STATUS                       */

/*                                INSERT DE USUARIO ADMIN                                   */
INSERT INTO User (firstName, lastName, email, cellphone, birthdate, password, userDescription, questionRecovery, answerRecovery, userImage, roleID, status)
  VALUES ('Admin', 'Admin', 'admin@pethero.com', '1234567890', '1990-01-01', MD5('123456'), 'Admin', 'What is your pet\'s name?'', 'Fluffy', '', 1, 1);
/*                                INSERT DE USUARIO ADMIN                                   */

/*                                    INSERT DE BREEDS EN TABLA BREED                       */
INSERT INTO breed VALUES 
  ("1", "Beagle"), 
  ("2", "Chihuahua"), 
  ("3", "Bulldog"), 
  ("4", "German Shepherd"),
  ("5", "Shih-tzu"), 
  ("6", "Dogo"), 
  ("7", "Golden Retriever"), 
  ("8", "Fox Terrier"), 
  ("9", "Whippet"),
  ("10", "Pinscher"), 
  ("11", "Cocker"), 
  ("12", "Shiba Inu"), 
  ("13", "Doberman"), 
  ("14", "Border Collie"), 
  ("15", "Yorkshire"),
  ("16", "Poodle"),
  ("17", "Rottweiler"),
  ("18", "Labrador Retriever"),
  ("19", "Pug"),
  ("20", "Siberian Husky"),
  ("21", "Boxer"),
  ("22", "Dalmatian"),
  ("23", "Maltese"),
  ("24", "Saint Bernard"),
  ("25", "Cavalier King Charles Spaniel"),
  ("26", "French Bulldog"),
  ("27", "Great Dane"),
  ("28", "Basenji"),
  ("29", "Akita"),
  ("30", "Alaskan Malamute"),
  ("31", "Samoyed"),
  ("32", "Basset Hound"),
  ("33", "Australian Shepherd"),
  ("34", "Pembroke Welsh Corgi"),
  ("35", "Bichon Frise"),
  ("36", "Papillon"),
  ("37", "Jack Russell Terrier"),
  ("38", "Weimaraner"),
  ("39", "Bull Terrier"),
  ("40", "Pekingese"),
  ("41", "Staffordshire Bull Terrier"),
  ("42", "Airedale Terrier"),
  ("43", "Cane Corso"),
  ("44", "English Setter"),
  ("45", "Saluki"),
  ("46", "Italian Greyhound"),
  ("47", "Portuguese Water Dog"),
  ("48", "Tibetan Mastiff"),
  ("49", "Chow Chow"),
  ("50", "Irish Wolfhound");
/*                                    INSERT DE BREEDS EN TABLA BREED                       */

/*Validacion para ver si los jobs estan activos (Por defecto no en mysql) */
SHOW VARIABLES LIKE 'event_scheduler';
SET GLOBAL event_scheduler = ON;

/*Job de actualización de estados*/
CREATE EVENT IF NOT EXISTS update_reservations_status
  ON SCHEDULE EVERY 5 MINUTE
  DO

    UPDATE Booking
    SET status = 6  
    WHERE status = 5
    AND endDate < NOW();
    
    UPDATE Booking
    SET status = 8
    WHERE status IN (1, 3, 4)
    AND endDate < NOW();

/*Job vencimiento de invitaciones - luego de 3 dias se vencen*/
CREATE EVENT IF NOT EXISTS update_invitation_status
  ON SCHEDULE EVERY 1 DAY
  DO
    UPDATE group_invitations
    SET status_id = 4
    WHERE status_id = 1 
    AND send_at < NOW() - INTERVAL 3 DAY
    AND responded_at IS NULL;

/*Job para marcar leidos los mensajes en el caso de que el usuario sea eliminado o salga del grupo*/
CREATE EVENT update_messages_for_inactive_members
  ON SCHEDULE EVERY 1 HOUR
  DO
  update group_message_reads SET is_read=1
  WHERE is_read=0 
  and user_id in (SELECT DISTINCT gm.user_id from group_members gm where gm.status=0);

/*Job para eliminar grupos de tipo evento que finalizaron su rango de fechas*/
CREATE EVENT updateGroupEventStatus
	ON SCHEDULE EVERY 1 DAY
	DO
		UPDATE groups g
		SET g.status_id = 3
		WHERE g.status_id = 1 
		  AND EXISTS (
			  SELECT 1
			  FROM group_info gi
			  WHERE gi.id = g.groupInfo_id
				AND gi.end_date < NOW()
		  );


/*Para ver los jobs*/
SELECT * FROM information_schema. EVENTS;