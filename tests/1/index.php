<h1>MySchema Test 1</h1>
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

echo "<br>cast3: ".json_encode($cast3);
echo "<br>valid3: ".json_encode($valid3);
echo "<br>validate3: ".json_encode($validate3);
echo "<br>invalidate3: ".json_encode($invalidate3);


