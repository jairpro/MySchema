<style>
  * {
    font-family: Verdana, Geneva, Tahoma, sans-serif;
  }

  body {
    font-size: 12px;
  }
  span {
    display: inline-block;
  }
  .row {
    padding: 5px 0;
  }
  .ri {
    background: #aaa;
  }
  .rp {
    background: #eee;
  }
  .number {
    width: 20px;
    text-align: right;
    margin: 0 20px;
  }
  .tag {
    width: auto;
    padding: 0 8px;
    border-radius: 3px;
  }
  .tag .label {
    text-align: right;
    width: auto;
    padding-right: 5px;
  }
  .data {
    width: auto;
    margin-left: 20px;
  }
  .data .label {
    width: 100px;
    margin-right: 10px;
  }
  .success {
    color: #fff;
    background: green;
  }
  .error {
    color: white;
    background: red;
  }
</style>

<h1>MySchema Test 3</h1>

<ul>
  <a href="../"><li>Home</li></a>
</ul>

<?php

require_once dirname(__FILE__)."/../../autoload.php";

$schema = MySchema::object([
  'password'=> MySchema::string()
    ->required()
    ->min(8)
]);
echo "<p>schema: ".json_encode($schema->getProps(), JSON_FORCE_OBJECT);
echo "<br>";

$detailCheck = filter_input(INPUT_GET, 'detail');
$detailChecked = $detailCheck==='on' ? ' checked' : '';
$detail = $detailChecked==true;
?>
  <form>
    <label>
      <input 
        type="checkbox" 
        name=detail 
        onclick="this.form.submit();"
        <?php echo $detailChecked; ?>
      >
      <span>Exibir detalhes</span>
    </label>
  </form>
<?php

$tests = [];

// approved, value 
$tests[] = [0,null];
$tests[] = [0,''];
$tests[] = [0,'123'];
$tests[] = [0,'1234567'];
$tests[] = [1,'12345678'];
$tests[] = [1,'123456789'];
$tests[] = [0,0];
$tests[] = [0,1];
$tests[] = [0,true];
$tests[] = [0,false];

$rowType = "ri";
$simple = !$detail;
$tagLabelVisible = $simple ? "style=\"display: none;\"" : "";
foreach ($tests as $index => $element) {
  $num = $index+1;
  $data = $element[1];
  $isValid = $schema->isValid($data);
  $tagLabel = $simple ? "" : "isValid:"; 
  $tagValue = $simple ? ($isValid ? "valid" : "invalid") : json_encode($isValid);
  $approved = $isValid==$element[0];
  $isValidClass = $approved ? "success" : "error";

  echo "<div class=\"row $rowType\">";

  echo   "<span class=\"number\">$num</span>";

  echo   "<span class=\"tag $isValidClass\">";
  echo     "<span class=\"label\"$tagLabelVisible>$tagLabel</span>";
  echo     "<span class=\"value\">$tagValue</span>";
  echo   "</span>";

  echo   "<span class=\"data\">";
  echo     "<span class=\"value\">".json_encode($data)."</span>";
  echo   "</span>";

  echo "</div>";

  $rowType = $rowType==='ri' ? 'rp' : 'ri';
}
