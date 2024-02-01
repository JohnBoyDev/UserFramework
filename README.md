# UserFramework

![Static Badge](https://img.shields.io/badge/PHP-8.1.6-474a8a?style=flat-square&logo=php)
![Static Badge](https://img.shields.io/badge/MariaDB-10.4.24-006064?style=flat-square&logo=MariaDB)
![Static Badge](https://img.shields.io/badge/License-GNU%20GPL3-643464?style=flat-square&logo=gnu)

A PHP-based framework for building authentication designed in object-oriented programming.

#### Table of Contents
* [Planning](#planning)
* [Key Features](#key-features)
* [Coding Example](#coding-example)
* [Web Design Screenshot](#web-design-screenshot)
* [Outside Resources](#outside-resources)

## Planning

The key to this framework is understanding how to create authentication in PHP while keeping it secure. The key points of security are:
* Escaping inputs from users and sanitizing them
* Disallow unauthorized attempts to edit an account or log in
* Ensuring there is no ability for [XSS](https://en.wikipedia.org/wiki/Cross-site_scripting) attacks

The design of the framework is only what is necessary. Note that this framework is not meant to go up against any other frameworks, such as [Laravel](https://laravel.com/) or [Symfony](https://symfony.com/). This framework is a learning experiment of safe, useful, and secure coding practices within PHP and Object-Oriented Programming.

The use of a database is required, and for interfacing with the database, the use of [PDO](https://www.php.net/manual/en/book.pdo.php) is implemented. This is the safest way to interface with the database connection. Calling the database connection only as needed on a single page is more secure than calling it on every single page.

## Key Features
| Features |
| -- |
| Secure from low-level XSS attacks |
| Scalability as a framework |
| Secure [Password Hashing](https://www.php.net/manual/en/function.password-hash) and Management using PHP |
| Closing database connections once results are gathered |
| Global file handling all database connections |

## Coding Example

For example, escaping and closing the cursor can be the safest way to use a database connection in PHP. In this example of using a getter to grab information from the user, there is a switch case gathering only information that needs to be accessible. This means no gathering password hash directly from inside the web interface. There is also a catch to ensure that only users who are logged in can gather information, otherwise the function will return nothing.

```php
function getInformation($info, $id) {
        $type = htmlspecialchars($info);
        $ID = htmlspecialchars($id);

        if (!isset($_SESSION["id"])) {
            return(null);
        }

        $statement = $this->UFDatabase->prepare("SELECT User_ID, User_Name, User_Email, User_Created FROM accounts WHERE User_ID = ?");
        $statement->execute([$ID]);
        $results = $statement->fetch(PDO::FETCH_ASSOC);

        $statement->closeCursor();

        if (!empty($statement->rowCount())) {
            switch (strtolower($type)) {
                case strtolower("id"):
                    return($results["User_ID"]);
                    break;
                case strtolower("username"):
                    return($results["User_Name"]);
                    break;
                case strtolower("email"):
                    return($results["User_Email"]);
                    break;
                case strtolower("created"):
                    return($results["User_Created"]);
                    break;
                default:
                    break;
            }
        }
    }
```

PDO's [closeCursor](https://www.php.net/manual/en/pdostatement.closecursor.php) feature is very useful for freeing up a database connection. Using this feature also allows statements to be reusable for another prepared statement.

## Web Design Screenshot

Here is the account page design using BootStrap 5.3.2.

![Account Page](https://github.com/JohnBoyDev/UserFramework/blob/main/Screenshots/firefox_PozLsmO3JJ.png?raw=true)

### Outside Resources

* https://en.wikipedia.org/wiki/Cross-site_scripting
* https://laravel.com/
* https://symfony.com/
* https://www.php.net/manual/en/book.pdo.php
* https://www.php.net/manual/en/function.password-hash
* https://www.php.net/manual/en/pdostatement.closecursor.php
