<?php

// require_once($_SERVER['DOCUMENT_ROOT'] . "/system/server-connection/db_info_MS.php");
// class DbConnection {

//     var $db_connection = null; // Database connection string        
//     var $db_server; // Database server
//     var $db_database; // The database being connected to
//     var $db_username; // The database username
//     var $db_password; // The database password
//     var $CONNECTED = false; // Determines if connection is established

//     function DbConnection() {
//         //global $DRAW_STORY_HOST;
//         //global $DRAW_STORY_DB_NAME;
//         //global $DRAW_STORY_USER;
//         //global $DRAW_STORY_PASSWD;
//         //$this->db_server = $SQL_HOST;
//         //$this->db_database = $SQL_DB;
//         $this->db_dsn = MS_DB_TYPE.':dbname='.MS_DB_NAME.';host='.MS_HOST;
//         $this->db_username = MS_USER;
//         $this->db_password = MS_PASSWD; 
//     }

//     * Open Method
//      * This method opens the database connection (only call if closed!) 

//     function Open() {
//         if (!$this->CONNECTED) {
//             $this->db_connection = new PDO($this->db_dsn, $this->db_username, $this->db_password);
//             //$dbh->exec("SET NAMES utf8");
//             if (!$this->db_connection) {
//                 echo('MySQL Connection Database Error');
//             } else {
//                 $this->CONNECTED = true;
//                 return $this->db_connection;
//             }
//         } else {
//             return "Error: No connection has been established to the database. Cannot open connection.";
//         }
//     }

//     /** Close Method
//      * This method closes the connection to the MySQL Database */
//     function Close() {
//         if ($this->CONNECTED) {
//             $this->db_connection = null;
//             $this->CONNECTED = false;
//         } else {
//             return "Error: No connection has been established to the database. Cannot close connection.";
//         }
//     }

// }
require_once("db_info_mssc.php");

class DbConnection {

    var $db_connection = null; // Database connection string        
    var $db_server; // Database server
    var $db_database; // The database being connected to
    var $db_username; // The database username
    var $db_password; // The database password
    var $CONNECTED = false; // Determines if connection is established

    function DbConnection() {
        //global $DRAW_STORY_HOST;
        //global $DRAW_STORY_DB_NAME;
        //global $DRAW_STORY_USER;
        //global $DRAW_STORY_PASSWD;
        //$this->db_server = $SQL_HOST;
        //$this->db_database = $SQL_DB;
		$this->db_dsn = MSSC_DB_TYPE.':dbname='.MSSC_DB_NAME.';host='.MSSC_HOST;
        $this->db_username = MSSC_USER;
        $this->db_password = MSSC_PASSWD; 
    }

    /** Open Method
     * This method opens the database connection (only call if closed!) */

    function Open() {
        if (!$this->CONNECTED) {
            $this->db_connection = new PDO($this->db_dsn, $this->db_username, $this->db_password);
			//$dbh->exec("SET NAMES utf8");
            if (!$this->db_connection) {
                echo('MySQL Connection Database Error');
            } else {
                $this->CONNECTED = true;
				return $this->db_connection;
            }
        } else {
            return "Error: No connection has been established to the database. Cannot open connection.";
        }
    }

    /** Close Method
     * This method closes the connection to the MySQL Database */
    function Close() {
        if ($this->CONNECTED) {
            $this->db_connection = null;
			$this->CONNECTED = false;
        } else {
            return "Error: No connection has been established to the database. Cannot close connection.";
        }
    }

}


?>