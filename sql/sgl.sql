DROP DATABASE IF EXISTS ex_tracker;
CREATE DATABASE ex_tracker;
USE ex_tracker;

CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATE NOT NULL DEFAULT CURRENT_DATE
);
CREATE TABLE accounts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    balance INT NOT NULL DEFAULT 0,
    start_balance INT NOT NULL DEFAULT 0,
    title VARCHAR(80) NOT NULL,
    user_id INT NOT NULL,
    FOREIGN KEY(user_id) REFERENCES users(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);
CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(80) NOT NULL,
    icon VARCHAR(80),
    hidden BOOL NOT NULL,
    expense BOOL NOT NULL,
    transfer BOOL NOT NULL,
    user_id INT NOT NULL,
    FOREIGN KEY(user_id) REFERENCES users(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);
CREATE TABLE transactions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    sum INT NOT NULL,
    expense BOOL NOT NULL,
    show_in_balance BOOL NOT NULL,
    create_at DATE DEFAULT CURRENT_DATE,
    user_id INT NOT NULL,
    account_id INT NOT NULL,
    category_id INT NOT NULL,
    FOREIGN KEY(account_id) REFERENCES accounts(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY(category_id) REFERENCES categories(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY(user_id) REFERENCES users(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

INSERT INTO users(id, username, password)
VALUES (null, 'user', '198988355201ae3a759e0a5b534b19ce'),
       (null, 'root', 'a9b7ba70783b617e9998dc4dd82eb3c5');

INSERT INTO accounts(id, balance, start_balance, title, user_id)
VALUES (null, 3500, 2000, 'N26', 1),
       (null, 1500, 800, 'Cash', 1),
       (null, 1000, 300, 'Cash', 2);

INSERT INTO categories(id, title, icon, hidden, expense, transfer,user_id)
VALUES (null, 'Supermarket', 'fa-shopping-cart', false, true, false, 1),
       (null, 'Household', 'fa-home', false, true, false, 1),
       (null, 'Supermarket', 'fa-shopping-cart', false, true, false, 2);

INSERT INTO transactions(id, sum, expense, show_in_balance, user_id, account_id, category_id)
VALUES (null, 23.5, true, true, 1, 1, 1),
       (null, 13, true, true, 1, 2, 2),
       (null, 3, true, true, 2, 3, 3);

SELECT
    transactions.id, sum, transactions.expense,
    show_in_balance, create_at,
    a.title AS account,
    c.title AS category
FROM transactions
         LEFT JOIN accounts a ON a.id = transactions.account_id
         LEFT JOIN categories c ON c.id = transactions.category_id
WHERE transactions.user_id = 1
GROUP BY transactions.id
LIMIT ?;

SELECT
    transactions.id, sum, transactions.expense,
    show_in_balance, create_at,
    group_concat(a.title
                 order by a.user_id
                 separator ', ') AS account,
    c.title AS category
FROM transactions
         LEFT JOIN accounts a ON a.id = transactions.account_id
         LEFT JOIN categories c ON c.id = transactions.category_id
WHERE transactions.user_id = 1
GROUP BY transactions.id;

SELECT
    SUM(transactions.sum),
    EXTRACT(month FROM transactions.create_at)
FROM transactions
         LEFT JOIN accounts a ON a.id = transactions.account_id
         LEFT JOIN categories c ON c.id = transactions.category_id
WHERE transactions.user_id = 1
  AND a.user_id = 1
  AND c.user_id = 1
  AND EXTRACT(year FROM transactions.create_at) = 2023
  AND EXTRACT(month FROM transactions.create_at) = 1
  AND transactions.expense = 1
LIMIT 20;

CREATE VIEW amount_per_year AS SELECT
   CAST(SUM(transactions.sum) AS DECIMAL(10, 2)) AS total
FROM transactions
WHERE transactions.user_id = 1
 AND EXTRACT(year FROM transactions.create_at) = 2023
 AND transactions.expense = 1
group by EXTRACT(month FROM transactions.create_at)
LIMIT 20;

SELECT MAX(total) from amount_per_year;