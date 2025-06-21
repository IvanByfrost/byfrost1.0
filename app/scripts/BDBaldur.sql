CREATE TABLE mainUser ( 
    userId INT PRIMARY KEY AUTO_INCREMENT, 
    userDocument VARCHAR(40) NOT NULL UNIQUE, 
    nameUser VARCHAR(50) NOT NULL, 
    lastnameUser VARCHAR(60) NOT NULL, 
    credType VARCHAR(2) NOT NULL, 
    addressUser VARCHAR(60) NOT NULL, 
    dob DATE NOT NULL, 
    userPassword VARBINARY(255) NOT NULL, 
    userStatus BIT NOT NULL DEFAULT 1, 
    phoneNumber text NOT null, 
    UserEmail text NOT null);