<?php
require 'credentials.php';
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("SELECT distinct transporter from thalilist where Active=1"); 
        $stmt->execute();
        $stmt = $stmt->fetchAll();
    }
    catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $conn = null;
    $checkbox_html = "";
    //var_dump($stmt);
    for($i=0; $i<count($stmt); $i++)
    {
        $val = $stmt[$i][0];
        $checkbox_html = $checkbox_html."\t<label><input type='checkbox' name = 'transporters[]' value='$val'/>$val</label>\n"; 
    }
    $checkbox_html.="\t<br>\n";
?>
<!DOCTYPE html>
<html>
   <head>
       <title> SMS filtering </title>
       
       <script type='text/javascript' src = 'https://code.jquery.com/jquery-2.2.0.min.js'></script>
       <script type='text/javascript' src="filter.js"></script>
    </head>
    <body>
        <p> Apply filtering on amount to select the recipients</p>
        <input type = 'text' id = 'amount_param2' value = '0' hidden />
        <select id='amount_operator'>
          <option value="none">None</option>
          <option value="<">Less than</option>
          <option value="<=">Less than or equal to</option>
          <option value="=">Equal to</option>
          <option value=">=">Greater than or equal to</option>
          <option value=">">Greater than</option>
          <option value="between">Between</option>
        </select>
        <input type='text' id = 'amount_param' value='0' hidden /><br>

        <p> Apply filtering on transporter </p>
        <select id='transporter_operator'>
            <option value = "none">None</option>
            <option value = "in">Equal to</option>
            <option value = "not in"> Not equal to</option>
        </select>
        <div id='transporter_param' style="display:inline" >
    <?php
    echo $checkbox_html;
    ?>
        </div>
        <br>
        <button id='filterButton'>Filter</button>
        <br>
        <p id = 'query_status'></p>
        <table style='border: solid 1px black;' id = 'recipientTable'>
            <tr><th>#</th><th>Thali No.</th><th>Name</th><th>Mob No.</th><th>Transporter</th><th>Amount</th></tr>
        </table>
    </body>
</html>

<?php
}
else{
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Connected successfully"; 
    }
    catch(PDOException $e)
    {
        echo "Connection failed: " . $e->getMessage();
    //return;
    }
    $query = "select thali, name, contact, transporter, total_pending from thalilist where Active=1 and ";
    $condition = "1=1";
    $amount_operator = $_REQUEST['amount_operator'];
    $amount_param = $_REQUEST['amount_param'];
    $amount_param2 = $_REQUEST['amount_param2'];
    $transporter_operator = $_REQUEST['transporter_operator'];
    $transporter_param = $_REQUEST['transporter_param']; // this will be an array
    //var_dump( $transporter_param); returns zero length string
    $field_amount = "total_pending";
    $field_transporter = "Transporter";
    switch($amount_operator)
    {
        case ">":
        case ">=":
        case "<":
        case "<=":
        case "=":
            $condition = $field_amount." ".$amount_operator." ".$amount_param;
        break;
        case "between":
            $condition = $field_amount." ".$amount_operator." ".$amount_param2." and ".$amount_param;
        break;
    }
    $query = $query.$condition;
    if(strlen($transporter_param) != 0)
    {
        $query = $query." and ";
        $condition = "1=1";
        switch($transporter_operator)
        {
            case 'in':
            case 'not in':
                $condition = $field_transporter." ".$transporter_operator." (".$transporter_param.")";
            break;
        }
        $query = $query.$condition;
    }
    //echo "\n\nfinal sql string = ".$query."\n\n";
    try{
        $stmt = $conn->prepare($query);
        //$stmt->debugDumpParams();
        $stmt->execute();

        // set the resulting array to associative
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    catch(PDOException $e)
    {
        echo "error ".$e->getMessage();
    }
    //print_r( $result);
    $result2 = array(
        'result' => 'success',
        'query' => $query,
        'data' => $result);
    //echo print_r($result2);
    $result_json = json_encode($result2);
    echo $result_json;

    
    $conn = null;
}
?>
