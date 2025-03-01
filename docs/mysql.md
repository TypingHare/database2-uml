## MySQL

### Change Password

Run the following MySQL queries to change password:

```mysql
ALTER USER 'root'@'localhost' IDENTIFIED BY '<new_password>';
FLUSH PRIVILEGES;
```

For instance, if the user is `root`, the hostname is `localhost`, and the password is an empty string, execute:

~~~sql
ALTER USER 'root'@'localhost' IDENTIFIED BY '';
FLUSH PRIVILEGES;
~~~

