<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

// $app->get('/[{name}]', function (Request $request, Response $response, array $args) {
//     // Sample log message
//     $this->logger->info("Slim-Skeleton '/' route");

//     // Render index view
//     return $this->renderer->render($response, 'index.phtml', $args);
// });

// get attendance for web display
$app->get('/attendance', function ($request, $response, $args) {
    $sth = $this->db->prepare("SELECT a.*, u.firstname, u.lastname, u.profile_picture  FROM attendance AS a 
    INNER JOIN users AS u ON u.rfid = a.rfid
    ORDER BY a.date_created DESC LIMIT 4");
    $sth->execute();
    $attendance = $sth->fetchAll();
    return $this->response->withJson($attendance)
    ->withHeader('Access-Control-Allow-Origin', '*')
    ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
    ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});


 // insert time_in
 $app->post('/time/in', function ($request, $response) {
    $input = $request->getParsedBody();
    $sql = "INSERT INTO attendance (rfid, time_id) VALUES (:rfid, :time_in)";
    $sth = $this->db->prepare($sql);
    $sth->bindParam("rfid", $input['rfid'], "time_in", $input['time_in']);
    $sth->execute();
    $input['id'] = $this->db->lastInsertId();
    return $this->response->withJson($input);
});