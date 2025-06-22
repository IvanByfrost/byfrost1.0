CREATE TABLE
    mainUser (
        userId INT PRIMARY KEY AUTO_INCREMENT,
        userDocument VARCHAR(40) NOT NULL UNIQUE,
        userName VARCHAR(50) NOT NULL,
        lastnameUser VARCHAR(60) NOT NULL,
        credType VARCHAR(2) NOT NULL,
        addressUser VARCHAR(60) NOT NULL,
        dob DATE NOT NULL,
        userPassword VARBINARY(255) NOT NULL,
        userStatus BIT NOT NULL DEFAULT 1,
        userPhone text NOT null,
        UserEmail text NOT null,
        roleId INT NULL,
        FOREIGN KEY (roleId) REFERENCES roles(roleId)
    );

ALTER TABLE mainUser ADD UNIQUE (userDocument);

CREATE INDEX idx_userDocument ON mainUser (userDocument);

CREATE TABLE
    roles (
        roleId INT PRIMARY KEY AUTO_INCREMENT,
        roleName VARCHAR(50) NOT NULL UNIQUE
    );
ALTER TABLE mainUser ADD roleId INT;
ALTER TABLE mainUser ADD FOREIGN KEY (roleId) REFERENCES roles(roleId);

//Consulta de usuario por rol

SELECT u.userId, u.userDocument, r.roleName
FROM mainUser u
JOIN roles r ON u.roleId = r.roleId
WHERE u.userDocument = :userDocument AND u.userPassword = :userPassword

//Insert de los roles
INSERT INTO roles (roleName)
VALUES 
  ('root'),
  ('headmaster'),
  ('treasurer'),
  ('coordinator'),
  ('teacher'),
  ('parent'),
  ('student');

  SELECT u.*, r.roleName AS rol
FROM mainUser u
JOIN roles r ON u.roleId = r.id