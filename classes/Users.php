<?php

class User
{
    // connection and table name
    private $conn;
    private $table_name = 'users';

    // instance variables (private)
    public $user_id;
    public $fname;
    public $mname;
    public $lname;
    public $email;
    public $password;
    public $contact;
    public $image;

    // constructor
    public function __construct($db)
    {
        $this->conn = $db;
    }


    // insert user
    public function create_user()
    {
        // sql query
        $sql = "INSERT INTO " . $this->table_name . " SET 
            user_fname = :fname,
            user_lname = :lname,
            user_email = :email,
            user_password = :password
        ";

        // clean data
        $this->fname = htmlspecialchars(strip_tags($this->fname));
        $this->lname = htmlspecialchars(strip_tags($this->lname));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));

        // prepare the sql statement
        $stmt = $this->conn->prepare($sql);

        // hash password
        $hash_password = password_hash($this->password, PASSWORD_BCRYPT);

        // bind the parameters 
        $stmt->bindParam(':fname', $this->fname);
        $stmt->bindParam(':lname', $this->lname);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $hash_password);

        // execute the query
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }


    // select one user
    public function get_one_user()
    {
        $sql = "SELECT * FROM " . $this->table_name . " WHERE user_id=:uid";

        // prepare the sql statement
        $stmt = $this->conn->prepare($sql);

        // bind params
        $stmt->bindParam(":uid", $this->user_id);

        // execute query
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties
        $this->user_id = $row['user_id'];
        $this->fname = $row['user_fname'];
        $this->mname = $row['user_mname'];
        $this->lname = $row['user_lname'];
        $this->contact = $row['phone_number'];
        $this->image = $row['user_image'];
        $this->email = $row['user_email'];
    }


    // select all users
    public function get_users()
    {
        $sql = "SELECT 
        u.user_id,
        u.user_fname,
        u.user_mname,
        u.user_lname,
        u.user_email,
        u.user_image,
        u.phone_number
        
        FROM " . $this->table_name . " u";

        // prepare the sql statement
        $stmt = $this->conn->prepare($sql);

        // Execute query
        $stmt->execute();

        return $stmt;
    }


    // check duplicates
    public function check_duplicates()
    {
        $sql = "SELECT user_email FROM " . $this->table_name . " WHERE user_email=:email";

        // prepare the sql statement
        $stmt = $this->conn->prepare($sql);

        // clean the email
        $this->email = htmlspecialchars(strip_tags($this->email));

        // bind the params
        $stmt->bindParam(":email", $this->email);

        // Execute query
        $stmt->execute();

        return $stmt;
    }


    // update user
    public function update_user()
    {
        $sql = "UPDATE " . $this->table_name . " u SET
            u.user_fname = :fname,
            u.user_mname = :mname,
            u.user_lname = :lname,
            u.phone_number = :contact
        WHERE u.user_id = :id";


        // prepare the sql statement
        $stmt = $this->conn->prepare($sql);

        // clean the data
        $this->fname = htmlspecialchars(strip_tags($this->fname));
        $this->mname = htmlspecialchars(strip_tags($this->mname));
        $this->lname = htmlspecialchars(strip_tags($this->lname));
        $this->contact = htmlspecialchars(strip_tags($this->contact));

        // bind parameters
        $stmt->bindParam(":id", $this->user_id);
        $stmt->bindParam(":fname", $this->fname);
        $stmt->bindParam(":mname", $this->mname);
        $stmt->bindParam(":lname", $this->lname);
        $stmt->bindParam(":contact", $this->contact);

        // execute the query
        $stmt->execute();

        return $stmt;
    }


    // update password


    // verify login
    public function verify_login()
    {
        $sql = "SELECT * FROM " . $this->table_name . " u WHERE u.user_email = :email";

        // prepare the statement
        $stmt = $this->conn->prepare($sql);

        // clean the data
        $this->email = htmlspecialchars(strip_tags($this->email));

        // bind the parameters
        $stmt->bindParam(":email", $this->email);

        // execute the query
        $stmt->execute();

        return $stmt;
    }
}
