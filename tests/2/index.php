<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MySchema Tests 2</title>
</head>
<body>
  
<?php

require_once dirname(__FILE__)."/../../autoload.php";

$schema = MySchema::object([
  'username'=> MySchema::string()
    ->required()
    ->max(10),
  'password'=> MySchema::string()
    ->required()
    ->min(8),
    //->oneOf('abcdefgHijklmnopqrstuvxwyzABCDEFGHIJKLMNOPQRSTUVXWYZ')
    //->oneOf('0123456789')
    //->oneOf('!@#$%¨&*()_+=-<>,.;:?/|\\'),
  'optional'=> MySchema::number()
    ->required(false),
  'sigla' => MySchema::string()
    ->min(2)
    ->max(3)
    ->oneOf(['JWT','API','YUP','PHP','js','céu'])
]);

$tests = [];

$tests[] = [
  'values' => [
    'username' => '1234567890',
    'password' => '12345678',
  ],
  'expected' => 1
]; 

$tests[] = [
  'values' => [
    'username' => '1234567890',
    'password' => '1234567',
  ],
  'expected' => 0
]; 

$tests[] = [
  'values' => [
    'username' => 'Fulano de Tal',
    'password' => '12345678',
  ],
  'expected' => 0
]; 


$tests[] = [
  'values' => [
    'username' => 'João',
    'password' => '12345678',
    'optional' => null,
  ],
  'expected' => 1
]; 

$tests[] = [
  'values' => [
    'username' => 'João',
    'password' => '12345678',
    'optional' => '',
  ],
  'expected' => 1
]; 

$tests[] = [
  'values' => [
    'username' => 'João',
    'password' => '12345678',
    'optional' => 0,
  ],
  'expected' => 1
]; 

$tests[] = [
  'values' => [
    'username' => 'João',
    'password' => '12345678',
    'optional' => '0',
  ],
  'expected' => 1
]; 

$tests[] = [
  'values' => [
    'username' => 'João',
    'password' => '12345678',
    'optional' => 1,
  ],
  'expected' => 1
]; 

$tests[] = [
  'values' => [
    'username' => 'João',
    'password' => '12345678',
    'optional' => '1',
  ],
  'expected' => 1
]; 

$tests[] = [
  'values' => [
    'username' => 'João',
    'password' => '12345678',
    'optional' => .5,
  ],
  'expected' => 1
]; 

$tests[] = [
  'values' => [
    'username' => 'João',
    'password' => '12345678',
    'optional' => '.5',
  ],
  'expected' => 1
]; 

$tests[] = [
  'values' => [
    'username' => 'João',
    'password' => '12345678',
    'optional' => 0.5,
  ],
  'expected' => 1
]; 

$tests[] = [
  'values' => [
    'username' => 'João',
    'password' => '12345678',
    'optional' => '0.5',
  ],
  'expected' => 1
]; 

$tests[] = [
  'values' => [
    'username' => 'João',
    'password' => '12345678',
    'optional' => -0,
  ],
  'expected' => 1
]; 

$tests[] = [
  'values' => [
    'username' => 'João',
    'password' => '12345678',
    'optional' => '-0',
  ],
  'expected' => 1
]; 

$tests[] = [
  'values' => [
    'username' => 'João',
    'password' => '12345678',
    'optional' => -1,
  ],
  'expected' => 1
]; 

$tests[] = [
  'values' => [
    'username' => 'João',
    'password' => '12345678',
    'optional' => '-1',
  ],
  'expected' => 1
]; 

$tests[] = [
  'values' => [
    'username' => 'João',
    'password' => '12345678',
    'optional' => -.5,
  ],
  'expected' => 1
]; 

$tests[] = [
  'values' => [
    'username' => 'João',
    'password' => '12345678',
    'optional' => '-.5',
  ],
  'expected' => 1
]; 

$tests[] = [
  'values' => [
    'username' => 'João',
    'password' => '12345678',
    'optional' => -0.5,
  ],
  'expected' => 1
]; 

$tests[] = [
  'values' => [
    'username' => 'João',
    'password' => '12345678',
    'optional' => '-0.5',
  ],
  'expected' => 1
]; 

$tests[] = [
  'values' => [
    'username' => 'João',
    'password' => '12345678',
    'optional' => -0.50,
  ],
  'expected' => 1
]; 

$tests[] = [
  'values' => [
    'username' => 'João',
    'password' => '12345678',
    'optional' => '-0.50',
  ],
  'expected' => 1
]; 

$tests[] = [
  'values' => [
    'username' => 'João',
    'password' => '12345678',
    'optional' => 'e',
  ],
  'expected' => 0
]; 

$tests[] = [
  'values' => [
    'username' => 'João',
    'password' => '12345678',
    'optional' => 'A',
  ],
  'expected' => 0
]; 

$tests[] = [
  'values' => [
    'username' => 'João',
    'password' => '12345678',
    'optional' => 'Isso não é uma string',
  ],
  'expected' => 0
]; 

$tests[] = [
  'values' => [
    'username' => 'João',
    'password' => '12345678',
    'optional' => false,
  ],
  'expected' => 0
]; 

