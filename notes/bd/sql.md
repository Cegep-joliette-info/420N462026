# MariaDB - SQL

Le SQL de MariaDB est simple:

## Select

```sql
SELECT * FROM users; /* avoir tous les utilisateurs */
SELECT DISTINCT roles FROM users; /* avoir tous les roles des utilisateurs, mais seulement 1 fois chaque */
SELECT * FROM users WHERE id = 1; /* avoir l'id 1 */
SELECT username as name FROM users as u; /* renommer la colonne username pour le retour et la table users pour la requête */
SELECT * FROM users LIMIT 5 OFFSET 10; /* avoir les users 11 à 15 (si on commence à 1) */
SELECT * FROM users LIMIT 10, 5; /* Même chose que la ligne précédente */
```

Le langage a les structures classiques (JOIN, LEFT JOIN, UNION, etc.). Dans l'ordre, les instructions sont:

 1. SELECT
 2. DISTINCT
 3. FROM
 4. WHERE
 5. GROUP BY
 6. HAVING
 7. ORDER BY
 8. LIMIT

## insert, update et delete

Exemples de: https://mariadb.com/kb/en/

```sql
INSERT INTO person (first_name, last_name) VALUES ('John', 'Doe'); /* Ajoute un enregistrement */
INSERT INTO person SET first_name = 'John', last_name = 'Doe'; /* Même chose que la précédente  */
INSERT INTO tbl_name VALUES (1, "row 1"), (2, "row 2"); /* Ajoute 2 enregistrements  */
INSERT INTO contractor SELECT * FROM person WHERE status = 'c'; /* Ajoute des enregistrements à partir d'un select  */

UPDATE table_name SET column1 = value1, column2 = value2 WHERE id=100; /* Modifie un enregistrement */
UPDATE tab1, tab2 SET tab1.column1 = value1, tab1.column2 = value2 WHERE tab1.id = tab2.id; /* Modifie des enregistrements de deux tables */

DELETE FROM page_hit ORDER BY timestamp LIMIT 1000000; /* Supprime le million de message plus vieux */
DELETE post FROM blog INNER JOIN post WHERE blog.id = post.blog_id; /* Supprime les éléments de post qui sont liés à un blog */
```

## create table

On crée souvent des tables via PHP (puisqu'on n'a pas toujours accès au PhpMyAdmin), voici un exemple de synthaxe:

```sql
CREATE OR REPLACE TABLE table_name (a int);
```

OU

```sql
DROP TABLE IF EXISTS table_name;
CREATE TABLE table_name (a int);
```

On ajoute souvent pleins de Timestamp en Web (heure de création, modification et suppression par exemple). Exemple pour la date de création:

```sql
CREATE TABLE test.`user` (
	ID INT UNSIGNED auto_increment NOT NULL,
	username varchar(100) NOT NULL,
	password varchar(100) NOT NULL,
	date_created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
	CONSTRAINT user_PK PRIMARY KEY (ID)
);
```