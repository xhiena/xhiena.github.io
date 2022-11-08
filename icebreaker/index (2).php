<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);
session_start();
$teams_implemented=['alpha','SG1'];
$team=filter_input(INPUT_GET,'team',FILTER_SANITIZE_STRING);
if(!in_array($team,$teams_implemented)){ $team='random';}
$persons= file(__DIR__ . "/team-".$team.".txt");

$last=$_SESSION['last']??"";

$get_persons=filter_input(INPUT_GET,"persons",FILTER_SANITIZE_STRING,FILTER_REQUIRE_ARRAY);
$get_fix=filter_input(INPUT_GET,"fixParticipant",FILTER_SANITIZE_STRING);
if($get_persons!=null){
    $persons=$get_persons;
}
$f_questions = file(__DIR__ . "/questions.txt");
shuffle($f_questions);
shuffle($f_questions);//double random, double fun
$question = $f_questions[0];
shuffle($persons);
$randomperson=rand(0,count($persons)-1);
$chosenOne= $persons[$randomperson];

if($get_fix!=null){
    $chosenOne=$last;
}else{
    while($chosenOne==$last){
        $randomperson=rand(0,count($persons)-1);
        $chosenOne= $persons[$randomperson];
    }
}
$_SESSION['last']=$chosenOne;
?><!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo strtoupper($team);?> Icebreaker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>
<body>
<div class="container">
    <h1><?php echo strtoupper($team);?> Icebreaker</h1>


    <form>
        <div class="container-fluid text-sm-center p-5 text-light bg-dark rounded-3">
            <h1 class="display-4">Hello, <?php echo $chosenOne;?>!</h1>
            <p class="lead"><?php echo $question;?></p>

            <input class="btn btn-outline-warning" type="submit" value='Refresh'>
        </div>


        <h2>Configuration</h2>
        <div class="row">
            <div class="col">
                <div  class="card">
                    <div class="card-header">How this works</div>
                    <div class="card-body">
                        <p class="card-text">Everytime the page is refreshed or you add a participant or hit submit it will randomize the list of users and select a random question from the list of questions.</p>
                        <p class="card-text">You can add or remove participants to return to the default list (Full <?php echo strtoupper($team);?> team) you can remove all participants</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div  class="card">
                    <div class="card-header">Participants</div>
                    <div class="card-body">
                        <div id="persons">
                            <?php
                            sort($persons);
                            foreach($persons as $person){
                                echo "<div><label><input type=\"checkbox\" checked name=\"persons[]\" value=\"".$person."\"> ".$person."</label></div>";
                            }
                            ?>
                        </div>
                        <input type="text" id="newPerson"> <button id="addPerson">Add</button><br>
                    </div>
                </div>
            </div>
            <div class="col">
                <div  class="card">
                    <div class="card-header">Options</div>
                    <div class="card-body">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" name="fixParticipant" value="1" id="fixParticipant">
                                <label class="custom-control-label" for="fixParticipant"> Fix the Participant</label> (<?php echo $chosenOne; ?> will get another question)<br />
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <input type="hidden" name="team" value="<?php echo $team;?>">
            <div class="col d-flex justify-content-center">
                <input class="btn btn-primary" type="submit">
            </div>
        </div>
    </form>
    <footer>
    <div class="d-flex justify-content-center">Powered by caffeine and boredom</div>
    </footer>
    <script>
        function addperson(name){
            var target = document.getElementById('persons');
            var div = document.createElement('div');
            var label = document.createElement('label');
            var check = document.createElement('input');
            check.type="checkbox";
            check.checked="checked";
            check.name="persons[]";
            check.value=name;
            var span=document.createElement('span');
            span.innerText=' '+name;
            label.appendChild(check);
            label.appendChild(span);
            div.append(label);
            target.append(div);
        }
        document.getElementById('addPerson').addEventListener('click', function (event) {
            var newPerson=document.getElementById('newPerson');
            if (newPerson.value!=''){
                addperson(newPerson.value);
                newPerson.value='';
            }
        }, false);
    </script>
<div>
</body>
</html>
