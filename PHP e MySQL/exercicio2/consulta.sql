CREATE TABLE endereco_t6 ( 
    id int PRIMARY KEY AUTO_INCREMENT, 
    cep char(10), 
    endereco varchar(100), 
    bairro varchar(50), 
    cidade varchar(50), 
    estado CHAR(2) 
) ENGINE=InnoDB;