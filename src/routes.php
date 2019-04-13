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
    $sth = $this->db->prepare("SELECT a.rfid, DATE_FORMAT(a.datetime_recorded, '%M %e, %Y %r') AS datetime_recorded, a.status, a.date_created, u.firstname, u.lastname, u.profile_picture  FROM attendance AS a INNER JOIN users AS u ON u.rfid = a.rfid
    ORDER BY a.date_created DESC LIMIT 4");
    $sth->execute();
    $attendance = $sth->fetchAll();
    return $this->response->withJson($attendance)
    ->withHeader('Access-Control-Allow-Origin', '*')
    ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
    ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});


 // insert time_in
 $app->post('/attendance/add', function ($request, $response) {
    $input = $request->getParsedBody();
    // var_dump($input);
    $sql = "INSERT INTO attendance (rfid, datetime_recorded, status) VALUES (:rfid, :dt_record, :stat)";
    $sth = $this->db->prepare($sql);
    $sth->bindParam("rfid", $input['rfid']);
    $sth->bindParam("dt_record", $input['date_time']);
    $sth->bindParam("stat", $input['stat']);
    $sth->execute();
    $input['id'] = $this->db->lastInsertId();
    return $this->response->withJson($input)
    ->withHeader('Access-Control-Allow-Origin', '*')
    ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
    ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});


$app->get('/attendance/[{id}]', function ($request, $response, $args) {

    $sth = $this->db->prepare("SELECT a.rfid, DATE_FORMAT(a.datetime_recorded, '%M %e, %Y %r') AS datetime_recorded, a.status, a.date_created, u.firstname, u.lastname, u.profile_picture  FROM attendance AS a 
    INNER JOIN users AS u ON u.rfid = a.rfid WHERE a.id = :id
    ORDER BY a.date_created DESC LIMIT 4");
    $sth->bindParam("id", $args['id']);
    $sth->execute();
    $attendance = $sth->fetchAll();
    return $this->response->withJson($attendance)
    ->withHeader('Access-Control-Allow-Origin', '*')
    ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
    ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');

});