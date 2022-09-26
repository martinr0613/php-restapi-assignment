# php-restapi-assignment


Prerequisites: <br/>
<ul>
  <li>PHP <br/></li>
  <li>MySQL <br/></li>
  <li>Postman <br/></li>
</ul>

# Create Database
Open MySQL terminal 
<ul>
  <li>Create database</li>
  <li>Create users table</li>
  <li>Create parcels table</li>
  <li>Insert data into users table</li>
  <li>Insert data into parcels table</li>
</ul>

# Start PHP server
php -S localhost:3000

# config.php
Contains constants needed for connecting your database

# Postman
For testing APIs, e.g. <br>
localhost:3000/users/ <br>
localhost:3000/parcels/ <br>
localhost:3000/parcels/1 <br>

# URIs
<table>
  <tr>
    <td> GET /users </td>
    <td> Get all users </td>
  </tr>
   <tr>
    <td> GET /parcels </td>
    <td> Get all parcels </td>
  </tr>
   <tr>
    <td> GET /users/id </td>
    <td> Get specific user  </td>
  </tr>
  <tr>
    <td> GET /parcels/id </td>
    <td> Get specific parcel  </td>
  </tr>
  <tr>
    <td> POST /users </td>
    <td> Create new user  </td>
  </tr>
  <tr>
    <td> POST /users </td>
    <td> Create new parcel  </td>
  </tr>
</table>
