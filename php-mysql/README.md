# Learning PHP + MySQL + Docker + Docker Compose

## Access Your Application
Once the containers are up and running, you can access your PHP application in your web browser at `http://localhost:8080`. You should see "Connected successfully" if everything is set up correctly.

## Database
### Database Access
You can access the MySQL database using a MySQL client with the following credentials:

- Host: `localhost` (or `mysql` if connecting from within another Docker container)
- Port: `3306`
- User: `myuser`
- Password: `mypassword`
- Database: `mydatabase`

### Database Operation
1. Create table: [[link]](http://localhost:8080/create_table.php)[(create_table.php)](src/create_table.php)
2. Insert data: [[link]](http://localhost:8080/insert_data.php)[(insert_data.php)](src/insert_data.php)
3. Query data: [[link]](http://localhost:8080/query_data.php)[(query_data.php)](src/query_data.php)
4. Update data: [[link]](http://localhost:8080/update_data.php)[(update_data.php)](src/update_data.php)
5. Delete data: [[link]](http://localhost:8080/delete_data.php)[(delete_data.php)](src/delete_data.php)
