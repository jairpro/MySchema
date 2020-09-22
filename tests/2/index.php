<style>
  .success {
    color: #fff;
    background: green;
  }
  .error {
    color: white;
    background: red;
  }
</style>

<h1>MySchema Test 2</h1>

<?php

require_once dirname(__FILE__)."/../../autoload.php";

$schema = MySchema::object([
  'username'=> MySchema::string()
    ->required(),
  'password'=> MySchema::string()
    ->required(),
  'optional'=> MySchema::string()
    ->required(false),
]);

$tests = [];

$tests[] = [
  'username' => 'Maria',
  'password' => '123',
  'optional' => '1'
]; 

$tests[] = [
  'username' => 'Maria',
  'password' => '123',
  'optional' => ''
]; 

$tests[] = [
  'username' => 'Maria',
  'password' => '123'
]; 

$tests[] = [
  'username' => '',
  'password' => '123'
]; 

$tests[] = [
  'password' => '123'
]; 

echo "<p>string required";
echo "<br>";
echo "<p>schema: ".json_encode($schema->getProps(), JSON_FORCE_OBJECT);
echo "<br>";
foreach ($tests as $index => $element) {
  $num = $index+1;
  $data = $element;
  $isValid = $schema->isValid($data);
  $isValidClass = $isValid ? "success" : "error";
  echo "<p>test$num.data: ".json_encode($data);
  echo "<br><span class=\"$isValidClass\">test$num.isValid: ".json_encode($isValid)."</span>";
  echo "<br>";
  echo "<hr>";
}