$tests[] = [
  'values' => [
    'username' => 'João',
    'password' => '12345678',
    'optional' => true,
  ],
  'expected' => 0
]; 

$tests[] = [
  'values' => [
    'username' => 'João',
    'password' => '12345678',
    'sigla' => ''
  ],
  'expected' => 0
]; 

$tests[] = [
  'values' => [
    'username' => '1234567890',
    'password' => '12345678',
    'optional' => '',
    'sigla' => 'A'
  ],
  'expected' => 0
]; 

$tests[] = [
  'values' => [
    'username' => 'Maria',
    'password' => '123456789',
    'sigla' => 'js'
  ],
  'expected' => 1
]; 

$tests[] = [
  'values' => [
    'username' => '',
    'password' => '123',
    'sigla' => 'PHP'
  ],
  'expected' => 0
]; 

$tests[] = [
  'values' => [
    'username' => '1',
    'password' => '12345678',
    'sigla' => 'ABCD'
  ],
  'expected' => 0
]; 

$tests[] = [
  'values' => [
    'username' => '1',
    'password' => '12345678',
    'sigla' => 'ABC'
  ],
  'expected' => 0
]; 

$tests[] = [
  'values' => [
    'username' => '1',
    'password' => '12345678',
    'sigla' => 'JWT'
  ],
  'expected' => 1
]; 

$tests[] = [
  'values' => [
    'username' => '',
    'password' => '12345678',
    'sigla' => 'JWT'
  ],
  'expected' => 0
]; 

$tests[] = [
  'values' => [
    'password' => '123'
  ],
  'expected' => 0
]; 

$countFails = 0;
foreach ($tests as $index => $element) {
  $data = $element['values'];
  $isValid = $schema->isValid($data);
  $tests[$index]['isValid'] = $isValid;
  $result = $element['expected']==$isValid;
  $tests[$index]['result'] = $result;
  if (!$result) {
    $countFails++;
  }
}

$countTests = count($tests);

?>

<style>
  @import url('https://fonts.googleapis.com/css2?family=Roboto&display=swap');

  body {
    font-family: 'Roboto', sans-serif;
    background: #282a36;
    color: #ff6;
    color: rgb(248,248,242);
  }
  ul {
    list-style: none;
  }
  ul a {
    color: rgb(241,237,104);
    font-size: 24 px;
  }
  ul a:hover {
    color: rgb(139,233,253);
  }
  .schema {
    margin-bottom: 40px;
    margin-left: 20px;
  }
  .schema .value {
    color: rgb(255,121,198);
    margin-left: 10px;
  }
  .score {
    margin-bottom: 20px;
    margin-left: 20px;
  }
  .score .tag {
    margin-left: 10px;
  }
  .tests {
    display: flex;
    flex-direction: column;
  }
  .test {
    padding: 3px 5px;
    margin: 1px 4px;
    display: grid;
    grid-template-columns: min-content min-content auto;
  }
  .test:hover {
    background: rgb(52,55,70);
  }
  .test .numb {
    width: 40px;
    color: rgb(98,114,139);
  }
  .test .data {
    margin-left: 20px;
  }
  .test .tag {
    margin-left: 20px;
    text-align: center;
  }
  .tag {
    color: rgb(248,248,242);
    color: #fff;
    padding: 3px 5px;
    border-radius: 3px;
  }
  .success {
    background: green;
    background: rgb(80,250,123);
    background: rgb(59,155,88);
    background: rgb(74,191,30);
  }
  .error {
    background: red;
    background: rgb(219,99,77);
  }
</style>

<h1>MySchema Test 2</h1>

<ul>
  <a href="../">
    <li>Home</li>
  </a>
</ul>

<?php
  echo "<div class=\"schema\">";
  echo   "<span class=\"label\">schema:</span>";
  echo   "<span class=\"value\">".json_encode($schema->getProps(), JSON_UNESCAPED_UNICODE)."</span>";
  echo "</div>";

  echo "<div class=\"score\">";
  $testsText = "test".($countTests <= 1 ? "" : "s");
  if ($countTests===0) {
    echo "<span>no $testsText</span>";
  }
  else if ($countFails>0) {
    $failsText = "fail".($countFails === 1 ? "" : "s");
    echo "<span>$countTests $testsText</span>";
    echo "<span class=\"tag error\">with $countFails $failsText</span>";
  }
  else {
    echo "<span class=\"tag success\">$countTests successful $testsText!</span>";
  }
  echo "</div>";

  echo "<div class=\"tests\">";
  foreach ($tests as $index => $element) {
    $numb = $index+1;
    $data = $element['values'];
    $resultClass = $element['result'] ? "success" : "error";
    $validText = $element['isValid'] ? "VALID" : "invalid";
    echo "<div class=\"test\">";
    echo   "<div class=\"numb\">$numb:</div>";
    echo   "<div class=\"tag $resultClass\">$validText</div>";
    echo   "<div class=\"data\">".json_encode($data, JSON_UNESCAPED_UNICODE )."</div>";
    echo "</div>";
  }
  echo "</div>";
?>

</body>
</html>
