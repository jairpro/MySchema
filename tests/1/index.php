<style>
  * {
    display: flex;
    flex-direction: column;
    align-items: top;
    justify-content: left;
  }
  style, script {
    display: none;
  }
  body {
    font-family: 'Fira Code','Courier New', Courier, monospace;
    background: #333;
    color: #fff;
  }
  .header {
    align-items: center;
  }
  ul {
    style: none;
  }
  ul a {
    color: #aaa;
  }
  ul a:hover {
    color: #fff;
  }
  .card {
    margin: 10px 10px;
    border: 3px solid #0ff;
    border-radius: 10px;
  }
  .label {
    padding-right: 10px;
  }
  .value {
    overflow: hidden;
    height: auto;
    width: auto;
    overflow-y: auto;
    overflow-x: auto;
  }
  .schema {
    display: flex;
    flex-direction: row;
    border-bottom: 3px solid #0ff;
    padding: 15px 20px;
    background: #333;
  }
  .tests {
    padding: 15px 20px;
    background: #444;
  }
  .test {
    display: flex;
    flex-direction: row;
  }
  .test .label {
    width: 200px;
  }
  .label {
    color: cyan;
  }
  .value {
    color: yellowgreen;
  }
</style>

<div class="header">
  <h1>MySchema Test 1</h1>
  <ul>
    <a href="../"><li>Home</li></a>
  </ul>
</div>

<?php

require_once dirname(__FILE__)."/../../autoload.php";

/*
$contactSchema1 = MySchema([
  MySchema('name')->string()
    ->required(),
  MySchema('age')->number()
    ->required()
    ->positive()
    ->integer(),
  MySchema('email')->string()
    ->email(),
  MySchema('website')->string()
    ->url(),
  MySchema('createdOn')->date()
    ->default(function(){
      return date("Y-m-dTh:i:s");
    }),
]);
*/

/*
$contactSchema2 = MySchema()->object([
  'name' => MySchema()->string()
    ->required(),
  'age' => MySchema()->number()
    ->required()
    ->positive()
    ->integer(),
  'email' => MySchema()->string()
    ->email(),
  'website' => MySchema()->string()
    ->url(),
  'createdOn' => MySchema()->date()
    ->default(function(){
      return date("Y-m-dTh:i:s");
    }),
]);
*/

$contactSchema3 = MySchema::object([
  'name' => MySchema::string()
    ->required(),
  'age' => MySchema::number()
    ->required()
    ->positive()
    ->integer(),
  'email' => MySchema::string()
    ->email(),
  'website' => MySchema::string()
    ->url(),  
  'createdOn' => MySchema::date()
    ->default(function(){
      return new DateTime();
    }),
]);
  
/*
$contactSchema4 = MySchemaObject([
  'name' => MySchemaString()
    ->required(),
  'age' => MySchemaNumber()
    ->required()
    ->positive()
    ->integer(),
  'email' => MySchemaString()
    ->email(),
  'website' => MySchemaString()
    ->url(),
  'createdOn' => MySchemaDate()
    ->default(function(){
      return date("Y-m-dTh:i:s");
    })
]);
*/

$cast = [
  'name' => 'jimmy',
  'age' => '24',
  'createdOn' => '2020-09-22T00:33:00Z'
];

//$cast1 = $contactSchema1->cast($cast);
//$cast2 = $contactSchema2->cast($cast);
$cast3 = $contactSchema3->cast($cast);
//$cast4 = $contactSchema4->cast($cast);

$contact = [
  'name' => 'Chewbacca',
  'age' => '300',
  'website' => 'a://a'
];
$contact = [
  'name' => 'jimmy',
  'age' => 24,
  'email' => 'jdog@cool.biz'
];


//$valid1 = $contactSchema1->isValid($contact);
//$valid2 = $contactSchema2->isValid($contact);
$valid3 = $contactSchema3->isValid($contact);
//$valid4 = $contactSchema4->isValid($contact);

//$validate1 = $contactSchema1->validate($contact);
//$validate2 = $contactSchema2->validate($contact);
$validate3 = $contactSchema3->validate($contact);
//$validate4 = $contactSchema4->validate($contact);

$contactError = [
  'name' => 'jimmy',
  'email' => 'jdog'
];
$options = ['abortEarly' => false];

//$invalidate1 = $contactSchema1->validate($contactError, $options);
//$invalidate2 = $contactSchema2->validate($contactError, $options);
$invalidate3 = $contactSchema3->validate($contactError, $options);
//$invalidate4 = $contactSchema4->validate($contactError, $options);

echo "<div class=\"card\">";
echo   "<div class=\"schema\"><span class=\"label\">contactSchema3:</span> <span class=\"value\">".json_encode($contactSchema3->getProps())."</span></div>";
echo   "<div class=\"tests\">";
echo     "<br>";
echo     "<div>cast: ".json_encode($cast)."</div>";
echo     "<div class=\"test\"><span class=\"label\">cast3:</span><span class=\"value\">".json_encode($cast3)."</span></div>";
echo     "<br>";
echo     "<div>contact: ".json_encode($contact)."</div>";
echo     "<div class=\"test\"><span class=\"label\">valid3:</span><span class=\"value\">".json_encode($valid3)."</span></div>";
echo     "<div class=\"test\"><span class=\"label\">validate3:</span><span class=\"value\">".json_encode($validate3)."</span></div>";
echo     "<br>";
echo     "<div>contactError: ".json_encode($contactError)."</div>";
echo     "<div class=\"test\"><span class=\"label\">invalidate3:</span><span class=\"value\">".json_encode($invalidate3)."</span></div>";

echo   "</div>";
echo "</div>";
