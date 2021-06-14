<?php
include '../../controllers/database.php';

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    echo 'Method not allowed';
}

///////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////   CATEGORY ACTIONS   ///////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////

// Create New Category

if (isset($_POST['create-category'])) {
    $title = $_POST['cat_name'];
    $status = $_POST['cat_status'];
    $sql = "INSERT INTO categories (name, status) VALUES ('$title', $status)";
    if ($conn->query($sql) == FALSE) {
        echo "Error" . $sql . $conn->error;
        exit();
    }
    header('Location: ../categories.php');
}


// Edit Category

if (isset($_POST['edit-category'])) {
    $cat_id = $_POST['category-id'];
    $cat_name = $_POST['cat_name'];
    $status = $_POST['cat_status'];

    $query = "UPDATE categories SET name ='" . $cat_name . "', status=$status WHERE id = '" . $cat_id . "'";

    if ($conn->query($query) == FALSE) {
        echo "Error" . $sql . $conn->error;
        exit();
    }

    header('Location: ../categories.php');
    exit();
}


// Remove Category

if (isset($_POST['remove-category'])) {
    $cat_id = $_POST['category-id'];
    $sql = "DELETE FROM categories WHERE id = '$cat_id'";

    if ($conn->query($sql) == FALSE) {
        echo "Error" . $sql . $conn->error;
        exit();
    }

    header('Location: ../categories.php');
}


///////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////   PRODUCT ACTIONS   ///////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////

// Create New Product

if (isset($_POST['create-product'])) {
    // echo '<pre>';
    // print_r($_FILES);
    // echo '</pre>';
    // exit();
    $image_path = 0;
    if (isset($_FILES['prod_image'])) {

        $file_name = $_FILES['prod_image']['name'];
        $file_size = $_FILES['prod_image']['size'];
        $file_tmp = $_FILES['prod_image']['tmp_name'];
        $file_type = $_FILES['prod_image']['type'];
        //$file_ext = strtolower(end(explode('.', $_FILES['prod_image']['name'])));

        if (empty($errors) == true) {
            $path = "products/" . $file_name;
            if (move_uploaded_file($file_tmp, __DIR__ . '/../../' . $path)) {
                //echo 1;
            } else {
                //echo 0;
            }
            $image_path = $path;
        }
    }

    $prod_title = $_POST['prod_title'];
    $prod_description = $_POST['prod_description'];
    $prod_price = $_POST['prod_price'];
    $prod_qty = $_POST['prod_qty'];
    $prod_category = $_POST['prod_category'];


    $sql = "INSERT INTO products (title, description, price, qty, category, images) VALUES
     ('$prod_title', '$prod_description','$prod_price','$prod_qty','$prod_category','$image_path')";

    if ($conn->query($sql) == FALSE) {
        echo "Error" . $sql . $conn->error;
        exit();
    }
    header('Location: ../products.php');
}

// Edit Product

if (isset($_POST['edit-product'])) {

    $product_id = $_POST['product_id'];
    $prod_title = $_POST['prod_title'];
    $prod_descriptionA = $_POST['prod_description'];
    $prod_price = $_POST['prod_price'];
    $prod_qty = $_POST['prod_qty'];
    $prod_category = $_POST['prod_category'];

    $prod_title = str_replace('*', 'x', $prod_title);
    $prod_title = str_replace("'", '"', $prod_title);

    $sql = "UPDATE products SET title='$prod_title',description='$prod_descriptionA', price='$prod_price', qty=$prod_qty, category=$prod_category WHERE id =$product_id";

    if ($conn->query($sql) == FALSE) {
        echo "Error" . $sql . $conn->error;
        exit();
    }

    if (isset($_FILES['prod_image'])) {

        $file_name = $_FILES['prod_image']['name'];
        $file_size = $_FILES['prod_image']['size'];
        $file_tmp = $_FILES['prod_image']['tmp_name'];
        $file_type = $_FILES['prod_image']['type'];
        //$file_ext = strtolower(end(explode('.', $_FILES['prod_image']['name'])));

        if (empty($errors) == true) {
            $path = "products/" . $file_name;
            if (move_uploaded_file($file_tmp, __DIR__ . '/../../' . $path)) {
                $sql = "UPDATE products SET images = '$path' WHERE id ='$product_id'";

                if ($conn->query($sql) == FALSE) {
                    echo "Error" . $sql . $conn->error;
                    exit();
                }
            }
        }
    }

    header('Location: ../products.php');
}


// Edit Customer / Admin Data

if (isset($_POST['edit-user-data'])) {

    $user_id = $_POST['user_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $mobile_no = $_POST['mobile_no'];
    $redir = $_POST['redir'];
    $delivery_address = $_POST['delivery_address'];

    $query = "UPDATE users SET firstName='$first_name', lastName='$last_name', mobile_no='$mobile_no', delivery_address='$delivery_address' WHERE id =$user_id";

    if ($conn->query($query) == FALSE) {
        echo "Error" . $sql . $conn->error;
        exit();
    }

    header("Location: ../$redir");
}

// Remove Customer/Admin Data

if (isset($_POST['remove-user'])) {
    $user_id = $_POST['user_id'];
    $query = "UPDATE users SET status=0 WHERE id =$user_id";

    if ($conn->query($query) == FALSE) {
        echo "Error" . $sql . $conn->error;
        exit();
    }

    header('Location: ../customers.php');
}


// Add New Admin

if (isset($_POST['create_new_admin'])) {
    $errors = [];

    // echo '<pre>';
    // print_r($_POST);
    // exit();

    if (empty($_POST['first_name'])) {
        $errors['first_name'] = 'First Name is required';
    }

    if (empty($_POST['last_name'])) {
        $errors['last_name'] = 'Second Name is required';
    }

    if (empty($_POST['email'])) {
        $errors['email'] = 'Email is required';
    }

    if (is_numeric($_POST['first_name'])) {
        $errors['first_name'] = 'Invalid First Name';
    }

    if (is_numeric($_POST['last_name'])) {
        $errors['last_name'] = 'Invalid Second Name';
    }

    if (empty($_POST['password_1'])) {
        $errors['password'] = 'Password required';
    }

    if (isset($_POST['password_1']) and $_POST['password_1'] !== $_POST['password_2']) {
        $errors['password'] = 'The two passwords do not match';
    }

    $firstname = $_POST['first_name'];
    $secondname = $_POST['last_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password_1'], PASSWORD_DEFAULT); //encrypt password
    $role = 'ROLE_ADMIN';
    $verified = 1;
    $status = 1;

    // Check if email already exists
    $sql = "SELECT * FROM users WHERE email='$email' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $errors['email'] = "Email already exists";
    }
    if (count($errors) === 0) {
        $sql = "INSERT INTO users (firstName, lastName, email, password, role, status, verified, delivery_address, mobile_no, postal_code, token ) 
                VALUES ('$firstname','$secondname','$email','$password','$role',$status, $verified, '-', '-', '-', '-')";

        if ($conn->query($sql) == TRUE) {
            echo 1;
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
            exit();
        }
    }

    foreach ($errors as $err) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        ' . $err . '
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
    }
    exit();
}
