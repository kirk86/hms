<?php
require_once ('functions.php');
__autoload("pdo");
__autoload("pagination");

# Assign local variables
if(get_magic_quotes_gpc())
{
	$id = @strip_tags(trim($_POST['id'])); // The id of the input that submitted the request.
	$data = @stripslashes(strip_tags(trim($_POST['data']))); // The value of the textbox.
}
else
{
	$id = @strip_tags(trim($_POST['id'])); // The id of the input that submitted the request.
	$data = @addslashes(strip_tags(trim($_POST['data']))); // The value of the textbox.
}

if ($id && $data)
{
    if ($id == 'patient')
	{
        try
		{
            $stmt = DB::getInstance()->query("SELECT id_patient, firstName, lastName
	   						                 FROM patient
											 WHERE firstName LIKE '$data%'
											 LIMIT 5");

            $dataList = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
                $toReturn = $row['firstName'] . " " . $row['lastName'];
                $dataList[] = '<li id="' . $row['id_patient'] . '"><a href="#">' . htmlentities($toReturn, ENT_QUOTES, "UTF-8") .
                    '</a></li>';
            }
            if (count($dataList) >= 1)
			{
                $dataOutput = implode("\r\n", $dataList);
                echo $dataOutput;
            }
			else
			{
                echo '<li><a href="#">No Results</a></li>';
            }
        }
        catch (PDOException $e)
		{
            DB::Close();
            //trigger_error($e->getMessage(), E_USER_ERROR);
			echo "Sorry! Use better search criteria";
        }
    } elseif ($id == 'pid')
	{
        try
		{
            $stmt = DB::getInstance()->query("SELECT category_id,category_name
						                  	  FROM tbl_categories
										  	  WHERE category_name LIKE '$data%'
										  	  LIMIT 5");
            $dataList = array();
            while ($row = $stmt - fetch(PDO::FETCH_ASSOC))
			{
                $toReturn = $row['category_name'];
                $dataList[] = '<li id="' . $row['category_id'] . '"><a href="#">' . htmlentities($toReturn, ENT_QUOTES, "UTF-8") .
                    '</a></li>';
            }

            if (count($dataList) >= 1)
			{
                $dataOutput = implode("\r\n", $dataList);
                echo $dataOutput;
            }
			else
			{
                echo '<li><a href="#">No Results</a></li>';
            }
        }
        catch (PDOException $e)
		{
            DB::close();
            trigger_error($e->getMessage(), E_USER_ERROR);
        }
    }
} else {
    echo 'Request Error';
}
?>