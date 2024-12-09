<?php

use Slim\App;

return function (App $app) {
    // GET all users
    $app->get('/users', function ($request, $response) {
        try {
            error_log("Attempting to fetch users...");
            $db = connectToDatabase();

            // Fetch all users
            $stmt = $db->query('SELECT * FROM users');
            $users = $stmt->fetchAll();

            // Debug: Log query results
            error_log("Users fetched: " . print_r($users, true));

            // Handle empty users
            if (empty($users)) {
                error_log("No users found.");
                $users = ['message' => 'No users found'];
            }

            // Encode users as JSON
            $json = json_encode($users);
            if ($json === false) {
                error_log("JSON encoding error: " . 
json_last_error_msg());
                return $response->withStatus(500)->write('JSON encoding 
error');
            }

            // Return JSON response
            $response->getBody()->write($json);
            return $response->withHeader('Content-Type', 
'application/json');
        } catch (Exception $e) {
            error_log("Error in /users route: " . $e->getMessage());
            return $response->withStatus(500)->write('Internal Server 
Error');
        }
    });

    // GET a user by ID
    $app->get('/users/{id}', function ($request, $response, $args) {
        try {
            error_log("Attempting to fetch user with ID: " . $args['id']);
            $db = connectToDatabase();

            // Fetch user by ID
            $stmt = $db->prepare('SELECT * FROM users WHERE id = ?');
            $stmt->execute([$args['id']]);
            $user = $stmt->fetch();

            // Debug: Log query result
            error_log("User fetched: " . print_r($user, true));

            // Handle user not found
            if (!$user) {
                error_log("User not found with ID: " . $args['id']);
                return 
$response->withStatus(404)->write(json_encode(['message' => 'User not 
found']));
            }

            // Encode user as JSON
            $json = json_encode($user);
            if ($json === false) {
                error_log("JSON encoding error: " . 
json_last_error_msg());
                return $response->withStatus(500)->write('JSON encoding 
error');
            }

            // Return JSON response
            $response->getBody()->write($json);
            return $response->withHeader('Content-Type', 
'application/json');
        } catch (Exception $e) {
            error_log("Error in /users/{id} route: " . $e->getMessage());
            return $response->withStatus(500)->write('Internal Server 
Error');
        }
    });

    // POST (Create a new user)
    $app->post('/users', function ($request, $response) {
        try {
            error_log("Attempting to add a new user...");
            $db = connectToDatabase();
            $data = $request->getParsedBody();

            // Insert a new user
            $stmt = $db->prepare('INSERT INTO users (username, role, 
password) VALUES (?, ?, ?)');
            $stmt->execute([$data['username'], $data['role'], 
$data['password']]);

            // Debug: Log inserted user data
            error_log("User added successfully: " . print_r($data, true));

            // Return success message
            $response->getBody()->write(json_encode(['message' => 'User 
added successfully']));
            return $response->withHeader('Content-Type', 
'application/json');
        } catch (Exception $e) {
            error_log("Error adding user: " . $e->getMessage());
            return $response->withStatus(500)->write('Internal Server 
Error');
        }
    });

    // PUT (Update a user by ID)
    $app->put('/users/{id}', function ($request, $response, $args) {
        try {
            error_log("Attempting to update user with ID: " . 
$args['id']);
            $db = connectToDatabase();
            $data = $request->getParsedBody();

            // Update the user
            $stmt = $db->prepare('UPDATE users SET username = ?, role = ?, 
password = ? WHERE id = ?');
            $stmt->execute([$data['username'], $data['role'], 
$data['password'], $args['id']]);

            // Debug: Log updated user data
            error_log("User updated successfully: ID " . $args['id']);

            // Return success message
            $response->getBody()->write(json_encode(['message' => 'User 
updated successfully']));
            return $response->withHeader('Content-Type', 
'application/json');
        } catch (Exception $e) {
            error_log("Error updating user: " . $e->getMessage());
            return $response->withStatus(500)->write('Internal Server 
Error');
        }
    });

    // DELETE (Remove a user by ID)
    $app->delete('/users/{id}', function ($request, $response, $args) {
        try {
            error_log("Attempting to delete user with ID: " . 
$args['id']);
            $db = connectToDatabase();

            // Delete the user
            $stmt = $db->prepare('DELETE FROM users WHERE id = ?');
            $stmt->execute([$args['id']]);

            // Debug: Log deletion
            error_log("User deleted successfully: ID " . $args['id']);

            // Return success message
            $response->getBody()->write(json_encode(['message' => 'User 
deleted successfully']));
            return $response->withHeader('Content-Type', 
'application/json');
        } catch (Exception $e) {
            error_log("Error deleting user: " . $e->getMessage());
            return $response->withStatus(500)->write('Internal Server 
Error');
        }
    });
};

