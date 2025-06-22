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
        UserEmail text NOT null
    );

ALTER TABLE mainUser ADD UNIQUE (userDocument);

CREATE INDEX idx_userDocument ON mainUser (userDocument);

CREATE TABLE roleUser (
    roleId INT PRIMARY KEY AUTO_INCREMENT,
    roleName VARCHAR(50) NOT NULL UNIQUE,
    userId INT NOT NULL,
    FOREIGN KEY (userId) REFERENCES user_main(user_main_id)
)