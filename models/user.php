<?php

/**
 * Created by PhpStorm.
 * User: tharindu
 * Date: 7/1/18
 * Time: 4:28 PM
 */
class UserModel extends Model
{
    public function register()
    {
        // Sanitize POST
        $post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        //print_r($post);
        $psswrd = $post['password'];
        $encrypted_psswrd = md5($psswrd);
        //die($encrypted_psswrd);
        if ($post['submit']) {


            // Insert into MySql
            $this->query('INSERT INTO users (name, email, password) VALUES (:name, :email, :password)');

            $this->bind(':name', $post['name']);
            $this->bind(':email', $post['email']);
            $this->bind(':password', $encrypted_psswrd);

            $this->execute();

            //Verify
            if ($this->lastInsertId()) {
                header('location: ' . ROOT_URL . 'shares');
            }
        }
    }

    public function login()
    {
        // Sanitize POST
        $post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        //print_r($post);
        $psswrd = $post['password'];
        $encrypted_psswrd = md5($psswrd);
        //die($encrypted_psswrd);
        if ($post['submit']) {
            // Compare
            $this->query('SELECT * FROM users WHERE email = :email AND password = :password');

            $this->bind(':email', $post['email']);
            $this->bind(':password', $encrypted_psswrd);

            $row = $this->single();

            if ($row) {
                $_SESSION['is_logged_in'] = true;
                $_SESSION['user_data'] = array(
                    "id" => $row['id'],
                    "name" => $row['name'],
                    "email" => $row['email'],
                );
                header('location: ' . ROOT_URL . 'shares');
            } else {
                Messages::setMsg('Incorrect Login', 'error');
            }
        }
    }
}