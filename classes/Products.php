<?php

class Product
{
    // connection and table name
    private $conn;
    private $table_name = 'products';

    // instance variables
    public $prod_id;
    public $prod_name;
    public $prod_desc;
    public $prod_cat;
    public $prod_price;
    public $prod_type;
    public $prod_keywords;
    public $prod_image;
    public $cat_name;
    public $type_name;


    // constructor
    public function __construct($db)
    {
        // instance of database connection
        $this->conn = $db;
    }

    /**
     * INSERT Queries
     */

    // insert product
    public function insertProduct()
    {
        $sql = "INSERT INTO " . $this->table_name . " SET 
        product_name = :prod_name,
        product_desc = :prod_desc,
        product_cat = :prod_cat,
        product_price = :prod_price,
        product_type = :prod_type,
        product_keywords = :prod_keywords,
        product_image = :prod_image
        ";

        // clean data
        $this->prod_name = htmlspecialchars(strip_tags($this->prod_name));
        $this->prod_desc = htmlspecialchars(strip_tags($this->prod_desc));
        $this->prod_cat = htmlspecialchars(strip_tags($this->prod_cat));
        $this->prod_price = htmlspecialchars(strip_tags($this->prod_price));
        $this->prod_type = htmlspecialchars(strip_tags($this->prod_type));
        $this->prod_keywords = htmlspecialchars(strip_tags($this->prod_keywords));
        $this->prod_image = htmlspecialchars(strip_tags($this->prod_image));


        // prepare the statement
        $stmt = $this->conn->prepare($sql);

        // bind the parameters
        $stmt->bindParam(':prod_name', $this->prod_name);
        $stmt->bindParam(':prod_desc', $this->prod_desc);
        $stmt->bindParam(':prod_cat', $this->prod_cat);
        $stmt->bindParam(':prod_price', $this->prod_price);
        $stmt->bindParam(':prod_type', $this->prod_type);
        $stmt->bindParam(':prod_keywords', $this->prod_keywords);
        $stmt->bindParam(':prod_image', $this->prod_image);


        // execute the statement
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // insert a product category
    public function insertCategory()
    {
        $sql = "INSERT INTO categories  SET cat_name = :cat_name";

        // clean data
        $this->cat_name = htmlspecialchars(strip_tags($this->cat_name));

        // prepare statement
        $stmt = $this->conn->prepare($sql);

        // bind params
        $stmt->bindParam(':cat_name', $this->cat_name);

        // execute query
        return $stmt->execute() ? true : false;
    }


    // insert a product type
    public function insertType()
    {
        $sql = "INSERT INTO type  SET type_name = :type_name";

        // clean data
        $this->type_name = htmlspecialchars(strip_tags($this->type_name));

        // prepare statement
        $stmt = $this->conn->prepare($sql);

        // bind params
        $stmt->bindParam(':type_name', $this->type_name);

        // execute query
        return $stmt->execute() ? true : false;
    }


    // insert a product image
    public function insertImage()
    {
        // sql query
        $sql = "INSERT INTO " . $this->table_name . " SET product_image=:prod_image";

        // clean the data
        $this->prod_image = htmlspecialchars(strip_tags($this->prod_image));

        // prepare the statement
        $stmt = $this->conn->prepare($sql);

        // bind the parameters
        $stmt->bindParam(':prod_image', $this->prod_image);

        // execute the query
        $stmt->execute();
    }



    //--------------------------------------------------------------------------- 
    /**
     * SELECT Queries
     */

    // get all products
    public function getAllProducts()
    {
        // sql query
        $sql = "SELECT 
        p.prod_id, 
        p.product_name,
        p.product_desc,
        p.product_cat,
        p.product_price,
        p.product_type,
        p.product_keywords,
        p.product_image,
        t.type_id,
        t.type_name,
        c.cat_id,
        c.cat_name
         FROM " . $this->table_name . " p JOIN type t ON (p.product_type = t.type_id) JOIN categories c ON (p.product_cat = c.cat_id)";


        // prepare the statement
        $stmt = $this->conn->prepare($sql);


        // execute the query
        $stmt->execute();


        return $stmt;
    }

    // check for duplicates
    public function getDuplicates()
    {
        $sql = "SELECT * FROM " . $this->table_name . " WHERE product_name = :prod_name";

        // clean the data
        $this->prod_name = htmlspecialchars(strip_tags($this->prod_name));

        // prepare the statement
        $stmt = $this->conn->prepare($sql);

        // bind params
        $stmt->bindParam(':prod_name', $this->prod_name);

        // execute query
        $stmt->execute();

        // get the required data from the query
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row;
    }


    // get a single product
    public function getOneProduct()
    {
        $sql = "SELECT * FROM " . $this->table_name . " p JOIN categories ON categories.cat_id=p.product_cat JOIN type ON type_id=p.product_type WHERE p.prod_id = :prod_id";

        // clean the data
        $this->prod_id = htmlspecialchars(strip_tags($this->prod_id));

        // prepare the statement
        $stmt = $this->conn->prepare($sql);

        // bind params
        $stmt->bindParam(':prod_id', $this->prod_id);

        // execute query
        $stmt->execute();

        // get the required data from the query
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row;
    }


    // check occurrence of product name excluding the product we're updating
    public function getOccurrence()
    {
        $sql = "SELECT * FROM " . $this->table_name . " WHERE NOT prod_id = :prod_id";

        // clean data
        $this->prod_id = htmlspecialchars(strip_tags($this->prod_id));


        // prepare the statement
        $stmt = $this->conn->prepare($sql);

        // bind params
        $stmt->bindParam(':prod_id', $this->prod_id);

        // execute statement
        $stmt->execute();

        return $stmt;
    }


    // get all categories
    public function getAllCategories()
    {
        $sql = "SELECT * FROM categories";

        // prepare the statement
        $stmt = $this->conn->prepare($sql);

        // execute the statement
        $stmt->execute();

        // get the required data from the query
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $row;
    }



    // get all types
    public function getAllTypes()
    {
        $sql = "SELECT * FROM type";

        // prepare the statement
        $stmt = $this->conn->prepare($sql);

        // execute the statement
        $stmt->execute();

        // get the required data from the query
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $row;
    }

    //--------------------------------------------------------------------------- 
    /**
     * UPDATE Queries
     */


    // update a product (without image)
    public function updateProduct()
    {
        $sql = "UPDATE " . $this->table_name . " SET 
            product_name = :prod_name,
            product_desc = :prod_desc,
            product_cat = :prod_cat,
            product_price = :prod_price,
            product_type = :prod_type,
            product_keywords = :prod_keywords
        WHERE prod_id=:prod_id";

        // clean the data
        $this->prod_name = htmlspecialchars(strip_tags($this->prod_name));
        $this->prod_desc = htmlspecialchars(strip_tags($this->prod_desc));
        $this->prod_cat = htmlspecialchars(strip_tags($this->prod_cat));
        $this->prod_price = htmlspecialchars(strip_tags($this->prod_price));
        $this->prod_type = htmlspecialchars(strip_tags($this->prod_type));
        $this->prod_keywords = htmlspecialchars(strip_tags($this->prod_keywords));

        // prepare the statement
        $stmt = $this->conn->prepare($sql);

        // bind params
        $stmt->bindParam(':prod_id', $this->prod_id);
        $stmt->bindParam(':prod_name', $this->prod_name);
        $stmt->bindParam(':prod_desc', $this->prod_desc);
        $stmt->bindParam(':prod_cat', $this->prod_cat);
        $stmt->bindParam(':prod_price', $this->prod_price);
        $stmt->bindParam(':prod_type', $this->prod_type);
        $stmt->bindParam(':prod_keywords', $this->prod_keywords);

        // execute the query
        return $stmt->execute();
    }






    //--------------------------------------------------------------------------- 
    /**
     * DELETE Queries
     */


    //  delete product
    public function deleteProduct()
    {
        // sql query to delete a product
        $sql = "DELETE FROM " . $this->table_name . " WHERE prod_id=:pid";

        // clean the id
        $this->prod_id = htmlspecialchars(strip_tags($this->prod_id));

        // prepare the statement
        $stmt = $this->conn->prepare($sql);

        // bind parameters
        $stmt->bindParam(':pid', $this->prod_id);

        // execute the statement
        return $stmt->execute();
    }
}
