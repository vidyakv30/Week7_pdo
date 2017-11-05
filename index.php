    <?php
//turn on debugging messages
ini_set('display_errors', 'On');
error_reporting(E_ALL);

echo '<link rel="stylesheet" href="styles.css" type="text/css">';

define('DATABASE', 'vk427');
define('USERNAME', 'vk427');
define('PASSWORD', 'R0adrunner');
define('CONNECTION', 'sql1.njit.edu');

class dbConn
{

    protected static $conn;

    private function __construct()
    {
        try {
            self::$conn = new PDO('mysql:host=' . CONNECTION . ';dbname=' . DATABASE, USERNAME, PASSWORD);
            // set the PDO error mode to exception
            self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo 'Connected successfully <br/>';

        } catch (PDOException $e) {
            echo 'Connection failed.<br>Exception Message: ' . $e->getMessage() . '<br/>';
        }
    }

    public static function getConnection()
    {
        if (!self::$conn) {
            //new connection object.
            new dbConn();
        }
        return self::$conn;

    }

}

class accounts
{
    private static $table;
    private static $recCount;
    private static $recordSet;

    /*
     * Method to fetch the records from accounts table
     */
    public static function fetchRecords()
    {
        $conn      = dbConn::getConnection();
        $tableName = get_called_class();
        $stmt      = $conn->prepare('SELECT * FROM ' . $tableName . ' WHERE id < 6');
        $stmt->execute();
        //Fetch the records as an associative array as we have to build the table column headers.
        $result          = $stmt->setFetchMode(PDO::FETCH_ASSOC);
        self::$recordSet = $stmt->fetchAll();

    }
    /*
     *Method to count the number of records returned.
     */
    public static function countRecords()
    {

        self::$recCount = count(self::$recordSet);

    }
    /*
     * Method to create the table for the  records returned.
     */
    public static function createTable()
    {
        if (self::$recCount > 0) {
            self::$table .= '<table>';
            self::$table .= '<tr>';

            $firstRow = self::$recordSet[0];

            foreach ($firstRow as $key => $value) {
                self::$table .= "<th>$key</th>";

            }

            self::$table .= '</tr>';
            foreach (self::$recordSet as $record) {

                self::$table .= '<tr>';
                foreach ($record as $key => $value) {
                    self::$table .= "<td>$value</td>";

                }
                self::$table .= '</tr>';
            }
            self::$table .= '</table>';
        } else {
            self::$table .= "No records returned</br>Nothing to display";

        }
    }
    /*
     * This method will print all the results.
     */
    public static function printResults()
    {
        echo " Number of records returned: <b>" . self::$recCount . "</b></br> ";
        echo self::$table;
    }

}

accounts::fetchRecords();
accounts::countRecords();
accounts::createTable();
accounts::printResults();

?>



